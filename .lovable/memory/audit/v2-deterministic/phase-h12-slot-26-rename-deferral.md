# Phase H12 — Slot-26 Script Rename Deferral (read-only audit)

**Date:** 2026-04-28
**Trigger:** Backlog item #6 — rename `linter-scripts/check-99-summary-freshness.py` to reflect its now multi-purpose scope (freshness + exemption [H8/AC-26-09] + stamp-position enforcement [H9/AC-26-10]).
**Type:** Audit-only — no §98/§99 banner bumps (per H6 lesson #2).

## Goal
Apply the H10 graduation filter + H11 surface-elimination preference to a proposed cosmetic rename. Decide whether the rename is justified vs. deferred.

## Reference-surface audit
Mechanical `grep` across `.github/`, `linter-scripts/`, `spec/`, `.lovable/` (excluding `__pycache__` and phase memos themselves) returned **40+ live references** to `check-99-summary-freshness` across:

| Surface | Count | Type |
|---|---|---|
| `.github/workflows/spec-health.yml` | 2 | live invocation + footer comment |
| `linter-scripts/audit-spec-vs-code-v2.py` (gate registry text) | 2 | gate #15 + gate #17 prose |
| `linter-scripts/check-99-stamp-bump.py` | 2 | sister-gate cross-reference docstring |
| `linter-scripts/check-99-summary-freshness.py` | 2+ | self-references (banner + usage) |
| `linter-scripts/test/README.md` | 2 | inventory rows for tests #8 and #10 |
| `linter-scripts/test/test-archive-exclusion-runtime.sh` | 4 | importlib probe |
| `linter-scripts/test/test-check-99-summary-freshness.sh` | 4 | self-test target |
| `linter-scripts/trace-map.toml` | 16 | 8 `[[trace]]` entries × (ac path + files path) for AC-26-01..08 |
| `spec/27-spec-toolchain/00-overview.md` | 1 | slot-26 row |
| `spec/27-spec-toolchain/26-check-99-summary-freshness.md` | 3 | front-matter + H1 + usage block (file slot itself) |
| `spec/27-spec-toolchain/27-check-99-stamp-bump.md` | 3 | sister-gate references |
| `spec/27-spec-toolchain/28-check-archive-exclusion-runtime.md` | 3 | probe-table + cross-link |
| `spec/27-spec-toolchain/98-changelog.md` | ~7 | historical Phase H1/H2/H3/H7/H8/H9 changelog rows |
| Phase memos (H1, H2, H3, H7, H8, H9, H10, H11) | many | historical narrative |

Plus the file slot itself (`spec/27-spec-toolchain/26-check-99-summary-freshness.md`) is **immutable** per memory Core: "File slots are immutable once shipped — never reuse a number; if content moves, rename the slot and add a §99 audit row." Renaming the slot file would trigger AC-31-31 cascade + 168/168 tree-health re-validation + lockstep re-issue across 3 cousin gates (slot 25, 27, 28 all link by relative path).

## H10 filter applied

| Criterion | Verdict | Reasoning |
|---|---|---|
| Mechanically detectable? | ❌ No | "Name understates scope" is a semantic judgment. No deterministic check distinguishes a "good" multi-purpose name from a "narrow" one. The file is correctly bound to its spec slot via `script:` front-matter (AC-26-* + AC-31-31). |
| Active regression surface? | ❌ No | Future contributors discover the script's capabilities via spec slot 26 (which IS authoritative — AC-26-01..10 enumerate every behavior). Filename is a label, not a contract. The contract surface (AC IDs + spec doc + trace-map binding) is intact. |
| Low false-positive risk? | ❌ No | A rename is mechanically clean per-reference but creates a **historical consistency burden**: every closed phase memo (H1–H11) references the old name as a stable identifier. Either (a) memos become stale (false memory drift) or (b) memos get bulk-rewritten (violates the "memos are historical artifacts" principle from H10/C3). |

## Surface-elimination check (H11 preference)
The "name understates scope" complaint surfaces because contributors might assume the script only does freshness. Has the regression surface already been eliminated?

**Yes — by the spec slot itself.** `spec/27-spec-toolchain/26-check-99-summary-freshness.md` already enumerates AC-26-09 (exemption) and AC-26-10 (stamp position) inline. Any contributor reading the slot doc (the canonical entry point for "what does this gate do?") sees the full scope. The filename is a navigational handle, not the documentation surface.

## Cost / benefit summary

**Cost of rename:**
- 40+ live reference updates across 6 file types and 4 directory trees.
- Slot-26 file rename (e.g. to `26-check-99-summary-stamps.py` or `26-check-99-stamp-contract.py`) violates the immutability rule UNLESS we keep the slot doc filename and only rename the script — but then `script:` front-matter / `00-overview.md` / `27-stamp-bump.md` cross-link / `28-archive-exclusion.md` probe path / 8 trace-map entries / `audit-spec-vs-code-v2.py` gate-registry prose / 2 self-test invocations all need updates.
- AC-31-31 cascade: footer registry text in `audit-spec-vs-code-v2.py` (gate #15 prose) requires version bump on §27 §98/§99 + RUBRIC_VERSION increment + qa-baseline-footer-test recalibration.
- Historical memo drift: H1, H2, H3, H7, H8, H9 memos all narrate "the slot-26 freshness gate" as a stable phase-spanning identifier. Renaming creates either stale memos or a bulk-rewrite phase.

**Benefit of rename:**
- Cosmetic clarity for contributors who don't read slot 26's spec doc.
- That's it.

## Verdict

**NO-OP — defer indefinitely.** The rename fails all 3 H10 filter criteria (not mechanically detectable, no regression surface, high false-positive risk via memo drift). The complaint it addresses (name understates scope) is **already surface-eliminated** by the spec slot doc's enumeration of AC-26-01..10. Per H11 L4 ("cross-language consolidation is premature without ≥3 incidents"), zero contributor confusion has been reported about the script's scope; the rename is preemptive cosmetic churn.

If this becomes a real problem in the future (e.g. a contributor PR adds a duplicate script believing slot-26 only handles freshness), revisit. Until then, the slot doc IS the source of truth and the filename is a stable historical handle.

## Lessons codified

### L1 — Cosmetic renames must clear the H10 filter
A rename is a no-op disguised as housekeeping. Apply the same 3-criterion filter (mechanical detectability + active regression surface + low false-positive risk) before any rename phase. Cosmetic clarity alone does not justify churn — the regression surface must be live.

### L2 — Spec slot docs are the canonical scope surface, not filenames
For any spec-toolchain script, scope is documented in the slot's `97-acceptance-criteria.md` + spec body. Filenames are navigational labels. Future "name doesn't reflect scope" complaints should first verify the slot doc is accurate; if it is, the filename is correctly stable.

### L3 — Phase memos are stable phase-spanning identifiers
Closed phase memos reference scripts/files by name as stable identifiers. A rename creates either memo drift or a bulk-rewrite burden — both violate the "memos are historical artifacts" principle (H10 C3 / C6 lesson). Plan renames to occur ONLY at major version boundaries with explicit memo-rewrite phase budget.

## No code changes
- 0 new files (apart from this memo)
- 0 modified scripts
- 0 spec edits
- 0 §98/§99 bumps
- 0 AC-31-31 cascade
- All gates remain green at H11-close baseline.

## Memory updates
- Mark backlog item #6 RESOLVED with verdict NO-OP.
- Add H12 lessons L1–L3 (compressed) to Core under the existing graduation-pattern bullet alongside H10/H11.
