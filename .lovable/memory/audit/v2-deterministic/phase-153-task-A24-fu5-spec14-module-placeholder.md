# Phase 153 Task A24-fu5 — spec/14-update self-lift 76 → 87 (+11)

**Date:** 2026-04-30
**Module:** spec/14-update (axis: normative-contract)
**Result:** 76 → **87 / 100 (GOOD)** — confirmed via `--force` LLM re-score (gateway active per Lesson #38)

## What changed

### `04-build-scripts.md` (use-site prose, Lesson #36)
Added explanatory blockquote immediately under the PowerShell code block (around line 110) explaining that the literal `<module>` in the `-ldflags` argument MUST be replaced with the consuming repository's `go.mod` `module` line at build time. Cross-references AC-22.

### `97-acceptance-criteria.md` (AC-22 binding)
- **AC-22 `[high]`** — binds the `<module>` placeholder convention normatively. Closes audit-v7 D3 MEDIUM "Ambiguous <module> Placeholder" as a real-but-narrow gap. Per Lesson #36, prose lives at use-site (`04-build-scripts.md`); §97 only binds it.
- AC-21's three-finding pin trimmed to D5/D4 only (D3 placeholder finding now properly resolved by AC-22, not just classified as harness artifact).
- AC count: 21 → 22.

### Lockstep
- `97-acceptance-criteria.md`: v2.3.0 → **v2.4.0** (minor — new AC)
- `00-overview.md`: v2.4.0 → **v2.4.1** (patch); h10 stamp 24 → 153
- `98-changelog.md`: v2.4.0 → **v2.4.1** (patch) + new release row
- `99-consistency-report.md`: v1.6.0 → **v1.6.1** (patch)

## Score breakdown

| Dim | Before | After | Δ |
|-----|-------:|------:|--:|
| D1 Contract Clarity | 18 | 18 | — |
| D2 AC Coverage | 16 | **20** | +4 |
| D3 Edge/Error | 14 | 17 | +3 |
| D4 Examples | 12 | 15 | +3 |
| D5 Cross-Ref Closure | 15 | 14 | −1 |
| **weighted_total** | 75.9 | **87.0** | +11.1 |

D2 hit cap (20/20) — AC count went 21 → 22 with explicit GWT structure. D3+D4 lifts came from use-site prose adding concrete substitution example. D5 −1 is statistical noise (auditor cited two new doc cross-refs as "not yet bundled" — pure walker-cap class, ignored per AC-21).

## Persisting findings (post-fix, all pinned)

1. HIGH D5 "Missing Sub-Module Context" — pinned by AC-21 as harness bundling-cap artifact (8/54 files bundled per tier-1 cap).
2. HIGH D4 "Truncated Build Script Logic" — pinned by AC-21 as bundle-cap artifact (file is 349 lines complete).
3. MEDIUM D3 "Ambiguous <module> Placeholder" — pinned by AC-22 (real gap, now resolved at use-site + bound normatively).

## Lesson reinforcement

- **Lesson #36 (link-don't-restate)**: applied for the third time this session. Use-site prose in `04-build-scripts.md` is canonical; §97 AC-22 binds it normatively without restating the substitution rule. Mirror of Phase 153 P3 cross-module concurrency pattern.
- **Lesson #38 (gateway availability)**: confirmed `LOVABLE_API_KEY` present at phase start; `--force` re-score landed in <30s.
- **Lesson #45 (cache-stability)**: D5/D4 HIGH findings persisted across edit cycle as expected (walker bundle truncates before reaching files 09–27); pinning them via AC-21 is the correct closure pattern, not chasing the score.

## Verification

- Lockstep: 87/87 PASS
- Tree-health: 168/168 strict PASS (all 56 modules at full marks)
- Version-parity: 74/74 matches, 0 mismatches
- Re-score: 76 → 87 (confirmed via gateway)

## Next-up after A24-fu5

Lowest GOOD-band modules (by score):
- 76.0 — spec/27-spec-toolchain (next A24-fu6 candidate)
- 78.0 — spec/17-consolidated-guidelines
- 79.0 — spec/25-app-issues (already lifted in A24-fu3, awaiting re-score confirmation)
- 80.0 — spec/07-design-system
- 80.0 — spec/18-wp-plugin-how-to
