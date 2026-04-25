# Audit v2 — `spec/02-coding-guidelines/21-app`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **50/100 (D)**  
**Blast radius:** 6/10

> This module serves as a high-level organizational guideline but lacks the concrete examples and enforcement mechanisms required for AI implementability. It sets expectations without providing the tools for an AI to meet them.


**Score justification:** The implementability is low because the spec describes a conceptual organizational structure but provides no concrete examples or DDL. The alignment is low because the spec describes a conceptual organizational structure yet there is no code that implements this structure. Consistency is capped at 70 due to a broken link.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 20 | 3.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 848,
  "ac_chars": 2942,
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
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No concrete examples of 'app-specific specification content'.
- No DDL or schema definitions for how these specs would be structured or stored.
- No clear method for an AI to parse or validate placement rules without explicit code examples or a schema.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `No actual app-specific content (as this is a guidelines document)`, `Code that enforces the placement rules (e.g., a linter or CI check)`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | The spec provides guidelines for placing app-specific content but lacks concrete examples or templates for what this content should look like. |
| 2 | broken-link | medium | 5/10 | One of the internal cross-references is broken. |
| 3 | missing-contract | medium | 6/10 | The document describes placement rules, but there is no explicit linter rule or script provided to automate and enforce these rules. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec provides guidelines for placing app-specific content but lacks concrete examples or templates for what this content should look like.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Overview: 'No content yet. Add app-specific specs as numbered files within this folder.'
- **Proposed correction:** Add a concrete example of an 'app-specific specification file' with a schema or template, even if it's a placeholder, to illustrate the expected structure and content.

#### 2. [MEDIUM] One of the internal cross-references is broken.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken: 1
- **Proposed correction:** Update the broken link to point to a valid destination.

#### 3. [MEDIUM] The document describes placement rules, but there is no explicit linter rule or script provided to automate and enforce these rules.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** AC-01, AC-02, AC-03 describe placement rules but are not linked to an enforcing mechanism.
- **Proposed correction:** Provide a code snippet for a linter configuration or a script that automates the validation of the specified placement rules.
