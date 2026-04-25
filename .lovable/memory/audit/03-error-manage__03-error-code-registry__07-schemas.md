# Audit — `spec/03-error-manage/03-error-code-registry/07-schemas`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **40/100 (F)**

> The spec is a pure 'orphan'—it defines rigorous technical constraints for JSON schemas that have no corresponding implementation files in the provided codebase index.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 60 | 15.0 |
| Consistency | 25% | 90 | 22.5 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 70 | 10.5 |
| Maintainability | 10% | 80 | 8.0 |
| Testability | 5% | 95 | 4.8 |

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `schemata/error-registry.schema.json`, `src/error-registry/validator.js`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | critical | 10/10 | The spec describes JSON schemas that do not exist in the codebase. |
| 2 | missing-spec | medium | 4/10 | Requirement details are only present in AC, missing from the overview/body. |
| 3 | ambiguity | low | 3/10 | Vague schema version target prevents strict validation setup. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] The spec describes JSON schemas that do not exist in the codebase.
- **Category:** orphan-spec  |  **Impact:** 10/10
- **Evidence:** Entire AC section defines JSON schema constraints (ErrorCodeRegex, SeverityEnum, HTTPRange) but no .json or .schema files exist in the index.
- **Proposed correction:** Create the JSON schema files defined in the AC (e.g., in a /schemata/ or /contracts/ directory).

#### 2. [MEDIUM] Requirement details are only present in AC, missing from the overview/body.
- **Category:** missing-spec  |  **Impact:** 4/10
- **Evidence:** 00-overview.md contains only metadata and a purpose statement; AC contains the actual logic.
- **Proposed correction:** Add a 'contracts' or 'definitions' section to 00-overview.md explaining the schema structure.

#### 3. [LOW] Vague schema version target prevents strict validation setup.
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** JSON Schema Versions: Targeting Draft 7 or 2020-12 compat.
- **Proposed correction:** Explicitly state which JSON schema version (Draft 7 OR 2020-12) is the standard.
