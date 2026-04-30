# Phase 153 Task A11e — spec/06 D3 Type-enum reconciliation

**Status:** CLOSED 2026-04-29
**Driver:** v5 audit `06-seedable-config-architecture.json` issue [HIGH/D3] "Inconsistent Type Enums between Schema and AC"
**User decision (visual_choice):** Align schema to AC-SC-14 (UI-aware enum)

## Finding (verified pre-edit, Lesson #30)

- `00-overview.md:151` JSON Schema `Type` enum: `["string", "int", "float", "bool", "json"]` (storage-type set, 5 values)
- `97-acceptance-criteria.md` AC-SC-14: `{boolean, number, string, select, multiselect}` (UI-aware set, 5 values)
- `00-overview.md:222` Forbidden-shapes table reinforced the storage-type set
- `00-overview.md` reference instance (Rag/Update/Storage examples) used `int`/`bool`

Two closed enums for the same `Type` field — direct contract drift; AC-31-31 says §97 wins.

## Resolution

§00 realigned to AC-SC-14 in three places:
1. **JSON Schema enum** (line 151) — replaced with `["boolean", "number", "string", "select", "multiselect"]` + inline `description` documenting the migration mapping (`int`+`float`→`number`, `bool`→`boolean`, `json`→`string`+`Validation.Pattern`) and explicitly FORBIDDING the legacy values.
2. **Reference instance** (Rag/Update/Storage) — `Type: "int"` → `"number"`, `Type: "bool"` → `"boolean"`, `Storage.Backend` promoted from `"string"` to `"select"` (it already declared `Validation.Enum` — semantically a select control).
3. **Forbidden-shapes table** (line 222) — `Type ∈ {string,int,float,bool,json}` → `Type ∈ {boolean, number, string, select, multiselect} (per AC-SC-14; legacy {string,int,float,bool,json} FORBIDDEN)`.

Sweep confirmed (`02-features/*.md`): zero remaining stale `"Type"` mentions outside §97 (legacy IDs there are quarantined under `## Legacy Index`).

## Lockstep banners

| File | Old | New | Why |
|------|-----|-----|-----|
| `00-overview.md` | 4.1.1 | **4.2.0** | Contract value-set narrowed (closed enum changed) |
| `97-acceptance-criteria.md` | 4.0.0 | unchanged | Already canonical — this commit closes drift in §00's favour |
| `98-changelog.md` | 4.1.1 | **4.2.0** | New release row |
| `99-consistency-report.md` | 4.1.1 | **4.2.0** | New audit-trail blockquote |

## CI gates (post-edit)

- **lockstep**: 87/87, 0 findings ✅
- **tree-health --strict**: 168/168, 56/56 modules at full marks ✅
- **version-parity --strict**: 74/74 matches, 0 mismatches ✅
- **freshness --strict-position**: 81 stamped + 6 exempt + 0 unstamped ✅
- **folder-refs**: 0 stale ✅

All GREEN — no regressions.

## Predicted v5+ score impact

spec/06 currently scores **86/100** in v5. This edit closes the only HIGH D3 finding directly. Expected lift to **≥90 (EXCELLENT)** on next `audit-ai-implementability.py --force` re-score. Re-score deferred per Lesson #20 (gateway 402-budget — also user has not asked for re-run).

## Lesson reuse

- **Lesson #30** (verify-before-open): grep `00-overview.md:151` BEFORE authoring the AC fix — confirmed the v5 cache finding was genuine, not stale (unlike spec/04 D2 + spec/02 D2 which both turned out to be cache-stale claims for already-shipped §97 contract).
- **Lesson #34** (cache may be stale, but CAN also be right): of three D2/D3 candidates verified this session, 1 was real (spec/06) and 2 were false positives (spec/04, spec/02). Verify-before-open held its value at 33% hit-rate.
- **Lesson #36** (cross-module cross-references must link, not restate): when §00 documented its own `Type` enum independently of §97, it created a dual-source drift class that silently diverged across phases. The fix preserves the schema in §00 (it must — JSON Schema is machine-validatable artifact) but its `description` now explicitly cites AC-SC-14 as the canonical source of truth.

## No new lesson — no new AC

This is a **mechanical contract realignment** of §00 to existing §97 (AC-SC-14 unchanged). Pattern was already covered by AC-31-31 + Lesson #36. No rubric bump, no gate-count change, no new self-test surface.
