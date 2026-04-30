---
phase: 153
task: A11d
status: CLOSED
date: 2026-04-29
module: spec/15-distribution-and-runner
finding-source: v5 audit (`/mnt/documents/spec-ai-implementability-audit-v5.md`) D1 HIGH "Conflicting Versioning Logic"
---

# Phase 153 Task A11d — spec/15 `--branch` CLI flag removal

## Finding (v5)

> **[HIGH/D1] Conflicting Versioning Logic.** `01-install-contract.md` defines `--version` and `--branch` flags, but AC-18 (the deeper contract) explicitly forbids branch names in `--ref` to ensure reproducibility. A mediocre coder will be confused whether to implement branch support or block it.
>
> **fix:** Remove `--branch` from `01-install-contract` and unify all reference flags into a single `--ref` flag that validates against the 'no-branch' rule in AC-18.

## Verification

Confirmed genuine on disk (Lesson #30 verify-before-open):

- `spec/15-distribution-and-runner/01-install-contract.md:51` — `Pinned branch | --branch <name> / -Branch <name> | Downloads from the specified branch head.`
- `spec/15-distribution-and-runner/97-acceptance-criteria.md:121` (AC-18) — `--ref MUST NOT accept a branch name like main — branches move, defeating reproducibility, so the installer MUST detect branch-shaped refs (...) and exit 2`.

Two surfaces, one contract, direct contradiction.

## User direction

Asked via `questions--ask_questions`. User chose **"Remove --branch entirely (strict)"** — single source of truth = AC-18.

## Resolution

### `01-install-contract.md` (v1.0.0 → v1.1.0)

- § "Versioning" table: deleted `Pinned branch | --branch <name> / -Branch <name>` row.
- Added `Pinned ref | --ref <tag-or-sha> (Bash) / -Ref <tag-or-sha> (PS)` row with explicit AC-18 cross-reference and exit-`2` enforcement note.
- Reframed `--version vX.Y.Z` row as a "convenience alias for `--ref vX.Y.Z`".
- Added "**Why no `--branch` flag?**" callout block under the table explaining the reproducibility rationale and pointing devs to `--ref <full-sha>` for unreleased commits.

### `04-install-config.md` (v1.0.0 → v1.1.0)

- § "Override precedence" CLI-flag bullet: removed `--branch`; added explicit prose declaring `--branch as a CLI flag is FORBIDDEN`; clarified that the JSON `branch` field is the **default-branch hint** for tag probing only (NOT a CLI override surface).

### Lockstep ripple

| File | Before | After | Reason |
|---|---|---|---|
| `01-install-contract.md` | 1.0.0 | **1.1.0** | CLI surface contract change (module is `future-spec` — no shipped binary impacted, so minor not major) |
| `04-install-config.md` | 1.0.0 | **1.1.0** | Precedence prose change |
| `00-overview.md` | 2.1.0 | **2.2.0** | Bumped to match §98 SemVer-max per `check-version-parity.py --strict` |
| `98-changelog.md` | 2.1.1 | **2.2.0** | New `Removed`-category release row; module surface change |
| `99-consistency-report.md` | 2.1.1 | **2.1.2** | Audit row added |

### No §97 AC change

AC-18 already declared the no-branch rule. This commit removes the contradicting prose surface — no new contract added, no AC count change, no AC-31-31 cascade.

## Gates verified

```
check-lockstep.cjs                : 87/87 pass · Findings: 0 · ✓ PASS
check-tree-health.cjs --strict    : 168/168 (15% weight) · Score 100/100 · ✓ PASS
check-version-parity.py --strict  : 74/74 matches · 0 mismatches · ✓ PASS
check-99-summary-freshness.py     : 81 stamped + 6 exempt + 0 unstamped · ✓ PASS
```

All 4 strict gates GREEN.

## Expected v6 score lift

spec/15: **92 → ≥96** (D1 +4 — direct contract conflict closed; pushes module from GOOD into EXCELLENT band joining spec/24 [93] and spec/23 [97]).

LLM re-score deferred per Lesson #20 (gateway budget — covered by next A8 rebaseline).

## Lessons reinforced

- **Lesson #30 (verify-before-open):** confirmed cache finding against disk before opening the phase. v5 cache was accurate.
- **Lesson #34 (cache-staleness audit):** cache HIGH-D1 finding was genuine, not a stale-cache artifact — exactly the class of finding the cache exists to surface.
- **Lesson #36 (cross-module link, never restate):** the new "Why no `--branch`?" callout in `01-install-contract.md` LINKS to AC-18 (does not restate); `04-install-config.md` LINKS to both AC-18 and `01-install-contract.md` § "Versioning" (does not restate the rule). Single source of truth = AC-18.
