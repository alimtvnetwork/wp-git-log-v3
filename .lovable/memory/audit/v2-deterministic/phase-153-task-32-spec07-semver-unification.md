# Phase 153 Task #32 — spec/07 §98 SemVer-track unification (parity drift CLOSED)

**Date:** 2026-04-29  
**Trigger:** User reply `next`. Long-standing parity drift `§00=v3.4.0 vs §98=v1.7.0` recorded in remaining-tasks list since Phase 151.

## Investigation

§07's §98 was tracking TWO SemVer namespaces in parallel:
- **1.x track** (rows 1.0.0..1.5.0) — file-scaffold versioning inherited from early-project format.
- **3.x track** — module SemVer started by Phase 56 (`3.3.0` row, 2026-04-27) intending to align §98 with §00's banner.

Phase 151 regressed onto the 1.x track, adding `1.7.0` at the top, producing `§00=3.4.0 vs §98=1.7.0` parity drift visible to `check-version-parity.py`.

## Resolution

Already partially fixed in a prior session: the topmost §98 row's heading was renumbered `1.7.0 → 3.4.0` and a Task-#32 lockstep-correction note was added inline. This Task #32 closure formalises the correction at the §99/§00/§98 banner level:

- **§99 v3.9.0 → v3.10.0** — new top blockquote documenting the SemVer-track unification + going-forward "one track per file" rule + supersedes-marker on the v3.9.0 blockquote's stale `§98 v1.6.0 → v1.7.0` claim.
- **§00 v3.4.0 → v3.4.1** — h10 stamp refreshed `verified-phase: 32` → `153`.
- **§98 v3.4.0 → v3.4.1** — new release row 3.4.1 documenting the §99 narrative refresh + h10 stamp refresh; v3.4.0 row already carries the renumbering note.

Older 1.x rows preserved as historical record; `check-version-parity.py` inspects only the topmost row.

## Validation

- `check-lockstep.cjs --strict`: 87/87 pass · 0 findings
- `check-tree-health.cjs --strict`: 168/168 (100/100)
- `check-ai-confidence.py`: 51/51 matches · 0 mismatches
- `check-version-parity.py`: spec/07 NOT in FAIL list (15 unrelated FAILs persist — separate work)

## Lesson codified (in §07 §98 v3.4.0 row, §07 §99 v3.10.0 banner, §07 §98 v3.4.1 row)

When §98 is tempted to track its own SemVer namespace independent of §00 (e.g. file-scaffold version vs module version), `check-version-parity.py` WILL fail at every §00 bump. **Pick one SemVer track per file and stick with it.** §07 §98 release rows MUST track §00's module SemVer going forward; if a file-scaffold version is needed, encode it in front-matter or a dedicated `**File-format:**` banner field — never in the §98 release-row heading.

Operational sub-lesson: renumbering ONLY the topmost §98 row is sufficient to clear the parity gate (no need to retroactively renumber historical rows), BUT the §99 narrative MUST be added so future readers don't see the topmost row at v3.4.x against historical v1.5.0..v1.0.0 below and assume corruption.

## State

Task #32 CLOSED. AI-confidence parity 51/51 (100%) and parity-gate exclusion for spec/07 both confirmed.


---

**Lessons codified:** #25 → see [`mem://process/phase-153-lessons`](../../../memory/process/phase-153-lessons.md) for the canonical contributor-rule statements.
