# History, Action, and AuditTrail (v2)

**Version:** 2.0.0  
**Updated:** 2026-04-25

Three distinct tables, three distinct purposes. None replaces the other.

---

## History — Domain timeline (per RepoVersion)

- Answers: "What happened to this repo version?"
- Rendered in the **History** UI menu.
- Rich row: App, Branch, Pipeline, GitSha256, ActionType, HasError snapshot, Summary.
- One row per CI/CD ingest event.

## Action — Enum-typed action log

- Answers: "Tally / filter all `Append`/`Fixed`/`Clear`/`ClearAll` actions across the system."
- Rendered in the **Action** UI menu with enum filters.
- Lighter columns (no Summary, no HasError snapshot).
- One row per CI/CD write action; useful for analytics and rate limits.

## AuditTrail — System forensics

- Answers: "Who hit which endpoint when, with what outcome?"
- Not surfaced in normal UI menus; reserved for admin/audit screens.
- Captures every endpoint hit (read or write), Auth Success/Fail, migration runs, profile/role/repo CRUD.

---

## Why three tables

| Concern | History | Action | AuditTrail |
|---------|---------|--------|------------|
| Domain-meaningful timeline | ✅ | partial | no |
| Enum-typed counters | partial | ✅ | no |
| Endpoint forensics | no | no | ✅ |
| Includes reads | no | no | ✅ |
| Includes auth attempts | no | no | ✅ |
| Includes migrations | no | no | ✅ |

Merging would either bloat domain views or lose forensic granularity.
