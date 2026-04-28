# Phase 30 — Spec-index drift gate strict-promotion (AC-31-31 cascade)

**Date:** 2026-04-28  
**Mode:** No-Questions Mode 14/40  
**Trigger:** Phase 29 close-out — queued cascade follow-up. Replicates the H4 → H5 split pattern.

## Goal

Promote the existing `Regenerate spec-index.md (drift check)` step in `.github/workflows/spec-health.yml` from advisory (`⚠️` warn + exit 0) to **strict** (`exit 1` on any `git status --porcelain spec/` delta), closing the "advisory CI gates silently rot" class identified in Phase 29.

## AC-31-31 cascade sites updated

| # | Site | Before | After |
|---|---|---|---|
| 1 | `linter-scripts/audit-spec-vs-code-v2.py` `RUBRIC_VERSION` | `v2.26` | **`v2.27`** |
| 2 | Same script — footer enumeration in `00-index.md` template (~L1118, L1136) | "17 strict CI gates", entries 1–17 | **"18 strict CI gates"**, added entry **#18** (Spec-index drift gate, Phase 30, AC-T-25) |
| 3 | Same script — EXECUTIVE-SUMMARY back-ref (~L1164) | "expanded Phases ... + H7" → "17 strict CI gates" | **"+ 30"** appended → **"18 strict CI gates"** |
| 4 | `linter-scripts/test/test-qa-baseline-footer.sh` workflow-gates awk | 17 patterns | **18** (added `/Spec-index drift gate/`) |
| 5 | `.github/workflows/spec-health.yml` step | `Regenerate spec-index.md (drift check)` advisory | **`Spec-index drift gate`** strict (`exit 1`) |
| 6 | `spec/27-spec-toolchain/00-overview.md` slot-70 description | "17 production gates" | **"18 production gates"** |
| 7 | `spec/27-spec-toolchain/97-acceptance-criteria.md` | v2.2.0, AC-T-01..24 | **v2.3.0**, added **AC-T-25** |
| 8 | `spec/27-spec-toolchain/98-changelog.md` | v2.46.3 | **v2.47.0** (new release entry) |
| 9 | `spec/27-spec-toolchain/99-consistency-report.md` | v2.43.3 | **v2.44.0** (new prepended `> v2.44.0 update` blockquote) |
| 10 | §27 §99 stamp `<!-- verified-phase: NNN -->` line 109 | `147` | **`147`** (kept; current_phase = 147 from `detect_current_phase()` which scans mem-index + §27 changelog max — H8 set this baseline; re-stamp would be a no-op because session "Phase 30" < 147) |

## Stamp-numbering reconciliation note (NEW LESSON)

The session has been using "Phase 18..30" as a session-local sequence per `.lovable/question-and-ambiguity/task-counter.md`. The project-wide integer phase counter sits at **147** (B1 close, Phase 147). The `check-99-summary-freshness.py` validator uses `detect_current_phase() = max(all phase nums in mem-index + §27 changelog)` which returns 147, NOT 30.

**Implication for §99 stamp-bump (AC-27-01..08, slot 27 H4/H5 gate):**
- The stamp number must satisfy `current_phase - stamp ≤ max-age (20)` → stamp ≥ 127.
- Naming a session-local edit "Phase 30" is fine in narrative prose (max() ignores it because 30 < 147).
- BUT stamping a §99 with `30` triggers the freshness gate (delta 117) AND the stamp-bump gate (mismatch with current 147).
- **Resolution**: when materially editing a §99 in this session, leave the stamp at **147** (current global head) until a future phase advances the project-wide counter past 147. The session-local "Phase 30" name is metadata only.

This is a new lesson worth Core-memory codification: **session-local phase counters MUST NOT collide with the project-wide phase numbering used by `detect_current_phase()`.** Future autonomous sessions either (a) continue the integer sequence past 147 (so this session's "Phase 30" would actually be "Phase 148"), or (b) keep session-local naming AS narrative metadata only and use `current_phase` (147) for any §99 stamps. Option (b) preserves session-mode semantics with zero validator friction.

## Verification (post-cascade)

```
✓ AUDIT_DETERMINISTIC=1 audit-spec-vs-code-v2.py     mean 98.0 / 99.8 (sha256 stable post-rollover)
✓ generate-spec-index.cjs                            883 files (zero delta after regen — Phase 29 baseline holds)
✓ check-lockstep.cjs --strict                        87/87 pass · 0 findings
✓ check-tree-health.cjs --strict                     100/100, 56/56 modules full marks
✓ test-qa-baseline-footer.sh                         11/11 pass · 18/18/18 parity (script=18, footer=18, workflow=18)
✓ test-overview-inventory-parity.sh                  6/6 pass · 36 tracked code paths
✓ test-readme-inventory.sh                           26/26 pass · 10 inventory entries
✓ check-99-summary-freshness.py                      87 scanned, 81 stamped, 6 exempt, 0 stale ✅
✓ check-99-stamp-bump.py                             1 changed, 1 bumped to current ✅
✓ test-archive-exclusion-runtime.sh                  10/10 pass
```

The Spec-index drift gate (now strict) cannot be exercised in this same commit because Phase 29 already brought the artifact into sync — `git status --porcelain spec/` reports zero post-regen. CI will exercise it at the next contributor commit that bumps a tracked version without re-running `bash linter-scripts/run.sh` locally.

## Lessons codified (in AC-T-25 + this memo)

1. **Advisory CI gates silently rot** (codified in AC-T-25). Second instance after H1's session-persistence-regression class. Future advisory steps require an explicit phased-rollout justification or they ship strict from day 1.
2. **Generator artifacts in `run.sh` need CI parity** (codified in AC-T-25). `spec-index.md` is now formally a build artifact whose canonical source is `generate-spec-index.cjs`; contributors must `bash linter-scripts/run.sh` before commit OR the strict gate will fail.
3. **AC-31-31 cascade discipline** (replicated H4→H5 pattern). Phase 29 regenned the artifact; Phase 30 carries the rubric cascade. Splitting reduces blast radius and makes each diff reviewable.
4. **Session-local phase counters MUST NOT collide with global integer sequence** (NEW; candidate for Core memory). When `detect_current_phase()` returns N, any §99 stamp written in the same diff must be ≥ N to avoid both freshness-gate (delta) and stamp-bump-gate (mismatch) failures. Either continue the integer sequence (this session's Phase 30 → Phase 148) or treat session-local names as metadata-only and use the global head (147) for stamps.

## Status: CLOSED · cascade green at 18/18/18
