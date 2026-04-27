---
phase: 125
title: AC-SAG-27 enumeration sweep — folders §02, §03, §05, §06, §07, §10, §11, §24
mode: discovery-only
predecessor: phase-121-folders-12-13-15-16-enumeration-sweep.md
successor: TBD (Phase 117 mechanization or §17 dedicated sweep)
date: 2026-04-27
note: §17 deferred to dedicated phase (36 files, audit folder); §04 already swept in Phase 118
---

# Phase 125 — Enumeration sweep across §02, §03, §05, §06, §07, §10, §11, §24

## Scope & method

Continuation of AC-SAG-27 sweep. Folders triaged with ALLCAPS-token frequency + cross-file restatement check.

| Folder | Files | Top-token signal | Verdict |
|---|---|---|---|
| §02 coding-guidelines | 6 | `NAMING_MATRIX`, `BOOLEAN_NAME_REGEX`, `MAX_RETRIES` | Dismissed — single-folder, contract-inlined in §97 (3/3 normative blocks per Phase 20) |
| §03 error-manage | 5 | `CODE_RE`, `VALID_SEVERITIES` | Dismissed — single-folder severity enum, defined inline at canonical AC |
| §05 split-db-architecture | 7 | SQL keywords (CREATE/INTEGER/PRIMARY/PRAGMA) + `LEGACY-*` AC IDs | Dismissed — all enums (3 layouts, attach budget, hierarchy layers) are §05-internal AC catalog (AC-SD-01..20 + LEGACY-001..002), not cross-folder |
| §06 seedable-config | 7 | SQL keywords + `XDG_CONFIG_HOME` | Dismissed — single-folder; `XDG_CONFIG_HOME` is an OS env var (3 sites), not a spec enum |
| §07 design-system | 17 | low-density token signal | Dismissed — design tokens (colors/spacing) are convention-bound, not enumeration |
| §10 research | 4 | 0 tokens with 3+ occurrences | Dismissed — research notes, not normative |
| §11 powershell-integration | 16 | `LASTEXITCODE`, `POLICY_VIOLATION` | Dismissed — `LASTEXITCODE` is PS built-in; `POLICY_VIOLATION`/`INVALID_INPUT` are §03 error categories cited in 1 §11 file (insufficient sites) |
| §24 app-design-system-and-ui | 4 | low signal | Dismissed — design-only, no normative enums |

## Surfaced candidates

**Zero new candidates**. AC-31-31 backlog remains at 7 (A, B, E, H, K, L, N).

## Pattern observation

Phase 125 confirms the Phase 121 hypothesis: **AC-SAG-27 sweeps yield diminishing returns on convention-heavy / design-heavy / single-folder-AC-catalog folders.** Real enumeration drift concentrates in:

- **CLI/CI/release toolchain** (§12, §13, §14, §16, §28) — heavy on error codes, platform tuples, placeholder tokens.
- **Domain model folders** (§22) — heavy on entity status enums, prefixed code namespaces.
- **Spec governance** (§27, §17) — heavy on AC IDs, gate IDs, lint rule IDs.

Folders that **define their own contract within their own §97** (§02, §05, §06) self-contain their enums and don't drift cross-folder. This matches AC-SAG-25 deference discipline working as intended.

## Recommended next phases (revised)

| Phase | Action | Mode |
|---|---|---|
| **117** | Mechanize the 7-candidate AC-31-31 backlog. Recommend 2 reusable harnesses: containment (A+H, defers N) + uniform-parity (B+E+K+L). | 🚧 Decision |
| **122** | §17 OpenAPI: enumerate `GLCI-*` codes (parity with §22) or leave code-free. | 🚧 Decision |
| **123** | Create `spec/16-generic-release/09-placeholder-tokens.md` canonical catalog (Candidate N pre-req), then mechanize containment. | 🤖 Autonomous (after Phase 117) |
| **124** | ~~Add `Defers-to: §14` banner to §16~~ — **REVERSED**. §16 is `kind: future-spec` (generic blueprint); §14 is the concrete consumer. Deference direction is §14 → §16, not §16 → §14. Phase 121 recommendation was wrong. **Action**: instead, audit whether §14's GOOS/GOARCH AC (AC-20) explicitly cites §16's `01-cross-compilation.md` as the generic source. | 🚧 Decision (reframe needed) |
| **126** | Dedicated AC-SAG-27 sweep of `spec/17-consolidated-guidelines/` (36 files — audit folder, distinct from content folders). Likely surfaces gate IDs, lint rule IDs, audit verdict bands. | 🤖 Autonomous |
| **127** | Real enumeration backlog from spec/17 sweep (whatever Phase 126 surfaces). | 🤖 Autonomous (after 126) |

## Completion certification

- ✅ 8 folders triaged
- ✅ 0 new candidates added (backlog unchanged at 7)
- ✅ Pattern observation recorded — AC-SAG-27 saturating on content folders
- ✅ Phase 124 reframe identified (deference direction was inverted)
- ✅ Discovery-only — zero spec files modified
