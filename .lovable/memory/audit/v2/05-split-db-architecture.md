# Audit v2 — `spec/05-split-db-architecture`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **64/100 (C)**  
**Blast radius:** 8/10

> This spec provides a well-structured architectural overview with some DDL and JSON schemas, but it falls short on implementability due to a significant lack of concrete implementation contracts for critical features. A mediocre AI coder would struggle to implement this without substantial human intervention to define these missing contracts.


**Score justification:** The spec has SQL DDL and JSON schemas, improving implementability, but crucial contracts like schema enforcement mechanisms and specific implementation patterns are missing. The low alignment score is due to the spec describing architectural patterns with no direct code implementation within the provided codebase.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 50 | 17.5 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 20 | 3.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 7,
  "overview_chars": 3931,
  "ac_chars": 639,
  "ac_count": 2,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 24,
  "code_blocks_by_lang": {
    "plain": 5,
    "bash": 1,
    "sql": 3,
    "go": 14,
    "json": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 8,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 2
}
```

## Implementability Blockers

- Lack of defined schema enforcement mechanisms for the databases.
- Absence of concrete code examples for database creation and management, beyond simple DDL.
- The specification does not detail the exact interaction patterns between the Root DB and child databases, particularly concerning data consistency and transaction management.
- The mechanism for dynamic database creation is not fully specified, including considerations for naming conventions, storage, and lifecycle management.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `Database creation and management utilities`, `Connection pooling implementation`, `WAL mode configuration`, `Backup and restore utilities`, `Cross-database query helpers`, `Migration system for schema changes`, `Casbin RBAC integration`, `User-scoped database isolation logic`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | The spec broadly discusses the need for 'schema enforcement mechanisms' and 'migration system handles schema changes' but provides no concrete DDL for how these would be implemented or managed for dynamically created databases. |
| 2 | missing-contract | high | 7/10 | While WAL mode is mentioned, the specific configuration and interaction with connection pooling for performance and concurrency are not detailed, which is crucial for an AI to implement efficiently. |
| 3 | missing-contract | medium | 5/10 | The spec outlines 'AC-02: Data Integrity' with 'Backup and restore operations handle all database partitions', but a contract for the backup and restore mechanism is missing. |
| 4 | missing-contract | high | 8/10 | The spec mentions RBAC with Casbin but lacks any contract for its implementation, such as policy definitions, enforcement points, or integration with the hierarchical database structure. |
| 5 | missing-contract | high | 8/10 | The 'user-scoped isolation' feature is critical but lacks detailed implementation contracts, leaving an AI coder to guess how row-level or database-level isolation is achieved. |
| 6 | missing-contract | high | 8/10 | The core concept of 'dynamic database creation as needed' is mentioned, but without concrete contracts detailing the lifecycle, naming conventions, and API for managing these dynamic databases. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec broadly discusses the need for 'schema enforcement mechanisms' and 'migration system handles schema changes' but provides no concrete DDL for how these would be implemented or managed for dynamically created databases.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** AC-01: 'Migration system handles schema changes per database partition'.
- **Proposed correction:** Add detailed DDL for schema enforcement, including examples for versioning and upgrade paths across different database partitions. Define the contract for the migration system.

#### 2. [HIGH] While WAL mode is mentioned, the specific configuration and interaction with connection pooling for performance and concurrency are not detailed, which is crucial for an AI to implement efficiently.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** Overview: 'wal-mode', 'connection-pooling'.
- **Proposed correction:** Specify the configuration parameters for WAL mode, connection pooling, and how they interact to ensure optimal performance and data consistency in a multi-threaded or concurrent environment.

#### 3. [MEDIUM] The spec outlines 'AC-02: Data Integrity' with 'Backup and restore operations handle all database partitions', but a contract for the backup and restore mechanism is missing.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** AC-02: 'Backup and restore operations handle all database partitions'.
- **Proposed correction:** Provide a concrete contract or pseudo-code for the backup and restore operations, detailing how data across multiple SQLite files would be consistently backed up and restored.

#### 4. [HIGH] The spec mentions RBAC with Casbin but lacks any contract for its implementation, such as policy definitions, enforcement points, or integration with the hierarchical database structure.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** 02-features/04-rbac-casbin.md (referenced child module); AC-SDB-000: 'Casbin RBAC enforcement points'.
- **Proposed correction:** Include concrete Casbin policy definitions (e.g., `.conf` files) and API contracts for how these policies are loaded, enforced, and integrated with user/role management in the Split DB architecture.

#### 5. [HIGH] The 'user-scoped isolation' feature is critical but lacks detailed implementation contracts, leaving an AI coder to guess how row-level or database-level isolation is achieved.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** 02-features/05-user-scoped-isolation.md (referenced child module); AC-SDB-000: 'user-scope isolation is enforced by row filters'.
- **Proposed correction:** Provide explicit contracts for user-scoped isolation, including examples of schema design for multi-tenancy, row-level security predicates, or database per user patterns with concrete implementations.

#### 6. [HIGH] The core concept of 'dynamic database creation as needed' is mentioned, but without concrete contracts detailing the lifecycle, naming conventions, and API for managing these dynamic databases.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Summary: 'item-specific databases are created dynamically as needed'.
- **Proposed correction:** Define a clear API contract for creating, managing, and destroying dynamic databases, including expected naming conventions, storage considerations, and lifecycle hooks.
