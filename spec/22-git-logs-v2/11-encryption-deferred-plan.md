# Encryption-at-Rest — Deferred Plan (v2)

**Version:** 2.0.0  
**Updated:** 2026-04-25  
**Status:** Deferred to v3. v2 stores all secrets as plain TEXT in SQLite.

This document records the deferred encryption design so v3 has an unambiguous starting point — no rework of v2 schema, only column-value substitution.

---

## Fields targeted for encryption (v3)

| Table | Column | Why | Lookup needed? |
|-------|--------|-----|----------------|
| Profile | GeneratedKeyApi | Long-lived API key | No (compared by hash) |
| Profile | Token | Long-lived token | No (compared by hash) |
| Profile | TempToken | CI/CD body secret | **Yes** — looked up to find the matching Profile |
| ConfigKv | ValueText (when KeyName is in a sensitive-keys list) | Future: GitHub PAT, webhook signing keys | No |

`UserName`, `Email`, `RepoUrl`, `Branch`, `Logs[]`, `ErrorLogs[]` are **not** encrypted — they are operationally needed in cleartext for queries, history rendering, and log retrieval.

---

## Storage strategy (v3)

Two-column approach per encrypted field; v2 schema already uses TEXT, so v3 simply re-purposes it and adds one sibling column via additive migration:

```
{Field}             TEXT  -- v3: encrypted ciphertext (base64), or hash for lookup-only fields
{Field}LookupHash   TEXT  -- v3: HMAC-SHA256(field, LookupKey) — only for fields needing lookup
```

For `Profile.TempToken`: store ciphertext in `TempToken` and HMAC in `TempTokenLookupHash`. Validation flow becomes:
1. Compute `HMAC(inboundTempToken, LookupKey)`.
2. `SELECT ProfileId FROM Profile WHERE TempTokenLookupHash = ?`.
3. If row found, decrypt stored `TempToken` and constant-time compare to `inboundTempToken`.

For `GeneratedKeyApi` and `Token`: store **only** Argon2id hash; verify with `password_verify`. No reversible storage.

---

## Key strategy

| Key | Purpose | Storage |
|-----|---------|---------|
| MasterKey | Unwraps DataKey | WP option `gitlogs_master_key`, populated from env var `GITLOGS_MASTER_KEY` at install; option deleted on uninstall. |
| DataKey | AES-256-GCM encryption of TEXT fields | Generated at install, wrapped by MasterKey, stored in `ConfigKv.KeyName='WrappedDataKey'`. |
| LookupKey | HMAC for `TempTokenLookupHash` | Derived from MasterKey via HKDF with label `gitlogs/lookup/v1`. |

Rotation: replacing MasterKey requires unwrapping DataKey with the old MasterKey and rewrapping with the new. DataKey rotation requires re-encrypting every encrypted column inside one transaction; gated by an admin tool, not automatic.

---

## Migration trigger (v2 → v3)

A v3 migration runs when `MigrationState.PluginVersion='3.0.0'` row is absent. It:
1. Adds `TempTokenLookupHash` column (`ALTER TABLE Profile ADD COLUMN TempTokenLookupHash TEXT`).
2. For every existing Profile: encrypts current plain `TempToken`, computes HMAC, updates row.
3. For every existing `GeneratedKeyApi` / `Token`: hashes with Argon2id and overwrites.
4. Inserts `MigrationState(PluginVersion='3.0.0')`.

The migration is idempotent; if a Profile already has `TempTokenLookupHash` set, it is skipped.

---

## What v2 must do to keep v3 cheap

- Keep secrets as `TEXT` columns of the names listed above (already the case).
- Never compose CI/CD validation logic that depends on `LIKE '%token%'` or substring searches on token columns — only equality.
- Never log raw secrets (already enforced by the no-swallow rule and the structured logger).
- Document any new secret-bearing column in this file as soon as it is added.
