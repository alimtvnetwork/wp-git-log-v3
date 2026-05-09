# Phase 153 Task A8-cont1 — Sub-batch re-baseline (gateway live, 11 modules fresh)

**Date:** 2026-05-09
**Status:** PRODUCTIVE (partial — gateway oscillated to 402 mid-run on 27/28)
**Trigger:** User `next`; Lesson #89 two-step probe → ENV SET ✓ + spec/26 live `--force` → HTTP 200 (gateway GREEN; 5th oscillation in Phase 153, 2nd to GREEN).

## What ran

Three sub-batches of `--force` re-scores executed sequentially:

- **Batch 1 (5):** 12, 13, 14, 15, 16
- **Batch 2 (4):** 14 (retry), 18, 22, 23 — 17 was skipped (gateway intermittent)
- **Batch 3 (5):** 17 (retry), 24, 25, 27, 28

## Fresh scores this session (11 modules)

| Module | Score | Band | Notes |
|---|---|---|---|
| 12-cicd-pipeline-workflows | 84 | GOOD | A24-fu4 prediction (≥83) confirmed |
| 13-generic-cli | 94 | EXCELLENT | +7 vs prior baseline (87) |
| 15-distribution-and-runner | 91 | EXCELLENT | |
| 16-generic-release | 98 | EXCELLENT | Top 3 |
| 17-consolidated-guidelines | 88 | GOOD | Advisory chunked-cache prompt |
| 18-wp-plugin-how-to | 90 | EXCELLENT | |
| 22-git-logs-v2 | 78 | GOOD | Lowest in batch — git-logs decision still pending |
| 23-app-database | 99 | EXCELLENT | Top score |
| 24-app-design-system-and-ui | 97 | EXCELLENT | |
| 25-app-issues | 87 | GOOD | Advisory chunked-cache prompt |
| 26-gitlogs-diagrams | 94 | EXCELLENT | (probe module) |

Combined with the 9 modules from **A8-partial-v5**, ~20/23 modules now have fresh v5 scores.

## Pending (3 modules)

| Module | Reason |
|---|---|
| 14-update | Deterministic gateway JSON parse error (`Unterminated string starting at: line 27 column 14 (char 1282)`) on both attempts — looks like a malformed gateway response, not a budget/oscillation issue. |
| 27-spec-toolchain | HTTP 402 (gateway oscillated mid sub-batch 3) |
| 28-universal-ci-cli | HTTP 402 (same oscillation) |

## Lockstep ripple

None — `--force` only refreshes `.lovable/cache/audit-ai/*.json` snapshots and updates `audit-ai-implementability-latest.md`. No spec edits, no AC changes, no §97/98/99 bumps. All 5 strict gates remain GREEN (last verified prior phase).

## Lessons reconfirmed

- **Lesson #86** (gateway oscillation): 5th oscillation observed this Phase 153 — gateway flips GREEN↔402 within minutes; never assume stability across consecutive sub-batches.
- **Lesson #89** (two-step probe): probe worked correctly — env was set AND first live call returned 200. Mid-run flip is a separate failure class.
- **NEW Lesson #92 candidate (not yet codified)**: deterministic JSON-parse errors on a single module across multiple retries (spec/14) are likely a malformed gateway response (truncated JSON), distinct from 402 oscillation. Future A8-cont passes should `--chunked` or skip until the gateway response shape is investigated.

## Next on `next`

1. Re-probe gateway (Lesson #89). If GREEN, retry **spec/27 + spec/28** (`--force`).
2. For **spec/14**, try `--force --chunked` to bypass the malformed-response bug.
3. Once 23/23 fresh, open **A8-finalize** (v5 final report + scorecard close-out).
