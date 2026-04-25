# Audit v2 — `spec/02-coding-guidelines/07-csharp`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **56/100 (D)**  
**Blast radius:** 8/10

> This spec provides a clear and consistent set of C# coding guidelines. However, its implementability by an AI is severely hampered by the lack of concrete C# code examples and the complete absence of C# code in the provided codebase index.


**Score justification:** The spec is well-written and clear, with no broken links and a low waffle score. However, it lacks concrete code examples to guide an AI, and there's no code in the provided index that implements these C# guidelines, leading to a very low implementability and alignment score.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 8,
  "overview_chars": 1982,
  "ac_chars": 3259,
  "ac_count": 7,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 15,
  "code_blocks_by_lang": {
    "csharp": 15
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 32,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.17,
  "child_modules": 0
}
```

## Implementability Blockers

- No concrete C# code examples provided within the spec for the AI to learn from.
- No DDL, JSON schema, or OpenAPI definitions to define data contracts.
- Lack of actual C# code in the codebase index makes it impossible for an AI to see how these guidelines are applied in practice.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | The spec provides coding guidelines but lacks concrete, inlined C# code examples to demonstrate the application of these rules. |
| 2 | drift | critical | 9/10 | The spec module outlines C# coding guidelines, but there is no C# code in the provided codebase index that demonstrably implements these guidelines. |
| 3 | untestable | medium | 5/10 | While AC are present, the absence of GWT blocks makes automated testing and AI validation of compliance more challenging. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec provides coding guidelines but lacks concrete, inlined C# code examples to demonstrate the application of these rules.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Pure prose guidelines without corresponding C# snippets for each rule.
- **Proposed correction:** Incorporate small, illustrative C# code snippets for each guideline in the main specification files (e.g., `01-naming-and-conventions.md`, `02-method-design.md`).

#### 2. [CRITICAL] The spec module outlines C# coding guidelines, but there is no C# code in the provided codebase index that demonstrably implements these guidelines.
- **Category:** drift  |  **Impact:** 9/10
- **Evidence:** The 'ACTUAL CODE IMPLEMENTATION INDEX' shows a list of linter scripts and miscellaneous files, but no C# project or files that would be subject to these guidelines.
- **Proposed correction:** Either provide relevant C# code in the index (e.g., an example project or repository) that is intended to follow these guidelines, or clarify if this spec is purely theoretical/aspirational without a current codebase it governs.

#### 3. [MEDIUM] While AC are present, the absence of GWT blocks makes automated testing and AI validation of compliance more challenging.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** gwt_block_count == 0, ac_count == 7.
- **Proposed correction:** Reformat existing acceptance criteria into GWT (Given/When/Then) blocks to improve testability and machine-readability for automated enforcement.
