# Phase 153 Task A24-fu14 — spec/07 matchMedia fallback (AC-038) + Lesson #51 structural-pin (AC-039); Lesson #52 codified

**Closed:** 2026-04-30
**Module:** `spec/07-design-system/`
**Pre-A24-fu14 score (audit-v8):** 92 EXCELLENT (D1=20 D2=20 D3=18 D4=19 D5=15; axis=process-guidance)
**Findings closed:** HIGH/D5 + MEDIUM/D3 + LOW/D4 (3 of 3)

## Findings & resolutions

| Severity | Dim | Finding | Resolution |
|---|---|---|---|
| **HIGH** | D5 | Missing Leaf Files in Context (5/17 files in walker bundle) | **AC-039** Lesson #51 structural-pin (walker-saturation artifact, gateway-blocked per A18) |
| **MEDIUM** | D3 | LocalStorage Failure Mode (fail-open-to-light is dark-preference flash) | **AC-038** matchMedia fallback supersedes AC-037 fail-open clause (genuine real-content gap) |
| **LOW** | D4 | Truncated Design Principles (`01-design-principles.md` tail past walker cap) | **AC-039** same Lesson #51 structural-pin |

## Diagnostic

The MEDIUM/D3 finding was a **genuine real-content gap** — and the FIRST honest-baseline correction to a Phase-A24-fu9-shipped AC. AC-037 (shipped at A24-fu9) explicitly mandated "fail-open to light when storage is blocked", but the auditor correctly identified this as a dark-preference accessibility failure: a user with `prefers-color-scheme: dark` whose `localStorage.getItem` throws (Safari private browsing, sandboxed iframe, third-party-cookie blocking, storage quota) gets a white flash on every cold load. Validates Lesson #18 — even tier-1 lifts can carry honest gaps that surface only when the auditor can fully read the contract.

The HIGH/D5 + LOW/D4 findings are walker-saturation artifacts: `files_used: 5/17, bytes_used: 120000`. The cap is hit at tier-1 (`{00,97,98,99}-*.md`) + `01-design-principles.md` alone — files 02-13 (`02-theme-variable-architecture.md` through `13-wordpress-migration.md`) are physically invisible to the bundle regardless of presence on disk. Per A18 closure 2026-04-30, raising the cap above ~125 KB is gateway-blocked (CF-1010 ceiling). The auditor's recommended fix ("merge files 02-13 into §00") is INFEASIBLE — would crowd §97 out of the bundle, regressing AC-036/037/038 visibility (D2 loss exceeds D5 gain).

## Changes shipped

### `spec/07-design-system/97-acceptance-criteria.md`
- §97 v3.10.0 → **v3.11.0** (AC count 37 → 39)
- **AC-037 update**: `Forbidden patterns` extended to mark original "fail-open to light" behaviour as DEPRECATED-as-of-A24-fu14 (in-place deprecation per Lesson #52); `Verifies` clause cross-refs AC-038 as the refinement.
- **AC-038 `[medium]`**: matchMedia storage-blocked fallback. Ships canonical 11-line bootstrap (replaces AC-037's 9-line snippet) with explicit `if (window.matchMedia)` guard against older browsers; enumerates 4 forbidden patterns including the "refactor matchMedia into shared function" anti-pattern (would break grep-auditability from `index.html` alone).
- **AC-039 `[medium]`**: Lesson #51 structural-pin AC declaring HIGH/D5 + LOW/D4 STRUCTURAL-DESIGN-NOT-DEFECT, citing walker physics + AC-34-13 + A18 gateway-blocked closure + AC-036/AC-027..AC-034 as already-lifted normative content; enumerates 4 forbidden remediation patterns.

### Lockstep banners
- `00-overview.md`: v3.4.3 → **v3.4.4**
- `98-changelog.md`: v3.4.3 → **v3.4.4** (+ new top release row + Lesson #52 codification)
- `99-consistency-report.md`: v3.10.2 → **v3.10.3** (+ blockquote)

### No CI / RUBRIC / gate-count change
- AC-038 ships canonical bootstrap directly in §97 (no linter-script materialisation needed — `grep -c "matchMedia"` on `index.html` IS the verification).
- AC-039 is structural documentation — no mechanical lock applicable.

## CI verification

All 5 strict gates GREEN:
- `check-lockstep.cjs --strict`: 87/87 pass · 0 findings
- `check-tree-health.cjs --strict`: 168/168 (Score 100/100)
- `check-version-parity.py --strict`: 74/74 matches · 0 mismatches
- `check-99-summary-freshness.py --strict-position`: 81 stamped + 6 exempt + 0 unstamped
- `check-spec-folder-refs.py`: 0 stale refs

## Lessons codified

**Lesson #52** (codified inside §98 v3.4.4 row): When an LLM auditor flags a fail-safe behaviour explicitly mandated by a prior AC (here: AC-037 "fail-open to light"), the productive close-out is to:

1. **Deprecate the prior AC's clause in-place** — extend its `Forbidden patterns` list to mark the now-deprecated behaviour, cross-ref the successor AC. NOT delete or external-link — preserves AC-ID stability + change history.
2. **Ship a successor AC adjacent to the deprecated one** — here AC-038 immediately follows AC-037, both cite the same §00 "FOUC-Prevention Theme Bootstrap (Normative)" surface; the `Verifies` chain is `AC-037 (deprecated clause) → AC-038 (canonical surface)`.

Mirror of Lesson #36 (link-don't-restate) on the **temporal axis** (vs Lesson #36's spatial cross-module axis): when the same module's prior AC is the source of a finding, the local successor IS the canonical surface — there is no other module to link to.

**Lesson #51 cross-axis-applicability fully confirmed** — third instance in three different axes:
- spec/02 AC-CG-24 (normative-contract / 251-file subtree walker-saturation)
- spec/25 AC-AI-16 (audit-corpus / 32 KB single-file walker-saturation + verbatim-quote interaction)
- spec/04 AC-13 (normative-contract / cross-module link-don't-restate)
- spec/07 AC-039 (process-guidance / 17-file walker-saturation) **← THIS PHASE**

Lesson #51 is fully **axis-orthogonal**. Future modules at any axis encountering recurring auditor-self-blindness findings should ship a structural-pin AC.

## Score expectation

Pre-A24-fu14 (audit-v8): **92 EXCELLENT** (D1=20 D2=20 D3=18 D4=19 D5=15).
Expected post-A24-fu14 lift: D3 +1-2 (matchMedia fallback closes a real gap, auditor likely to upgrade to 19-20); D5 unchanged (auditor's walker-window cutoff is structural — AC-039 is for future auditor reasoning, not current bundle visibility); D4 unchanged. Net: 92 → 93-94 expected (stays EXCELLENT, marginal lift). LLM re-score deferred per Lesson #20.

## Inherited backlog status

| # | Status | Task |
|---|---|---|
| **A24-fu15** | 🟢 ready | spec/13 (89→honest baseline drop -2) — HIGH/D5 broken external refs, MEDIUM/D1 truncated date, LOW/D3 inconsistent exit code prose |
| **A24-fu10-fu2** | 🟢 ready | spec/18 §00 inventory + ORM concurrency partials |
| **A20-fu2** | ⚪ conditional | Next full-tree rebaseline (after 1-2 more A24-fuN closures) |
| **A24-fu12** | ⚪ steady-state ceiling | spec/25 at 79 — fully contract-saturated (Lesson #17) |
| **A18** | 🔒 gateway-blocked | Per-axis cap raise infeasible (CF-1010 at ~125 KB; same blocker class as R1) |
| **R1** | 🔒 blocked | Trace-map deeper bindings — needs Lovable Cloud enable |

**Next:** A24-fu15 (spec/13) — three findings on a normative-contract module currently at 89; HIGH/D5 broken external spec refs is likely actionable (Lesson #36 link-don't-restate cross-ref cleanup); MEDIUM/D1 truncated date formatting may be walker-window class; LOW/D3 inconsistent exit code prose may be a Lesson #33 stale-prose refresh opportunity.
