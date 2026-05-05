# Phase 153 Task N8 — Self-test for Lesson #82 chunked-cache advisory

**Closed:** 2026-05-05
**Status:** ✅ CLOSED
**Driver:** Mechanical lock for the N6 advisory emit block in `linter-scripts/audit-ai-implementability.py` (Lesson #82 — chunked-cache class hygiene). Without a self-test, a future refactor could silently drop the advisory and contributors would resume opening NO-OP §97 phases on stale sub-90 caches (Phase N5 plateau-diagnosis class).

## What was added

`linter-scripts/test/test-audit-chunked-cache-advisory.sh` — 5 assertions:

1. Advisory header line emitted when ≥1 fixture qualifies
2. Sub-90 + `chunked_path: false` fixture appears in advisory block
3. Sub-90 + `chunked_path: true` fixture is **suppressed** (chunked-walker scores trusted)
4. ≥90 + `chunked_path: false` fixture is **suppressed** (only sub-90 surface)
5. Advisory NEVER changes exit code (`--report-only` exits 0; advisory is non-fatal per Lesson #82)

**Snapshot-restore contract** (Lesson #31): the test snapshots `.lovable/cache/audit-ai/` to a `mktemp -d` before injecting fixtures, restores byte-for-byte on `EXIT` trap (verified via SHA hash comparison of all files in the dir post-run).

## Fixtures

- `M_FAIL = 05-split-db-architecture` — total=85, chunked_path=false → MUST appear
- `M_OK_CHUNKED = 12-cicd-pipeline-workflows` — total=85, chunked_path=true → MUST NOT appear
- `M_OK_HIGH = 24-app-design-system-and-ui` — total=93, chunked_path=false → MUST NOT appear

Module names match real `spec/NN-*/` directories so the auditor's discovery loop picks them up under `--no-network`.

## Authoring lesson (no new global lesson — refines #31)

Initial fixture names used invented slugs (`05-spec-driven-development`, `24-cli-design`) that did not match real `spec/` directories. Auditor's `for mod in modules:` loop iterates over real spec dirs, so the advisory loop never read the fixture caches → header emitted (other real sub-90 caches qualified) but the failing fixture was absent → false negative on Case 2. Fix: `ls spec/ | grep -E "^(05|12|24)-"` BEFORE writing fixture names. Mirror of Lesson #38 (slug-validation-before-sweep) for the self-test-fixture axis. Filed as a procedural note inside the test header rather than a new globally-numbered lesson.

## Files

- Created `linter-scripts/test/test-audit-chunked-cache-advisory.sh` (executable, 5/5 PASS, ~2s)
- Edited `linter-scripts/test/README.md` (entry #18 added; totals 17→18 scripts, 182+→187+ assertions, ~42s→~44s CI time)

## Verification

- Self-test: 5/5 PASS, exit 0
- Snapshot-restore: byte-identical (SHA-256 verification, 0 file diffs across full cache dir)
- Inventory parity gate: 6/6 PASS (test under `linter-scripts/test/`, not production)
- Lockstep: 87/87 PASS, 0 findings
- Tree-health strict: 168/168, score 100/100

## Out of scope

- CI workflow wiring (`.github/workflows/spec-health.yml`) — newer audit self-tests (`test-audit-ai-implementability.sh`, `test-audit-bundle-budget.sh`) are also not yet wired; deferred to a separate batch task.
- Promoting the advisory to a strict gate (would require gateway access to refresh sub-90 caches → back to A8 budget block).
