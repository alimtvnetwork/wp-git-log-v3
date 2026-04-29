# Phase 153 P-L36 — spec/17 Lesson #36 cross-reference anchors

**Date:** 2026-04-29
**Status:** CLOSED
**Driver:** Lesson #36 (cross-module restatements MUST link to canonical AC for drift detection)
**Trigger:** Forward-looking sweep after autonomous-backlog exhaustion — scanned tree for `busy_timeout=5000|journal_mode=WAL|BEGIN IMMEDIATE` outside spec/13.

## Survey

5 candidate files surveyed; 2 genuine restatements confirmed:

| File | Match | Verdict |
|---|---|---|
| `spec/17/18-database-conventions.md` | § 9 SQLite-Specific Rules table (lines 450-456) | **PATCH** — restates 3 PRAGMAs verbatim, missing `synchronous=NORMAL` |
| `spec/17/05-split-db-architecture.md` | § 5 Concurrency & Locking, Go example (lines 199-218) | **PATCH** — restates 3 PRAGMAs in code, missing `synchronous=NORMAL` |
| `spec/18/08-wordpress-integration-patterns.md` | line 783 — `$db->exec('PRAGMA journal_mode=WAL')` | NO-OP — PHP-specific WP example, mentions one PRAGMA in passing for an error-handling table row |
| `spec/14/24-update-check-mechanism/08-error-handling.md` | line 132 — `BEGIN IMMEDIATE / COMMIT` | NO-OP — transaction primitive in error-table cell, not the AC-22 retry contract |
| `spec/_archive/21-git-logs-v1/diagrams/03-seedable-config-flow.mmd` | various | NO-OP — archived |

## Resolution

Per Lesson #36, spec/17 (consolidated-guidelines roll-up) is the **one** surface where restatement is by-design — contributors land here precisely so they don't need to navigate to source. The fix is "**link AND restate** with link as tiebreaker", NOT "link only".

Patches:

1. `spec/17/18-database-conventions.md` § 9 — added `**Canonical source:**` paragraph linking to `spec/13-generic-cli/97-acceptance-criteria.md` AC-22 + `10-database.md`; added `synchronous=NORMAL` row to PRAGMA table; appended "AC-22 wins" tiebreaker. Banner v3.3.0 → v3.3.1.
2. `spec/17/05-split-db-architecture.md` § 5 — added `**Canonical source:**` paragraph; updated prose "all three PRAGMAs" → "all four PRAGMAs (AC-22)" with note that the Go example shows three for brevity. Banner v3.2.0 → v3.2.1; footer date refreshed.

Lockstep: spec/17 §00 3.4.3 → 3.4.4, §98 row 3.4.4 added, §99 v4.6.4 update added.

## Lesson reinforced

**Lesson #36 nuance for roll-up surfaces:**
- Peer-module restatements → DELETE the restatement, link only.
- Roll-up restatements (spec/17, spec/24-design-system, etc.) → KEEP the restatement, ADD `**Canonical source:**` anchor + tiebreaker clause.

Detection mechanism going forward: any future drift between AC-22's PRAGMA set and spec/17's table will be grep-detectable via the explicit anchor. Without the anchor, a contributor editing AC-22 would have no signal that spec/17 mirrors it.

## Side-correction

Both spec/17 surfaces were missing `synchronous=NORMAL` from the AC-22 4-PRAGMA set — this is a real (latent) drift class that the anchor + sweep just caught. The `synchronous=NORMAL` PRAGMA was added to AC-22 during the spec/13 D1 contract closure (Phase 153 A11a) but spec/17's mirror was never updated.

## Verification

- Lockstep 87/87 GREEN
- Tree-health 168/168 strict GREEN
- Version-parity 74/74 GREEN
- Folder-refs 0 stale GREEN
- Freshness 81 stamped + 6 exempt + 0 unstamped GREEN

## Status post-close

Autonomous backlog re-confirmed empty after this sweep. The 4 surveyed-but-no-op candidates document the precedent for future Lesson #36 sweeps: not every PRAGMA mention is a restatement — only those that mirror the **complete** AC-22 contract (PRAGMA set + retry discipline + lock file) require anchoring.
