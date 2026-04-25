# Audit — `spec/05-split-db-architecture`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **33/100 (F)**

> The spec is a pure architectural guideline with 0% code alignment; it describes a system that does not exist in the provided repository (which only contains linter scripts). Furthermore, the spec's internal inventory is broken as it references sub-directories and files that are missing from the folder structure.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 60 | 15.0 |
| Consistency | 25% | 40 | 10.0 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 70 | 10.5 |
| Maintainability | 10% | 50 | 5.0 |
| Testability | 5% | 30 | 1.5 |

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `src/database/root.db`, `src/database/session_manager.py`, `src/database/migrations/`, `src/auth/rbac_casbin.py`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | medium | 3/10 | Document Inventory references missing files. |
| 2 | orphan-spec | critical | 10/10 | Complete absence of implementation code for a 'Production-Ready' spec. |
| 3 | untestable | medium | 5/10 | Acceptance criteria are vague and not objectively verifiable. |
| 4 | inconsistency | low | 4/10 | Redundant and potentially conflicting AC files. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] Document Inventory references missing files.
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** Document Inventory lists 02-features/* and 03-issues/* but these are not in the File Inventory.
- **Proposed correction:** Ensure the document inventory matches the actual files present in the file system.

#### 2. [CRITICAL] Complete absence of implementation code for a 'Production-Ready' spec.
- **Category:** orphan-spec  |  **Impact:** 10/10
- **Evidence:** The spec describes a 'Production-Ready' pattern for SQLite organization but the code index contains only linter scripts.
- **Proposed correction:** Implement the Split-DB architecture or remove/reclassify the spec as a 'Future Pattern' document.

#### 3. [MEDIUM] Acceptance criteria are vague and not objectively verifiable.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** AC-01: 'SQLite databases split correctly by domain'
- **Proposed correction:** Define specific SQLite PRAGMA or table checks instead of generic 'correctly split' bullet points.

#### 4. [LOW] Redundant and potentially conflicting AC files.
- **Category:** inconsistency  |  **Impact:** 4/10
- **Evidence:** Both 97 and 98 contain acceptance criteria with different version numbers and formatting.
- **Proposed correction:** Consolidate or clarify the difference between 97-acceptance-criteria.md and 98-acceptance-criteria.md.
