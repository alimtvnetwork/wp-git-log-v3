# Audit v2 — `spec/03-error-manage/02-error-architecture/04-error-modal/03-error-modal-reference`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **71/100 (C)**  
**Blast radius:** 7/10

> This spec provides good detail and clear acceptance criteria within itself. However, its primary failing is the almost complete disconnect from the provided codebase, making it practically unimplementable by an AI without significant human guidance to map the specification to the actual code.


**Score justification:** The broken link significantly impacts consistency. The lack of direct code alignment lowers the alignment score. However, clear contracts and acceptance criteria bolster implementability and testability.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 80 | 28.0 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 5 | 0.8 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 16,
  "overview_chars": 6800,
  "ac_chars": 4843,
  "ac_count": 9,
  "gwt_block_count": 8,
  "consistency_report": true,
  "code_blocks_total": 34,
  "code_blocks_by_lang": {
    "plain": 10,
    "typescript": 12,
    "json": 3,
    "tsx": 9
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 45,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- The spec mentions 'React', 'Go', and 'PHP' components but provides no corresponding code references in the `File Inventory` or `Code Implementation Index`. An AI would not know where to start or how the spec maps to actual files.
- No concrete examples of error envelopes or actual API responses are provided to guide the AI coder in understanding the exact data format and structure for capturing and displaying errors. While has_json_schema and has_ts_enums are true for parts of the spec, a full end-to-end example is missing.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | A link in the spec is broken. |
| 2 | missing-spec | high | 8/10 | The spec broadly refers to React, Go, and PHP components but the code implementation index shows only linter scripts and a generic 'src/' folder, making it impossible for an AI to map the spec to the code. |
| 3 | missing-contract | medium | 7/10 | While 'has_json_schema' is true and some interfaces are inlined, a complete, end-to-end example of an error envelope, including example values for all fields, is missing. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] A link in the spec is broken.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** > **Parent:** [Error Modal Spec](../00-overview.md)
- **Proposed correction:** Update the link `../00-overview.md` to a valid path.

#### 2. [HIGH] The spec broadly refers to React, Go, and PHP components but the code implementation index shows only linter scripts and a generic 'src/' folder, making it impossible for an AI to map the spec to the code.
- **Category:** missing-spec  |  **Impact:** 8/10
- **Evidence:** Overview mentions 'React → Go → Delegated Server request chain' and a 'Location: `src/components/errors/`' but the actual code index does not reflect this structure, making the `src/` reference vague.
- **Proposed correction:** Explicitly list and link to the relevant React, Go, and PHP source code files within the 'File Inventory' or provide a dedicated 'Code Mapping' section in the spec to clearly define which code implements which part of the spec.

#### 3. [MEDIUM] While 'has_json_schema' is true and some interfaces are inlined, a complete, end-to-end example of an error envelope, including example values for all fields, is missing.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** The 'RawEnvelope Shape' outlines the structure but lacks concrete example data. 'CapturedError Interface Snippet' also lacks full example values.
- **Proposed correction:** Provide full JSON or Typescript literal examples for the `RawEnvelope` and `CapturedError` interfaces, complete with example values for all fields, to aid in understanding the data's expected content and format.
