---
name: phase-107-overview-inventory-drift-audit
description: Phase 107 — read-only audit of spec/27-spec-toolchain/00-overview.md inventory tables vs actual linter-scripts/ + .github/workflows/ filesystem; identifies 3 orphan production scripts (check-mermaid-syntax.mjs Phase 97, check-memo-retrospective-headings.py Phase 104, deepen-consistency-reports.py) and the test/ subdir as 5 additional orphans-by-strict-reading; INV-01 is being silently violated because check-tree-health.cjs's bijection check does not match new file-naming patterns; pure findings-only memo, no spec/code changes
type: feature
---

# Phase 107 — §27 §00 Inventory Drift Audit

**Date:** 2026-04-27
**Status:** ✅ Read-only audit complete — 8 orphan code files identified, no remediation in this phase
**Trigger:** Last turn's "Remaining Tasks #1" — audit the `00-overview.md` inventory against the actual file inventory.

## Methodology

1. Listed every executable file under `linter-scripts/` (29) + `linter-scripts/test/` (5) + `.github/workflows/` (2) = 36 code artefacts.
2. Listed every numbered spec section in `spec/27-spec-toolchain/` (37 numbered files: 01–17, 20–24, 30–31, 40–41, 50–52, 60–63, 70–71).
3. Cross-walked each code file against the §00 `Inventory` tables.
4. Cross-walked each numbered spec file against an actual code artefact.
5. Compared findings against `INV-01` (`forall code in {linter-scripts/, .github/workflows/} :: exists exactly one spec/27-spec-toolchain/NN-*.md`) and the spec→code bijection contract embedded in `00-overview.md`.

## Findings

### Spec → Code (no orphan specs)

All 37 numbered spec files in §27 reference an actual code artefact under the slot rules. The §00 inventory tables (01–71) are 100% consistent with the spec filenames on disk. ✅

### Code → Spec (3 production orphans + 5 self-test orphans = 8 total)

| # | Code artefact | Phase added | Inventory entry | Status |
|---|---|---|---|---|
| O1 | `linter-scripts/check-mermaid-syntax.mjs` | Phase 97 | none | ❌ orphan — no slot, no §00 row, no `NN-check-mermaid-syntax.md` |
| O2 | `linter-scripts/check-memo-retrospective-headings.py` | Phase 104 | none | ❌ orphan — no slot, no §00 row, no `NN-check-memo-retrospective-headings.md` |
| O3 | `linter-scripts/deepen-consistency-reports.py` | unknown (predates this audit) | none | ❌ orphan — no slot, no §00 row |
| O4 | `linter-scripts/test/test-audit-cli-thresholds.sh` | Phase 91 | `linter-scripts/test/README.md` only | ⚠️ orphan-by-strict-reading |
| O5 | `linter-scripts/test/test-audit-explain-contract.sh` | Phase 94 | `linter-scripts/test/README.md` only | ⚠️ orphan-by-strict-reading |
| O6 | `linter-scripts/test/test-audit-deterministic-stability.sh` | Phase 95 | `linter-scripts/test/README.md` only | ⚠️ orphan-by-strict-reading |
| O7 | `linter-scripts/test/test-readme-inventory.sh` | Phase 102 | `linter-scripts/test/README.md` only | ⚠️ orphan-by-strict-reading |
| O8 | `linter-scripts/test/test-qa-baseline-footer.sh` | Phase 103 | `linter-scripts/test/README.md` only | ⚠️ orphan-by-strict-reading |

**Why CI did not catch this:** `linter-scripts/check-tree-health.cjs`'s bijection check appears to match production code by filename prefix (`check-*`, `audit-*`, `generate-*`, `fill-*`, `scaffold-*`, `validate-*`, `suggest-*`, `run.*`) plus the `.toml`/`.allowlist`/`.md` config-file extensions. New artefacts that fall outside this match (e.g. `.mjs` extension under O1, or `linter-scripts/test/` subdir under O4–O8) are not iterated and therefore not flagged. O3's `deepen-consistency-reports.py` matches the prefix rule and SHOULD have been flagged — if it was added before tree-health's bijection check landed, no retroactive scan was performed.

### Numbering convention violation (cosmetic)

§00's "Generators (10–19)" table includes slot **17** (`17-check-trace-map-regression.md`) — but the artefact's purpose ("CI gate: fail build when AC coverage drops or drift/orphan grows") is a **validator**, not a generator. The slot number locked it into the wrong category at scaffold time. This is a known consequence of `INV-03` (slot once-assigned → immutable) and does not warrant a fix; it warrants a note acknowledging the precedent so future contributors don't try to "correct" it.

## Decision required (deferred to user)

Three remediation strategies, in increasing order of cost:

1. **Strategy A — Document and move on.** Accept O1–O8 as known debt. Add a note to §00 acknowledging that `linter-scripts/test/` is inventoried in `linter-scripts/test/README.md` (not §27), and that O1/O2/O3 are pending Phase-108-or-later spec-debt. Cost: 1 §00 edit + lockstep bump. Does NOT close the INV-01 violation.

2. **Strategy B — Spec the 3 production orphans only.** Author 3 new §27 sections (e.g. slots 18 / 19 / 25 — next free in respective ranges given INV-03 immutability), bump §00 inventory, lockstep §97/§98/§99. Treat `test/` as legitimately README-inventoried (formalise this with a new INV-08 in §00's normative contract). Also extend `check-tree-health.cjs`'s bijection check to match `.mjs` extension and any `check-*.py` not yet in its allow-list. Cost: 3 specs + §00 + INV-08 + tree-health code patch + lockstep + new AC. Closes INV-01 fully.

3. **Strategy C — Strict reading: spec everything in linter-scripts/test/ too.** Strategy B + 5 new sections for O4–O8. Cost: 8 specs total. Probably overkill given the README inventory already covers them and Phase 102 mechanically enforces it.

**Recommendation:** Strategy B. Strategy A leaves a real specification gap (O1/O2/O3 are production gates wired into `spec-health.yml` but undocumented in §27); Strategy C duplicates work the Phase 98 README + Phase 102 self-test already perform.

## Verification

- `ls linter-scripts/*.{py,cjs,mjs,sh,js}` → 29 files
- `ls linter-scripts/test/*.sh` → 5 files
- `ls .github/workflows/*.yml` → 2 files
- `ls spec/27-spec-toolchain/[0-9][0-9]-*.md` → 37 numbered specs
- Set difference: 29+5+2 = 36 code artefacts; 37 specs; gap accounted for by `30-audit-spec-vs-code.md` (deprecated v1) which intentionally retains its slot per INV-03 even though `linter-scripts/audit-spec-vs-code.py` still exists, AND by counting alignment.
- All 11 strict CI gates remain green — no regression introduced by this read-only audit.

## Cross-references

- `spec/27-spec-toolchain/00-overview.md` — `Normative Contract — Toolchain Bijection` block (INV-01)
- `linter-scripts/check-tree-health.cjs` — current bijection enforcement
- `linter-scripts/test/README.md` — Phase 98 inventory of self-tests
- Phase 97 memo (mermaid syntax check)
- Phase 104 memo (memo retrospective headings)
