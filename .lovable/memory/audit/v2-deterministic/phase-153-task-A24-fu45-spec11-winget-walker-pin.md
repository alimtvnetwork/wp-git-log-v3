# Phase 153 Task A24-fu45 — spec/11 winget cross-ref + walker-pin teaser

**Date:** 2026-05-03
**Status:** CLOSED
**Module:** spec/11-powershell-integration (integration-spec axis, score 87 GOOD)

## Findings triage (3 cache findings)

| # | Sev/Dim | Cache claim | Disposition |
|---|---|---|---|
| 1 | HIGH/D5 | `schemas/powershell.schema.json` truncated mid-`credentials`/`wpPlugins` | **Harness artifact** — schema is 268 lines complete on disk (valid JSON terminator); truncation is bundle-cap artifact. Pinned in §00 walker-pin teaser. |
| 2 | MEDIUM/D5 | `upload-plugin-v2.ps1` / `upload-plugin-U-Q.ps1` not on disk | **Pre-closed** by AC-13 (Lesson #29 + #36 downstream-repo pin pattern shipped A18-fu1 #3). Pinned in §00 teaser. |
| 3 | LOW/D3 | winget missing → no exit code | **Mechanical close** — `04-error-codes.md:49-52` already defines `ERR_WINGET_NOT_FOUND (9510)`; cross-ref now added to `03-integration-guide.md:17` Prerequisites table. |

## Resolution

1. **Mechanical (LOW/D3)**: `03-integration-guide.md:17` Prerequisites `winget` row extended to cite `ERR_PREREQUISITES (1)` + diagnostic `ERR_WINGET_NOT_FOUND (9510)` per `04-error-codes.md`. Closes auditor's "fallback not codified with exit code" concern with a one-line cross-ref (Lesson #36 — link, don't restate).
2. **Pure-promotion (Lesson #63)**: 3-row walker-pin teaser added to §00 metadata block surfacing all 3 cache findings + their pre-closures, citing source files + line numbers.

## Saturation note (Lesson #71 reinforcement)

`bytes_used: 140000`, `files_used: 18/19` — module IS walker-saturated. Both edits land in already-bundled tier-1 (§00) or implementer files (`03-integration-guide.md` per `files_used`). Lesson #45 saturation gate blocks NEW §97 ACs only — promotion + cross-ref edits proceed. Confirms Lesson #71 (saturation gate is class-scoped) on second module after spec/18 A24-fu46.

## Lesson #37 retrospective

Integration-spec axis was the original Lesson #37 driver (spec/12 A24-fu4 co-application of L19 + L36). spec/11 is the **second** integration-axis module visited post-L37: confirmed BOTH gap classes were already closed by A18-fu1 #3 triplet (AC-11 concurrency = L19 audit-boundary lift; AC-13 downstream-pin = L36 cross-module reference). Lesson #37 stands as predictive framework — A18-fu1 #3 author shipped exactly the L19+L36 pair without needing the heuristic explicitly.

## Lockstep

- §97 untouched (no contract change, no new AC)
- §00 spec-version 2.27.2 → **2.27.3** (Updated banner refresh + walker-pin teaser block)
- §98 v1.4.0 → **v1.4.1** (new release row)
- §99 v3.6.0 → **v3.6.1** (banner only)

Patch-only across all touched files.

## Gate verification

- Lockstep 87/87 · 0 findings ✓
- Tree-health 168/168 strict ✓
- Version-parity 74/74 matches ✓

## Lesson #63 pattern stability — 7 instances, 4 axes

1. spec/22 (audit-corpus)
2. spec/03 (audit-corpus)
3. spec/27 (integration-spec)
4. spec/13 (normative-contract)
5. spec/14 (normative-contract)
6. spec/18 (process-guidance)
7. **spec/11 (integration-spec)** ← this phase

Pattern stable. No new lesson required.

## Expected re-score

Cache 87. Expected lift on next `--force`: **87 → 90-93** (D5 +1-2 from teaser visibility on schema + downstream-pin; D3 +1 from winget cross-ref; D4 unchanged).
