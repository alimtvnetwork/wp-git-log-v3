---
phase: 153
task: A24-fu43-fu1
date: 2026-05-04
status: CLOSED — 3/3 spec/12 subfolder archetype GWT ACs shipped; closes parent AC-13 stub mandate tree-wide
gates: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN
predecessor: A24-fu43 (parent AC-12 Subfolder Delegation Map + AC-13 stub mandate)
backlog-source: parent §97 AC-13 explicit "Tracker: backlog `A24-fu43-fu1`" reference
---

# A24-fu43-fu1 — spec/12 archetype GWT close-out (3/3 subfolders)

## What shipped

Per parent AC-13's forward-looking authoring contract (Lesson #29 pattern),
each spec/12 subfolder gained one archetype-specific GWT AC anchoring the
runtime contract its `00-overview.md` declares as in-scope:

| Subfolder | New AC | Severity | Coverage |
|---|---|---|---|
| `01-browser-extension-deploy/` | **AC-BX-09** | `[high]` | source-map exclusion, MV3 invariant, diamond-build ordering, asset naming, native-binary exclusion + 5 forbidden patterns |
| `02-go-binary-deploy/` | **AC-GB-09** | `[high]` | 6-target cross-compile matrix, version embedding via ldflags, asset naming/compression, SHA dedup gate, per-asset SHA256 checksums, install-script attachment + 5 forbidden patterns |
| `03-reusable-ci-guards/` | **AC-CG-09** | `[high]` | forbidden-name guard runtime: diff-scope, exit-code separation (1=finding/2=infra), greppable output, config schema, language-adapter contract, baseline regen + 4 forbidden patterns |

Each AC closes audit-v7 finding `[D2 HIGH] Archetype GWT Stubs` for its
respective subfolder by adding ≥1 runtime-behaviour GWT beyond the AC-01..08
structural floor.

## Lockstep deltas (8 banner bumps across 3 modules)

| File | Before | After | Bump kind |
|---|---|---|---|
| `01-browser-extension-deploy/97` | v1.1.0 | **v1.2.0** | minor (new AC) |
| `01-browser-extension-deploy/98` | v3.4.2 | **v3.4.3** | patch (banner-only absorb) |
| `01-browser-extension-deploy/99` | v3.5.1 | **v3.5.2** | patch |
| `01-browser-extension-deploy/00` | v3.4.2 | **v3.4.3** | patch + h10 stamp 153 |
| `02-go-binary-deploy/97` | v1.1.0 | **v1.2.0** | minor |
| `02-go-binary-deploy/98` | v3.4.2 | **v3.4.3** | patch |
| `02-go-binary-deploy/99` | v3.5.1 | **v3.5.2** | patch |
| `02-go-binary-deploy/00` | v3.4.2 | **v3.4.3** | patch |
| `03-reusable-ci-guards/97` | v1.1.0 | **v1.2.0** | minor |
| `03-reusable-ci-guards/98` | v1.0.1 | **v1.0.2** | patch |
| `03-reusable-ci-guards/99` | v1.0.1 | **v1.0.2** | patch |
| `03-reusable-ci-guards/00` | v1.0.1 | **v1.0.2** | patch + h10 stamp 32→153 |

Parent `spec/12-cicd-pipeline-workflows/97-acceptance-criteria.md` deliberately
NOT bumped — per **Lesson #36** the AC-13 contract surface (parent) and its
implementations (subfolders) are different files; the subfolder shipments
satisfy AC-13's forward-looking authoring contract without modifying AC-13's
prose. Restating archetype invariants in parent §97 would create dual-source
drift.

## Why this was unblocked (vs walker-cap-blocked findings)

Per **Lesson #75** triage (walker-cap before self-lift): the spec/12 D2 HIGH
finding was **not** walker-cap-blocked. The auditor explicitly cites the
subfolder §97 contents ("AC-01..AC-08 structural stubs only") proving it can
see them — distinct from spec/22's "Missing Core Normative Files" or
spec/18's "Truncated Context Cap" findings where the cited files never enter
the bundle. AC-BX-09/GB-09/CG-09 will be visible to the next auditor pass
because the subfolder bundles cap at <40 KB each (well under 140 KB walker
budget).

## Expected next-rebaseline impact

When gateway capacity returns (Lesson #74 probe):
- spec/12 D2 HIGH finding closes → projected lift 83 → 87+ (D2 axis multiplier
  on integration-spec is 1.18, so a HIGH→none transition on D2 yields ~+4
  weighted points; the CRITICAL D5 walker-cap finding remains until A18
  raises bundle budget further or sub-archetype specs are inlined).
- 3 subfolders gain archetype GWT visible to deep-walk audits.

## Lessons applied

- **#21** (Subfolder Delegation Map): parent AC-12 already shipped; this
  phase fills in the delegated surfaces.
- **#23** (legacy ACs need GWT successors): AC-01..08 structural floor was
  necessary but insufficient; archetype GWT extends.
- **#29** (forward-looking authoring contract): parent AC-13 was a contract
  binding future contributors; this phase IS that contributor.
- **#36** (cross-module cross-references MUST link, never restate): parent §97
  unmodified; subfolder ACs cite parent AC-13 in their **Verifies:** clauses.
- **#37** (integration-axis modules need both Lesson #19 and Lesson #36 ACs):
  spec/12 already had both via A24-fu43; A24-fu43-fu1 fulfils the deferred
  third leg (Lesson #29-style forward-looking close-out).
- **#75** (walker-cap triage): confirmed not blocked before opening.

## Files changed

- `spec/12-cicd-pipeline-workflows/01-browser-extension-deploy/{00,97,98,99}*.md`
- `spec/12-cicd-pipeline-workflows/02-go-binary-deploy/{00,97,98,99}*.md`
- `spec/12-cicd-pipeline-workflows/03-reusable-ci-guards/{00,97,98,99}*.md`
- (this memo)

No script changes · no CI workflow changes · no RUBRIC bump · no AC-31-31
cascade (subfolder ACs are not §27 module-level parity ACs).
