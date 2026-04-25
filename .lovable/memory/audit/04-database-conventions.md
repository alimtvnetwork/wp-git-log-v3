# Audit — `spec/04-database-conventions`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **51/100 (F)**

> The specification is exceptionally well-written and clear, providing a robust 'Golden Rule' set for database design. However, it receives a failing grade because it is entirely 'orphan'—there is zero database implementation code (schemas, migrations, or models) in the provided index to align with, making it a document-only module that does not govern actual code yet.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 95 | 23.8 |
| Consistency | 25% | 90 | 22.5 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 90 | 13.5 |
| Maintainability | 10% | 85 | 8.5 |
| Testability | 5% | 30 | 1.5 |

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | critical | 8/10 | Entirely orphaned spec: no database schemas, migrations, or ORM models exist in the code index. |
| 2 | drift | low | 2/10 | Minor internal link/index naming inconsistency likely between overview and detailed content files. |
| 3 | untestable | medium | 5/10 | Acceptance criteria only test the existence of files, not the technical database rules defined in the 'Golden Rules'. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] Entirely orphaned spec: no database schemas, migrations, or ORM models exist in the code index.
- **Category:** orphan-spec  |  **Impact:** 8/10
- **Evidence:** Purpose: Comprehensive database design and implementation conventions... single source of truth for how databases are designed and used across all languages.
- **Proposed correction:** Create initial migration and base model implementation matching PascalCase and singular table rules.

#### 2. [LOW] Minor internal link/index naming inconsistency likely between overview and detailed content files.
- **Category:** drift  |  **Impact:** 2/10
- **Evidence:** 07 | [07-split-db-pattern.md](./07-split-db-pattern.md) | Split DB pattern... vs AC listing 07-split-db-pattern.md
- **Proposed correction:** Rename `07-split-db-pattern.md` to `07-split-db-architecture.md` to match index or update index to match filename.

#### 3. [MEDIUM] Acceptance criteria only test the existence of files, not the technical database rules defined in the 'Golden Rules'.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** AC-01 through AC-05 focus exclusively on file existence and linter passing.
- **Proposed correction:** Define specific SQL/ORM output patterns in AC (e.g., 'Table names must match PascalCase regex').
