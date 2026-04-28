---
title: "§00 ↔ §98 Version-field parity gate"
slot: 29
kind: validator
band: 20-29
script: linter-scripts/check-version-parity.py
status: active
---

# 29 — `check-version-parity.py`

**Phase:** P15 / H10 (2026-04-28)
**Type:** validator (advisory-by-default)
**Band:** 20-29 (validators on §99/§97/§98 lifecycle)

## Purpose

Codify the **Phase 21 lesson** as a CI gate: when a spec module's
`00-overview.md` carries a `**Version:** vX.Y.Z` banner AND a sibling
`98-changelog.md` ships a parseable release line, the §00 banner version
SHOULD equal the latest §98 release version. The lockstep gate (§24)
only checks **date relations** (L1: §98 latest date ≥ §00 Updated date)
— it does NOT check version strings, so the §00 banner can drift many
releases behind §98 while lockstep stays green.

**Phase 21 precedent**: `spec/27-spec-toolchain/00-overview.md` was
discovered at v1.7.0 while §98 was at v2.46.2 — ~39 patch releases
behind, lockstep-invisible.

**Phase P15 baseline sweep (2026-04-28)**: validator's first run
discovered **59 / 74 eligible modules** carry a §00 ↔ §98 version-field
mismatch. The Phase 21 disposition note ("1/3 historical incident, low
surface") was a vast under-count — the surface is systemic, not
incidental. Advisory mode chosen so adoption can proceed PR-by-PR
without blocking 59 unrelated modules in a single sweep.

## Scope and skip rules

The gate scans every `spec/**/00-overview.md` (recursive), with these
skip rules:

1. **`spec/_archive/**` excluded** — archived modules are frozen by
   design (H2 lesson, mirrors slot 26 freshness gate).
2. **No `**Version:**` banner in §00** → counted under
   `skipped(no-banner)`. Opt-in: a module without a banner version cannot
   drift, so it cannot be flagged.
3. **No sibling `98-changelog.md`** → silently skipped (the module isn't
   under lockstep at all).
4. **Sibling `98-changelog.md` with no parseable release line** →
   counted under `skipped(no-release)`. Accepts four release shapes:
   - `## 1.2.0 — 2026-04-28` (heading)
   - `### v4.0.0 — 2026-04-26` (heading)
   - `## [4.1.0] — 2026-04-26` (bracketed heading)
   - `| 3.9.8 | 2026-04-28 | … |` (table-row format, folder 22 style)

## CLI

```
python3 linter-scripts/check-version-parity.py [--strict] [--report-only] [--json] [--spec-root PATH]
```

| Flag | Behavior |
|---|---|
| _(default)_ | Advisory tree-wide; exit 0 even on mismatch — UNLESS the mismatched §00 carries a Phase P20 `<!-- h10-verified-phase: NNN -->` stamp, in which case that file fails per-file strict and the gate exits 1. |
| `--strict` | Exit 1 on any mismatch (tree-wide CI gate when adoption matures). |
| `--report-only` | Never fails (overrides `--strict` AND per-file stamps). Useful for dashboards. |
| `--json` | Machine-readable output with `details[]` array (each entry includes `stamped: <int|null>`); top-level `stamped` and `stamped_failed` counts. |
| `--spec-root PATH` | Override scan root (used by self-test sandboxes). |

## Exit codes

| Code | Meaning |
|---|---|
| 0 | Default mode with zero stamped failures, OR strict with zero mismatches, OR `--report-only` |
| 1 | `--strict` mode AND any mismatch present, OR default mode AND any STAMPED §00 has a mismatch (per-file strict promotion) |
| 2 | Structural error (spec root not found) |

## Output line shape

```
§00 ↔ §98 Version-field parity: scanned=87; eligible=74; matches=15; mismatches=59; skipped(no-banner)=5; skipped(no-release)=8
  (info) spec/01-spec-authoring-guide: §00=3.7.0 vs §98 latest=4.13.0
  (info) spec/22-git-logs-v2: §00=3.8.8 vs §98 latest=3.9.8
  …
```

The shape is asserted by self-test T1 (`scanned=` / `eligible=` /
`matches=` / `mismatches=` / `skipped(no-banner)=` / `skipped(no-release)=`
all present). Future field additions MUST extend T1.

## Why the slot is 29

- Slots 20-29 are the validator/filler band on §99 / §97 / §98 lifecycle.
  §29 sits cleanly between `28-check-archive-exclusion-runtime` (validator)
  and the auditor band starting at §30.
- Slots 25–28 are occupied; 29 is the next free position in-band.
- **No slot-range exception needed** (mirrors slot 26's clean fit, unlike
  slots 18/19 in the 10-19 generator band per AC-T-22/AC-T-23).

## Why advisory-by-default (AC-T-25 dispensation)

AC-T-25 (Phase 30) requires "advisory CI gates require explicit
phased-rollout justification or they ship strict from day 1." The
P15 dispensation:

- **Surface size**: 59/74 mismatches at gate landing. Flipping strict
  immediately would block 59 unrelated PRs OR require a single sweep PR
  that touches every module's §00 banner.
- **Phased-rollout plan**: contributors fix `§00 Version` to match
  `§98 latest release` opportunistically as they touch each module's §98
  in normal phase work. When mismatch count reaches 0, a follow-up phase
  flips `--strict` (mirrors H1→H8 stamp adoption: 0/89 → 87/87 over
  ~1 day; H8 then locked the gain).
- **Visibility**: every CI run prints the count and per-module mismatches
  in the workflow log, so drift cannot grow silently (the AC-T-25 failure
  mode is exactly an unprinted, unwatched advisory).

## Acceptance criteria

### AC-29-01 — Mismatched §00↔§98 in default mode does not fail
- **Given** a module with `**Version:** 1.0.0` in `00-overview.md` and `## 2.0.0 — …` in `98-changelog.md`,
- **When** `check-version-parity.py` runs (default mode, no flags),
- **Then** the file MUST be reported as `(info) … §00=1.0.0 vs §98 latest=2.0.0` AND exit code MUST be 0.

### AC-29-02 — Matched §00↔§98 counts as match
- **Given** a module with `**Version:** 1.2.3` in `00-overview.md` and `## 1.2.3 — …` in `98-changelog.md`,
- **When** the gate runs,
- **Then** the file MUST contribute to `matches=N` AND MUST NOT appear in the `(info)` mismatch list.

### AC-29-03 — Strict mode exits 1 on mismatch
- **Given** at least one mismatch in scope,
- **When** the gate runs WITH `--strict` (and WITHOUT `--report-only`),
- **Then** exit code MUST be 1.

### AC-29-04 — `--report-only` overrides `--strict`
- **Given** any combination of mismatches,
- **When** the gate runs WITH both `--strict` and `--report-only`,
- **Then** exit code MUST be 0 with a `--report-only: not failing.` footer line.

### AC-29-05 — Module without §00 Version banner is skipped
- **Given** a module whose `00-overview.md` carries no `**Version:**` line in its first 40 lines,
- **When** the gate runs,
- **Then** the file MUST contribute to `skipped(no-banner)=N` AND MUST NOT appear under `eligible` or `mismatches`.

### AC-29-06 — Changelog without parseable release is skipped
- **Given** a module whose `98-changelog.md` matches none of the four accepted release shapes (heading / bracketed heading / `vX.Y.Z` heading / table-row),
- **When** the gate runs,
- **Then** the file MUST contribute to `skipped(no-release)=N` AND MUST NOT appear under `eligible` or `mismatches`.

### AC-29-07 — Table-row §98 format (folder 22 style) parsed
- **Given** a `98-changelog.md` whose latest release is `| 3.9.8 | 2026-04-28 | … |`,
- **When** the gate runs against a sibling `00-overview.md` with `**Version:** 3.9.8`,
- **Then** the file MUST contribute to `matches=N` (the table-row format is a first-class release shape).

### AC-29-08 — `spec/_archive/**` excluded from scan
- **Given** any `00-overview.md` under `spec/_archive/`,
- **When** the gate runs,
- **Then** the file MUST NOT contribute to any counter (scanned/eligible/skipped/mismatches) — archived modules are frozen by design.

### AC-29-09 — `--json` output schema
- **Given** the gate runs with `--json`,
- **When** the output is parsed,
- **Then** the JSON object MUST contain top-level keys `scanned`, `eligible`, `matches`, `mismatches`, `skipped_no_banner`, `skipped_no_release`, `details` (array of `{module, banner, latest_release}` objects).

### AC-29-10 — Output line shape contract
- **Given** the gate runs in default text mode,
- **When** the first non-empty stdout line is inspected,
- **Then** it MUST contain all six tokens: `scanned=`, `eligible=`, `matches=`, `mismatches=`, `skipped(no-banner)=`, `skipped(no-release)=`. Self-test T1 enforces this; future field additions MUST extend T1 and bump this AC.

## Self-test

`linter-scripts/test/test-check-version-parity.sh` exercises 10 assertions
(T1–T10) covering: banner shape, default vs strict exit codes,
`--report-only` override, sandboxed match/no-banner/no-release/table-row
modules, `--json` schema, and `_archive/` exclusion. Per the H1 lesson on
workflow-step parity, the self-test is **collapsed into the gate's own
workflow step** (no standalone self-test step) — the gate runs the
self-test first, then runs against the real tree. This preserves
AC-31-28 gate-count parity at 19/19/19.

## Cross-references

- §24 [`24-check-lockstep.md`](./24-check-lockstep.md) — sibling lockstep
  gate that polices DATE relations (L1/L2/L3); H10 polices VERSION strings
  in the same §00 ↔ §98 surface but cannot reuse §24's machinery because
  date arithmetic is a different invariant from version-string equality.
- §26 [`26-check-99-summary-freshness.md`](./26-check-99-summary-freshness.md)
  — H1 lesson source: advisory-then-strict pattern + workflow-step
  collapse for self-tests.
- §27 [`27-check-99-stamp-bump.md`](./27-check-99-stamp-bump.md) — H4/H5
  sibling pattern (event-based stamp gate vs snapshot freshness gate).
- §97 `97-acceptance-criteria.md` — AC-T-26 codifies H10's CI integration
  + AC-T-25 dispensation rationale.
- Phase 21 retrospective (Phase 21 §27 §00-overview stale-prose sweep
  in `spec/27-spec-toolchain/98-changelog.md` row v2.46.3) — H10 origin.

## Slot-range note

Slot 29 is a clean fit in the 20-29 validator/filler band — no exception
needed (unlike slots 18/19 in the 10-19 generator band per AC-T-22/AC-T-23).
The next free slot in this band after H10 is 32 (slots 30/31 are auditors).

## Changelog

### 1.0.0 — 2026-04-28 — Phase P15 / H10
- Initial version. Advisory-by-default §00 ↔ §98 Version-field parity gate.
- Baseline sweep at landing: 87 scanned, 74 eligible, 15 matches, 59
  mismatches (the Phase 21 surface estimate of "1/3 historical incident,
  low surface" was a vast under-count — actual surface is 59/74 ≈ 80%).
- CI wired into `.github/workflows/spec-health.yml` after the §99
  freshness gate (gate #19); runs in default advisory mode (exits 0).
- Self-test 10/10 ✅.
- AC-31-31 cascade: gate count 18 → **19**; `RUBRIC_VERSION` v2.27 → **v2.28**.
