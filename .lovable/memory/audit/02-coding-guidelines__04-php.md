# Audit — `spec/02-coding-guidelines/04-php`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **28/100 (F)**

> The spec is a total 'orphan'—it describes a complex PHP architecture (RiseupAsia namespace, enums, ResultHelpers) for which ZERO corresponding PHP code exists in the provided index. The index contains only linter scripts.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 40 | 10.0 |
| Consistency | 25% | 50 | 12.5 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 70 | 10.5 |
| Maintainability | 10% | 60 | 6.0 |
| Testability | 5% | 30 | 1.5 |

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `includes/Enums/UploadStatusType.php`, `includes/Helpers/ResultHelper.php`, `includes/Enums/ResponseKeyType.php`, `includes/Enums/HookType.php`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | critical | 10/10 | Spec describes an entire PHP framework/library that is missing from the provided code index. |
| 2 | inconsistency | low | 2/10 | Redundant/repeating rows in the Overview table. |
| 3 | untestable | high | 5/10 | AC-06 is truncated and incomplete. |
| 4 | ambiguity | medium | 3/10 | Enforced manual implementation of identical methods across all enums is high-maintenance. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] Spec describes an entire PHP framework/library that is missing from the provided code index.
- **Category:** drift  |  **Impact:** 10/10
- **Evidence:** Acceptance Criteria mentions RiseupAsia\Enums and includes/Enums/ but code index only shows linter-scripts/.
- **Proposed correction:** Verify if the RiseupAsia namespace exists or use the provided codebase index which only contains linter scripts.

#### 2. [LOW] Redundant/repeating rows in the Overview table.
- **Category:** inconsistency  |  **Impact:** 2/10
- **Evidence:** Document Inventory table lists the same files (01-enums.md, etc.) three times in a row.
- **Proposed correction:** Remove redundant file listings from the Document Inventory table.

#### 3. [HIGH] AC-06 is truncated and incomplete.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** AC-06: REST Error Handling Guardrails is cut off ("Given a REST API handler in a WordPress compa").
- **Proposed correction:** Provide the referenced 05-response-array-standard.md or equivalent code to define error handling guardrails.

#### 4. [MEDIUM] Enforced manual implementation of identical methods across all enums is high-maintenance.
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** AC-01: "implement the isEqual(self $other): bool method"
- **Proposed correction:** Clarify if 'isEqual' is meant to be a Trait or if it must be manually implemented in every enum.
