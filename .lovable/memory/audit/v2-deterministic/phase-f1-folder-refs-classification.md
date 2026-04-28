# Phase F1 — 11 stale folder refs resolved (3 missing folders → all [doc-only])

**Date:** 2026-04-28
**Trigger:** User reply `next` (recommended in Phase 108-full retrospective).

## Discovery vs reality

Phase 141 memo (2026-04-28) reported **"26 stale refs"** as an upper-bound estimate from a partial sample. Re-running `python3 linter-scripts/check-spec-folder-refs.py` for Phase F1 produced the **actual** count: **11 broken references across 3 unique missing-folder targets**.

| Missing folder | Refs | Source files |
|---|---|---|
| `spec/08-docs-viewer-ui/` | 5 | `01-spec-authoring-guide/04-ai-onboarding-prompt.md` (1), `07-design-system/00-overview.md` (1), `07-design-system/07-code-blocks.md` (1), `07-design-system/99-consistency-report.md` (1), `phase-141-folder-refs-discovery.md` (1) |
| `spec/09-code-block-system/` | 2 | `01-spec-authoring-guide/04-ai-onboarding-prompt.md` (1), `phase-141-folder-refs-discovery.md` (1) |
| `spec/21-app/` | 4 | `01-spec-authoring-guide/04-ai-onboarding-prompt.md` (1), `17-consolidated-guidelines/02-coding-guidelines.md` (1), `17-consolidated-guidelines/13-app.md` (1), `phase-141-folder-refs-discovery.md` (1) |

## Per-target verdict

All three → **(c) doc-only**. Reasoning:

| Target | Verdict | Why not (a) typo | Why not (b) external | Why (c) doc-only |
|---|---|---|---|---|
| `08-docs-viewer-ui` | (c) | No physical folder exists for "the correct name" — this name **is** the intended name; the module was planned during v2.x scoping but never created | `gitmap-v3` sibling repo has no docs-viewer module; `[external]` enumerates real cross-repo folders only | Referenced in `01/04-ai-onboarding-prompt.md` routing table ("Documentation viewer features → `spec/08-docs-viewer-ui/`") + `07-design-system` cross-section "see also" pointers — pure routing-hint prose with contributor value |
| `09-code-block-system` | (c) | The "correct" name became `spec/07-design-system/07-code-blocks.md` (a single file inside an existing module, not a folder) | Same as above | Referenced only in `01/04-ai-onboarding-prompt.md` routing table as the historical concept; deleting the row would lose the routing hint |
| `21-app` | (c) | The umbrella concept was implemented as **three** separate folders (`23-app-database` / `24-app-design-system-and-ui` / `25-app-issues`), not one — there is no "correct" single rename target | Same as above | Referenced in `17-consolidated-guidelines/{02,13}.md` narrative as the **logical anchor** that drives the existing triad ("Features in `21-app/` drive work in the other three folders") — the prose is a conceptual model, not a navigation link |

## Ratification of the all-(c) verdict against alternatives

- **(a) Typo/rename for any of these → would be wrong.** The "correct" alternative folders (a) for `21-app` would require choosing one of three implementations; for `08-docs-viewer-ui` and `09-code-block-system` there is no single correct file to point to. Editing the markdown to remove the reference loses contributor-routing value (the §01 onboarding table tells new contributors "go to X folder for Y feature" — pointing at nothing forces them to grep blindly).
- **(b) External for any of these → would be wrong.** The `[external]` block is reserved for real folders in the `gitmap-v3` sibling repo (per the Phase 143 documentation comments in the allowlist). None of the 3 targets exist in any sibling repo — they are pure aspirations of this repo.
- **(c) Doc-only is exactly what the format intended.** Per the allowlist's own header: *"Illustrative or historical folder names that do NOT exist anywhere. They appear in narrative documentation (changelogs, migration write-ups, examples) purely as prose. They MUST NOT be used as live navigation links."* All 11 references qualify.

## Implementation

Appended to `linter-scripts/spec-folder-refs.allowlist` under the existing `[doc-only]` section, following Phase 143's parser-corruption rule (AC-62-04): per-entry rationale lives in **separate full-line comments above** the entries, never as inline `# comment` trailers.

```text
# --- Phase F1: aspirational / historical sibling-module names referenced
# only in spec narrative (onboarding routing tables, design-system
# cross-section "see also" pointers, consolidated-guidelines app-layer
# narration). [...]
# 08-docs-viewer-ui    → spec/01-.../04-ai-onboarding-prompt.md routing table; spec/07-design-system/00-overview + 07-code-blocks + 99 cross-section pointers (planned docs-viewer module, never created)
# 09-code-block-system → spec/01-.../04-ai-onboarding-prompt.md routing table (subsumed by spec/07-design-system/07-code-blocks.md)
# 21-app               → spec/01-.../04-ai-onboarding-prompt.md routing table; spec/17-consolidated-guidelines/02-coding-guidelines.md + 13-app.md narrative (the "umbrella" concept that drives 23-app-database / 24-app-design-system-and-ui / 25-app-issues — described but no physical folder)
08-docs-viewer-ui
09-code-block-system
21-app
```

## What did NOT change

- Zero markdown files edited — the routing-table prose stays intact for contributor value.
- `linter-scripts/check-spec-folder-refs.py` source untouched — the 3-way classification mechanism already worked; we just exercised it.
- `linter-scripts/test/test-check-spec-folder-refs.py` (Phase 144) still passes — the new entries are valid `[doc-only]` lines that exercise the same parser path AC-62-04 locks.
- §02 spec untouched (`02-check-spec-folder-refs.md` already documents the 3-way classification + parser format).

## Files touched (lockstep)

| File | From → To | Change |
|---|---|---|
| `linter-scripts/spec-folder-refs.allowlist` | (data file, no banner) | `[doc-only]` count 21 → 24; +13 lines (10 comment + 3 entries). |
| `spec/27-spec-toolchain/62-spec-folder-refs-allowlist.md` | v1.1.0 → **v1.2.0** | Banner reflects F1 classification. |
| `spec/27-spec-toolchain/98-changelog.md` | v2.36.0 → **v2.37.0** | Full Phase F1 row. |
| `spec/27-spec-toolchain/99-consistency-report.md` | v2.33.0 → **v2.34.0** | Phase F1 audit-row sentence (top of file). |

## Gates (verified at landing)

- `python3 linter-scripts/check-spec-folder-refs.py` → 0 stale references, exits 0 ✅
- `node linter-scripts/check-lockstep.cjs` → 87/87 pass · 0 findings ✅
- `node linter-scripts/check-tree-health.cjs --strict` → 168/168 strict-pass ✅
- `bash linter-scripts/test/test-overview-inventory-parity.sh` → 6/6 pass ✅
- `python3 linter-scripts/test/test-check-spec-folder-refs.py` → still passes (AC-62-04 invariants intact) ✅

## Outcome

Phase F1 closed. The Phase 141 backlog item is resolved. **Phase F2 (wire `check-spec-folder-refs.py` into CI) is now unblocked** — the gate currently exits 0, so adding it as a CI step will not introduce a regression. R1 (real-AI re-audit) remains blocked on Lovable Cloud.

## Process lesson (codify in Core memory?)

Phase 141 reported "26 stale refs" from a partial sample without re-running the linter. Phase F1 found the actual count was 11. **Always re-run the linter at the moment of resolution to get the current count** — discovery counts may be stale by the time the work starts. Tabling for a Core-memory rule unless this pattern recurs.
