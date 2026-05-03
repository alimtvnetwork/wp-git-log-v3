# Phase 153 Task A24-fu44 — spec/03 self-lift (post-A18-full second floor)

**Closed:** 2026-05-03
**Module:** spec/03-error-manage
**Score path:** 81 → ≥87 expected (LLM rescore deferred — gateway 402 last attempt, Lesson #20)

## Findings closed (audit-v7 cache)
| # | Sev | Dim | Title | Closed by |
|---|-----|-----|-------|-----------|
| 0 | HIGH | D3 | Concurrency/Race Condition in ZIP Finalization | AC-10 |
| 1 | MEDIUM | D2 | Incomplete AC Coverage for Sub-modules | AC-12 |
| 2 | LOW | D5 | Dangling References to Downstream Repos | AC-11 |

## ACs added (count 9 → 12)
- **AC-10 [high]** — ZIP must-cleanup contract. Lifts retro `01-error-resolution/03-retrospectives/03-zip-finalization-before-return.md` lines 38–129 into normative §97; explicit-close sequence + `pathutil.IsFileValid` + cleanup-on-every-error-branch + `publishFailed` flag for temp ZIPs. Lesson #36 link-don't-restate (retro stays canonical implementer prose; AC pins contract).
- **AC-11 [low]** — Downstream-repo references = Interface Contracts. Closed set `{backend/internal/, backend/cmd/, frontend/src/, wp-plugin-publish/pkg/}` declared as Phase 27 drift acknowledgment. Lesson #29 cross-repo extension.
- **AC-12 [medium]** — Sub-module GWT mandate. AC-family namespaces `AC-ER/EA/ECR-NN`. Lesson #23 + Lesson #29 forward-looking pattern; tracker `A24-fu44-fu1`.

## Banners
- §97 v2.2.0 → **v2.3.0**
- §00/§98 v3.4.4 → **v3.4.5**
- §99 v3.3.2 → **v3.3.3**
- h10 stamps already at 153 (no refresh needed)

## NEW Lesson #40 — Lesson #39 triplet is axis-independent
spec/12 was integration-spec (`d2≤0.83 + d5≥1.10`); spec/03 is normative-contract (`d2=1.5 + d5=0.5`). Same 3-class triplet shape applied. The triplet pattern (Lesson #21 sub-module map + Lesson #36 cross-module link + Lesson #29 module-kind/asset pin) applies to ANY module satisfying both: (a) ≥2 deep subfolders with own §97; (b) ≥1 outside-spec reference class. Future first-pass self-lifts on such modules SHOULD ship the full triplet in a single phase regardless of axis.

## Backlog tracker
**A24-fu44-fu1** (NEW) — Per-sub-module GWT-stub extension, 3 sub-tasks:
- (a) `01-error-resolution/97-acceptance-criteria.md` — add ≥1 `AC-ER-NN` GWT (resolution flows / retry-backoff values)
- (b) `02-error-architecture/97-acceptance-criteria.md` — add ≥1 `AC-EA-NN` GWT (3-tier dispatch / response envelope / AppError contract)
- (c) `03-error-code-registry/97-acceptance-criteria.md` — add ≥1 `AC-ECR-NN` GWT (range allocation / collision detection)

Defer until next floor-shift or rescore confirms AC-12 sufficient as forward-looking guard.

## Gates GREEN
- lockstep 87/87 · 0 findings
- tree-health 168/168 strict
- version-parity 74/74 · 0 mismatches
- §99 freshness 81 stamped + 6 exempt + 0 unstamped
