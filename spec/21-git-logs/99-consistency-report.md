# Consistency Report — `git-logs` App Specification

**Module:** `spec/21-git-logs/`  
**Version:** 1.0.0  
**Generated:** 2026-04-25  
**Generator:** Manual audit pass (anchored in [02-consolidated-audit-findings](../22-app-issues/02-consolidated-audit-findings/00-overview.md))

---

## Module Health Score

Per `spec/01-spec-authoring-guide/03-required-files.md`, the health score is the sum of four 25-point criteria:

| # | Criterion | Status | Points |
|---|-----------|--------|-------:|
| 1 | `00-overview.md` present | ✅ Present | 25 / 25 |
| 2 | `99-consistency-report.md` present (this file) | ✅ Present | 25 / 25 |
| 3 | All files/folders use lowercase kebab-case with numeric prefixes | ✅ Compliant | 25 / 25 |
| 4 | Unique sequence prefixes within the folder (no duplicates) | ✅ Compliant | 25 / 25 |
| **Total** | | | **100 / 100 (A+)** |

> **Caveat — content completeness is NOT part of the formula.** The four-criteria health score reflects governance hygiene only. Content-level coverage is tracked separately through the audit findings below; see "Coverage Gaps" §.

---

## File Inventory (Present)

| # | File | Status | Notes |
|---|------|--------|-------|
| 00 | `00-overview.md` | ✅ Present | Inventory + locked decisions (12 rows) |
| 01 | `01-glossary-and-enums.md` | ✅ Present | Domain glossary, enum catalog (132 lines) |
| 02 | `02-database-schema-and-erd.md` | ✅ Present | 7 entity tables, ERD, views (663 lines) |
| 08 | `08-allowlist-and-wildcard-matching.md` | ✅ Present | Allowlist resolver, wildcard regex (268 lines) |
| 11 | `11-error-management.md` | ✅ Present | 30+ `GL-*` codes, no-swallow rules (479 lines) |
| 12 | `12-logging-strategy.md` | ✅ Present | Structured JSON logs, redaction (373 lines) |
| 16 | `16-jwt-onboarding-and-token-usage.md` | ✅ Present | 5-phase JWT lifecycle (414 lines) |
| 17 | `17-spec-consistency-checklist.md` | ✅ Present | App-vs-guidelines checklist (245 lines) |
| 99 | `99-consistency-report.md` | ✅ Present | This file |

**Files present:** 9.

---

## File Inventory (Promised in `00-overview.md` but Absent)

These files are listed in the **Document Inventory** of `spec/21-git-logs/00-overview.md` but do not exist on disk. They lower content coverage but **do not** reduce the formula-based health score above.

| # | File | Promised at | Audit ID |
|---|------|-------------|----------|
| 03 | `03-admin-ui.md` | overview L61 | F-20 |
| 04 | `04-rest-api-endpoints.md` | overview L62 | F-01 |
| 05 | `05-auth-jwt-flow.md` | overview L63 | F-18 |
| 06 | `06-auth-wordpress-bridge.md` | overview L64 | F-19 |
| 07 | `07-log-push-flow.md` | overview L65 | F-04 |
| 09 | `09-log-retrieval-flow.md` | overview L67 | F-04 |
| 10 | `10-audit-trail.md` | overview L68 | F-04 |
| 13 | `13-coding-guidelines-applied.md` | overview L71 | F-21 |
| 14 | `14-acceptance-criteria.md` | overview L72 | F-22 |
| 15 | `15-blind-audit-checklist.md` | overview L73 | F-04 |
| 97 | `97-acceptance-criteria.md` | overview L74 | F-05 |
| 98 | `98-changelog.md` | overview L75 | F-05 |

**Files missing:** 12. Coverage = 9 / (9+12) = **43 %**.

Also missing per `01-spec-authoring-guide/03-required-files.md`: `error-codes.json` (F-06).

---

## Naming Convention Compliance

| Check | Result |
|-------|--------|
| All `*.md` files use lowercase kebab-case | ✅ |
| All files start with a 2-digit zero-padded prefix | ✅ |
| Sequence prefixes are unique within the folder | ✅ |
| No underscores in spec file names | ✅ |
| `00-overview.md` present | ✅ |
| `99-consistency-report.md` present | ✅ |

---

## Cross-Reference Validation

The 9 present files have been scanned for internal links:

| File | Outbound links | Status |
|------|---:|--------|
| `00-overview.md` | 19 | 12 broken (links to absent files — see "Promised but Absent") |
| `01-glossary-and-enums.md` | 5 | All resolve |
| `02-database-schema-and-erd.md` | 6 | All resolve |
| `08-allowlist-and-wildcard-matching.md` | 7 | All resolve |
| `11-error-management.md` | 6 | All resolve |
| `12-logging-strategy.md` | 6 | All resolve |
| `16-jwt-onboarding-and-token-usage.md` | 5 | All resolve |
| `17-spec-consistency-checklist.md` | 9 | All resolve |
| `99-consistency-report.md` (this) | 4 | All resolve |

**Broken-link total:** 12 (all attributable to F-04, the missing-files finding).

---

## Internal Consistency

| Check | Result | Audit ID |
|-------|--------|----------|
| Schema doc + allowlist doc agree on `Repository` columns | ❌ Disagree on `LogSenderTokenVerifier` | F-15 |
| `RevokedJti` table referenced by `16` is declared in `02` | ❌ Missing | F-03 |
| Header precedence (`X-Request-Id` vs `Traceparent`) is unambiguous | ❌ Undefined | F-11 |
| Every `GL-*` code in `11` is present in `error-codes.json` | ❌ Registry absent | F-06 |
| Every `AC-*` ID is rolled up into `97-acceptance-criteria.md` | ❌ Roll-up absent | F-22 |
| Cross-References table in `00-overview.md` lists every present file | ❌ Missing entry for `17` | F-16 |

---

## Audit Anchor

For full per-finding evidence (file paths + line numbers + verbatim snippets + fixes), see:

- [Consolidated Audit Findings](../22-app-issues/02-consolidated-audit-findings/00-overview.md) — 24 numbered findings.
- [Phase-2 Audit](../22-app-issues/01-phase-2-git-logs-audit/00-overview.md) — 25 findings (predecessor).
- [Spec Consistency Checklist](./17-spec-consistency-checklist.md) — guideline-domain compliance walkthrough.

---

## Summary

- **Health Score (formula):** 100 / 100 (A+) — governance trio is now complete.
- **Content Coverage:** 43 % (9 of 21 promised files present).
- **Open Audit Findings:** 24 (5 Critical · 9 High · 8 Medium · 2 Low).
- **Cross-link Integrity:** 12 broken outbound links — all resolve when missing files are authored.

---

## Validation History

| Date | Version | Action |
|------|---------|--------|
| 2026-04-25 | 1.0.0 | Initial consistency report generated alongside audit cycle |
