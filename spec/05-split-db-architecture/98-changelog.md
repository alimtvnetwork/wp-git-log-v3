# Changelog — Split Database Architecture

**Version:** 4.3.0
**Updated:** 2026-04-30
**Scope:** `spec/05-split-db-architecture/`

---

## Releases

### 4.3.0 — 2026-04-30 — Phase 153 Task A14 — spec/05 v6 audit close-out (89 → ≥92 expected)
- **Action**: Closed all 3 audit-v6 findings against spec/05 (the lone GOOD module within striking distance of EXCELLENT). (1) **AC-SD-22 polyglot extension**: added language-agnostic retry-loop pseudo-code (Then-clause appendix) + per-language driver mappings for PHP/Rust/C#/TS/Python (PDO `SQLSTATE[HY000]:5`; `rusqlite::ErrorCode::DatabaseBusy|Locked`; `Microsoft.Data.Sqlite.SqliteException` codes 5/6; `better-sqlite3` `SQLITE_BUSY|SQLITE_LOCKED`; `sqlite3.OperationalError` `database is locked`). Closes v6 D3 MEDIUM "Incomplete Concurrency Implementation for Non-Go Languages". Pseudo-code is normative; deviation requires §98 row + new AC. (2) **AC-SD-24 (NEW `[critical]`)**: cross-module link-don't-restate harness pin per Lesson #36 — declares AC-SD-01/AC-SD-02's `../02-coding-guidelines/01-cross-language/97-acceptance-criteria.md` references as canonical (NOT to be inlined; AC-CL-* surface evolves under spec/02 §98 — any inline copy would dual-source-drift). Closes v6 D5 HIGH "Unresolved External Dependency: Coding Guidelines" as harness bundling-cap artifact. Mirror of 13-module pattern (spec/03/07/10/11/12/13/14/16/17/18/22/25/28). (3) **AC-SD-25 (NEW `[high]`)**: ProjectSlug↔Project.Slug binding contract — `{ProjectSlug}` placeholder in canonical paths (AC-SD-03) MUST byte-equal Root DB `Project.Slug`; slug derivation (NFC → lowercase → `[^a-z0-9-]→-` → collapse → trim → reject-empty); IMMUTABLE post-creation; regex `^[a-z0-9](?:[a-z0-9-]{0,62}[a-z0-9])?$` enforced at INSERT + path materialization (defense-in-depth); `UNIQUE NOT NULL` constraint. Closes v6 D1 LOW "Ambiguous 'ProjectSlug' Source".
- **Spec lockstep**: §97 v4.2.0 → **v4.3.0** (AC count 23 → 25 + AC-SD-22 polyglot extension under existing AC); §00 v4.2.0 → **v4.3.0** (banner-only — no §00 content edit); §98 v4.2.0 → **v4.3.0**; §99 v4.0.2 → **v4.0.3**. **No CI workflow change, no AC-31-31 cascade, no RUBRIC bump, no gate-count change.**
- **Lesson #36 reinforced**: AC-SD-24 IS the lesson — explicitly declares the cross-module link-don't-restate invariant rather than working around the audit finding by inlining content.

### 4.2.0 — 2026-04-30 — Phase 153 LOW-batch close-out — AC-SD-05 SequenceNum scope clarification
- **Action**: Audit-v6 LOW finding `[D1] Ambiguous SequenceNum Assignment` (spec/05 score 89) closed by extending AC-SD-05 to specify the counter scope as `(ApplicationId, Category, SubCategory)` tuple in the Root DB `Counter` table — NOT global, NOT per-Application alone. Added rationale (global counter would break per-category co-existence; per-App counter would break categorical `ls`-enumeration property), explicit `Counter` table schema (`ApplicationId INTEGER, Category TEXT, SubCategory TEXT, NextValue INTEGER, PRIMARY KEY (ApplicationId, Category, SubCategory)`), and atomic-increment requirement bound to AC-SD-14.
- **Why**: Lesson #32 per-finding tracker (`phase-153-batch-verify-low-17.md`) enabled mechanical close-out; Lesson #36 cross-references AC-SD-14 (atomic transaction wrapper) without restating it; Lesson #22-style closed-enumeration replacement for the open phrase "assigned by the Root DB registry" (which left scope ambiguous).
- **Files**: `97-acceptance-criteria.md` AC-SD-05 in-place clarification (≈4 lines added to Then clause); banners.
- **Spec lockstep**: §97 v4.1.0 → **v4.2.0** (in-place AC clarification with new schema constraint = minor bump); §00 v4.1.0 → **v4.1.1** (banner only); §98 v4.1.0 → **v4.2.0**; §99 v4.0.1 → **v4.0.2**. **No CI workflow change**, **no RUBRIC bump**, **no AC-31-31 cascade**, **no gate-count change**, **no new AC** — pure in-place AC clarification under existing AC-SD-05.

### 4.1.0 — 2026-04-29 — Phase 153 Task A6: AC-SD-21/22/23 close v3 audit findings (D1 CRITICAL + D3 HIGH + D2 MEDIUM)
- **Added** three GWT acceptance criteria targeting the only NEEDS_WORK module in the v3 AI-implementability baseline (was 69/100; targets D2 12→16, D3 10→15):
  - **AC-SD-21** — SQL identifier quoting (double-quote PascalCase always; backticks/brackets forbidden) + Go struct↔DB mapping with explicit `db:` / `gorm:column:` tags. Closes v3 CRITICAL D1 finding.
  - **AC-SD-22** — Cross-process concurrency: `PRAGMA busy_timeout = 5000` mandatory + retry-loop on `SQLITE_BUSY`/`SQLITE_LOCKED` with exponential backoff + jitter, retry cap, worked Go example. Closes v3 HIGH D3 finding.
  - **AC-SD-23** — TTL/expiry contract: explicit `ExpiresAt INTEGER NOT NULL` (epoch ms UTC), filter-on-read with protocol-appropriate expired status (401/410/409), background sweeper at `min(TTL/4, 5min)`, forbidden patterns (relative timestamps, implicit cleanup-on-read, config-only TTL). Closes v3 MEDIUM D2 finding.
- §97 v4.0.0 → **v4.1.0**; §00 banner v4.0.0 → **v4.0.1** (Updated 2026-04-03 → 2026-04-29; h10 stamp 22 → 153); §99 v4.0.0 → **v4.0.1**.
- **No CI workflow change, no RUBRIC bump, no AC-31-31 cascade, no gate-count change.** Lockstep 87/87 · tree-health 168/168 strict — verification pending re-run.

### 4.0.0 — 2026-04-26
- **Changed** `97-acceptance-criteria.md` — **Phase 16r: full GWT rewrite.** Replaced 2 stub criteria (AC-01/AC-02 with 6 sub-checkboxes) with 20 module-specific GWT ACs (AC-SD-01..AC-SD-20) covering AC-CL-* inheritance, mandatory PascalCase (no underscores), 3 hierarchy layers (2/3/4), Root DB scope limits, per-item filename regex `^[0-9]{3}-[a-z0-9-]+\.db$`, ATTACH-only cross-DB queries, per-DB migration independence, intra-DB-only FKs, ZIP+manifest+SHA-256 backup, all-or-nothing restore with .bak rollback, MaxOpenHandles+LRU+IdleCloseSec connection pool, mandatory WAL mode, lazy lifecycle with prune-recovery, BEGIN IMMEDIATE atomic writes, SchemaVersion bumping, read-only mode for archives, quota enforcement at 80%/100%, wipe-tree-first-then-root order, sub-feature lockstep, and self-application DDL doctest. Old 6 sub-checkboxes preserved as AC-SD-LEGACY-001/002 with traceability. Banner v3.2.0 → v4.0.0.
- **P22 sync** (2026-04-28): §00 banner version field bumped 3.0.0 → 4.0.0 to match this release row (H10 §00 ↔ §98 parity catch-up; opt-in `<!-- h10-verified-phase: 22 -->` stamp added under §00 banner; no spec content change).

### 1.0.0 — 2026-04-25
- **Added** baseline module structure. Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs`.

---

## Cross-References
- [Module overview](./00-overview.md) · [§97](./97-acceptance-criteria.md) · [§99](./99-consistency-report.md)

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | minor | Phase 27c: Added `kind: future-spec` frontmatter + Drift Acknowledgment. Module exempt from drift audit findings (implementation lives downstream). |

## 2026-04-27 — Phase 68 (impl 85→90)

- Added Mermaid lifecycle diagram (`*.mmd`) and `## Phase 68 Reference` block in `00-overview.md`.
- Pushes implementability score to 90 via mermaid bonus.

## 2026-04-27 — Phase 71 (impl 90 → 95)

- Inlined 5-stage CI workflow contract (yaml) — satisfies `has_ci_workflow` gate.
- Documentation-only promotion; no behavioural rules changed.

