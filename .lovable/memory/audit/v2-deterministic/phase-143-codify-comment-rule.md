# Phase 143 — Codify Allowlist Comment-Placement Rule (AC-62-04)

**Date:** 2026-04-28
**Mode:** 🤖 Autonomous (toolchain-only, zero spec churn)
**Trigger:** Core memory rule about line-strip-only parser was floating without code-level enforcement.

## Action
Hardened `linter-scripts/check-spec-folder-refs.py::load_allowlist()` to strip inline trailing `# comment` from entry lines before insertion, preventing the bucket-poisoning class of bug observed in Phase 143's first write attempt.

## Diff
```python
# After the existing full-line comment skip:
if "#" in line:
    line = line.split("#", 1)[0].rstrip()
    if not line:
        continue
```
Tagged AC-62-04 inline. Full-line comments above entries remain the preferred (documented) form; inline trailers are now defensively neutralized rather than silently corrupting buckets.

## Verification
- `python3 linter-scripts/check-spec-folder-refs.py` → exit 0, same 26 dormant warnings as Phase 141. No behavior change for clean allowlists.
- Linter is still not CI-gated (Phase F2 pending Phase F1 verdicts).

## Why this is safe
- No spec/ files touched.
- No allowlist file touched.
- Pure defensive hardening — clean input produces identical output; malformed input now degrades gracefully instead of silently.

## Status of the autonomous queue
**Exhausted again.** Phases 142 and 143 both confirmed: the only remaining work requires user decisions (Phase 117, Phase 108, B1, Phase F1) or capability unlock (R1).

Next `next` will be a no-op unless the user provides at least one verdict.
