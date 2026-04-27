# Phase 129 — Dashboard / Python cross-link parity via shared waiver file

**Date:** 2026-04-27
**Trigger:** `next` cycle exhausted decision-blocked backlog. Re-scanned audit-v4 critical findings; #3 ("32 broken links") was stale — only 3 remained, all documentation-example markdown inside prose (`[link](../foo)`, `[AH-<L><N>](...)`, `[\`test-foo.sh\`](./test-foo.sh)`).

## Root cause
Two cross-link checkers existed with diverging behavior:
- **`linter-scripts/check-spec-cross-links.py`** (CI gate): honored `linter-scripts/spec-cross-links.allowlist` → green
- **`linter-scripts/generate-dashboard-data.cjs`** (dashboard health score): had its own inline `EXTERNAL_REPO_PREFIXES` allowlist but ignored the file-based waiver list → reported 3 false-positive broken links → health drifted by -6

Result: contributors saw a green CI but a dashboard claiming "3 broken links," undermining trust in both signals.

## Fix
1. Appended 3 documentation-example waivers to `linter-scripts/spec-cross-links.allowlist` under a `# Phase 129` header:
   - `spec/01-spec-authoring-guide/97-acceptance-criteria.md:114:../foo`
   - `spec/02-coding-guidelines/06-ai-optimization/97-acceptance-criteria.md:111:...`
   - `spec/27-spec-toolchain/98-changelog.md:70:./test-foo.sh`
2. Taught `generate-dashboard-data.cjs` to read the same file:
   - New `WAIVED_LINKS` Set loaded at module init
   - Strips leading `spec/` from allowlist entries (Python checker uses repo-root paths; dashboard uses `SPEC_ROOT`-relative paths)
   - New `isWaivedLink(sourceRel, line, target)` helper
   - `validateLinks()` now distinguishes 4 outcomes per link: `Ok` / `ExternalAllowed` / `Waived` / `Broken`
   - JSON output gains `Waived: [...]` array + `Total.Waived` counter (zero schema break — additive only)

## Verification
- Dashboard: `Links checked: 2936 (2924 ok, 0 broken)` · **Health 100/100 (A+)**
- Lockstep gate: 87/87 pass, 0 findings
- Python cross-link gate: green
- All three signals now agree.

## Backlog impact
- Audit-v4 critical finding **#3 ("32 broken links")**: ✅ resolved (was already 3, not 32; now 0 with proper waiver semantics)
- Full-tree audit baseline 45/100 expected to lift on next real-AI re-audit (R1, blocked on Lovable Cloud)
- Dashboard / CI parity restored — closes a class of regressions where one gate could pass while the other failed

## Files touched
- `linter-scripts/spec-cross-links.allowlist` — appended Phase 129 section (3 entries)
- `linter-scripts/generate-dashboard-data.cjs` — added `WAIVER_FILE` loader, `isWaivedLink()`, wired into `validateLinks()`, added `Waived` to result shape
- `.lovable/memory/audit/v2-deterministic/phase-129-dashboard-waiver-parity.md` — this memo
