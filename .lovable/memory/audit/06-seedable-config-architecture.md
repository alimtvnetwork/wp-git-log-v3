# Audit — `spec/06-seedable-config-architecture`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **25/100 (F)**

> The spec is a 'phantom' architecture: it describes a sophisticated configuration and seeding system (Go/GORM/SQLite) that is completely absent from the provided codebase, which consists entirely of linter scripts. Furthermore, the spec's own internal file inventory refers to multiple feature files that are missing from the bundle.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 30 | 7.5 |
| Consistency | 25% | 40 | 10.0 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 60 | 9.0 |
| Maintainability | 10% | 70 | 7.0 |
| Testability | 5% | 20 | 1.0 |

## Code Mapping

**Implemented by:** `linter-scripts/check-spec-cross-links.py`
**Expected but missing:** `config.seed.json`, `internal/config/loader.go`, `internal/db/seeder.go`, `internal/config/schema.json`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | critical | 10/10 | Spec describes a production-ready system that does not exist in the code index. |
| 2 | missing-spec | high | 7/10 | The actual code (linter scripts) is entirely undocumented by the spec. |
| 3 | inconsistency | medium | 5/10 | The document inventory lists 6 feature files that do not exist in the file list. |
| 4 | untestable | low | 4/10 | Acceptance criteria are high-level and lack objective technical validation steps beyond a cross-link check. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] Spec describes a production-ready system that does not exist in the code index.
- **Category:** orphan-spec  |  **Impact:** 10/10
- **Evidence:** The spec describes application-level seeding (SQLite, Go, config.seed.json), but the repo only contains linter scripts.
- **Proposed correction:** Implement the CW Config pattern in the codebase or mark this spec as 'Draft/Proposed'.

#### 2. [HIGH] The actual code (linter scripts) is entirely undocumented by the spec.
- **Category:** missing-spec  |  **Impact:** 7/10
- **Evidence:** Codebase consists of ~20 linter scripts/workflows not described in the architecture spec.
- **Proposed correction:** Add a spec module or file documenting the actual codebase (linter/CI tools).

#### 3. [MEDIUM] The document inventory lists 6 feature files that do not exist in the file list.
- **Category:** inconsistency  |  **Impact:** 5/10
- **Evidence:** Overview lists 02-features/01...06, but the 'Signal metrics' and 'File inventory' show these files are missing.
- **Proposed correction:** Update the document inventory to match the actual files on disk.

#### 4. [LOW] Acceptance criteria are high-level and lack objective technical validation steps beyond a cross-link check.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** AC-01: 'Initial seed data populates all required configuration' - lacks technical definition of 'required'.
- **Proposed correction:** Define specific, measurable criteria for GORM merge-strategies and JSON schema validation.
