# Phase 153 Task A11g — spec/13 + spec/28 v5 finding closeout

**Date:** 2026-04-30
**Scope:** spec/13-generic-cli + spec/28-universal-ci-cli
**Lessons applied:** #16 (walker bias) · #29 (audit-corpus pin → generalised to harness-truncation pin) · #30 (verify-before-open) · #33 (§97-WINS prose-mirror cadence) · #34 (cache-stale ≠ contract gap) · #36 (link-don't-restate)

## Findings inspected (audit-v5 cache)

| # | Module | Sev/Dim | Finding | Verification | Decision |
|---|--------|---------|---------|--------------|----------|
| 1 | spec/13 | HIGH/D5 | "Broken external refs (AC-22/23 cite spec/05/27)" | AC-24 already pins (Phase 153 earlier today) | NO-OP |
| 2 | spec/13 | MED/D3 | "Missing concrete Go BEGIN IMMEDIATE example" | `10-database.md` lines 45-90 has table+rules, no Go snippet | **Genuine** — added |
| 3 | spec/13 | LOW/D1 | "09-help-system truncated mid-runScan" | File 144 lines, ends cleanly with Contributors | Walker bias — covered by AC-24 |
| 4 | spec/28 | HIGH/D4 | "07-error-catalog truncated; 08/09/17/18 missing" | All files exist; 07 ends with closing `}` ` ``` ` | Walker bias — added AC-28-41 pin |
| 5 | spec/28 | MED/D3 | "PTY vs pipe ambiguous" | Line 57 says "interleaved" without specifying mechanism | **Genuine** — added AC-28-42 |
| 6 | spec/28 | LOW/D1 | "GitProfile undefined in CLI surface" | Mentioned in §04:118 + §07:41 without binding | **Genuine** — added AC-28-43 + clarified §07:41 |

## Edits (8 files)

### spec/13
- `10-database.md` — added `### Reference Go implementation (normative example)` between PRAGMA table and Transaction discipline. Snippet covers `openDB` (DSN with PRAGMAs + `SetMaxOpenConns(1)`), `withWriteTx` (BEGIN IMMEDIATE + 3-attempt retry + ±25% jitter), `isBusyOrLocked` predicate documented.
- `00-overview.md` v1.1.6 → v1.1.7 (banner)
- `98-changelog.md` v1.1.6 → v1.1.7 (banner + new row 1.1.7)
- `99-consistency-report.md` v1.1.6 → v1.1.7 (banner)

### spec/28
- `97-acceptance-criteria.md` v2.1.0 → v2.2.0; added 3 ACs (AC-28-41/42/43); AC count 40 → 43.
- `00-overview.md` v2.1.3 → v2.2.0 (h10 stamp 22 → 153)
- `98-changelog.md` [2.1.3] → [2.2.0] (new row + sub-file bumps documented)
- `99-consistency-report.md` v2.1.3 → v2.2.0 (banner + new update note)
- `04-command-surface.md` v1.0.0 → v1.1.0 (line 57 expanded with normative pipe-merge mechanism + PTY-forbidden + monotonic-timestamp)
- `07-error-catalog.md` v1.1.0 → v1.1.1 (line 41 GLCI-DOCTOR-PROFILE-NOT-FOUND row clarified server-side resolution + RepoUrl keying)

## AC summaries

- **AC-28-41** `[critical]` — Module-kind / cross-ref pin (Lesson #29 + #36). Mirror of spec/13 AC-24. Declares D4/D5 audit findings as harness scope artifacts.
- **AC-28-42** `[high]` — Pipe-merge interleaving; PTY FORBIDDEN; monotonic-timestamp. Closes D3 MED ambiguity.
- **AC-28-43** `[low]` — `GLCI-DOCTOR-PROFILE-NOT-FOUND` server-side resolution; CLI passive. Closes D1 LOW.

## Validation

All 5 strict CI gates GREEN (post-edit verify):
- Lockstep 87/87 · 0 findings
- Tree health 168/168 strict · 100/100
- Version parity 74/74 matches · 0 mismatches
- §99 freshness 81 stamped + 6 exempt + 0 unstamped
- Folder refs 0 stale · 25 [external] + 25 [doc-only]

No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no gate-count change.

## Lessons reinforced

- **Lesson #30 (verify-before-open) — applied twice this phase**:
  1. Found spec/13 AC-24 already shipped today; finding #1 was a no-op.
  2. Found spec/28's "missing files 08/09/17/18" all exist; finding #4 was walker bias.
- **Lesson #29 generalised**: the audit-corpus pin pattern (originally for spec/25 quoting other specs) extends naturally to **harness-truncation pins** (modules whose §99 inventory is the canonical truth vs an LLM bundle's 90 KB cap). spec/13 AC-24 + spec/28 AC-28-41 are the two precedents; future modules can mint analogous ACs as needed.
- **Lesson #16 (tier-1 walker) — necessary but insufficient**: tier-1 ordering puts §00/§97/§98/§99 first (good for contract visibility) but doesn't change that feature files past 90 KB cap will still produce truncation findings. AC-level disambiguation is the durable fix; harness improvements (split bundles, larger context window) would be belt-and-braces.

## Forward backlog (post-A11g)

| # | Module | Status | Notes |
|---|--------|--------|-------|
| spec/06 D3 MED + D5 HIGH | spec/06 | ✅ CLOSED earlier today (A11f) | |
| spec/15 (--branch removal) | spec/15 | ✅ CLOSED earlier today (A11d) | |
| spec/06 Type enum reconciliation | spec/06 | ✅ CLOSED earlier today (A11e) | |
| spec/13 v5 D3 MED + D1 LOW | spec/13 | ✅ CLOSED this phase (A11g) | |
| spec/28 v5 D4/D3/D1 | spec/28 | ✅ CLOSED this phase (A11g) | |
| Audit-corpus 75-floor | spec/03/12/17/25 | 🔴 structural | Rubric limitation for meta-modules |
| 5 deferred cosmetics | spec/07/10/14/16/22 | 🟡 backlog | Lower leverage |
| A8 re-score | full tree | 🟢 ready when user invokes | Validates A11d/e/f/g cumulative lifts |
| R1 / Cloud features | trace-map | 🔒 blocked | Needs `enable cloud` |
