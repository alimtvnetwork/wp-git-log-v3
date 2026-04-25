# Audit v2 — `spec/02-coding-guidelines`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **50/100 (D)**  
**Blast radius:** 8/10

> This module provides a good overview of coding guidelines with deterministic metrics and a strong emphasis on AI adherence to rules. However, its implementability for an AI is significantly hindered by the lack of inlined contracts (like DDL, precise enum definitions, and complete schemas), absence of GWT blocks for concrete examples, and a fragmented approach to its 'single canonical location' claim. The disconnect between spec and code also needs to be addressed.


**Score justification:** Implementability is low because while DDL is present, it's not inlined, requiring additional steps. There are also no GWT blocks for concrete examples, and a broken link impacts consistency. Alignment is 0 as the spec doesn't align with the listed code.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 80 | 2.4 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 6,
  "overview_chars": 10822,
  "ac_chars": 3327,
  "ac_count": 5,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 32,
  "code_blocks_by_lang": {
    "plain": 2,
    "bash": 1,
    "go": 16,
    "ts": 1,
    "typescript": 11,
    "sql": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 31,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.1,
  "child_modules": 16
}
```

## Implementability Blockers

- No inlined DDL for database conventions (requires external lookup).
- Lack of GWT (Given/When/Then) blocks for concrete examples of guidelines.
- Multiple child modules for different languages, but the specific guidelines within them are not inlined.
- Unclear how AI should handle `consolidated-review-guide-condensed.md` and `consolidated-review-guide.md` files; it's unclear whether these are meant to be consumed by the AI or just for human consumption.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `Coding guideline validation logic (not covered by existing linters).`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 7/10 | Database conventions are mentioned but the DDL is not inlined within the spec, requiring external lookup for AI implementation. |
| 2 | untestable | medium | 5/10 | Absence of GWT (Given/When/Then) blocks makes it difficult for an AI to generate concrete examples and for automated testing. |
| 3 | broken-link | medium | 3/10 | One broken link within the spec impacts consistency and clarity. |
| 4 | inconsistency | medium | 5/10 | The spec states it is the 'single canonical location' for guidelines, yet it refers to numerous child modules and external links for details, creating a fragmented information architecture. |
| 5 | missing-spec | high | 8/10 | While comprehensive, the spec lacks specific guidance on how an AI should process and interpret the existence of both 'consolidated-review-guide-condensed.md' and 'consolidated-review-guide.md' files. |

### Detail + Proposed Corrections

#### 1. [HIGH] Database conventions are mentioned but the DDL is not inlined within the spec, requiring external lookup for AI implementation.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** Overview mentions 'Database conventions — Singular table names...FK uses the exact PK name. See [Database Conventions](../04-database-conventions/00-overview.md)' but the DDL is not present in this spec module.
- **Proposed correction:** Inline the SQL DDL for all database conventions or provide a clear, direct reference to the DDL within the spec itself.

#### 2. [MEDIUM] Absence of GWT (Given/When/Then) blocks makes it difficult for an AI to generate concrete examples and for automated testing.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** gwt_block_count is 0.
- **Proposed correction:** Add GWT blocks to all acceptance criteria to provide clear, actionable examples for AI and testing.

#### 3. [MEDIUM] One broken link within the spec impacts consistency and clarity.
- **Category:** broken-link  |  **Impact:** 3/10
- **Evidence:** links_broken is 1.
- **Proposed correction:** Fix the broken link to ensure all references are resolvable and the spec is consistent.

#### 4. [MEDIUM] The spec states it is the 'single canonical location' for guidelines, yet it refers to numerous child modules and external links for details, creating a fragmented information architecture.
- **Category:** inconsistency  |  **Impact:** 5/10
- **Evidence:** ''This folder is the **single canonical location** for all language-specific and cross-language coding guidelines...''. However, there are many child sub-modules and external links for details, e.g., 'See 05-rust/01-naming-conventions.md for the complete Rust naming reference.'
- **Proposed correction:** Either truly consolidate all detailed guidelines within this module or rephrase the 'single canonical location' claim to accurately reflect the distributed nature of the documentation.

#### 5. [HIGH] While comprehensive, the spec lacks specific guidance on how an AI should process and interpret the existence of both 'consolidated-review-guide-condensed.md' and 'consolidated-review-guide.md' files.
- **Category:** missing-spec  |  **Impact:** 8/10
- **Evidence:** Two files, `consolidated-review-guide-condensed.md` and `consolidated-review-guide.md`, are present in the inventory but their purpose and relationship to what the AI should consume are unclear.
- **Proposed correction:** Add explicit instructions within the spec describing the purpose of 'condensed' vs. full review guides and which one(s) the AI should prioritize for ingestion and adherence.
