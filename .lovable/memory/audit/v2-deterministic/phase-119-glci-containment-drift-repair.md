# Phase 119 — §28 §07 ↔ §97 GLCI-* containment drift repair

**Date:** 2026-04-27
**Type:** Substantive spec content fix (catalog hygiene)
**Trigger:** Phase 118's AC-31-31 bounding sweep against §28 surfaced 2 codes referenced in §97-acceptance-criteria.md but undefined in §07-error-catalog.md. This phase closes that drift while the mechanical regression guard (`test-glci-error-code-containment.sh`) remains in the Phase 117 backlog awaiting user go/no-go.

---

## What was broken

Two codes had been introduced in §97 acceptance criteria without corresponding rows in §07:

| Code | First cited in §97 | Why it was missing from §07 |
|---|---|---|
| `GLCI-EXEC-DEPS-MISSING` | AC-28-37 (TypeScript runtime, line 244) and AC-28-39 (PHP runtime, line 258) | AC-28-37/39 were authored as part of the v2.0.0 per-runtime tool-selection deepening (Phase 16d-v) but the corresponding catalog row was overlooked because §07's `## Execution` section already had 3 rows and the new code was treated as an acceptance-criteria detail rather than a catalog entry |
| `GLCI-STREAM-MALFORMED` | AC-28-26 (line 200, `--stream` mid-frame failure) | Adjacent to existing `GLCI-PUSH-STREAM-BROKEN` semantically but distinct (server-rejection vs connection-drop); the distinction was made in AC-28-26's prose without back-propagating to §07 |

A third Phase-118-flagged token, `GLCI-TELEMETRY-`, was re-classified on inspection: §97 line 231 reads "Verifies: Locked Decision #10; §07 (no `GLCI-TELEMETRY-*` codes — telemetry doesn't exist)". This is a **negative reference** asserting the absence of a code family, not a real undefined code. No §07 row added.

## What was fixed

Added 2 rows to `spec/28-universal-ci-cli/07-error-catalog.md`:

- `## Execution` table — `GLCI-EXEC-DEPS-MISSING | 1 | <cause> | <action>` with explicit scope: TypeScript+PHP only, Go excluded (Go's module cache lives outside the repo so the failure mode does not apply). The cause column cites AC-28-37 and AC-28-39 as the introducing ACs.
- `## Push (transport)` table — `GLCI-STREAM-MALFORMED | 4 | <cause> | <action>` with an explicit comparison clause distinguishing it from the adjacent `GLCI-PUSH-STREAM-BROKEN`: that code = post-retry connection drop; this code = active server framing rejection. Cites AC-28-26.

Catalog GLCI-* count: 27 → **29**. Both new codes were added in the same conceptual section as their nearest sibling, preserving §07's organisational structure.

## Lockstep cascade

| File | Before | After |
|---|---|---|
| `spec/28-universal-ci-cli/07-error-catalog.md` | v1.0.0 | v1.1.0 |
| `spec/28-universal-ci-cli/98-changelog.md` | (no current entry) | new `## [2.1.0] — 2026-04-27` row |
| `spec/28-universal-ci-cli/99-consistency-report.md` | v2.0.0 (claimed "all 28 GLCI-* codes have direct AC coverage" — now factually wrong by undercount) | v2.1.0 (now claims 29 codes, all with direct AC coverage, AND inverse §97 ⊆ §07 containment empirically verified) |

§99's prior v2.0.0 claim is explicitly noted as **superseded** in the new v2.1.0 banner — the count was wrong (should have been 29, not 28) and the AC-coverage claim was vacuously true (all *defined* codes had ACs, but the inverse — every cited code is *defined* — was not asserted and was in fact violated).

## What was NOT changed

- §97 acceptance criteria — unchanged. AC-28-26 / AC-28-37 / AC-28-39 already cite the now-defined codes correctly. This phase is catalog hygiene, not coverage extension.
- No new ACs added to §97.
- No mechanical regression guard authored. `test-glci-error-code-containment.sh` remains in the Phase 117 backlog. Without it, the same drift can re-occur on the next AC that introduces a code; reviewer attention is the only current defence.
- §17-openapi-client.yaml — unchanged. Phase 118 noted §17 carries 0 GLCI-* codes (vs §22's §17 which carries all 44 GL-* codes); whether §17-openapi-client should enumerate GLCI codes is a Phase 120+ design question, not a Phase 119 hygiene fix.

## Why fix the substance now without the mechanical guard

Phase 117 is blocked on user go/no-go for two new AC-31-31 pattern variants (canonical+containment hybrid; N-enum loop). Waiting for that decision before fixing demonstrably-broken substantive content would propagate the drift further. Phase 119 is a **pure spec content fix** — it does not author the parity test, does not register an AC-31-31 row, does not bump CI gate count. When Phase 117 is unblocked, the future `test-glci-error-code-containment.sh` will assert `set(GLCI-* in §97) ⊆ set(GLCI-* in §07)` and pass on the first run thanks to this phase.

## Verification

Re-ran Phase 118's inverse-direction check after the fix:

```
$ rg -o "GLCI-[A-Z][A-Z0-9-]+" spec/28-universal-ci-cli/97-acceptance-criteria.md | sort -u > /tmp/glci-97-after.txt
$ rg -o "GLCI-[A-Z][A-Z0-9-]+" spec/28-universal-ci-cli/07-error-catalog.md | sort -u > /tmp/glci-07-after.txt
$ comm -23 /tmp/glci-97-after.txt /tmp/glci-07-after.txt
GLCI-TELEMETRY-      # negative reference — see "What was broken" above
```

`GLCI-TELEMETRY-` remains as a regex artefact of the negative reference; the substantive containment §97 ⊆ §07 is now satisfied. Memo-headings gate green; cross-links and lockstep gates green.
