# Audit v2 — `spec/06-seedable-config-architecture`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **57/100 (D)**  
**Blast radius:** 8/10

> This module provides a good overview of a seedable config architecture and includes some SQL DDL, but lacks crucial inlined contracts (Go structs, JSON schemas) necessary for autonomous AI implementation. The number of acceptance criteria is also very low given the complexity of the domain.


**Score justification:** Implementability is capped at 50% because crucial contracts like Go structs for configuration are not inlined, forcing an AI to infer or guess. Testability is capped at 20% due to only having 2 ACs, despite having a consistency report.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 70 | 14.0 |
| Alignment | 15% | 20 | 3.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 80 | 2.4 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 7,
  "overview_chars": 3989,
  "ac_chars": 624,
  "ac_count": 2,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 16,
  "code_blocks_by_lang": {
    "plain": 2,
    "bash": 1,
    "json": 3,
    "markdown": 1,
    "sql": 3,
    "go": 2,
    "typescript": 3,
    "css": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 8,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 2
}
```

## Implementability Blockers

- Go structs for various config objects are referenced but not inlined, requiring inference.
- JSON schemas for 'config.seed.json' are mentioned but not provided.
- Specific database schema for config storage (beyond generic SQL DDL presence) is not detailed.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | Go struct definitions for configuration objects are not provided. |
| 2 | missing-contract | high | 7/10 | JSON schema for 'config.seed.json' is referenced but not included. |
| 3 | ambiguity | medium | 5/10 | The spec describes a 'SemVer-aware GORM merge' but doesn't detail the merge strategy logic. |
| 4 | missing-contract | medium | 6/10 | Specific database schema for the configuration storage is not exhaustively defined, despite mentioning 'SQLite DB'. |
| 5 | untestable | medium | 4/10 | The 'Expected' outcome for the verification command 'AC-CFG-000' is generic ('exit 0') and not specific enough for the described behavior. |

### Detail + Proposed Corrections

#### 1. [HIGH] Go struct definitions for configuration objects are not provided.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Mention of 'Go validation patterns for RAG config' and 'Go validation patterns for RAG config' in child modules suggests Go objects exist but their schema isn't inlined.
- **Proposed correction:** Inline all Go struct definitions for configuration objects directly within the spec.

#### 2. [HIGH] JSON schema for 'config.seed.json' is referenced but not included.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** The spec mentions 'First-run seeding populates SQLite DB from `config.seed.json`' and 'Seed files use JSON format with schema validation', but the JSON schema itself is missing.
- **Proposed correction:** Provide the complete JSON schema for `config.seed.json`.

#### 3. [MEDIUM] The spec describes a 'SemVer-aware GORM merge' but doesn't detail the merge strategy logic.
- **Category:** ambiguity  |  **Impact:** 5/10
- **Evidence:** Description in AC-CFG-000: 'Given Diff the running config tree against `config.seed.json` after a SemVer-aware GORM merge.'
- **Proposed correction:** Provide a detailed, step-by-step description or pseudocode of the 'SemVer-aware GORM merge' logic, including conflict resolution strategies.

#### 4. [MEDIUM] Specific database schema for the configuration storage is not exhaustively defined, despite mentioning 'SQLite DB'.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** The spec states 'First-run seeding populates SQLite DB from `config.seed.json`', but lacks detailed table schemas and relationships.
- **Proposed correction:** Include the full SQLite DDL for all tables involved in configuration storage. (The existing 'has_sql_ddl: true' likely refers to a generic example, not the specific config schema.)

#### 5. [MEDIUM] The 'Expected' outcome for the verification command 'AC-CFG-000' is generic ('exit 0') and not specific enough for the described behavior.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** The AC states: 'Expected: exit 0. Any non-zero exit is a hard fail and blocks merge.' This is a general command success expectation, not a specific verification of the config merge logic.
- **Proposed correction:** Refine the 'Expected' section for AC-CFG-000 to clearly outline how to verify the merge logic (e.g., 'Expected: The merged config in the database matches the expected output after applying the seed data and user overrides, demonstrating preservation, addition, and pruning of keys.').
