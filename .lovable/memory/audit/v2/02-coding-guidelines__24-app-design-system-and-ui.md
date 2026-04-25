# Audit v2 — `spec/02-coding-guidelines/24-app-design-system-and-ui`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **71/100 (C)**  
**Blast radius:** 5/10

> This spec provides a good structural definition for a design system, but lacks machine-readable contracts and has no corresponding code implementation. Its value to an AI coder is limited without actual code.


**Score justification:** The implementability is capped at 70 because while contracts are inlined, they are not machine-readable (e.g., no JSON schema for theming keys). The alignment is very low because the spec describes UI components but the codebase contains only linter scripts.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 70 | 24.5 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 20 | 3.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 766,
  "ac_chars": 3800,
  "ac_count": 6,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 0,
  "code_blocks_by_lang": {},
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 8,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- Theming keys are defined in prose, not machine-readable format (e.g., JSON schema or TypeScript enums).
- Layout constants are defined in prose, not machine-readable format.
- No DDL for database definitions (though not explicitly required for this spec, it's a general observation).

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `Any UI code implementing the design system (e.g., React components, CSS files, design tokens).`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 7/10 | Theming keys and layout constants are defined in prose, which is not easily machine-readable for AI implementation. |
| 2 | drift | critical | 10/10 | The spec describes a design system and UI components, but the codebase only contains linter scripts and no actual UI implementation. |

### Detail + Proposed Corrections

#### 1. [HIGH] Theming keys and layout constants are defined in prose, which is not easily machine-readable for AI implementation.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** Inlined Contracts section under 'Theming Keys' and 'Layout Constants'.
- **Proposed correction:** Convert theming keys and layout constants into machine-readable formats such as JSON schema or TypeScript enums.

#### 2. [CRITICAL] The spec describes a design system and UI components, but the codebase only contains linter scripts and no actual UI implementation.
- **Category:** drift  |  **Impact:** 10/10
- **Evidence:** Overview mentions 'App-specific design system specifications, theming rules, component patterns, and layout conventions', while the code inventory lists only linter scripts and an empty 'src/' directory.
- **Proposed correction:** Either provide actual UI code that implements the design system or clearly state that this is a pure documentation spec without corresponding code.
