# Changelog — Spec Toolchain

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Scope:** `spec/27-spec-toolchain/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.1.0 — 2026-04-26
- **Added** §17 [`17-check-trace-map-regression.md`](./17-check-trace-map-regression.md) and `linter-scripts/check-trace-map-regression.py` — CI gate that runs `generate-trace-map.py` and fails the build when `ac_traced` drops or `ac_drifted`/`code_orphan`/`missing_ac`/`missing_file` grow against the committed baseline at `.lovable/memory/audit/trace-map-baseline.json`.
- **Added** new step **Trace-map regression gate** in `.github/workflows/spec-health.yml`; gate is also surfaced in the `$GITHUB_STEP_SUMMARY`.
- **Added** 8 new acceptance criteria (AC-17-01..AC-17-08) and matching `linter-scripts/trace-map.toml` entries — module trace coverage rose from 16 → 24 ACs.

### 1.0.0 — 2026-04-25
- **Added** module created to spec the toolchain. Closes the largest single audit-v2 finding category (`missing-spec` × 32) by giving every executable artifact a home.
- **Added** 28 per-artifact spec sections covering all current `linter-scripts/` files and `.github/workflows/spec-health.yml`.
- **Added** numbering convention (validators 01–09, generators 10–19, fillers 20–29, auditors 30–39, runners 40–49, source validators 50–59, configs 60–69, CI 70–79).
- **Added** module-level invariants: bijection (AC-T-01), exit-code contract (AC-T-03), idempotency declaration (AC-T-04), slot immutability (AC-T-07).
- **Added** [`99-consistency-report.md`](./99-consistency-report.md) with full file inventory and code-artifact bijection table.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)
