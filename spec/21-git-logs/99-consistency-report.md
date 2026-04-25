# Consistency Report — Git Logs v1 (LEGACY)

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** ⚠️ **DEPRECATED** — superseded by [`spec/22-git-logs-v2/`](../22-git-logs-v2/00-overview.md) (v2.8.7 authoritative).

---

## Status

This folder is **legacy v1**. All 10 spec files were banner-deprecated in v2.7.1 (2026-04-25). No further consistency audits are performed against v1 content — drift is expected and acceptable.

For the current authoritative spec, see:

- **Folder 22 v2 overview**: [`spec/22-git-logs-v2/00-overview.md`](../22-git-logs-v2/00-overview.md)
- **Folder 22 v2 consistency report**: [`spec/22-git-logs-v2/99-consistency-report.md`](../22-git-logs-v2/99-consistency-report.md)
- **v1 ↔ v2 mapping**: distributed across folder 22 §05/§18/§30/§31 + this folder's deprecation banners (see `mem://specs/git-logs.md` for the locked decision).

---

## File Inventory (v1 — frozen)

| # | File | Status |
|---|------|--------|
| 1 | `00-overview.md` | ⚠️ Deprecated (banner) |
| 2 | `01-glossary.md` | ⚠️ Deprecated (banner) |
| 3 | `02-schema-erd.md` | ⚠️ Deprecated (banner) |
| 4 | `05-auth-jwt.md` | ⚠️ Deprecated (banner) — JWT removed in v2 |
| 5 | `08-allowlist.md` | ⚠️ Deprecated (banner) |
| 6 | `11-error.md` | ⚠️ Deprecated (banner) — superseded by v2 §15 |
| 7 | `12-logging.md` | ⚠️ Deprecated (banner) |
| 8 | `16-jwt-onboarding.md` | ⚠️ Deprecated (banner) — N/A in v2 |
| 9 | `17-checklist.md` | ⚠️ Deprecated (banner) |
| 10 | `97-acceptance-criteria.md` | ⚠️ Deprecated (banner) |
| 11 | `reference/00-verbatim-brief.md` | ✅ Preserved (verbatim source brief) |
| 12 | `reference/*.md` | ✅ Preserved (historical reference) |

> The `reference/` sub-folder is intentionally without `00-overview.md` — it contains the verbatim brief and supporting raw notes that fed v2.

---

## Health Score

N/A — folder is deprecated. Consistency is measured against v2 (folder 22) only.

---

## Validation History

| Date | Version | Action |
|------|---------|--------|
| 2026-04-25 | 1.0.0 | Initial consistency stub added to satisfy universal §99 convention; folder remains deprecated |
