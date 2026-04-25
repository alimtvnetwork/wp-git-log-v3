# Audit v2 — `spec/15-distribution-and-runner`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **67/100 (C)**  
**Blast radius:** 7/10

> This module outlines key distribution and runner components but lacks crucial inlined code for scripts and CI/CD, significantly hindering AI implementability. The presence of JSON schema and OpenAPI definitions is a positive.


**Score justification:** Implementability is low because while JSON schemas and YAML OpenAPI definitions are present, crucial contracts like shell scripts (install/run) and the CI/CD pipeline are described only in prose, requiring significant human interpretation. Consistency is affected by a broken link. Clarity is high due to low waffle_per_kchar.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 70 | 10.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 8,
  "overview_chars": 5469,
  "ac_chars": 2687,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 14,
  "code_blocks_by_lang": {
    "plain": 3,
    "json": 4,
    "bash": 4,
    "powershell": 1,
    "yaml": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_total": 25,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.08,
  "child_modules": 0
}
```

## Implementability Blockers

- Description of install scripts (install.sh, install.ps1) is prose, not executable code.
- Description of runner scripts (run.sh, run.ps1) is prose, not executable code.
- Description of CI/CD pipeline (.github/workflows/release.yml) is prose, not executable YAML.

## Code Mapping

**Implemented by:** `linter-scripts/run.sh`, `linter-scripts/run.ps1`, `.github/workflows/spec-health.yml`
**Expected but missing:** `.github/workflows/release.yml`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | Install and runner scripts are described in prose, not inlined code. |
| 2 | missing-contract | high | 8/10 | CI/CD release pipeline is described in prose, not inlined YAML. |
| 3 | broken-link | medium | 3/10 | One broken link found in cross-references section. |

### Detail + Proposed Corrections

#### 1. [HIGH] Install and runner scripts are described in prose, not inlined code.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Overview mentions 'install scripts (install.sh, install.ps1)' and 'runner scripts (run.sh, run.ps1)' but their full implementation is not present.
- **Proposed correction:** Inline the full code for install.sh, install.ps1, run.sh, and run.ps1.

#### 2. [HIGH] CI/CD release pipeline is described in prose, not inlined YAML.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Overview mentions 'CI/CD release pipeline (.github/workflows/release.yml)' but the full YAML is not present.
- **Proposed correction:** Inline the full YAML for .github/workflows/release.yml.

#### 3. [MEDIUM] One broken link found in cross-references section.
- **Category:** broken-link  |  **Impact:** 3/10
- **Evidence:** Cross-references section in 00-overview.md has a truncated link: `[`spec/12-cicd-pipeline-workflows/`(`
- **Proposed correction:** Fix the broken link in `00-overview.md` to `spec/12-cicd-pipeline-workflows/00-overview.md` or similar valid path.
