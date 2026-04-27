---
phase: 121
title: AC-SAG-27 enumeration sweep — folders §12, §13, §15, §16
mode: discovery-only
predecessor: phase-118-folders-04-14-28-enumeration-sweep.md
successor: TBD (Phase 117 mechanization or further sweeps)
date: 2026-04-27
---

# Phase 121 — Enumeration sweep across §12, §13, §15, §16

## Scope

Continuation of the AC-SAG-27 (3+ files = qualifying enumeration) sweep started in Phase 116/118. Folders triaged this phase:

| Folder | Files | Verdict |
|---|---|---|
| `spec/12-cicd-pipeline-workflows/` | 22 + 3 nested subdirs | 1 candidate surfaced (Candidate N) |
| `spec/13-generic-cli/` | 24 | 1 candidate surfaced (Candidate L) |
| `spec/15-distribution-and-runner/` | 8 | dismissed (no 3+ enumeration restatement; install/runner contracts are narrative) |
| `spec/16-generic-release/` | 13 | folds into existing §14 Candidate E (no NEW enumeration surfaced) |

## Surfaced candidates (added to AC-31-31 backlog)

### Candidate L — Shell-wrapper activation states (3-enum)

- **Enum**: `LOADED` / `INSTALLED_BUT_NOT_LOADED` / `NOT_INSTALLED`
- **Canonical site**: `spec/13-generic-cli/21-post-install-shell-activation.md` (PIA-5 acceptance criterion + state-table at lines 182–184 + decision-tree at lines 190–192 + cross-shell support matrix at lines 238–240).
- **Restatement count within §21**: 4 sites (PIA-5 AC, state-table, detection algorithm, support matrix).
- **Cross-folder restatement**: `spec/12-cicd-pipeline-workflows/02-release-pipeline.md` references `LOADED` in post-install activation discussion (1 site).
- **Total sites**: ≥4 (single-folder dominant).
- **Drift risk**: HIGH within §21 — same 3-tuple appears in 4 separate tables/lists with no canonical-vs-citer discipline. If a 4th state (e.g., `LOADED_STALE`) is ever introduced, all 4 sites must be updated synchronously.
- **Test type**: **3-enum uniform-parity** (same shape as §22 Candidate B `AppStatus` and §14 Candidate E GOOS/GOARCH). Reusable harness `test-enum-uniform-parity.sh` would cover B + E + L in one mechanization pass.
- **Recommendation**: Mechanize alongside Phase 117 group `B+E+L` (now a 3-candidate uniform-parity batch).

### Candidate N — Install-script placeholder tokens (4-enum)

- **Enum**: `VERSION_PLACEHOLDER`, `REPO_PLACEHOLDER`, `EMBEDDED_VERSION`, `EMBEDDED_REPO`.
- **Canonical site**: `spec/16-generic-release/03-install-scripts.md` (definitional) + `spec/16-generic-release/08-version-pinned-release-installers.md` (rationale).
- **Restatement sites (8 files)**:
  - `spec/16-generic-release/03-install-scripts.md`
  - `spec/16-generic-release/07-known-issues-and-fixes.md`
  - `spec/16-generic-release/08-version-pinned-release-installers.md`
  - `spec/12-cicd-pipeline-workflows/04-install-script-generation.md`
  - `spec/12-cicd-pipeline-workflows/02-release-pipeline.md`
  - `spec/12-cicd-pipeline-workflows/10-release-pipeline-issues-rca.md`
  - `spec/12-cicd-pipeline-workflows/02-go-binary-deploy/02-release-pipeline.md`
  - `spec/12-cicd-pipeline-workflows/02-go-binary-deploy/03-complete-workflow-reference.md`
- **Drift risk**: HIGH — the 4 placeholder tokens span TWO folders (§12, §16) with no §07-style canonical catalog. Adding a 5th token (e.g., `EMBEDDED_COMMIT`) requires synchronized edits across 8 files in 2 folders.
- **Test type**: **Containment** (every placeholder used in §12 or §16 MUST appear in a yet-to-be-created canonical list). Same shape as §22 Candidate A `GL-*` and §28 Candidate H `GLCI-*`.
- **Pre-mechanization need**: a canonical placeholder catalog file. Recommendation: add `spec/16-generic-release/09-placeholder-tokens.md` (new file, AC-PT-1..N) before authoring the containment test.
- **Recommendation**: Defer to **Phase 123** (after Phase 122 §17 OpenAPI decision) — Candidate N requires a NEW catalog file, which is heavier than mechanization-only.

## Dismissed candidates

| Candidate | Folder | Why dismissed |
|---|---|---|
| `Mode<Name>` / `Output<Name>` / `Cmd<Name>` constants | §13 (15-constants-reference.md) | These are **naming conventions**, not enumerations. The catalog is open-ended (any future subcommand adds a `Cmd<Name>`). Conventions are tested by lint rules (already covered by AC-SAG-15), not by containment. |
| `ErrSourceRequired` / `ErrConfigLoad` style error names | §13 (07-error-handling.md) | Defined as a **convention** ("All error format strings in `constants`") with only 2 illustrative examples. No enumeration restated across files. Dismissed (insufficient sites). |
| Install/runner exit codes | §15 (01-install-contract.md, 02-runner-contract.md) | Exit codes mentioned narratively (0 / 1 / non-zero) but not restated as a 3+ site enumeration. Borderline — flagged for re-check IF Phase 117 surfaces additional exit-code overlap with §13 §07. |
| GOOS/GOARCH 6-tuple in §16 | §16 (01-cross-compilation.md, 97-acceptance-criteria.md, 98-changelog.md) | Already captured as **Candidate E** in Phase 118. §16 sites add to E's site count (E is now ≥10 sites across §13/§14/§16/§12) but introduce no new enum. Increment E's site-count estimate, do NOT spawn new candidate. |
| `CGO_ENABLED=0` static-link enforcement | §16, §12 | Single binary flag (1-token), not an enumeration. Convention, not an enum. |
| `release-metadata.json` schema keys | §16 (06-release-metadata.md) | Same dismissal logic as Phase 118 Candidate I (`latest.json`): keys are **API surface**, schema-validated by JSON Schema, not enumeration drift. Out of AC-31-31 scope. |

## Cross-folder pattern observations

1. **Placeholder tokens are an under-managed enum class.** Both §12 and §16 use `VERSION_PLACEHOLDER`/`REPO_PLACEHOLDER`/`EMBEDDED_*` informally. This is the SECOND cross-folder enumeration found (after §13/§14/§16/§12 GOOS/GOARCH). Reinforces Phase 118's cross-folder-pattern observation queued for AC-SAG-27 amendment.
2. **§16 is a heavy citer of §14 enumerations.** §16's GOOS/GOARCH coverage is 100% derivative of §14 Candidate E. §16 should add a `**Defers-to:** §14` banner clause (per AC-SAG-25 deference discipline) — surfaces Phase 124 candidate.
3. **§13 is enumeration-light.** Despite 24 files, §13 yielded only 1 enumeration candidate (L). Most §13 content is naming conventions, code style, and protocol descriptions — not enums. This suggests AC-SAG-27 sweeps will surface diminishing returns on convention-heavy folders.

## Updated AC-31-31 backlog (post-Phase 121)

| ID | Source phase | Type | Sites | Mechanization group |
|---|---|---|---|---|
| A | 116 (§22 `GL-*`) | containment | 9 | **A+H+N** (containment harness) |
| B | 116 (§22 `AppStatus`) | uniform-parity | 4 | **B+E+L** (uniform-parity harness) |
| E | 118 (GOOS/GOARCH 6-tuple) | uniform-parity | ≥10 (§13/§14/§16/§12) | **B+E+L** |
| H | 118 (§28 `GLCI-*`) | containment | 9+ | **A+H+N** |
| K | 120 (§28 output-buckets) | uniform-parity | 3 | **B+E+L** (could fold) |
| **L (NEW)** | 121 (§13 wrapper states) | uniform-parity | ≥4 | **B+E+L+K** |
| **N (NEW)** | 121 (placeholder tokens) | containment | 8 | **A+H+N** (needs new catalog file) |

**Backlog total**: 7 candidates (was 5).

## Recommended next phases

| Phase | Action | Mode |
|---|---|---|
| **117** | Mechanize. Recommend grouping: (a) **containment harness** covers A + H (catalog-already-exists) and **defers** N until Phase 123 creates its catalog. (b) **uniform-parity harness** covers B + E + K + L in one go. CI gates 13 → **15** (one per harness). | 🚧 Decision |
| **122** | §17 OpenAPI carries 0 GLCI-* despite §22 §17 carrying all GL-*. Decide enumerate-vs-leave-code-free. | 🚧 Decision |
| **123** | Create `spec/16-generic-release/09-placeholder-tokens.md` canonical catalog (Candidate N pre-req), then mechanize Candidate N containment. | 🤖 Autonomous after Phase 117 |
| **124** | Add `**Defers-to:** §14` banner to §16 cross-compilation files (per cross-folder observation 2). Lightweight banner-discipline fix. | 🤖 Autonomous |
| **125** | Continue AC-SAG-27 sweep across remaining content folders: `spec/02-*` through `spec/11-*`, `spec/17-consolidated-guidelines/`, `spec/24-*`, `spec/29-*`. | 🤖 Autonomous |

## Completion certification

- ✅ 4 folders triaged
- ✅ 2 new candidates (L, N) added to backlog with site counts and mechanization grouping
- ✅ 6 candidates explicitly dismissed with rationale
- ✅ 3 cross-folder pattern observations recorded
- ✅ Discovery-only — zero spec files modified
