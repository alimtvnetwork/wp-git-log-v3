# `git-logs` — Diagrams

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active  
**AI Confidence:** High  
**Ambiguity:** Low

---

## Inventory

| # | File | Purpose | Render hint |
|---|------|---------|-------------|
| 01 | [01-rest-endpoints-mindmap.mmd](./01-rest-endpoints-mindmap.mmd) | Mind-map of every REST endpoint under `/wp-json/git-logs/v1` — HTTP method, auth mechanism, intended caller. Branch-coloured per subsystem (auth, users, repositories, logs, audit). | Wide canvas (≥ 1600 px). Dark theme baked in. |
| 02 | [02-sqlite-split-db-design.mmd](./02-sqlite-split-db-design.mmd) | **Proposed** SQLite re-cast of the data layer following [`05-split-db-architecture`](../../05-split-db-architecture/00-overview.md): one `root.db` for app metadata + one `logs/repo-{id}-…db` per allowlisted repository. Includes lifecycle arrows for `POST /repositories`. | Tall canvas (≥ 2000 px height). |
| 03 | [03-seedable-config-flow.mmd](./03-seedable-config-flow.mmd) | First-run vs subsequent-run bootstrap flow per [`06-seedable-config-architecture`](../../06-seedable-config-architecture/00-overview.md) (CW Config). Shows seeding of lookup tables, JWT key generation, SemVer-aware merge on config bumps, and per-repo logs-DB creation. | Tall canvas. |

---

## Important context

The current normative data-layer spec — [`02-database-schema-and-erd.md`](../02-database-schema-and-erd.md) — targets **MySQL via `wpdb`** (WordPress plugin deployment). Diagram 02 is a **proposed alternative** for self-hosted / CLI deployments using SQLite, and is not yet promoted into the schema spec. Treat it as a design proposal for review; if accepted, fold it into a new `02b-sqlite-schema-and-erd.md` and link from `00-overview.md`.

Key proposed additions (not yet in `02-…`):

- `Repository.LogsDbPath VARCHAR(255) NULL` — soft FK to the per-repo SQLite file.
- `RepositoryMeta` table inside each logs DB — self-describing row so a detached `.db` file can be re-attached without ambiguity.
- `EnvelopeNonce` lives **inside** the per-repo logs DB (matches the bucket scope from [`08-allowlist-and-wildcard-matching.md`](../08-allowlist-and-wildcard-matching.md) §11.5).
- `JwtKey`, `JwtDenylist`, `AppConfig` move from WP options into Root DB tables.

---

## Cross-references

| Reference | Location |
|-----------|----------|
| Endpoint inventory (normative) | [`../11-error-management.md`](../11-error-management.md) §6 |
| Allowlist / envelope JWT | [`../08-allowlist-and-wildcard-matching.md`](../08-allowlist-and-wildcard-matching.md) |
| Plugin JWT subsystem | [`../05-auth-jwt-flow.md`](../05-auth-jwt-flow.md) |
| Onboarding / token flow | [`../16-jwt-onboarding-and-token-usage.md`](../16-jwt-onboarding-and-token-usage.md) |
| Split-DB pattern | [`../../05-split-db-architecture/00-overview.md`](../../05-split-db-architecture/00-overview.md) |
| Seedable config (CW Config) | [`../../06-seedable-config-architecture/00-overview.md`](../../06-seedable-config-architecture/00-overview.md) |

---

*Mermaid sources only. Render with any Mermaid 10+ engine; theme is pinned to `dark` inside each `%%{init}%%` block — do not override unless you also adjust the `classDef` palette.*
