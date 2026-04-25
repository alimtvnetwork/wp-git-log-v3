# Backup & Restore (v2)

**Version:** 2.5.0  
**Updated:** 2026-04-25

The Git Logs plugin stores everything in a **single SQLite file** at `wp-content/uploads/git-logs/git-logs.sqlite`. Backup = file copy. Restore = file overwrite. This document spells out the safe procedure so a hot copy never produces a torn or unreadable database.

---

## File layout

| Path | Purpose |
|------|---------|
| `wp-content/uploads/git-logs/git-logs.sqlite`     | Main DB |
| `wp-content/uploads/git-logs/git-logs.sqlite-wal` | Write-Ahead Log (transient) |
| `wp-content/uploads/git-logs/git-logs.sqlite-shm` | Shared memory (transient) |

WAL mode is mandatory (set via `PRAGMA journal_mode = WAL` in §18). The `.sqlite-wal` file MUST be included in any backup, OR a `wal_checkpoint(TRUNCATE)` must be run before copying so all data lives in the main file.

---

## Backup — recommended (`wp git-logs backup`)

```
wp git-logs backup --to=<path> [--checkpoint] [--gzip]
```

Internally:

```
1. PRAGMA wal_checkpoint(TRUNCATE);        -- merge WAL into main
2. Open SQLite Online Backup API handle
3. Stream pages to <path> in 1000-page chunks
4. Verify with PRAGMA integrity_check on the destination
5. Optional gzip
6. Write manifest <path>.json:
   {
     "PluginVersion": "2.0.0",
     "SchemaChecksum": "<sha256 of CREATE statements>",
     "TakenAt": 1714000000,
     "RowCounts": { "Pipeline": 117, "LogEntry": 1402901, ... },
     "WpVersion": "6.5.2",
     "PhpVersion": "8.2.10"
   }
```

The Online Backup API is the **only** safe hot copy — `cp` of a live DB will tear under concurrent writes even in WAL mode.

---

## Backup — operator manual (no CLI)

If WP-CLI is unavailable:

```
1. systemctl stop php-fpm    # or set plugin to maintenance mode
2. sqlite3 git-logs.sqlite "PRAGMA wal_checkpoint(TRUNCATE);"
3. cp git-logs.sqlite /backup/git-logs-$(date +%Y%m%d).sqlite
4. systemctl start php-fpm
```

Cold-copy is always safe. Hot-copy without the checkpoint is unsafe.

---

## Restore — recommended (`wp git-logs restore`)

```
wp git-logs restore --from=<path> [--force]
```

Internally:

```
1. Refuse unless plugin is in maintenance mode (`ConfigKv.MaintenanceMode = '1'`)
   OR --force was passed.
2. PRAGMA integrity_check on <path>; abort on failure.
3. Compare <path>.json manifest:
   - SchemaChecksum must match current code expectations,
     OR --force allows mismatch (operator accepts migration risk).
4. Move current git-logs.sqlite{,-wal,-shm} to .bak with timestamp.
5. Copy <path> into place; chmod 0600; chown to web user.
6. Open DB; run migrator (idempotent — see §06).
7. Verify row counts non-zero on Pipeline + Profile.
8. Clear MaintenanceMode.
9. Emit AuditTrail row (AuditActionType=Restore, new seed ID 20).
```

---

## Manifest schema

Stored alongside the backup file. Required for safe restore.

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| PluginVersion  | string  | yes | semver |
| SchemaChecksum | string  | yes | sha256 of canonicalized `CREATE` statements from `sqlite_master` |
| TakenAt        | int64   | yes | unix seconds |
| RowCounts      | object  | yes | per-table counts; sanity check on restore |
| WpVersion      | string  | no  | for diagnostics |
| PhpVersion     | string  | no  | for diagnostics |
| Notes          | string  | no  | free text from operator |

---

## Cross-version restore

| From → To | Behavior |
|-----------|----------|
| Same major | Auto-migrate via `MigrationState`; no operator action. |
| Older major (e.g. 2.x → 3.x) | `--force` required. Migrator runs forward; no rollback path. |
| Newer major (downgrade) | **Refused, no override.** Schema may have columns the older code can't read. Operator must restore to a matching plugin version first. |

---

## Verification after restore

`wp git-logs verify` runs:

1. `PRAGMA integrity_check` (must return `ok`).
2. `PRAGMA foreign_key_check` (must return zero rows).
3. Row count of `Profile` ≥ 1.
4. `MigrationState.PluginVersion = ConfigKv.PluginVersion`.
5. Latest `AuditTrail` row is the `Restore` event (sanity).

Exit 0 on all-pass; exit 1 with the failed check name otherwise. Site Health card (§20) surfaces the same checks for non-CLI operators.

---

## What is NOT in scope for v2

- Incremental / differential backups.
- Off-site upload (S3, GCS).
- Encryption-at-rest of the backup file (operator's job until v3 §11 lands).
- Point-in-time restore (no WAL archive shipping).
