---
phase: 123
title: Placeholder Token Catalog — canonical SoT created (Candidate N pre-req)
mode: implementation
predecessor: phase-128-lint-rule-catalog-shipped.md
successor: TBD (Phase 117 mechanization — all containment pre-reqs now satisfied)
date: 2026-04-27
---

# Phase 123 — `09-placeholder-tokens.md` shipped

## What changed

Created `spec/16-generic-release/09-placeholder-tokens.md` v1.0.0 as the canonical
SoT for install-script placeholder tokens surfaced as Candidate N in Phase 121.

### Files touched (all in §16)

| File | Change |
|---|---|
| `spec/16-generic-release/09-placeholder-tokens.md` | **NEW** v1.0.0 — 7-section catalog covering 2 placeholder families |
| `spec/16-generic-release/97-acceptance-criteria.md` | Added `09-placeholder-tokens.md` to module-specific acceptance surface |
| `spec/16-generic-release/98-changelog.md` | Banner 2.1.0 → 2.2.0, Phase 123 row appended |
| `spec/16-generic-release/99-consistency-report.md` | Inventory 11 → 12, Phase 123 audit row appended |

Slot `09-` was the next free integer; immutable-slot rule respected
(`08-version-pinned-release-installers.md` already shipped, never reused).

## Catalog content — 6 tokens in 2 families

### Family 1 — Legacy `<NAME>_PLACEHOLDER`

| Token | Source variable |
|---|---|
| `VERSION_PLACEHOLDER` | `$VERSION` |
| `REPO_PLACEHOLDER` | `$GITHUB_REPOSITORY` |

Used in §03 install-scripts + §12 install-script-generation + §14 updater paths.

### Family 2 — Modern `__<NAME>__` (double-underscore)

| Token | Source variable |
|---|---|
| `__EMBEDDED_VERSION__` | `$VERSION` (with leading `v`) |
| `__REPO_SLUG__` | `$GITHUB_REPOSITORY` |
| `__BUILD_DATE_UTC__` | `date -u +'%Y-%m-%dT%H:%M:%SZ'` |
| `__COMMIT_SHA__` | `$GITHUB_SHA` |

Used in §08 version-pinned release installer (newer hardened path).

## Important discovery — two coexisting families

The Phase 121 sweep memo listed only the legacy family. The actual implementation
ships **two distinct conventions** that the catalog now reconciles:

- The legacy `_PLACEHOLDER` suffix predates the current `__DOUBLE_UNDERSCORE__`
  convention introduced in §08.
- Both must be replaced before publication; the post-replacement invariant grep
  now needs to match either family: `! grep -qE "PLACEHOLDER|__[A-Z_]+__"`.

The catalog documents this explicitly in §2 and gives separate canonical
replacement commands in §3.1 and §3.2.

## Backlog impact — all containment pre-reqs satisfied

| Candidate | Pre-req status |
|---|---|
| A — §22 `GL-*` codes | ✅ No catalog needed (already canonical in §22 §15) |
| H — §28 `GLCI-*` codes | ✅ No catalog needed (already canonical in §28 §07) |
| N — §16 placeholder tokens | ✅ **Catalog shipped (this phase)** |
| O — Lint rule IDs | ✅ Catalog shipped (Phase 128) |

**All 4 containment candidates are now ready for Phase 117 mechanization.**
The harness can cover the full A+H+N+O set without further deferrals.

## Recommended next phases

| Phase | Action | Mode |
|---|---|---|
| **117** | Mechanize 8-candidate AC-31-31 backlog. Containment harness now covers **A+H+N+O (full set)**. Uniform-parity covers B+E+K+L. | 🚧 Decision |
| **122** | §17 OpenAPI: enumerate `GLCI-*` codes or leave code-free. | 🚧 Decision |
| **124** | Audit §14 GOOS/GOARCH AC-20 cite to §16 generic source. | 🚧 Decision |

## Completion certification

- ✅ New file scaffolded with full v1.0.0 banner, frontmatter, 7-section content
- ✅ Two placeholder families documented (corrects Phase 121 single-family assumption)
- ✅ Lockstep: target file banner + §97 surface + §98 changelog + §99 inventory
- ✅ Slot rule respected (09- was free; never reused 08)
- ✅ Lockstep gate passes (87/87 modules)
- ✅ Memory mirror updated (this memo)
- ✅ Pre-req unblocked for Phase 117 Candidate N
- ✅ **All Phase 117 catalog pre-reqs now satisfied — no further deferrals**
