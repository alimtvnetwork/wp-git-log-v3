# Consistency Report — Generic CLI

**Version:** 1.1.3  
**Updated:** 2026-04-29


> **v1.1.3 update (Phase 153 Task A11a-fu1 — stale-prose refresh, P0 audit-v6 follow-through):** User reply `next`. Closes the CRITICAL D1 file-grep finding from `/mnt/documents/spec-deterministic-audit.md` by refreshing four stale-prose hotspots in `03-subcommand-architecture.md` (lines 84, 102) + `07-error-handling.md` (§ Exit Codes table widened to full 5-value `ExitCode` enum; missing-arg example exits `ExitMisuse`; batch-ops section follows AC-17 three-way conditional; closing handler rule generalised to enum). **No new AC**, **no §97 contract change**, **no AC count change** — patch-level lockstep only. AC-21 §97-WINS contract was already authoritative pre-refresh (per Lesson #24); this patch silences file-grep auditors that don't parse contract supersession. Banners: §00 v1.1.2 → **v1.1.3** (h10 stamp 22 → 153); §98 v1.1.2 → **v1.1.3**; §99 v1.1.2 → **v1.1.3**. **No CI workflow change, no RUBRIC bump, no AC-31-31 cascade, no gate-count change.** **Lesson #33**: Lesson #24 contract-pin is correct for *contract integrity*, but file-grep auditors will keep flagging literal stale strings until prose is refreshed — schedule a follow-up patch-level prose-refresh phase after every §97-WINS AC ships. Cross-references Lesson #24 (contract-pin first), Lesson #29 (audit-corpus module-kind pin — same "audit reads literal text" failure mode).

> **v1.1.2 update (Phase 153 Task A11a — spec/13 self-lift 75 → ≥88; AC-21/22/23 close all 3 v4 findings):** User reply `next`. Targeted lift on the lone CRITICAL-D1 75-scorer in the v4 baseline. Three new module-level ACs close all findings in one phase: **AC-21** declares §97 AC-10/AC-17 authoritative over sub-file `exit 1` prose (mandates typed `ExitCode` enum at every call site; bare integer literals other than `os.Exit(0)` FORBIDDEN) — closes CRITICAL D1. **AC-22** binds DB + file concurrency (SQLite WAL + `busy_timeout=5000` + `BEGIN IMMEDIATE` + 3× back-off; cross-cuts spec/05 AC-SD-22; atomic temp-then-rename for files cross-cuts spec/27 AC-T-28 R1+R3; connection-pool for `--parallel=N`; `update.lock` for self-update) — closes HIGH D3. **AC-23** binds checklist Phase 5 (Database) → AC-22 + AC-10 + spec/05 AC-SD-21/22/23, Phase 8 (Build) → AC-22 + AC-10 + `11-build-deploy.md`; future phases without binding ACs FORBIDDEN — closes MEDIUM D2. Banners: §97 v2.1.0 → **v2.2.0** (AC count 20 → 23); §00 v1.1.1 → **v1.1.2**, §98 v1.1.1 → **v1.1.2**. **No CI workflow change, no RUBRIC bump, no AC-31-31 cascade, no gate-count change.** **Lesson #24**: §97-WINS supersession ACs are the canonical fix for contract-vs-prose drift (mirrors AC-CG-23 / Lesson #23); pin the contract first, refresh prose later. **Lesson #25**: Cross-cutting infrastructure ACs belong in the consuming §97 with explicit cross-references to their authoritative home — duplication bloats bundle + creates drift; cross-references keep SSoT.

> **v1.1.1 update (Phase 153 Task #31):** §97 boilerplate ACs (AC-01..AC-08) gained `**Verifies:**` clauses (8 added). Bulk sweep closes the audit-v6 boilerplate blind spot tree-wide. §97 v2.0.0→**v2.1.0**; §98 v1.1.0→**v1.1.1**.

> **v1.1.0 (Phase 16a):** §97 deepened from 5 generic structural ACs to 20 ACs total — added 15 module-specific GWT ACs (AC-06..AC-20) covering subcommand dispatch, flag parsing, three-layer config, multi-format output, exit-code contract, code-style limits, embedded help, date format centralization, constants discipline, verbose logging, progress tracking, batch execution, shell completion, terminal palette, and post-install doctor activation. §97 banner v1.0.0 → v2.0.0; §98 v1.0.0 → v1.1.0; spec-index updated.

---

## File Inventory

| # | File | Status |
|---|------|--------|
| 1 | `00-overview.md` | ✅ Present |
| 2 | `02-project-structure.md` | ✅ Present |
| 3 | `03-subcommand-architecture.md` | ✅ Present |
| 4 | `04-flag-parsing.md` | ✅ Present |
| 5 | `05-configuration.md` | ✅ Present |
| 6 | `06-output-formatting.md` | ✅ Present |
| 7 | `07-error-handling.md` | ✅ Present |
| 8 | `08-code-style.md` | ✅ Present |
| 9 | `09-help-system.md` | ✅ Present |
| 10 | `10-database.md` | ✅ Present |
| 11 | `11-build-deploy.md` | ✅ Present |
| 12 | `12-testing.md` | ✅ Present |
| 13 | `13-checklist.md` | ✅ Present |
| 14 | `14-date-formatting.md` | ✅ Present |
| 15 | `15-constants-reference.md` | ✅ Present |
| 16 | `16-verbose-logging.md` | ✅ Present |
| 17 | `17-progress-tracking.md` | ✅ Present |
| 18 | `18-batch-execution.md` | ✅ Present |
| 19 | `19-shell-completion.md` | ✅ Present |
| 20 | `20-terminal-output-design.md` | ✅ Present |
| 21 | `21-post-install-shell-activation.md` | ✅ Present |

**Total:** 21 files (excluding this report)

---

## Known Gaps

- `01-*.md` slot intentionally skipped — `00-overview.md` carries the introduction. No content gap.

---

## Summary
<!-- verified-phase: 153 -->
- **Errors:** 0
- **Warnings:** 0
- **Health Score:** 100/100 (A+)

---

## Validation History

| Date | Version | Action |
|------|---------|--------|
| 2026-04-16 | 1.0.0 | Initial consistency report — inventory baseline after folder renumber |

---

*Consistency Report — updated: 2026-04-16*

## 2026-04-27 — Phase 68 audit

- Mermaid lifecycle diagram present (`has_mermaid=true`).
- Lockstep & tree-health gates: PASS.
- Implementability promoted from 85 → 90.

### 2026-04-27 — Phase 72 deepening

- CI workflow contract inlined: 5 stages (detect, validate, lint, promote, report).
- Implementability raised 90 → 95 (deterministic audit).

