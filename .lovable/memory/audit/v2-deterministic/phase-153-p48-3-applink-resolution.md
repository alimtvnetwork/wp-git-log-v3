# Phase 153 P48-3 — Polymorphic AppLink Resolution Algorithm

**Date:** 2026-04-29
**Status:** CLOSED — 2nd of 3 P47-fu1 critical findings (#17 of session backlog)

## Closes
**P47-fu1 critical finding** "23-adb polymorphic AppLink resolution" (`mem://index.md` line 55; P47-fu1 audit JSON at `/mnt/documents/audit-phase-p47-followup1.json` → spec/23-app-database top_blocker #1: "Polymorphic AppLink resolution logic — DDL describes structure not algorithm; XOR enforcement at DB level not specified beyond CHECK").

## Work
- `spec/23-app-database/00-overview.md`: added `## Polymorphic AppLink Resolution (Normative)` section between "Seed data" and "Query Patterns" — discriminator→target binding table (locked IDs 1=GitProfile, 2=Repo per AC-ADB-13), 4-step deterministic resolution algorithm (canonicalise → Direct candidates `AppLinkTypeId=2` → Transitive candidates `AppLinkTypeId=1` via `Repo.GitProfileId` → tie-break Direct>Transitive>newer-CreatedAt + Active-App requirement), 4-state closed-enumeration outcome table (`RESOLVED_DIRECT` / `RESOLVED_TRANSITIVE` / `REJECTED_INACTIVE_APP` / `REJECTED_NO_MATCH`), and Forbidden Resolution Patterns subsection.
- `spec/23-app-database/97-acceptance-criteria.md`: AC-ADB-14 (`[critical]`) binds the algorithm; cross-references AC-ADB-05/06/10/13 as load-bearing prerequisites; declares prose authoritative over Q1 SQL.

## Lockstep
§00 v4.0.3 → **v4.1.0** (minor — new normative subsection = new public contract surface) · §97 v3.1.0 → **v3.2.0** (minor — AC count 13 → 14) · §98 v4.0.2 → **v4.1.0** · §99 v2.0.3 → **v2.1.0**. h10 stamp 153 (no refresh — already current). No CI workflow change, no RUBRIC bump, no AC-31-31 cascade (no new linter slot — runtime-enforced by `app-database` binary, not static-checkable).

## Lesson #33 (codified inside AC-ADB-14 + §98 P48-3 row + §99 v2.1.0 update banner)
Polymorphic-FK resolution algorithms MUST be lifted to normative prose with closed-enumeration outcome tables. Example SQL (Q1) is illustrative, not authoritative. Relying on `ORDER BY` clauses to encode precedence rules is invisible to context-window-bounded auditors and to fresh implementers — both will read the DDL and the example query and miss the precedence semantics. The contract surface (algorithm + outcome states + forbidden patterns) MUST live in prose. Mirror of Lessons #19 (parent-vs-child audit boundary) / #21 (Subfolder Delegation Map) / #26 (external-FK inlined-summary): when audit-boundary < verification-boundary, the consuming surface MUST inline a normative summary.

## Remaining P47-fu1 critical findings
- **#18** — 11-ps Pipeline Steps lack per-step exit codes (open)

## Index update
Append-only narrative row in `mem://index.md` (Phase 153 section); Lesson #33 codified.
