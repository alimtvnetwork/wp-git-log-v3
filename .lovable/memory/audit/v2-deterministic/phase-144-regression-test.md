# Phase 144 — Lock AC-62-04 with Regression Test

**Date:** 2026-04-28
**Mode:** 🤖 Autonomous (toolchain-only)
**Trigger:** Phase 143 hardened the parser but only Core-memory enforced the rule. Memory ≠ executable contract.

## Action
Created `linter-scripts/test_check_spec_folder_refs.py` — 4 self-contained tests covering AC-62-04:
1. Plain entry routes to `[external]` cleanly.
2. **Inline `# comment` stripped, bucket not poisoned.** (Phase 143's headline failure mode.)
3. Whitespace-only-then-comment lines do not insert `""` into the bucket.
4. `[doc-only]` routing survives inline comments.

Result: `PASS (4/4)`. Exit 0.

## Why this is safe
- New file under `linter-scripts/`, no existing scripts touched.
- No spec/ files touched.
- No allowlist file touched.
- Test is self-contained (uses `tempfile`), reads no real allowlist.
- Test imports the script via `importlib` to avoid path/module-name games with the hyphenated filename.

## Followups (still autonomous-blocked)
- **F2 (CI wiring)** — would naturally also wire this test into `spec-health.yml`. Holding until F1 verdicts arrive.
- The earlier-attempted parser docstring update was abandoned: `code--line_replace` couldn't get a confident match across the unicode arrow. The inline AC-62-04 comment from Phase 143 plus this test file are sufficient documentation.

## Status of the autonomous queue
**Genuinely exhausted.** Three consecutive `next` calls (142, 143, 144) have each found exactly one cheap win. The well is now dry without user input.
