# Phase 153 Task S23-01 — spec/23 PostgreSQL DDL appendix marked Reference-only inline

**Closed**: 2026-05-03
**Module**: `spec/23-app-database/`
**Finding**: audit-v6 MED/D1 — appendix self-claims authoritative ("Every implementing repo MUST materialize these tables EXACTLY") despite AC-ADB-11 declaring SQLite primary + PostgreSQL reference.

## Resolution
Pure prose-mirror of existing AC-ADB-11 into the appendix surface (no new contract):
- Added normative `> ⚠️ Reference / Secondary dialect (per AC-ADB-11)` callout above "Inlined Contracts (Phase 53 — SQL DDL lever)" appendix.
- Retitled appendix heading: `… (SQL DDL, PostgreSQL 15+)` → `… (SQL DDL, PostgreSQL 15+ — REFERENCE ONLY)`.
- Rewrote in-block SQL comment from "Every implementing repo MUST materialize these tables EXACTLY" → "REFERENCE-ONLY PostgreSQL dialect (per AC-ADB-11). The Primary Implementation Target is the SQLite block under § 'Schema' above."

## Lockstep
- §00 v4.2.0 → **v4.2.1** (patch — clarifying prose around existing AC-ADB-11)
- §97 v3.2.0 unchanged (AC-ADB-11 already binds the rule)
- §98 v4.2.0 → **v4.2.1**
- §99 v2.1.1 → **v2.1.2**
- h10 stamp: 153 (unchanged)

**No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no new AC · no gate-count change.**

## Lesson reinforced
**Lesson #36 (cross-module link-not-restate) applies intra-file too.** When one section of a file is normatively superseded by an AC declared elsewhere in the same file, the section MUST link/defer to the AC, not restate authoritative-sounding prose. Two contradictory in-file signals (appendix says "MUST materialize EXACTLY" + AC-ADB-11 says "reference only") create the same dual-source drift class as cross-module restatements.

## Gates
- Lockstep 87/87 · Tree-health 168/168 strict · Version-parity 74/74 · Freshness 81 stamped + 6 exempt + 0 unstamped — all GREEN.
- Folder-refs: 3 pre-existing stale refs to `spec/22-pre-fu29/` in memory files (unrelated to S23-01).
