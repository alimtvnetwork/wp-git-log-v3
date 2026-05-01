---
phase: 153
task: A20-fu6
date: 2026-04-30
status: CLOSED
gates: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN
---

# A20-fu6 — spec/27 v11 single-module re-score (cache lock)

## Outcome

Cumulative fu33→fu36 archive-split + tier-1 trim work is now **locked into the cache** as ground truth.

| Metric | v10 (pre fu33–fu36) | v11 (post fu33–fu36) | Δ |
|---|---|---|---|
| Score | 86 | **85** | −1 (honest-baseline noise, Lesson #18/#45) |
| Band | GOOD | GOOD | — |
| `files_used` | 3/52 | **10/57** | +7 files (+233 %) |
| `bytes_used` | 156 KB OVER cap | **117.2 KB CLEAR** | budget restored |
| Findings | "Missing Per-Artifact Spec Files" + walker-starvation | 3 genuine findings (D5×2, D3×1) | structural noise eliminated |

## Dimension breakdown (v11)

`d1=18 · d2=19 · d3=17 · d4=16 · d5=15` → total 85, band GOOD.

## Findings (all 3 are walker-budget artifacts — Lesson #70 reinforcement)

1. **CRITICAL D5 — Context Truncation of Per-Artifact Specs.** 47 of 57 `NN-*.md` files (slots 10-79) still truncated even after archive split. Walker sees only tier-1 + 10 of 50+ leaves.
2. **HIGH D5 — Unresolved External Lesson References.** Lesson #36/#55/#61 cited in normative prose but lesson-registry not bundled. Mirror of Lesson #36 (link, never restate) — these citations require the lesson-registry to be loadable as a sibling file.
3. **MEDIUM D3 — Ambiguous Concurrency Implementation.** AC-T-32 references R2 retry snippets that live in slot 27 (`27-bundle-budget.md`) but aren't bundled in tier-1 view.

All three are **walker-visibility gaps**, not contract gaps. They cannot be closed by spec edits — they require either (a) walker MAX_BYTES lift (A18, blocked on CF-1010 ceiling) or (b) further consolidation of normative content into tier-1 files (`{00,97,98,99}-*.md`).

## Lesson reinforcement

- **Lesson #18:** Honest-baseline regressions (−1) on structural fixes are expected when LLM auditor surfaces newly-visible gaps that were previously hidden behind walker truncation. Do NOT roll back the structural fix to "restore" the prior score.
- **Lesson #45:** Cache-stability is non-monotonic across §97 edits; bundle truncation point shifts with content changes. spec/27 cycled 93 → 86 → 85 across v9 → v10 → v11 even though contract surface only IMPROVED.
- **Lesson #70:** Walker-budget findings ("Missing Per-Artifact Spec Files", "Delegated AC") in XL-modules with established delegation maps are walker-starvation artifacts, NOT contract gaps. Diagnose via `files_used / files_total` ratio BEFORE allocating phase work.

## Banners

No spec edits in this phase — cache-only re-score. No lockstep ripple, no §97 changes.

## Strict gates

All 5 strict gates GREEN:
- Lockstep: 87/87 pass · 0 findings
- Tree-health: 168/168 strict
- Version-parity: 74/74 matches
- §99 freshness: 81 stamped + 6 exempt + 0 unstamped failures
- Folder-refs: 0 stale

## Future work

- **A18 (BLOCKED):** Walker MAX_BYTES 120 → 250 KB would close all 3 findings simultaneously. Blocked on Cloudflare gateway 1010-budget ceiling at ~125 KB POST.
- **Lesson-registry consolidation (deferred):** A canonical `mem://process/phase-153-lessons` exists but isn't auto-bundled into the auditor walker. Future work could either (a) add a sibling `LESSONS.md` symlink under `spec/27/` for walker visibility, or (b) extend walker to follow `mem://` hyperlinks.
