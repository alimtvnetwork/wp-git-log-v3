---
phase: 153
task: A24-fu44
date: 2026-05-04
status: CLOSED — spec/15 closes 2 audit-v7 MEDIUM findings via AC-21 + AC-22
gates: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN
backlog-source: cache `.lovable/cache/audit-ai/15-distribution-and-runner.json` (2 MEDIUM findings)
---

# A24-fu44 — spec/15 installer timeout + Bun toolchain pin (2 ACs)

## What shipped

Closed both spec/15 audit-v7 MEDIUM findings via small mechanical AC additions
(L72 pre-flight: §97 at 22 KB, well under 75 KB normative-contract budget):

| AC | Sev | Closes finding |
|---|---|---|
| **AC-21** | `[medium]` | `[D3 MEDIUM] Missing timeout/retry logic for installers` — pins curl flags (`--connect-timeout 10 --max-time 60 --retry 3 --retry-delay 2 --retry-connrefused`), PowerShell flags (`-TimeoutSec 60 -MaximumRetryCount 3`), per-attempt-not-cumulative SLO interpretation, exit-code separation (1=network, 2=checksum, 3=unsupported OS), forbidden patterns (`curl \| bash` without `--fail`, `Invoke-WebRequest` without `-ErrorAction Stop`, `set -e` without `set -o pipefail`). |
| **AC-22** | `[low]` | `[D1 MEDIUM] Ambiguous 'slides' build toolchain` — pins Bun as sole supported toolchain; pnpm fallback FORBIDDEN (different lockfile + `node_modules` resolution + `bun:*` import semantics); missing-toolchain → exit 4 + bun.sh install link. |

Per **Lesson #36** (link-don't-restate), AC-22 cites `02-runner-contract.md`
as the canonical surface AND the prose at line 42 step 4 was refreshed from
"fall back to pnpm" → "Bun is the SOLE supported toolchain per AC-22"
(Lesson #33 stale-prose pattern: AC ships + sibling prose refreshes in same
commit to prevent file-grep auditors flagging the literal stale string).

## Lockstep deltas

| File | Before | After | Bump |
|---|---|---|---|
| §97 | v2.0.0 | **v2.1.0** | minor (2 new ACs, count 20→22) |
| `02-runner-contract.md` | v1.0.0 | **v1.1.0** | minor (pnpm fallback prose removed per AC-22) |
| §00 | v2.2.0 | **v2.3.0** | minor (file-content-bearing absorb of §97 minor) |
| §98 | v2.2.0 | **v2.3.0** | minor (new release row) |
| §99 | v2.1.2 | **v2.1.3** | patch (audit row) |

## Pre-flight gates passed (Lessons applied)

- **L72** (saturation pre-flight): §97 22 KB → projected ~25 KB after edit (well under 75 KB normative-contract budget).
- **L74** (gateway probe before rebaseline): probed `06-seedable-config-architecture --force` → **HTTP 402**, gateway-budget-deferred per Lesson #20. v13 rebaseline NOT attempted; cache will refresh next session when capacity returns. AC-21/22 will be visible to that re-score.
- **L34** (cache MUST NOT be authoritative for content claims): all other HIGH findings on healthy modules (spec/04 D5, spec/05 D5, spec/06 D5, spec/14 D5, spec/01 D3) audited and confirmed already closed by structural-pin ACs (AC-17, AC-SD-24, AC-SC-22/23, AC-21, AC-SAG-29/30) — no work needed; cache is stale per Lesson #34.
- **L75** (walker-cap triage before self-lift): truncation-class findings on spec/{17,18,22,25,27} remain blocked on A18 walker raise.

## Out of scope

- **Parent §97 of spec/12** untouched (Lesson #36): A24-fu43-fu1 already shipped subfolder GWT ACs in prior phase.
- **No CI workflow / RUBRIC / gate-count change.**
- **No AC-31-31 cascade** — spec/15 ACs are not §27 module-level parity ACs.

## Files changed

- `spec/15-distribution-and-runner/{00-overview,02-runner-contract,97-acceptance-criteria,98-changelog,99-consistency-report}.md` (5 files)
- `.lovable/memory/audit/v2-deterministic/phase-153-task-A24-fu44-spec15-installer-timeout.md` (this memo)

## Lessons applied

- **#36** link-don't-restate (AC-22 + prose refresh — single source = `02-runner-contract.md`)
- **#33** stale-prose follow-up bundled with AC ship
- **#34** cache cross-reference before opening
- **#72** saturation pre-flight
- **#74** gateway probe before rebaseline
- **#20** gateway 402 → defer score, ship mechanical work

## Backlog after this phase

Floor candidates (sorted by leverage):
- **spec/12** (83) — saturated; A24-fu43-fu1 closed D2 HIGH; D5 walker-cap remains
- **spec/27** (83) — slot 34 walker-cap; needs A18 raise
- **spec/18** (85) — walker-cap, L75 blocked
- **spec/22** (87) — walker-cap, L75 blocked
- **spec/17** (88) — walker-cap, L75 blocked

Most remaining findings are walker-cap class (L75 blocked → A18) or stale-cache class (L34 — already closed in spec). The non-walker-cap MEDIUMs (spec/02 AST count, spec/06 SettingValue DDL, spec/10 registry tables, spec/11 timeout, spec/14 updater lifecycle, spec/26 xmllint dependency, spec/28 parallel runtime) are the next mechanical-close pool when this loop runs again.
