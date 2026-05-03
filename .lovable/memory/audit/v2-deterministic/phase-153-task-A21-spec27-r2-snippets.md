# Phase 153 Task A21 — spec/27 R2 reference snippets restored

**Date:** 2026-05-03
**Trigger:** F-03b tree-wide refresh surfaced spec/27 −3 honest-baseline drop (88 → 85). Triage produced 1 productive lift target.

## Findings (audit-v5 cache, 23 modules · spec/27 = 85/100 GOOD)

| # | Sev | Dim | Finding | Disposition |
|---|---|---|---|---|
| 1 | HIGH | D5 | Dangling External References (Truncation) | **Harness-artifact** — already mitigated by AC-T-30 Slot Delegation Map + AC-T-31 AC Family Prefix Index per Lesson #29 (`§00 lines 27–31` mitigation block already directs auditor to downgrade to harness-artifact). No edit. |
| 2 | MEDIUM | D4 | Missing Concrete Implementation for R2 Snippets | **Productive** — see Action below. |
| 3 | LOW | D3 | Concurrency/Locking jitter/back-off ambiguity | **Productive** — closed by same edit (snippets specify `(0.75 + random() * 0.5)` jitter explicitly). |

## Action

Added `### R2 — File-Locking Retry Reference Snippets (Normative, AC-T-32)` subsection under §00 `## Resilience — CI Edge Cases`:

- **Python**: `Path.read_bytes()` + `json.loads` + 3× retry on `JSONDecodeError` + `time.sleep(0.100 * (0.75 + random.random() * 0.5))` + `errno.EAGAIN`/`EACCES` → `sys.exit(2)`.
- **Node**: `fs.readFileSync` + `JSON.parse` + 3× retry on `SyntaxError` + same jitter formula + `e.code === 'EBUSY'`/`'EACCES'` → `process.exit(2)`.
- Cross-references AC-T-32 (line 188 of §97) and clarifies these are reference *code* (allowed), not rule *restatement* (forbidden by Lesson #36).

## Why F-03b's −3 happened

A24-fu36 (this morning) trimmed §00's `## Resilience` from 149 lines to a 10-line cross-reference per Lesson #36. The trim correctly removed AC-T-28's R1–R5 *rule restatement* but accidentally also removed the AC-T-32-mandated *reference snippets* — the auditor then flagged the (still-present) AC-T-32 as having no concrete implementation in §00.

## Lockstep

| File | From | To |
|---|---|---|
| §00 (00-overview.md) | v2.88.0 | **v2.88.1** |
| §98 (98-changelog.md) | v2.88.0 | **v2.88.1** + new release row |
| §99 (99-consistency-report.md) | v2.85.0 | **v2.85.1** (banner only) |

No §97 / AC count / CI / RUBRIC / gate-count change — AC-T-32 already exists at line 188 with full GWT contract; this restores the executable analogue it mandates.

## Gates

- Lockstep 87/87 strict — GREEN
- Tree-health 168/168 strict — GREEN
- Version-parity 74/74 — GREEN
- §99 freshness 81 stamped + 6 exempt + 0 unstamped — GREEN

Re-score deferred to next session per Lesson #38 (gateway budget shared with F-03b's 23-module run earlier).

**Expected lift**: D3 17→18-19, D4 16→18-19 → 85 → ~89-92.

## NEW Lesson #78 — Lesson #36 has a code-vs-rule distinction

Trims that remove cross-module restatements of *rules* (e.g. AC-T-28's R1–R5 GWT in spec/27 §00 when AC-T-28 owns it in §97) are correct Lesson #36 application.

Trims that remove *reference-implementation code* mandated by an AC in the SAME module (e.g. AC-T-32 mandates Python+Node snippets in spec/27 §00) are NOT Lesson #36 violations and the code MUST be preserved.

**Future archive-split protocol** (codifies the corrective rule):
1. Before archiving any §00 prose section, `rg "AC-[A-Z]+-[0-9]+" <section>` to extract every AC reference.
2. For each cited AC, read its §97 contract and verify whether the AC mandates *code* or merely *describes* a rule.
3. Code mandates MUST survive the trim — extract them into a smaller subsection if needed; never archive them.
4. Add the verification list to the archive memo for traceability.
