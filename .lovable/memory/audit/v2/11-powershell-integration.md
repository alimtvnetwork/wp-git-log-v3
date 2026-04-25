# Audit v2 — `spec/11-powershell-integration`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **71/100 (C)**  
**Blast radius:** 7/10

> This module provides a good conceptual overview for PowerShell integration but lacks critical inlined code and schema definitions to be fully implementable by a mediocre AI. It currently serves more as a design document than a self-contained implementation spec, but it could be improved with concrete code. The module's acceptance criteria are good and comprehensive.


**Score justification:** The implementability is capped due to the lack of inlined DDL for the configuration schema, requiring external lookup for data shapes. However, clarity is high due to low waffle, and consistency is perfect due to no broken links. Testability is good with 5 ACs.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 80 | 12.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 15,
  "overview_chars": 11739,
  "ac_chars": 2914,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 77,
  "code_blocks_by_lang": {
    "plain": 10,
    "json": 20,
    "powershell": 39,
    "gitignore": 2,
    "bash": 2,
    "yaml": 2,
    "php": 1,
    "md": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_total": 34,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.09,
  "child_modules": 0
}
```

## Implementability Blockers

- JSON schema for `powershell.json` is not directly inlined, only represented by example snippets. An AI would need to infer the full schema, including allowed fields, types, and constraints, which can lead to errors.
- The spec describes PowerShell scripts (`run.ps1`) but doesn't provide the complete, executable code of these scripts, only conceptual steps and fragments. A mediocre AI cannot implement a working system from these fragments alone.

## Code Mapping

**Implemented by:** `linter-scripts/run.ps1`
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | The full JSON schema for `powershell.json` is not explicitly defined or inlined. While examples are given, a complete schema including all fields, types, and constraints is missing. |
| 2 | missing-contract | critical | 10/10 | The core PowerShell scripts (`run.ps1`) described conceptually are not fully provided as executable code. Only high-level steps and fragments are present. |

### Detail + Proposed Corrections

#### 1. [HIGH] The full JSON schema for `powershell.json` is not explicitly defined or inlined. While examples are given, a complete schema including all fields, types, and constraints is missing.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Overview mentions `powershell.json` config, and `01-configuration-schema.md` likely provides examples, but no single, comprehensive JSON schema definition is present.
- **Proposed correction:** Inline the complete JSON schema for `powershell.json` within a dedicated section or file, adhering to a standard schema definition language (e.g., JSON Schema draft 7).

#### 2. [CRITICAL] The core PowerShell scripts (`run.ps1`) described conceptually are not fully provided as executable code. Only high-level steps and fragments are present.
- **Category:** missing-contract  |  **Impact:** 10/10
- **Evidence:** The spec details 'Pipeline Steps' and 'Architecture' implying PowerShell scripts, but the actual script code that performs these actions is omitted.
- **Proposed correction:** Include the complete and executable `run.ps1` PowerShell script, or equivalent, directly within the spec, or link to a definitive, version-controlled source for the script.
