# Phase 153 P3 — spec/22 Cross-Module Externalized Citation Map (AC-79)

**Date:** 2026-05-06
**Module:** spec/22-git-logs-v2 (integration-axis)
**User request:** "Add explicit normative anchors for any cross-module externalized citations in spec/22 so the auditor can follow the dependency chain."

## Survey

`rg -no "spec/[0-9]+-[a-z-]+" spec/22-git-logs-v2/*.md` → 6 externalized contract dependencies lacking a §97-bound anchor:

| # | Cite | From file:line | Owning module + AC |
|---|---|---|---|
| 1 | spec/04-database-conventions | `02-database-schema.md:368` | spec/04 §02 AC-09 (boolean storage) |
| 2 | spec/12-cicd-pipeline-workflows | `05-auth-and-validation.md:114` | spec/12 §97 AC-09/AC-10 |
| 3 | spec/13 §97 AC-22 | AC-26 + AC-78 line 505 (already inline) | spec/13 §97 AC-22 |
| 4 | spec/26-gitlogs-diagrams | `39-split-db-log-storage.md:184–185` | spec/26 §97 |
| 5 | spec/05-split-db-architecture | `39-split-db-log-storage.md:186` | spec/05 §97 AC-SD-21..23 |
| 6 | linter-scripts/check-spec-folder-refs.py | AC-22-LV1 line 477 | spec/27 slot 02 §97 AC-62-01..04 |

Mirror citations in AC-78 line 507 (spec/N AC-XX form) are meta-references to other module-kind pin ACs — not externalized contract dependencies, no anchor needed.

## Resolution

Added `[critical]` **AC-79 — Cross-Module Externalized Citation Map** to spec/22 §97 with a 6-row normative table. Each row: `(External cite | Owning module + AC | Cited from spec/22 file | Citation purpose | Restate-in-22 forbidden?)`.

- All 6 rows: Restate-in-22 = **YES** by construction (Lesson #36 dual-source drift class).
- Append-only within a phase.
- Future amendments dropping the FORBIDDEN flag require new locked-decision in spec/22 §07 + §99 audit row.
- Auditor finding "external dependency unresolved" against any of the 6 rows → stale-cache (Lesson #34).

## Lockstep

- §97 v3.10.2 → **v3.11.0** (minor — new AC; count 73 → 74)
- §00 v3.13.3 → **v3.13.4** (banner — patch)
- §98 v3.13.3 → **v3.13.4** (banner + row — patch)
- §99 v3.13.3 → **v3.13.4** (banner + audit row — patch)

No CI / RUBRIC / AC-31-31 / gate-count / DDL / schema change.

## Lesson #37 reinforcement (second instance)

spec/12 (A24-fu4) was the codifying instance for integration-axis modules co-needing Lesson #19 (audit-boundary pin) + Lesson #36 (cross-module anchor) ACs together. **spec/22 (this phase) confirms the pattern**: AC-78 (Lesson #29 module-kind pin, audit-boundary class) + AC-79 (Lesson #36 cross-module anchor, externalized-citation class) ship as a complete pair.

**Pattern generalized**: every integration-axis module SHOULD carry both:
- Audit-boundary pin AC (Lesson #19/#29 — declares module-kind + on-disk inventory)
- Cross-module anchor map AC (Lesson #36/#37 — explicit table of every externalized cite)

Tree-wide candidates for the same complete-pair treatment: spec/14, spec/16, spec/03, spec/10, spec/11, spec/17, spec/18 — verify each has BOTH ACs; author the missing one when audit findings surface.

## Verification

- lockstep 87/87 ✅
- tree-health 168/168 strict ✅
- version-parity 74/74 ✅

## Expected cache lift

When A8 LLM-gateway re-score next runs cleanly (gateway oscillating per Lesson #86), AC-79's anchor table should resolve any v3/v4/v5 [D5] dangling-cross-module-reference findings in tier-1 reach.

## Cross-references

- AC-78 (Lesson #29 module-kind pin — audit-boundary class)
- spec/02 AC-CG-21 (Subfolder Delegation Map — same pattern, intra-module sub-folder axis)
- spec/12 AC-11 (linter-script anchor pattern from A24-fu4)
- Lesson #36 (link-don't-restate cross-module boundary)
- Lesson #37 (integration-axis modules co-need #19 + #36 ACs)
- Lesson #34 (stale-cache deferral when gateway 402)
