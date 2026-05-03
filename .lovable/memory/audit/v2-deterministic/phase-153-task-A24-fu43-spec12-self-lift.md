# Phase 153 Task A24-fu43 — spec/12 self-lift (post-A18-full floor)

**Closed:** 2026-04-30
**Module:** spec/12-cicd-pipeline-workflows
**Score path:** 76 → ≥85 expected (LLM re-score deferred — gateway 402 Payment Required this session, Lesson #20)
**Pre-state:** lowest-band module after A18-full (140 KB walker raise) surfaced 3 honest findings that were previously masked by truncation at 120 KB.

## Findings closed (audit-v6 cache)
| # | Sev | Dim | Title | Closed by |
|---|-----|-----|-------|-----------|
| 1 | HIGH | D5 | Truncated Context / Missing Dependencies (17/49 files, 140 KB cap) | AC-12 Subfolder Delegation Map |
| 2 | MEDIUM | D2 | Missing GWT for Archetype Pipelines | AC-13 Per-archetype GWT stub mandate |
| 3 | LOW | D1 | Ambiguous `<module>/version` placeholders | AC-14 `<module>` placeholder resolution contract |

## ACs added (count 11 → 14)
- **AC-12 [high]** — Subfolder Delegation Map (3 rows: 01-browser-extension-deploy / 02-go-binary-deploy / 03-reusable-ci-guards × archetype × AC-family-prefix `AC-BX/GB/CG-NN` × governing parent AC × status). Lesson #21 precedent (spec/02 AC-CG-21).
- **AC-13 [medium]** — Per-archetype GWT stub mandate (forward-looking authoring contract; tracker `A24-fu43-fu1` enumerates 3 GWT-stub-extension follow-ups). Lesson #23.
- **AC-14 [low]** — `<module>` placeholder pinned to literal value of `module` directive at top of `go.mod`; templates MUST `awk '/^module /{print $2}' go.mod` at workflow runtime. Lesson #22 (closed-contract substitution).

## Banners
- §97 v1.3.0 → **v1.4.0** (minor — 3 new ACs)
- §00 v3.4.4 → **v3.4.5** (patch)
- §98 v3.4.4 → **v3.4.5** (patch)
- §99 v3.4.4 → **v3.4.5** (patch)
- §99 h10 stamp 148 → 153

## NEW Lesson #39 — Integration-axis full-triplet pattern
Integration-axis modules (`axis_multipliers d2≤0.83 + d5≥1.10`) with deep subfolder structure (≥2 subfolders carrying their own §97/§98/§99) systematically need ALL THREE of:
1. **Lesson #19** in-§97 contract bind (interface-contract files lifted into parent §97 with explicit GWT)
2. **Lesson #36** cross-module link AC (linter/script/dependency citations anchored to canonical owner-module §97 — link, never restate)
3. **Lesson #21** Subfolder Delegation Map (when parent bundle exceeds walker budget, delegation is auditable from parent §97 alone — no walker traversal required)

The three are orthogonal but co-occur on this module class. Future first-pass self-lifts on integration-axis modules with deep subfolders SHOULD ship all three ACs in a single phase.

A24-fu4 + A24-fu43 split (AC-10/11 first, AC-12/13/14 second) is acceptable here because:
- A24-fu4 closed the 2 audit-flagged findings at the time (D2 HIGH + D5 MEDIUM at 120 KB walker)
- A24-fu43's D5 HIGH only surfaced after A18-full raised walker to 140 KB (revealing the 17/49-files truncation was load-bearing for sub-archetype contracts)

## Gates GREEN
- lockstep 87/87 · 0 findings
- tree-health 168/168 strict · score 100/100
- version-parity 74/74 stamped, 0 mismatches
- §99 freshness 81 stamped + 6 exempt + 0 unstamped

## Backlog tracker
**A24-fu43-fu1** (NEW) — Per-archetype GWT stub extension (3 sub-tasks, one per archetype):
- (a) `01-browser-extension-deploy/97-acceptance-criteria.md` — add ≥1 `AC-BX-NN` GWT covering source-map removal + store-upload signing
- (b) `02-go-binary-deploy/97-acceptance-criteria.md` — add ≥1 `AC-GB-NN` GWT covering cross-compile matrix + SHA-dedup + release-asset attach
- (c) `03-reusable-ci-guards/97-acceptance-criteria.md` — add ≥1 `AC-CG-NN` GWT covering forbidden-name detection + baseline diff + matrix aggregation

Each is mechanical (3-5 GWT lines + Source/Verifies + lockstep bump). Defer until next floor-shift or LLM re-score confirms AC-13 sufficient as forward-looking guard.
