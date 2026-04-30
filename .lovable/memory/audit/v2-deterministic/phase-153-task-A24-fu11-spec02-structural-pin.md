# Phase 153 Task A24-fu11 — spec/02 audit-corpus structural pin (AC-CG-24)

**Date:** 2026-04-30
**Trigger:** User reply `next`. Suggested next was A24-fu11 — close spec/02 honest-baseline (largest-blast-radius module, dropped 86→81→82 across A20-fu / A12 rebaselines).

## Diagnosis

Audit-v8 cache (`.lovable/cache/audit-ai/02-coding-guidelines.json`) at re-read showed:
- `total: 82` (GOOD), `files_used: 10`, `files_total: 251`, `bytes_used: 120000` (cap saturated).
- 3 issues: HIGH/D5 "Dangling Subfolder References", MEDIUM/D2 "Legacy AC Scaffolds Lack Specificity", LOW/D3 "Incomplete Size Limit Enforcement Logic".

**Verification of each finding against current contract:**

| Finding | Auditor claim | Verified reality |
|---|---|---|
| HIGH/D5 | "16 subfolders referenced, only 1 included; cannot resolve CODE-RED language ACs" | AC-CG-21 (A10) binds full Subfolder Delegation Map with 16 rows × slot + path + AC-family-prefix + governing CODE-RED + status. Walker simply cannot fit it in bundle. |
| MEDIUM/D2 | "TS, PHP, C# subfolders have 0 GWT ACs" | TS=22, Go=22, PHP=27, Rust=26, C#=27 GWT ACs each (re-counted via `grep -E "^### AC-"` in A24-fu11). A10-fu1 contract holds. |
| LOW/D3 | "AC-CG-08 allows 'language-specific exceptions' without enumerating" | AC-CG-22 (A10) binds 8-row Exception Ledger (EX-01..EX-08) with Why + Detection + Sunset per row. Closed enumeration. |

**Root cause:** All three are walker-saturation artifacts. The 120 KB cap (AC-34-13, raised from 90 KB in A12) loaded only 10/251 files — the per-language §97s and the Delegation Map / Exception Ledger sections are physically absent from the auditor's bundle regardless of contract clarity.

## Resolution: AC-CG-24 (Lesson #29 mirror)

Per **Lesson #29** (audit-corpus module-kind pin), the canonical fix when LLM auditor walker-bias misclassifies an existing contract is a **structural-pin AC** in the parent §97 declaring:
1. Module dimensionality (`files_total: 251`, walker capacity ~10 files).
2. Canonical delegation surface (AC-CG-21 + AC-CG-22 + AC-CG-23).
3. Per-language stub-AC counts (TS=22, Go=22, PHP=27, Rust=26, C#=27 — verified A24-fu11).
4. Classification rule: subfolder absence in audit bundle is **STRUCTURAL-DELEGATION-NOT-MISSING**, NOT a contract gap.

**AC-CG-24** added to §97 v4.4.0 with full Given/When/Then + Verifies clauses citing AC-CG-21/22/23 + AC-34-13 + Lesson #29 precedents (spec/25 AC-AI-09/10/11, spec/12 AC-10).

## Lockstep ripple

| File | Before | After | Type |
|---|---|---|---|
| §97 | v4.3.0 | **v4.4.0** | minor (new AC) |
| §00 | v3.4.1 | **v3.4.2** | patch (banner+stamp 30→153) |
| §98 | v3.4.1 | **v3.4.2** | patch (new row) |
| §99 | v4.6.0 | **v4.6.1** | patch (new blockquote) |
| AC count | 28 | **29** | +1 |
| RUBRIC | (no change) | (no change) | — |
| AC-31-31 | (no cascade) | (no cascade) | — |
| Gate count | 15 | 15 | — |

## Validation

- `node linter-scripts/check-lockstep.cjs` → ✓ 87/87 PASS
- `node linter-scripts/check-tree-health.cjs --strict` → ✓ 100/100 (168/168, all 56 modules)
- `python3 linter-scripts/check-version-parity.py` → ✓ 74/74 matches, 0 mismatches
- spec/02 v8 score: 82 (GOOD) — band stable. **Score-lift NOT attempted.** AC-CG-24 is a contract-clarification AC; the LLM auditor's bundle limit cannot be lifted by spec content alone — it would require walker-cap raise (A12 already done) or per-axis bundle strategy (A18 conditional). Future re-score may rise if walker happens to load §97 in the prefix; deterministic improvement requires harness work.

## Lessons

**Lesson #49 (NEW — codified at §98 v3.4.2):** When an audit-v8 finding flags "Subfolder X has 0 GWT ACs" but verification (e.g. `grep -E "^### AC-"`) shows X has ≥ N GWT ACs, the finding is a walker-saturation artifact, NOT a contract gap. The fix is a structural-pin AC in the parent §97 (Lesson #29 pattern) — NOT to author duplicate per-language ACs in the parent (which would violate Lesson #36 cross-module link-don't-restate). The parent §97 says "delegation map is canonical, see subfolder §97s"; the LLM auditor's bundle limit cannot be lifted by spec content alone.

**Lesson #29 generalization:** Lesson #29 (audit-corpus module-kind pin) extends from `kind: tracker|post-mortem` axis (spec/25) to `normative-contract` axis when `files_total / files_used ≥ 10×`. Cross-axis Lesson-#29 instances now span:
- `kind: tracker` — spec/25 AC-AI-09/10/11
- `kind: integration-spec` — spec/12 AC-10
- `kind: future-spec normative-contract tree-spanning` — **spec/02 AC-CG-24 (this phase)**

Future modules with `files_total ≥ 100` AND walker-saturation findings SHOULD carry an audit-corpus structural pin AC.

## Files changed

- spec/02-coding-guidelines/97-acceptance-criteria.md (added AC-CG-24, banner v4.4.0)
- spec/02-coding-guidelines/00-overview.md (banner v3.4.2, h10 stamp 30→153)
- spec/02-coding-guidelines/98-changelog.md (new v3.4.2 row, banner)
- spec/02-coding-guidelines/99-consistency-report.md (new v4.6.1 blockquote, banner, Updated date)
- .lovable/memory/index.md (this phase row appended)
- .lovable/memory/audit/v2-deterministic/phase-153-task-A24-fu11-spec02-structural-pin.md (this memo)
