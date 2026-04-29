# Phase 153 Task #32 — Version-Parity Drift Closed (spec/07 + Task #31 fallout)

**Date:** 2026-04-29
**Driver:** Long-standing `spec/07-design-system` version-parity drift surfaced by `check-version-parity.py` during Phase 153 Task #29a verification. Investigation revealed it was a **multi-namespace SemVer drift** (§00 tracks module version 3.x; §98 tracks file-scaffold version 1.x); also discovered Task #31 had introduced 12 NEW parity failures by patch-bumping §98 without bumping §00.

## Root cause analysis

### spec/07-design-system (long-standing)
§98 was scaffolded later than §00 and tracked its own SemVer (1.0.0 baseline) instead of mirroring §00's track (3.x). Phase 56 (`3.3.0` row) had started realigning, but Phase 151 regressed back to 1.x (`1.7.0` row), leaving the parity gate's check `§00 banner = §98 latest release` to fail with `3.4.0 vs 1.7.0`.

### spec/14-update/diagrams (long-standing, same pattern)
Identical multi-namespace issue: §00=3.4.0 vs §98=1.2.1.

### 12 modules from Task #31 (regression I caused this session)
The Phase 153 Task #31 bulk-lockstep script patch-bumped §98 (e.g. `3.3.1 → 3.3.2`) but did NOT bump §00 banners — for unstamped modules this is benign, but 12 of these were stamped (per-file strict promoted) so they failed the gate immediately.

## Fixes applied

### Multi-namespace renumbering (2 modules)
- `spec/07-design-system/98-changelog.md`: file banner `1.7.0 → 3.4.0`; topmost release row heading `### 1.7.0 → ### 3.4.0`. Older 1.x rows left as historical record (gate only inspects topmost row). Lesson note added inline.
- `spec/14-update/diagrams/98-changelog.md`: file banner `1.2.1 → 3.4.0`; topmost release row heading `### 1.2.1 → ### 3.4.0`. Same treatment.

### §00 banner patch bumps (12 modules)
For each Task #31 fallout, bumped `**Version:**` on §00 to match §98 latest release; also bumped `**Updated:**` to today. Paths:
```
spec/13-generic-cli                                                            (1.1.0 → 1.1.1, blockquote-style banner)
spec/25-app-issues/02-consolidated-audit-findings                              (1.1.0 → 1.1.1)
spec/18-wp-plugin-how-to/02-enums-and-coding-style                             (1.1.0 → 1.1.1)
spec/12-cicd-pipeline-workflows/03-reusable-ci-guards                          (1.0.0 → 1.0.1)
spec/03-error-manage/01-error-resolution                                       (3.2.1 → 3.2.2)
spec/03-error-manage/02-error-architecture                                     (3.2.1 → 3.2.2)
spec/03-error-manage/03-error-code-registry                                    (3.2.1 → 3.2.2)
spec/03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference  (3.2.1 → 3.2.2)
spec/02-coding-guidelines/10-research                                          (3.3.1 → 3.3.2)
spec/02-coding-guidelines/22-app-issues                                        (3.3.1 → 3.3.2)
spec/02-coding-guidelines/11-security/01-axios-version-control                 (3.2.0 → 3.2.1)
spec/02-coding-guidelines/03-golang/01-enum-specification                      (3.3.1 → 3.3.2)
```

### §99 Updated date back-fill (7 modules)
Bumping §00 `Updated:` triggered lockstep failures (L1: §99 stale). Bulk-bumped §99 `Updated:` to today on the 7 affected modules.

## Validation

- `python3 linter-scripts/check-version-parity.py` → **74/74 matches, 0 mismatches** (was 73/74 even at session start — net improvement, not just regression-recovery).
- `node linter-scripts/check-lockstep.cjs --strict` → **PASS** (87/87, 0 findings).
- `node linter-scripts/check-tree-health.cjs --strict` → **PASS** (168/168, 100/100).

## Lessons codified

1. **Multi-namespace SemVer in §98 is a structural anti-pattern.** Two modules in the tree (spec/07, spec/14/diagrams) had §00 tracking module-version while §98 tracked file-scaffold-version. The check-version-parity gate enforces "single namespace" implicitly — pick one track per file and stick with it. Going forward, §98 release rows MUST track §00's module-version namespace. Documented inline in spec/07 §98 Phase 153 Task #32 row.
2. **Bulk-lockstep scripts MUST bump §00 in lockstep with §98 patch bumps for stamped modules.** Phase 153 Task #31's `bulk_lockstep.py` only touched §97/§98/§99 — for stamped modules (where check-version-parity is strict-promoted), this is a regression. Future bulk scripts SHOULD: (a) detect `<!-- h10-verified-phase: -->` stamps; (b) when present, also bump §00 `**Version:**` and `**Updated:**`; (c) THEN bump §99 `Updated:` to match. Codify in `mem://process/verifies-clause-authoring` if reused.
3. **Run `check-version-parity.py` AFTER any bulk-lockstep sweep.** Lockstep gate ONLY checks DATE relations (L1: §98 latest date ≥ §00 Updated date), not version strings. Version-parity is a separate gate. Phase 153 Task #31 skipped this verification step — caught here.
4. **`spec/13-generic-cli` uses blockquote-prefixed banner** (`> **Version:**`) — most other modules use bare. Regex fixers MUST handle both shapes.


---

**Related lessons:** see [`mem://process/phase-153-lessons`](../../../memory/process/phase-153-lessons.md) for the consolidated Phase 153 contributor rules (#11–#37).
