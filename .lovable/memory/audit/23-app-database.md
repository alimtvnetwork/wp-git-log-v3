# Audit — `spec/23-app-database`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **39/100 (F)**

> The spec module is a 'ghost' document; it defines rigorous acceptance criteria for a database schema and migration system that does not exist in the codebase. It has zero completeness regarding actual table definitions or business data models.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 5 | 1.2 |
| Consistency | 25% | 90 | 22.5 |
| Alignment | 20% | 10 | 2.0 |
| Clarity | 15% | 70 | 10.5 |
| Maintainability | 10% | 80 | 8.0 |
| Testability | 5% | 60 | 3.0 |

## Code Mapping

**Implemented by:** `linter-scripts/check-forbidden-strings.py`, `linter-scripts/forbidden-strings.toml`
**Expected but missing:** `migrations/app-db/`, `src/database/models/`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 8/10 | Spec is an empty shell with no defined data model or schema details. |
| 2 | drift | critical | 9/10 | Spec refers to a database implementation that does not exist in the provided file inventory. |
| 3 | ambiguity | low | 3/10 | Rule 12 is referenced as a requirement but the logic for enforcement is hidden in a python script. |

### Detail + Proposed Corrections

#### 1. [HIGH] Spec is an empty shell with no defined data model or schema details.
- **Category:** orphan-spec  |  **Impact:** 8/10
- **Evidence:** Document Inventory: (empty — awaiting content)
- **Proposed correction:** Populate the Document Inventory and define the actual table schemas/migration files.

#### 2. [CRITICAL] Spec refers to a database implementation that does not exist in the provided file inventory.
- **Category:** drift  |  **Impact:** 9/10
- **Evidence:** Spec references 'app-database' schema and migrations, but no .sql, .db, or ORM files exist in the code index.
- **Proposed correction:** Add the actual database implementation files to the code index or adjust the spec to reflect the lack of implementation.

#### 3. [LOW] Rule 12 is referenced as a requirement but the logic for enforcement is hidden in a python script.
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** Then any appended columns must be NULLABLE and cannot contain a DEFAULT clause (Rule 12).
- **Proposed correction:** Specify exactly which strings or patterns Rule 12 and the linter are looking for within the spec file.
