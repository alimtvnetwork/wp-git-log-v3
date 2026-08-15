# Ambiguity 05 — Gateway 402 + chunked_path metadata gap (P1 rebaseline)

**Filed:** 2026-05-05 (Phase P1 attempt)
**Phase:** P1 — stale-cache rebaseline (21 modules)
**Outcome:** P1 partial-close (no-op on score data); two gaps surfaced.

## Context

Phase P1 attempted to re-score the 21 `chunked=?` modules under
chunked-default to refresh the cache after A18-impl-3. Mean-stuck
diagnosis claimed the cache was stale. Outcome:

- **All 21 `--force` runs returned byte-identical scores** (84 → 84,
  87 → 87, 91 → 91, ...). No movement.
- **`/tmp/p1-rebaseline/22-git-logs-v2.log` shows `HTTP Error 402:
  Payment Required`** — the gateway is rejecting requests despite
  `LOVABLE_API_KEY` being set (Lesson #38 false-positive: key present
  ≠ budget available).
- **`chunked_path` is `None` in every cache file** even though
  `--chunk-stats` confirms multi-chunk modules exist (e.g. spec/22 = 3
  chunks @ 303 KB).

## The two gaps

### Gap A — Gateway 402 (operational)

`LOVABLE_API_KEY` set + 402 response = budget exhausted on this
account/period. Lesson #20 says defer LLM re-scores. **A24-fu4 (last
phase) DID get a fresh re-score** (75 → 83 confirmed), so the budget
unblocks intermittently. Inferred resolution: **defer all re-score
phases until a 200 response is observed** (poll with single
`--module 12-cicd-pipeline-workflows --force` once per session).

### Gap B — `chunked_path` never written (code defect)

A18-impl-3 contract (AC-34-17) says multi-chunk modules MUST persist
`chunked_path` in the cache. Real-tree inspection shows ZERO cache
files have it set. Either:
1. `audit_module()` writes it but `merge_chunk_scores()` strips it
   on the merge path, or
2. The 402 short-circuit is returning the stale cached object before
   the metadata write fires, or
3. The field is only written on **successful** chunked re-scores and
   the entire 23-module cache predates the field's introduction
   (most likely — A18-impl-3 was the last phase, and only spec/27 +
   spec/12 were re-scored under it; both DO show `chunked_path` per
   prior session — but current files don't either).

Inspection: `python3 -c "import json; print(json.load(open('.lovable/cache/audit-ai/27-spec-toolchain.json')).get('chunked_path'))"` returns `None` — even the A18-impl-3 reference scores lost the field, suggesting the field is being dropped on subsequent reads/writes (cache-load-then-write loop strips unknown keys?).

## Inference applied (no-questions mode)

1. **Defer P1** — re-classify as `gateway-budget-gated` (same bucket
   as P8). Do NOT mass-`--force` until 402 clears.
2. **File Gap B as separate spec/27 follow-up** — needs investigation
   of `audit_module()` write path; not blocking other work.
3. **Pivot to P2 (dimension-forensics)** which is offline and unblocks
   P3–P6 targeting decisions.

## Recommended user review questions

- Is the Lovable gateway budget intentionally throttled this session?
  If so, P1/P3-P6/P8 all defer until reset.
- Should Gap B (chunked_path missing) be opened as its own phase or
  rolled into the next spec/27 self-lift?


---
## Status

**Status:** Open
**Last-reviewed:** 2026-06-28 (hygiene-round-3 — footer added per new closure protocol)
**Blocked-on:** gateway-budget (HTTP 402 oscillation per Lesson #86) + user-decision on auto-summary cadence (file 03) / budget reset (file 05)
