# Phase 141 — Discovery: 26 stale folder refs in spec/ (not CI-gated)

**Date:** 2026-04-28
**Trigger:** Phase 140 sweep / autonomous queue empty → ran remaining linters → `check-spec-folder-refs.py` reports 26 stale refs.

## Finding

`check-spec-folder-refs.py` reports **26 stale references** to non-existent spec folders, primarily from:
- `spec/01-spec-authoring-guide/04-ai-onboarding-prompt.md` → `spec/08-docs-viewer-ui/`, `spec/09-code-block-system/`, `spec/21-app/`
- `spec/07-design-system/00-overview.md` → `spec/08-docs-viewer-ui/`
- (22 more, sample only inspected)

Script exits 0 (non-fatal) and is **NOT wired into any CI workflow** (verified: zero refs in `.github/workflows/`, zero refs in `linter-scripts/run.sh`). So the gate is dormant — findings are surfaced only on manual run.

## Why not autonomous

Each of the 26 refs needs a 3-way classification per the script's own resolve hint:
- (a) Typo/rename → edit the markdown (fix it)
- (b) Sibling-repo ref → add to `[external]` allowlist
- (c) Documentation-only → add to `[doc-only]` allowlist

That's a governance call (some may be intentional forward-references to planned modules; some may be archaic; some may be sibling-repo paths). Cannot be batched autonomously without per-ref intent.

## Other linters this sweep

| Linter | Status |
|---|---|
| `check-spec-cross-links.py` | ✓ OK — all internal cross-refs resolve |
| `check-mermaid-syntax.mjs` | ✓ 106/106 files clean |
| `check-memo-retrospective-headings.py` | ✓ 0 forbidden headings |
| `check-memory-mirror-drift.py` | ✓ 21/21 core tokens mirrored |
| `check-spec-folder-refs.py` | 🔴 **26 stale refs, not CI-gated** |

## Action

Memo only. Adding **Phase F (folder-refs)** as a new user-decision row in the remaining-tasks queue. No spec edits, no version bumps.

## Recommendation when user picks it up

Cheapest path: dump all 26 refs, group by target folder (likely 5-6 unique missing folders), and bulk-classify each missing target as one of (a/b/c) once. That collapses 26 decisions into ~6.

## Process lesson

`linter-scripts/run.sh` skips on missing Go (this sandbox), so several non-Go linters never run unless invoked individually. Possible follow-up: a `--skip-go` flag on `run.sh` so the rest of the suite still executes locally. Same lesson as Phase 140; tabling.
