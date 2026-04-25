# Audit v2 — `spec/02-coding-guidelines/01-cross-language/02-boolean-principles`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **65/100 (C)**  
**Blast radius:** 7/10

> This module provides a good overview of Boolean Principles with clear acceptance criteria. However, significant implementability gaps exist due to external dependencies on un-inlined linter logic and code generation rules, which would prevent a mediocre AI from fully implementing the spec.


**Score justification:** Implementability is low because while contracts are inlined the spec still references external linters and code generators without inlining their logic. Consistency is capped due to broken links. Alignment is low because the many linting and code generation tools mentioned are not directly mapped to the code index.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 60 | 9.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 75 | 2.2 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 8,
  "overview_chars": 3375,
  "ac_chars": 5368,
  "ac_count": 11,
  "gwt_block_count": 10,
  "consistency_report": true,
  "code_blocks_total": 40,
  "code_blocks_by_lang": {
    "php": 12,
    "typescript": 12,
    "go": 14,
    "csharp": 1,
    "python": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 31,
  "links_broken": 2,
  "todo_density": 0,
  "waffle_per_kchar": 0.2,
  "child_modules": 0
}
```

## Implementability Blockers

- Lack of inlined linter logic for BOOL-NEG-001 and CODE-RED-002 makes AI implementation of actual linting impossible without further external information.
- The 'Codegen tool' (linters-cicd/codegen/) is referenced but its specific logic for emitting Go methods, PHP traits, and TypeScript getters is not inlined.
- Go-specific rules (P7, P8) and exemptions are mentioned but not fully detailed in the provided Go Boolean Standards link.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `linter-scripts/check-boolean-principles.py`, `linter-scripts/check-boolean-principles.go`, `linters-cicd/codegen/*`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | Two broken links were detected in the spec module, impacting direct navigation to related documentation. |
| 2 | missing-contract | high | 8/10 | The spec references specific linters (BOOL-NEG-001, CODE-RED-002) and a code generation tool without inlining their detailed logic. |
| 3 | missing-spec | medium | 6/10 | The spec mentions Go-specific rules (P7, P8) and exemptions but does not provide their full details. |
| 4 | inconsistency | low | 3/10 | The 'AI Confidence' is stated as 'Production-Ready' but the audit reveals significant implementability gaps for an AI. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] Two broken links were detected in the spec module, impacting direct navigation to related documentation.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken: 2
- **Proposed correction:** Update the broken links specified in the overview section to point to valid resources: 'No Raw Negations' and 'PHP Boolean Guard Inventory'.

#### 2. [HIGH] The spec references specific linters (BOOL-NEG-001, CODE-RED-002) and a code generation tool without inlining their detailed logic.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** The 'Inlined Contracts' section outlines linter codes but does not provide the actual rules or code for these linters or the code generator.
- **Proposed correction:** Inline the full definitions of the linter rules (BOOL-NEG-001, CODE-RED-002) and the code generation logic within the spec, or provide direct, implementable pseudo-code.

#### 3. [MEDIUM] The spec mentions Go-specific rules (P7, P8) and exemptions but does not provide their full details.
- **Category:** missing-spec  |  **Impact:** 6/10
- **Evidence:** Cross-References includes 'Go Boolean Standards (P7, P8)' which would contain these details but they are not inlined.
- **Proposed correction:** Inline the full details of Go-specific rules (P7, P8) and their exemptions directly into the spec module to ensure completeness and implementability.

#### 4. [LOW] The 'AI Confidence' is stated as 'Production-Ready' but the audit reveals significant implementability gaps for an AI.
- **Category:** inconsistency  |  **Impact:** 3/10
- **Evidence:** AI Confidence: Production-Ready, yet implementability score is 45.
- **Proposed correction:** Adjust the 'AI Confidence' rating to better reflect the current implementability score and the identified gaps.
