# Audit — `spec/02-coding-guidelines/23-app-database`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **40/100 (D)**

> The module is an empty shell (Type: Orphan Spec). While it sets up rules, it contains zero actual database designs despite a high version number (3.2.0) and detailed acceptance criteria that have nothing to validate against.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 30 | 7.5 |
| Consistency | 25% | 60 | 15.0 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 70 | 10.5 |
| Maintainability | 10% | 80 | 8.0 |
| Testability | 5% | 50 | 2.5 |

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `01-users-table.md`, `02-orders-table.md`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 7/10 | The spec module is a shell with no actual database design content despite being versioned 3.2.0. |
| 2 | untestable | medium | 3/10 | AC-05 refers to a 'Purpose' section that does not actually contain the UUID requirement; the requirement is only in the inlined contract. |
| 3 | ambiguity | low | 5/10 | Conflicting signals between 'Purpose' (which implies content) and 'Contents' (which is empty). |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec module is a shell with no actual database design content despite being versioned 3.2.0.
- **Category:** orphan-spec  |  **Impact:** 7/10
- **Evidence:** _No content yet. Add database design documents as numbered files within this folder._
- **Proposed correction:** Populate the folder with at least one representative numbered file (e.g., 01-data-model.md) as the overview claims to cover app-specific designs.

#### 2. [MEDIUM] AC-05 refers to a 'Purpose' section that does not actually contain the UUID requirement; the requirement is only in the inlined contract.
- **Category:** untestable  |  **Impact:** 3/10
- **Evidence:** AC-05: UUID Primary Key Standardization (Verifies: 00-overview.md - Purpose section)
- **Proposed correction:** Specify an automated tool or manual check process in the AC to verify UUID usage in markdown files.

#### 3. [LOW] Conflicting signals between 'Purpose' (which implies content) and 'Contents' (which is empty).
- **Category:** ambiguity  |  **Impact:** 5/10
- **Evidence:** Purpose: Covers data model decisions, table designs... Contents: No content yet.
- **Proposed correction:** Clearly state in the Overview whether this module is intended to be a repository of schema definitions or just a guideline for how to write them.
