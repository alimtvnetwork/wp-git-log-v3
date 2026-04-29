# Phase 153 P48-2 — Cross-Language Boolean Storage Convention

**Date:** 2026-04-29
**Status:** CLOSED — first of 3 P47-fu1 critical findings closed (#16 of session backlog)

## Closes
**P47-fu1 critical finding** "04-db cross-lang boolean conventions" (`mem://index.md` line 55).

## Work
- `spec/04-database-conventions/02-schema-design.md`: added `## 2.1 Cross-Language Boolean Storage Convention (Normative)` with 4 subsections — per-engine storage table (SQLite/MySQL/PostgreSQL × allowed × forbidden), per-language scan/insert table (Go/PHP/Rust/C#/TS), tri-state `NULL` exception, migration discipline.
- `spec/04-database-conventions/97-acceptance-criteria.md`: AC-09 binds the four required subsections.

## Lockstep
§00 v3.3.3 → v3.4.0 · §02 v3.3.0 → v3.4.0 · §97 v1.1.0 → v1.2.0 · §98 v3.3.3 → v3.4.0 · §99 v3.5.1 → v3.6.0. h10 stamp 153 (no refresh). No CI/RUBRIC/AC-31-31/gate-count change.

## Gates
Lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN.

## Lesson #32 (codified inside §98 P48-2 row + §99 audit row)
P47-fu1 backlog items survived 7+ phases without resolution because findings were buried in a single index-line memo with no per-finding tracker. Future phase-spanning audit findings MUST get one-finding-per-file trackers under `.lovable/memory/audit/` so individual closures are discoverable. Mirror of Lesson #30 — #30 is "verify before opening", #32 is "anchor at source so verification is possible".

## Remaining P47-fu1 critical findings
- **#17** — 23-adb polymorphic AppLink resolution (open)
- **#18** — 11-ps Pipeline Steps lack per-step exit codes (open)
