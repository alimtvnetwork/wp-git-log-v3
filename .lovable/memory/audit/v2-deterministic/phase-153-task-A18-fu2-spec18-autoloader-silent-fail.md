# Phase 153 Task A18-fu2 — spec/18 self-lift: AC-16 autoloader silent-fail + §00 walker-pin teaser refresh

**Date:** 2026-05-03
**Module:** `spec/18-wp-plugin-how-to/`
**Status:** CLOSED — score 85 (cache); LLM re-score deferred per Lesson #20 (gateway HTTP 402)

## Findings closed (cache snapshot 2026-05-03)

| Sev | Dim | Title | Resolution |
|-----|-----|-------|------------|
| HIGH | D5 | Truncated Context Cap (Phases 4-21 missing) | Walker-cap artifact — pinned by §00 teaser citing AC-09 + AC-15 |
| MEDIUM | D4 | Missing FileLogger implementation | Walker-cap artifact — `04-logging-and-error-handling.md` is 836 L on disk; §4.3/§4.9/§4.12 all complete (verified `grep -n`) |
| LOW | D3 | Partial Failure in Autoloader | **Closed by AC-16 (this phase)** — silent try-catch around Phase 1.4 diagnostic-write |

## AC-16 (the only contract change)

`[low]` Autoloader diagnostic-write must be silent-fail (no fatal loop):

- Wraps every diagnostic write to `wp-content/uploads/{slug}/logs/autoloader.log` in `try { ... } catch (\Throwable $logFailure) { /* swallow + Tier 1 error_log fallback */ }`.
- Original `require_once` failure still re-throws per Phase 1.4 row 3.
- Forbidden: unguarded `file_put_contents($logPath, ...)` in autoloader catch-block; calling `$this->fileLogger->...` (FileLogger not yet bootstrapped).
- Verifying linter: `check-forbidden-strings.py` pattern `file_put_contents.*autoloader\.log` outside a `try {` block.
- Cross-references AC-11 FileLogger concurrency posture per **Lesson #36** (link-don't-restate — AC-11 owns the FileLogger surface; AC-16 owns only the pre-FileLogger autoloader-diagnostic surface).

## Lockstep

| File | Before | After | Reason |
|------|--------|-------|--------|
| §97 | v1.4.1 | **v1.5.0** | New AC (count 15→16) — minor per Lesson #24 |
| §00 | v1.4.3 | **v1.5.0** | Walker-pin teaser refresh against current cache snapshot |
| §98 | v1.4.3 | **v1.5.0** | New row + banner |
| §99 | v1.4.5 | **v1.5.0** | Baseline preserved + AC-16 status note |

**No CI / RUBRIC / gate-count change · no AC-31-31 cascade · saturation-safe (AC-16 lands in tier-1 §97 head, inside the bundle window — Lesson #45 not violated).**

## Gate verification

- Lockstep: 87/87 ✅
- Tree-health: 168/168 strict ✅
- Version-parity: 74/74 ✅

## Lesson reinforcement

- **Lesson #19** (audit-boundary < verification-boundary): the autoloader silent-fail rule existed implicitly in Phase 1.4 prose (rows 2 + 3) but had no §97 GWT; AC-16 binds it to a verifiable forbidden-pattern.
- **Lesson #36** (link-don't-restate): AC-16 explicitly does NOT restate AC-11's FileLogger contract — it cross-references it and scopes itself to the pre-FileLogger autoloader-diagnostic surface only. The two ACs are orthogonal layers (Tier-1 native vs Tier-2 FileLogger).
- **Lesson #63** (walker-pin teaser refresh): when a self-lift lands a new closing AC against a saturated module, the §00 teaser MUST be re-anchored against the *current* cache snapshot — the previous A24-fu46 teaser cited an older snapshot's findings (path-drift / Verifies-extension / casing) which had since been replaced by Truncated-Cap / FileLogger / Autoloader. Without re-anchoring, future auditors would see a teaser that doesn't address what their cache shows.
- **Lesson #20** (HTTP 402 → defer score): gateway returned 402 on `--force`; the contract is closed, the LLM re-score will confirm (or not) on next gateway availability.

## Next-up candidates (other GOOD-band modules with similar one-genuine-finding shape)

- **spec/05-split-db-architecture (89)** — LOW/D1 ProjectSlug/Slug/AppName naming disambiguation (single AC clarifying the canonical column per table)
- **spec/04-database-conventions (89)** — already pinned (AC-13 + AC-14/15/16 walker-pin teaser); no new gap
- **spec/01-spec-authoring-guide (89)** — already fully pinned (AC-SAG-29/30/31 walker-pin teaser); no new gap
- **spec/17-consolidated-guidelines (88)** — already fully pinned (AC-10/11/15 + extensive teaser); no new gap
- **spec/22-git-logs-v2 (87) / spec/27-spec-toolchain (83) / spec/12-cicd-pipeline-workflows (83)** — all already pinned (S22-fu/S27-fu/A24-fu4 close-outs)
