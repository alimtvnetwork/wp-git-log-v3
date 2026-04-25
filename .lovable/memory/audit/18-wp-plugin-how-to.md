# Audit — `spec/18-wp-plugin-how-to`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **58/100 (C)**

> The documentation is exceptionally thorough and well-organized as a 'How-To' guide, but it is currently an 'Orphan Spec' because the codebase contains none of the WordPress PHP patterns it claims to govern.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 90 | 22.5 |
| Consistency | 25% | 85 | 21.2 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 95 | 14.2 |
| Maintainability | 10% | 90 | 9.0 |
| Testability | 5% | 40 | 2.0 |

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `WordPress Plugin Boilerplate/Scaffold`, `PHP Enum Domain Models`, `REST API Controller Reference`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 8/10 | Spec describes a production-ready 'Gold Standard' for WP plugins, but no actual PHP/WP code exists in the index. |
| 2 | inconsistency | medium | 3/10 | The overview references internal subfolders that appear to be missing or flattened in the actual file list. |
| 3 | untestable | medium | 5/10 | Acceptance Criteria verify Markdown structure but do not verify the technical content of the WP architecture. |

### Detail + Proposed Corrections

#### 1. [HIGH] Spec describes a production-ready 'Gold Standard' for WP plugins, but no actual PHP/WP code exists in the index.
- **Category:** orphan-spec  |  **Impact:** 8/10
- **Evidence:** Comprehensive blueprint for building WordPress plugins... Any new WordPress plugin MUST follow the contracts and patterns in this folder.
- **Proposed correction:** Supply the reference implementation or boilerplate code described in 07-reference-implementations.md and 01-foundation-and-architecture.md.

#### 2. [MEDIUM] The overview references internal subfolders that appear to be missing or flattened in the actual file list.
- **Category:** inconsistency  |  **Impact:** 3/10
- **Evidence:** File Inventory Table item 02 shows a subfolder './02-enums-and-coding-style/' which is not present in the provided file list.
- **Proposed correction:** Rename or re-index the '02-enums-and-coding-style/' folder to match the flat file list or update the inventory.

#### 3. [MEDIUM] Acceptance Criteria verify Markdown structure but do not verify the technical content of the WP architecture.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** AC-01 through AC-05 focus exclusively on Markdown linting and folder structure.
- **Proposed correction:** Update AC to include specific PHP/WP structural requirements (e.g., PSR-4 namespace presence).
