# Audit v2 — `spec/02-coding-guidelines/11-security`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **87/100 (A)**  
**Blast radius:** 8/10

> This is a well-structured and mostly comprehensive security guideline spec. It provides clear ACs and even inlines critical data tables, making it highly implementable. The primary gap is the lack of inlining of the `check-axios-version.sh` logic, which prevents true AI self-implementation.


**Score justification:** The spec is largely self-contained with clear ACs and inlined contracts. The implementability is capped due to one critical finding. The alignment is strong, with directly linked code, though one critical piece is not directly referenced in the code alignment. All metrics are excellent.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 80 | 28.0 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 80 | 12.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 85 | 6.0 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 2282,
  "ac_chars": 3192,
  "ac_count": 6,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 1,
  "code_blocks_by_lang": {
    "plain": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 10,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.13,
  "child_modules": 1
}
```

## Implementability Blockers

- The `check-axios-version.sh` script is not directly referenced in the spec's code mapping, making it harder for an AI to identify the correct implementation context.

## Code Mapping

**Implemented by:** `linter-scripts/check-axios-version.sh`, `linter-scripts/generate-gwt-acceptance.py`
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 8/10 | The spec explicitly calls for all contracts to be inlined, and while the Axios version matrix and document structure are inlined, the actual implementation of the Axios version check is a shell script (`check-axios-version.sh`) which is not inlined. |
| 2 | ambiguity | medium | 5/10 | AC-05 mentions 'unlisted version of Axios (e.g., 1.15.0)' and 'unlisted version is detected in configuration'. The term 'configuration' is vague. It's unclear what specific configuration files or methods for detection an AI should check. |
| 3 | missing-contract | low | 3/10 | The 'Security Document Structure' explicitly lists four required files for subfolders, but does not provide any content or schema for these files beyond their names. An AI would be unsure about the expected content of `01-implementation-rules.md` or `02-security-notes.md`. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] The spec explicitly calls for all contracts to be inlined, and while the Axios version matrix and document structure are inlined, the actual implementation of the Axios version check is a shell script (`check-axios-version.sh`) which is not inlined.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** AC-01, AC-02, AC-03 reference Axios version pinning. The `check-axios-version.sh` implements this, but the script itself or its logic is not inlined.
- **Proposed correction:** Inline the `check-axios-version.sh` script or its core logic (e.g., regex patterns, version comparison rules) directly into the 'Inlined Contracts' section of the acceptance criteria to ensure full self-containment.

#### 2. [MEDIUM] AC-05 mentions 'unlisted version of Axios (e.g., 1.15.0)' and 'unlisted version is detected in configuration'. The term 'configuration' is vague. It's unclear what specific configuration files or methods for detection an AI should check.
- **Category:** ambiguity  |  **Impact:** 5/10
- **Evidence:** AC-05: "When An unlisted version is detected in configuration Then The version MUST be treated as BLOCKED until it undergoes manual verification and is added to the Version Matrix."
- **Proposed correction:** Clarify which configuration files (e.g., `package.json`, `.npmrc`, CI/CD pipeline definitions) or external registries should be monitored for unlisted Axios versions.

#### 3. [LOW] The 'Security Document Structure' explicitly lists four required files for subfolders, but does not provide any content or schema for these files beyond their names. An AI would be unsure about the expected content of `01-implementation-rules.md` or `02-security-notes.md`.
- **Category:** missing-contract  |  **Impact:** 3/10
- **Evidence:** Security Document Structure section in 'Acceptance Criteria': lists `00-overview.md`, `01-implementation-rules.md`, `02-security-notes.md`, `99-consistency-report.md` as required.
- **Proposed correction:** Provide a template or a minimum set of expected headings/sections for each of the required files (`01-implementation-rules.md`, `02-security-notes.md`) to guide content creation and ensure consistency.
