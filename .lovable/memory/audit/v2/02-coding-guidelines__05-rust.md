# Audit v2 — `spec/02-coding-guidelines/05-rust`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **73/100 (C)**  
**Blast radius:** 7/10

> This is a well-structured and clear set of coding guidelines for Rust. However, its implementability is severely hampered by the absence of a concrete Rust codebase to which it applies, resulting in an alignment score of zero. Furthermore, some key contracts like database DDL examples and a definition of 'project error codes' are missing, which would prevent a mediocre AI from fully implementing or verifying compliance without human intervention.


**Score justification:** The ac_count met the threshold for good testability. Waffle per kchar is very low, contributing to strong clarity. Links are unbroken, ensuring good consistency.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 80 | 28.0 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 10,
  "overview_chars": 3191,
  "ac_chars": 1632,
  "ac_count": 6,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 58,
  "code_blocks_by_lang": {
    "plain": 5,
    "sql": 2,
    "rust": 50,
    "toml": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 9,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.27,
  "child_modules": 0
}
```

## Implementability Blockers

_(none — AI can build this)_

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `Rust project or library implementing these guidelines`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-spec | high | 8/10 | No actual Rust codebase or module is listed as implementing these guidelines. |
| 2 | missing-contract | medium | 5/10 | While providing guidelines for database PascalCase, no DDL is inlined or referenced for concrete examples. |
| 3 | missing-contract | medium | 5/10 | The spec mentions 'project error codes' for domain errors but does not define or reference a centralized list of these codes. |

### Detail + Proposed Corrections

#### 1. [HIGH] No actual Rust codebase or module is listed as implementing these guidelines.
- **Category:** missing-spec  |  **Impact:** 8/10
- **Evidence:** The code implementation index does not show any Rust project or library that this spec applies to. The 'src/' directory is explicitly excluded as 'Lovable scaffold; not part of any spec implementation'.
- **Proposed correction:** Identify and link the Rust codebase that this spec module is intended to govern. If none exists, either create one, or remove this spec module.

#### 2. [MEDIUM] While providing guidelines for database PascalCase, no DDL is inlined or referenced for concrete examples.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** The spec mentions PascalCase for 'Database identifiers (table names, column names, view names, primary keys)' but does not provide any SQL DDL to demonstrate this or to be automatically parsable by an AI.
- **Proposed correction:** Add SQL DDL examples demonstrating the PascalCase naming convention for database identifiers within the relevant section (e.g., `01-naming-conventions.md`).

#### 3. [MEDIUM] The spec mentions 'project error codes' for domain errors but does not define or reference a centralized list of these codes.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** AC-02 states 'Domain errors use `thiserror` with project error codes' and 'All error variants map to documented error codes,' but no definition of these error codes or where to find them is provided.
- **Proposed correction:** Define the 'project error codes' or provide a clear reference to a centralized document (e.g., an enum or a separate spec module) where these error codes are documented.
