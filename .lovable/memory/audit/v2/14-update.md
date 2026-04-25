# Audit v2 — `spec/14-update`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **82/100 (B)**  
**Blast radius:** 9/10

> This module is a well-structured and detailed specification for a CLI update mechanism, but its implementability by an AI is severely hampered by the lack of inlined code contracts and a complete misalignment with the provided codebase index. The absence of the actual CLI implementation code makes it impossible for an AI to 'ship a working implementation'.


**Score justification:** The module is generally well-written and clear. However, due to the large number of child modules and the lack of inlined code contracts within the main overview, the implementability is capped. Alignment is reduced because the spec details a CLI update mechanism, but the provided code index contains only linter scripts and internal scaffold code, not the actual CLI implementation.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 80 | 28.0 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 60 | 9.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 31,
  "overview_chars": 7401,
  "ac_chars": 3319,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 229,
  "code_blocks_by_lang": {
    "plain": 76,
    "bash": 70,
    "powershell": 33,
    "json": 7,
    "go": 34,
    "yaml": 7,
    "markdown": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_total": 137,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.3,
  "child_modules": 2
}
```

## Implementability Blockers

- Lack of inlined code contracts (e.g., Go structs for specified JSON schemas, detailed interface definitions).
- Dependency on numerous child modules for full implementation details, requiring an AI to synthesize information across many files.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `Core CLI update mechanism code (Go)`, `Installation scripts (bash, powershell)`, `Binary handoff and cleanup logic`, `Version probing and verification logic`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 7/10 | While JSON schemas are indicated, the actual JSON schemas and other crucial code contracts (e.g., Go structs for API responses or configuration files, shell script interfaces) are not inlined within the overview or readily discoverable. |
| 2 | drift | high | 8/10 | The spec describes a comprehensive CLI update mechanism in Go, but the provided 'ACTUAL CODE IMPLEMENTATION INDEX' lists only linter scripts and generic scaffold code. There is a complete mismatch between the described functionality and the available code. |

### Detail + Proposed Corrections

#### 1. [HIGH] While JSON schemas are indicated, the actual JSON schemas and other crucial code contracts (e.g., Go structs for API responses or configuration files, shell script interfaces) are not inlined within the overview or readily discoverable.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** has_json_schema: true but actual schemas not inlined in overview; description of 'latest.json schema' without providing it.
- **Proposed correction:** Inline all critical JSON schemas, Go struct definitions, and shell/powershell script function signatures directly into the relevant overview documents or dedicated contract files that are easily referenced.

#### 2. [HIGH] The spec describes a comprehensive CLI update mechanism in Go, but the provided 'ACTUAL CODE IMPLEMENTATION INDEX' lists only linter scripts and generic scaffold code. There is a complete mismatch between the described functionality and the available code.
- **Category:** drift  |  **Impact:** 8/10
- **Evidence:** Description of CLI update mechanisms, Go CLI tools, `run.ps1`/`run.sh` build/release scripts, `updater-binary.md`, etc., versus a code index containing only linters and an empty 'src/' folder for TS/TSX.
- **Proposed correction:** Either provide the actual Go CLI implementation code that corresponds to this specification, or explicitly state that this is a pure design document with no accompanying code in this index.
