# Audit v2 — `spec/26-gitlogs-diagrams`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **49/100 (D)**  
**Blast radius:** 6/10

> This spec module describes diagrams and their contents but lacks the actual diagram code and database DDL, severely hindering AI implementability. It also lacks actionable acceptance criteria.


**Score justification:** The implementability is low because the spec describes diagrams with no embedded diagram code for an AI to use. Testability is capped at 20 because ac_count is 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 70 | 14.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 794,
  "ac_chars": 1837,
  "ac_count": 0,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 0,
  "code_blocks_by_lang": {},
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 4,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No mermaid diagram code is provided in the spec; only references to filenames.
- No DDL for the database schema described in AC-D-01.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `01-er-diagram.mmd`, `02-domain-design.mmd`, `03-endpoints-write.mmd`, `04-endpoints-read.mmd`, `05-auth-validation.mmd`, `06-permission-flow.mmd`, `07-rate-limit-flow.mmd`, `08-encryption-v3-flow.mmd`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 9/10 | The spec describes a set of diagrams but does not include the mermaid code for these diagrams within the spec itself. |
| 2 | missing-contract | high | 8/10 | AC-D-01 refers to a database schema without providing the actual DDL for an AI to implement. |
| 3 | missing-spec | medium | 5/10 | The spec lists diagram files in its inventory (e.g., '01-er-diagram.mmd') but these files are not found in the provided code implementation index. |
| 4 | untestable | medium | 4/10 | The spec has 0 acceptance criteria actions (ac_count), making it difficult for an AI to objectively verify implementation. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] The spec describes a set of diagrams but does not include the mermaid code for these diagrams within the spec itself.
- **Category:** missing-contract  |  **Impact:** 9/10
- **Evidence:** Overview lists files like '01-er-diagram.mmd' but these are not present in the spec module or the provided code index. AC-D-01 refers to '01-er-diagram.mmd' and database schema without providing the DDL.
- **Proposed correction:** Embed the mermaid diagram code directly into the spec using code blocks, and if referring to a database, inline the DDL.

#### 2. [HIGH] AC-D-01 refers to a database schema without providing the actual DDL for an AI to implement.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** AC-D-01: '`01-er-diagram.mmd` includes every table from `spec/22-git-logs-v2/02-database-schema.md` (Profile, RoleAssignment, RolePermission, GitProfile, Repo, RepoVersion, Pipeline, LogEntry, ErrorLogEntry, App, AppLink, History, Action, AuditTrail, MigrationState + lookup tables).'
- **Proposed correction:** Include the complete SQL DDL for all mentioned tables within the spec, or link to a definitive, inlined source within the spec module.

#### 3. [MEDIUM] The spec lists diagram files in its inventory (e.g., '01-er-diagram.mmd') but these files are not found in the provided code implementation index.
- **Category:** missing-spec  |  **Impact:** 5/10
- **Evidence:** Overview inventory lists files such as '01-er-diagram.mmd', '02-domain-design.mmd', etc., which are absent from the 'ACTUAL CODE IMPLEMENTATION INDEX'.
- **Proposed correction:** Either include the diagram files themselves in the spec module or clearly state that the spec only describes the *contents* of such diagrams, not the diagrams themselves.

#### 4. [MEDIUM] The spec has 0 acceptance criteria actions (ac_count), making it difficult for an AI to objectively verify implementation.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** Deterministic metrics show ac_count == 0.
- **Proposed correction:** Reformulate acceptance criteria using Given/When/Then (GWT) blocks for clear, verifiable actions.
