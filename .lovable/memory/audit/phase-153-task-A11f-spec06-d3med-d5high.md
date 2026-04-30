# Phase 153 Task A11f — spec/06 D3 MEDIUM + D5 HIGH closure

**Status:** CLOSED 2026-04-29
**Driver:** v5 audit `06-seedable-config-architecture.json` issues [MEDIUM/D3] CHANGELOG concurrency + [HIGH/D5] missing apperror registry

## Verified-before-open (Lesson #30)

- **D3 MEDIUM**: AC-SC-11 says CHANGELOG write is "outside the SQL transaction"; AC-SC-17 mentions file lock but does not bind ordering with the SQL transaction or CHANGELOG write. Genuine ambiguity — two concurrent seeders could COMMIT in serial then race on CHANGELOG append.
- **D5 HIGH**: 14 occurrences of `apperror`/`AppError`/`AB-9301`/`ErrSeedLoadFailed` across `01-fundamentals.md` + `02-features/01-rag-chunk-settings.md` + `02-features/02-rag-validation-helpers.md`. Canonical apperror package exists at `spec/03-error-manage/02-error-architecture/06-apperror-package/`; canonical error-code registry at `spec/03-error-manage/03-error-code-registry/01-registry.md`. spec/06 has zero binding to either.

Both findings are real (vs Lesson #34 cache-stale).

## Resolution

### AC-SC-21 — CHANGELOG concurrency lock-ordering

Single shared file lock (`<DBPath>.seeder.lock` per AC-SC-17) MUST be:
1. **Acquired** BEFORE `BEGIN IMMEDIATE`
2. **Held** through the sequence `BEGIN IMMEDIATE → INSERT/UPDATE → COMMIT → CHANGELOG append → fsync(CHANGELOG.md)`
3. **Released** ONLY after fsync returns

Forbidden patterns:
- Releasing the lock between COMMIT and CHANGELOG write (loses entries on race)
- Taking a SEPARATE `<CHANGELOG.md>.lock` (creates a deadlock class with AC-SC-17's lock)
- Holding the lock across AC-SC-17's user-facing 30s wait loop (would deadlock waiters)

Crash semantics inherit from AC-SC-11 (CRITICAL log + exit 1) and AC-SC-17 (PID-staleness for `LockFileEx`; `flock` auto-releases).

### AC-SC-22 — apperror cross-reference (Lesson #36 — link, never restate)

Every `apperror.*` symbol, `*apperror.AppError` type, `Err*` sentinel, and `AB-NNNN` code in spec/06 code samples MUST resolve via:
- **Package contract**: `spec/03-error-manage/02-error-architecture/06-apperror-package/`
- **Error-code registry**: `spec/03-error-manage/03-error-code-registry/01-registry.md`

Sub-feature files introducing a NEW error code MUST add a registry row in the same PR.

**Rejected the auditor's recommendation** ("inline a minimal Go package + const block"). Per Lesson #36, restating spec/03's contract in spec/06 would create a dual-source drift class. The correct fix is a normative cross-reference AC.

## Lockstep banners

| File | Old | New | Why |
|------|-----|-----|-----|
| `97-acceptance-criteria.md` | 4.0.0 | **4.1.0** | New ACs (AC-SC-21 + AC-SC-22); count 20 → 22 |
| `00-overview.md` | 4.2.0 | **4.3.0** | Tracks §97 minor |
| `98-changelog.md` | 4.2.0 | **4.3.0** | New release row |
| `99-consistency-report.md` | 4.2.0 | **4.3.0** | New audit-trail blockquote |

## CI gates (post-edit)

- **lockstep**: 87/87, 0 findings ✅
- **tree-health --strict**: 168/168, 56/56 modules at full marks ✅
- **version-parity --strict**: 74/74 matches, 0 mismatches ✅
- **freshness --strict-position**: 81 stamped + 6 exempt + 0 unstamped ✅
- **folder-refs**: 0 stale ✅

All GREEN.

## Predicted v5+ score impact

spec/06 currently scores **86/100** in v5. A11e closed the D3 HIGH (Type enum); A11f closes the remaining D3 MEDIUM + D5 HIGH. Expected combined lift to **≥92 (EXCELLENT)** on next `audit-ai-implementability.py --force` re-score (deferred per Lesson #20).

## Lesson reuse

- **Lesson #30** (verify-before-open): both findings were genuine (vs spec/04 D2 + spec/02 D2 which were cache-stale this session). Verify-before-open hit rate this session now 3/5 = 60%.
- **Lesson #36** (link, never restate): the D5 HIGH "missing external registry" auditor recommendation literally said "inline a minimal Go apperror package". Following it would have created exactly the dual-source drift Lesson #36 forbids. The correct fix is cross-reference, not duplication. AC-SC-22 codifies this in spec/06's surface.
- **Lesson #34** (cache-stale awareness): cache will not refresh until A8 re-run. The §97 + §98 prose are now the authoritative sources for what's closed.

## No new lesson — no new gate

All patterns covered by existing Lessons #30/#34/#36. No rubric bump, no new AC pattern, no self-test surface change.
