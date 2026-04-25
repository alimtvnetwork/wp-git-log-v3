# Audit v2 — `spec/16-generic-release`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **76/100 (B)**  
**Blast radius:** 7/10

> This spec provides a comprehensive blueprint for a generic release pipeline. However, its implementability is hampered by the lack of explicit data models and the need to refer to external specs for critical contracts. The alignment is low because the provided implementation code does not reflect the generic release pipeline.


**Score justification:** This spec module has a high waffle per kchar, but it also has a consistency report, many code blocks, and no broken links. The low alignment score is due to the fact that the spec describes a generic release pipeline, while the codebase contains linter scripts.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 80 | 28.0 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 20 | 3.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 12,
  "overview_chars": 5168,
  "ac_chars": 2839,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 89,
  "code_blocks_by_lang": {
    "bash": 35,
    "yaml": 11,
    "plain": 27,
    "markdown": 4,
    "powershell": 9,
    "json": 1,
    "diff": 1,
    "go": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_total": 41,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.2,
  "child_modules": 0
}
```

## Implementability Blockers

- No explicit data models or schemas beyond JSON/YAML examples.
- Crucial contracts like shell activation or terminal output design are referenced but not inlined, requiring navigation to sibling specs.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `the described generic, reusable blueprint for releasing cross-compiled CLI binaries via CI/CD`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 7/10 | While JSON/YAML schemas are present as examples, a formal, explicit data model is not provided (e.g., as a dedicated `schema.json` or equivalent). |
| 2 | missing-contract | medium | 5/10 | Critical external contracts like terminal output design and post-install shell activation are referenced via relative links but not explicitly inlined or summarized, requiring navigation to other specs. |

### Detail + Proposed Corrections

#### 1. [HIGH] While JSON/YAML schemas are present as examples, a formal, explicit data model is not provided (e.g., as a dedicated `schema.json` or equivalent).
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** has_json_schema: true but no dedicated schema file, rather examples are provided in various `md` files.
- **Proposed correction:** Create a `09-schemas.json` or `09-data-models.md` file that explicitly defines all data structures and contracts, including their types, constraints, and relationships.

#### 2. [MEDIUM] Critical external contracts like terminal output design and post-install shell activation are referenced via relative links but not explicitly inlined or summarized, requiring navigation to other specs.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** Related local specs: `../13-generic-cli/20-terminal-output-design.md`, `../13-generic-cli/21-post-install-shell-activation.md`
- **Proposed correction:** Inline the core definitions of `terminal-output-design` and `post-install-shell-activation` directly within this spec, or at minimum provide a concise summary of their key contracts.
