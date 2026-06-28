---
name: Spec-only mode (no coding)
description: User directive 2026-06-28 — all future tasks are spec writing only, no script/code authoring even under linter-scripts/
type: preference
---

**Rule:** Every task from 2026-06-28 onward is **spec writing only**. Do NOT create or modify:
- `linter-scripts/**` (no new self-tests, no Python/bash scripts, no test harnesses)
- `src/**`, `index.html`, any frontend code
- `.github/workflows/**`
- Any executable artifact

**Permitted edits:**
- `spec/**/*.md` (contract surfaces, ACs, sections, banners)
- `.lovable/memory/**` (audit memos, lessons, trackers)
- `mem://**` (index + memory files)

**Why:** User stated 2026-06-28 — "At this stage, everything should be spec, no coding. Remember for the next few tasks as well." Repeated correction risk if violated.

**How to apply:** When a closure pattern historically required a self-test (e.g. Lesson L21 / Phase P47–P49 mechanical-lock graduation), substitute a **spec-only equivalent**: bind the invariant in §97 with `**Verifies:**` prose + add a §99 audit row + log a "deferred mechanical lock" entry in the closing memo. Do NOT author the `.sh` self-test. When the user lifts this directive, schedule deferred mechanical locks as a follow-up phase.

**Reverse mapping for previously-built reflexes:**
- "Add conformance self-test" → bind as `[critical]`/`[high]` AC in §97 with explicit forbidden-pattern enumeration + `**Detection:**` clause naming the grep/regex an auditor would use.
- "Wire CI workflow step" → defer; log in memo.
- "Add Python helper / linter script" → defer; log in memo.
