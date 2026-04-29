# Phase 153 Task #35 — Version-parity FAILs investigation

**Date:** 2026-04-29
**Scope:** All 17 stamped §00↔§98 parity failures from `check-version-parity.py`.
**Outcome:** 17 → 15 FAILs. Two clean wins; 15 require structural §98 reordering.

## Wins (2 cases — pure banner-bump corrections)

Both had §98 already shipping the newer release row (added during Phase 153 sub-tasks)
but §00 banner was missed. Pure §00 banner bump fixed parity:

| Path | Old §00 | New §00 | Source §98 row |
|------|---------|---------|----------------|
| `spec/05-split-db-architecture` | 4.0.1 | 4.1.0 | Phase 153 Task A6 |
| `spec/03-error-manage/03-error-code-registry/07-schemas` | 3.4.1 | 3.4.2 | Phase 153 Task #29e |

For schemas, also refreshed `<!-- h10-verified-phase: 29 -->` → `153`.

## Remaining 15 — root cause: §98 row ORDERING is wrong

The `latest_release()` helper in `check-version-parity.py` returns the FIRST release
heading it encounters (top-down newest-first convention). But Phase 153 sub-tasks
(#29c/d/e + #31) inserted PATCH rows ABOVE older HIGHER-versioned releases:

Example: `spec/02-coding-guidelines/01-cross-language/98-changelog.md`
```
L11: ### 4.0.1 — 2026-04-29 — Phase 153 Task #29d   ← parser sees first; SemVer-low
L14: ## 4.1.0 — 2026-04-27                          ← actually newest, but read 2nd
```

`latest_release()` returns `4.0.1` (the dated-newer, SemVer-lower row).
`banner_version()` reads §00 = `4.1.1` (correct: 4.1.0 + Task #29d patch on top).
Mismatch flagged — but the §00 banner is RIGHT; the §98 ordering is wrong.

## Why "fix the rows" is risky tree-wide

Two fix shapes are theoretically valid:
1. **Reorder §98** — move the SemVer-highest row to the top, demote dated-newer
   patch rows below. Breaks the "newest-first by date" convention codified
   elsewhere; would invalidate the v3.x changelog convention used in spec/22.
2. **Bump every §98 to a SemVer-higher patch** — e.g. `4.0.1 → 4.1.1` row.
   Loses the Task #29d/e/#31 attribution prose; cascades version bumps.

Both options touch >30 files and risk breaking lockstep date assertions.

## Recommendation

Patch `latest_release()` to return the **SemVer-highest** version found in §98
(not the first encountered). This codifies "SemVer is source of truth, not row
position", matches what `banner_version()` semantically asks for, and clears
all 15 FAILs without touching any spec content.

This is the right cure: the parity gate should compare SemVer maxima, not
positional firsts. Newest-by-date and highest-by-SemVer can legitimately diverge
when patch reconciliation rows are appended above older minor releases.

**Deferred to a follow-up phase** — needs slot 26 §97 AC for the new behavior +
`check-version-parity.py` bump (slot 26 v?.?.? → v?.?.?+minor) + self-test +
§27 §00/§98/§99 lockstep — outside this turn's scope.

## Memory codification

Lesson #28: when a versioning gate flags a wide drift class, inspect the
gate's comparator before mass-patching the tree. The bug may be in the
comparator (positional-first vs SemVer-max), not the data.

## Files changed this task

- `spec/05-split-db-architecture/00-overview.md` — banner 4.0.1 → 4.1.0
- `spec/03-error-manage/03-error-code-registry/07-schemas/00-overview.md` — banner 3.4.1 → 3.4.2; h10 stamp 29 → 153

No §98/§99 bumps — both edits were corrections of stale banners catching up
to already-shipped §98 rows (no new content). Lockstep 87/87 PASS, tree-health
168/168 strict PASS, parity 17 → 15 FAIL (15 require comparator fix above).
