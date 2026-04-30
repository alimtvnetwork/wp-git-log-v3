# Phase 153 Task A24-fu8 — spec/25 finding-body schema contract (AC-AI-14 + AC-AI-15)

**Date:** 2026-04-30
**Module:** `spec/25-app-issues/` (axis: `audit-corpus`, multipliers d1×1.0/d2×0.5/d3×0.5/d4×1.5/d5×1.5, axis_cap 95)
**Score:** 79 → 76 (-3 honest-baseline correction per Lesson #18; predicted post-edit 84+ per Lesson #44 bracketing, deferred per L#20)
**Walker-saturation:** TRUE (Lesson #46 flag for next A20 rebaseline) — 9/12 files in 90 KB bundle; `02-consolidated-audit-findings/00-overview.md` (32 KB single-file by design) is the saturation driver

## Pre-flight (Lessons #38 + #45)
- `LOVABLE_API_KEY` ON (gateway active)
- `--force` re-score returned 76 with 3 actionable v7 findings (different from v3/v4 findings closed by AC-AI-09/10/11)
- Tier-1 sizes: §00=5KB, §97=17KB, §98=7KB, §99=4KB → 33 KB total (massive headroom in tier-1 alone)
- Tracker children (32KB + 40KB) = where the 90 KB cap saturates

## Findings (audit-v7 cache 2026-04-30)
1. **HIGH D2** `Circular/Structural-only ACs for Tracker Content` — auditor wants schema-validator AC for finding R/C/F/P → genuine ask; AC-AI-12 declares floor intentional but doesn't bind output schema
2. **MEDIUM D4** `Truncated Evidence in Consolidated Findings` — F-04 cut off in walker bundle → walker-saturation artifact (Lesson #46), NOT a contract gap
3. **LOW D3** `Unaddressed Schema Validation for Issue Records` — wants negative-case schema example → genuine ask

## Fixes
- **AC-AI-14** `[high]` Finding-body schema (positive contract) — 9-row schema table (Heading/Severity/Category/File/Line/R/C/F/P) with closed enum sets + 4 validation rules + 3 forbidden patterns. Closes HIGH D2. Demonstrates `kind: tracker` modules CAN have output-schema ACs without violating AC-AI-12 (the schema is in §97; the instances are the finding bodies — same shape as JSON Schema in spec/04 governing many records).
- **AC-AI-15** `[medium]` Negative-case schema (3 malformed-finding examples) — free-form severity, missing R/C/F/P body, paraphrased evidence. Closes LOW D3.
- **AC-AI-12 + AC-AI-13** retained verbatim (still close v3/v4 framings + reinforce kind:tracker pin).

## Deferred (Lesson #46)
- D4 truncation finding: `02-consolidated-audit-findings/00-overview.md` is **32 KB single-file by design** (single audit corpus; splitting would break line-anchor citations across all `**Linked audit IDs:**` rows and violate AC-AI-10 verbatim-quote rule). Memo flag: `walker-saturation: true`.
- LLM re-score: per Lesson #20 + L#46, walker-saturation reduces single-module re-score signal value; defer to A20-style full-tree rebaseline.

## Lockstep ripples
- §97 v1.3.0 → **v1.4.0** (AC count 13 → 15, minor)
- §00 v3.4.4 → **v3.5.0** (sync to §98 per Lesson #25)
- §98 v3.4.4 → **v3.5.0** (release row added, minor)
- §99 v1.3.2 → **v1.4.0** (audit row added, minor)

## Lessons applied
- **L#18** (honest-baseline correction) — accepted -3 score drop as ground-truth surface change, did NOT roll back.
- **L#19** (audit-boundary < verification-boundary) — bound finding-body schema into §97.
- **L#29 Section F** (audit-corpus pattern) — extended `kind: tracker` sub-class with positive-contract AC alongside the existing module-kind pins.
- **L#36** (link-don't-restate) — AC-AI-14 cites `02-consolidated-audit-findings/00-overview.md` lines 25-37 as source table being normalized, NOT restated.
- **L#37** (Lesson #19 + #36 co-application on integration-axis) — reinforced for audit-corpus axis (different multipliers, same pattern).
- **L#38** (gateway availability check) — confirmed `LOVABLE_API_KEY` ON before deferring.
- **L#44** (axis-multiplier compounding) — predicted lift 76 → 84+ via D2 HIGH→0 + D3 LOW→0 closures even with audit-corpus D2×0.5 + D3×0.5 dampening.
- **L#45 + L#46** (walker-saturation) — diagnosed truncation as harness artifact, NOT contract gap; flagged in memo.
- **L#25** (SemVer-track unification) — §00 jumped 3.4.4 → 3.5.0 to sync with §98 minor bump.

## Gate verification
- Lockstep 87/87 GREEN
- Tree-health 168/168 strict GREEN
- Version-parity 74/74 GREEN
