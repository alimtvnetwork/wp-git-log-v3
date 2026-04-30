# Phase 153 Task A12-walker-cap — `MAX_BYTES` 90 KB → 120 KB (CLOSED 2026-04-30)

## Trigger
A24-fu6 closing memo + Lesson #45 codification: every audited module hit the 90 KB bundle cap exactly. spec/27 fit 3/50 files; spec/02 fit 6/251. Tree-wide D2/D3/D4 dimensions systematically under-counted past Tier 1.

## Saturation probe (pre-flight)
| Module | Files in/total | bytes_used |
|---|---|---|
| spec/02 | 6/251 | 90000 |
| spec/05 | 5/20 | 90000 |
| spec/07 | 3/17 | 90000 |
| spec/17 | 4/39 | 90000 |
| spec/18 | 10/35 | 90000 |
| spec/27 | 3/50 | 90000 |

100% saturation across representative sample.

## Live-probe (gateway safety)
`POST /v1/chat/completions` with 119 KB user-content + `User-Agent: lovable-spec-audit/1.0` → **HTTP 200**. Confirms the explicit-UA path tolerates ~125 KB; Lesson #11's 25 KB cliff applies only to default Python UA. 5 KB headroom below 125 KB MUST be preserved.

## Implementation
- `linter-scripts/audit-ai-implementability.py` line 45: `MAX_BYTES = 90_000` → `MAX_BYTES = 120_000`
- Docstring `load_module_bundle()` lines 171-185: appended A12 paragraph
- Truncation marker line 207: "90KB" → "120KB"

## Codification
**AC-34-13** `[critical]` added to `spec/27-spec-toolchain/34-audit-ai-implementability.md` §97 with full Why prose:
- Pre-flight saturation evidence
- Live-probe HTTP 200 evidence
- 5 KB headroom requirement
- Tier-1 contract priority (AC-34-09) preserved
- Future raises require fresh live-probe

## Post-fix saturation
| Module | Files in/total | bytes_used |
|---|---|---|
| spec/02 | 10/251 | 120000 |
| spec/05 | 7/20 | 120000 |
| spec/07 | 5/17 | 120000 |
| spec/17 | 6/39 | 120000 |
| spec/18 | 15/35 | 120000 |
| spec/27 | 3/50 | 120000 (Tier-1 only — slot files >120 KB; expected) |

~33% additional Tier-2 capacity unlocked.

## Lockstep
- slot 34 §00 v1.3.1 → **v1.4.0** (AC count 12 → 13)
- §27 §00 v2.80.0 → **v2.81.0**
- §98 v2.80.0 → **v2.81.0**
- §99 v2.77.0 → **v2.78.0**

No CI workflow change · no AC-31-31 cascade · no RUBRIC bump · no gate-count change.

## Verification
- Lockstep 87/87 GREEN
- Tree-health 168/168 strict GREEN
- Version-parity 74/74 GREEN
- Slot-34 self-test 9/9 PASS

## Cache invalidation
All 23 `.lovable/cache/audit-ai/*.json` entries become stale-by-construction (bundle content lengthens). A20-fu rebaseline will refresh them in one pass per Lesson #20 graduation note.

## Lesson #48 codified (in §98 row + §99 update)
`MAX_BYTES`-class fixes follow a 5-step sequence:
1. Probe current saturation across representative modules.
2. Live-probe proposed ceiling against gateway with explicit-UA payload.
3. Atomic raise of constant + docstring + truncation marker in one edit.
4. Codify new ceiling as contract AC with live-probe evidence cited.
5. Defer rebaseline to a separate phase per Lesson #41 (foundation→contract→wiring→measurement).

Skipping step (2) risks shipping a cap that immediately fails CI on next gateway round-trip. Mirror of Lesson #16 (tier-1 ordering) on the bundle-completeness axis.

## Predecessors
- A6 (tier-1 ordering, AC-34-09)
- A8 (v5 rebaseline at 90 KB cap, surfaced saturation)
- A24-fu6 (canonical Lesson #45 diagnostic — spec/27 3/50)

## Successor
- **A20-fu** (full-tree v8 rebaseline with `--force` — batches all stale caches)
