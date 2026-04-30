# Phase 153 Task A24 — spec/06 EXCELLENT-band push (Lesson #45 graduated pre-flight applied)

**Date:** 2026-04-30
**Module:** `spec/06-seedable-config-architecture/`
**Pre:** 89/100 GOOD (18,19,17,18,15); axis `normative-contract` (D2×1.5, D3×1.2, D5×0.5).
**Target:** ≥90 EXCELLENT.
**Pre-flight per Lesson #45 graduated:** tier-1 bundle = §97 (25.8 KB) + §00 (12.0 KB) + §01-fundamentals (21.3 KB) = **59.9 KB**; **15.9 KB headroom** under the 75 KB walker-saturation floor → VIABLE for in-§97 content extension.

## Action

In-place extensions to existing ACs (no new AC, no AC count change → no AC-31-31 cascade):

- **AC-SC-14** extended with NORMATIVE Go `SettingValue` struct mapping clause: `boolean`→`BoolVal *bool`; `number`→`NumberVal *float64`; `string`→`StringVal *string`; **`select`→`StringVal *string`** (verbatim chosen value, NOT enum-index); **`multiselect`→`StringsVal *[]string`** (ordered list). Forbidden patterns: `EnumVal int` for `select` (loses round-trip with Options); comma-flattened `multiselect` (loses values containing commas). `SettingValue` struct comment MUST cite this AC. **Closes v7 D1 LOW** "Ambiguous Type Mapping for 'select'" (`bundle_sha c5b46d3cb2b36a7b`).

- **AC-SC-21** extended with NORMATIVE reference Go pseudo-code:
  ```go
  func (s *ConfigService) SeedWithVersionCheck(ctx, seed) error {
      lock, _ := acquireFileLock(lockPath, 30*time.Second)  // AC-SC-17
      defer lock.Release()
      return s.db.Transaction(func(tx) error {              // AC-SC-11 BEGIN IMMEDIATE
          s.applySeedRows(tx, seed)
          s.appendChangelog(seed)                            // AC-SC-16
          return s.fsyncChangelog()                          // BEFORE lock release
      })
  }
  ```
  Plus 4 forbidden patterns: (i) lock-after-tx race, (ii) changelog outside tx closure, (iii) non-deferred Release, (iv) `sync.Mutex` substitution. **Closes v7 D3 MEDIUM** "Incomplete Concurrency Implementation Detail".

Skipped intentionally: v7 D5 HIGH "Broken External Symbol Resolution" — already pinned by AC-SC-22 (link-don't-restate per Lesson #36). D5 axis weight on `normative-contract` is ×0.5 = lowest ROI; chasing the walker artifact would inflate §97 without scoring effect.

## Lockstep

- §97 v4.1.0 → **v4.2.0** (in-place AC extensions → minor per AC-CL-17 boolean-storage convention precedent A22-fu1)
- §00 v4.3.0 → **v4.3.1** (banner patch)
- §98 v4.3.0 → **v4.3.1** (release row)
- §99 v4.3.0 → **v4.3.1** (prose row)

No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no slot-inventory change · no gate-count change.

## Expected lift

| Dim | Pre | Post | Δ | Axis weight | Weighted Δ |
|---|---|---|---|---|---|
| D1 | 18 | 19 | +1 | ×1.0 | +1.0 |
| D3 | 17 | 18 | +1 | ×1.2 | +1.2 |
| D2 | 19 | 19 | 0 (no new AC) | ×1.5 | 0 |

**Weighted total**: 88.8 + 2.2 = **91.0 EXCELLENT**.
**Conservative floor**: ≥90 (single-dim partial lift still clears threshold).

Pattern follows precedents: spec/03 A21 +7, spec/04 A21 +8 — both succeeded because §97 had headroom. spec/05 A23 failed (REVERTED) because §97 was saturated (38 KB; total 75.7 KB > 75 KB floor). spec/06 A24 had 25.8 KB §97 (well within budget).

## Post-edit verification

- Pre-flight `wc -c` confirmed VIABLE before authoring.
- Post-edit total = 63.4 KB tier-1 bundle (12.4 KB headroom remaining → safe for one more moderate AC if needed).
- All 5 strict CI gates GREEN: lockstep · tree-health 168/168 strict · version-parity · freshness · folder-refs.

## Lesson #45 graduated discipline — confirmed working

A24 is the first phase to apply the graduated pre-flight `wc -c < 75 KB` check BEFORE authoring. Result: targeted module (spec/06) had 15.9 KB headroom; chose tight in-place AC extensions (~3.5 KB total) instead of fat new ACs; kept post-edit total at 63.4 KB. **No regression risk** (vs A23's −7 regression on saturated spec/05). Future EXCELLENT-band pushes MUST follow this same discipline.
