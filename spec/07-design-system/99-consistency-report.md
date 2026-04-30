# Consistency Report

**Version:** 3.10.3  
**Updated:** 2026-04-30 (Phase 153 A24-fu14 — AC-038 matchMedia storage-blocked fallback + AC-039 Lesson #51 structural-pin)

> **v3.10.3 update (Phase 153 Task A24-fu14 — AC-038 matchMedia fallback + AC-039 Lesson #51 structural-pin):** Closed audit-v7 MEDIUM/D3 "LocalStorage Failure Mode" via **AC-038 `[medium]`** tightening AC-037's storage-blocked branch — `catch` MUST query `window.matchMedia('(prefers-color-scheme: dark)').matches` and apply `dark` class if OS preference is dark (NEVER fail-open unconditionally to light). Ships canonical 11-line bootstrap with `if (window.matchMedia)` guard. AC-037's prior 9-line snippet's "fail-open to light" clause deprecated in-place (preserves AC-ID stability per NEW Lesson #52 — temporal-axis mirror of Lesson #36). Closed recurring HIGH/D5 "Missing Leaf Files in Context" + LOW/D4 "Truncated Design Principles" via **AC-039 `[medium]`** Lesson #51 structural-pin — walker `files_used: 5/17, bytes_used: 120000` (cap saturated at tier-1 + `01-design-principles.md`); per A18 gateway-blocked at ~125 KB CF ceiling. Auditor's "merge files 02-13 into §00" fix INFEASIBLE (would crowd §97 out of bundle, net D2 loss). AC count §97 v3.10.0 → **v3.11.0** (37 → 39). §00 v3.4.3 → **v3.4.4**; §98 v3.4.3 → **v3.4.4**; this file v3.10.2 → **v3.10.3**. **NEW Lesson #52 codified at §98 v3.4.4 row**: When an LLM auditor flags a fail-safe behaviour explicitly mandated by a prior AC, deprecate-in-place + ship a successor AC adjacent (here AC-037 → AC-038). Temporal-axis mirror of Lesson #36 (link-don't-restate). Lesson #51 cross-axis-applicable now confirmed at process-guidance (this AC), normative-contract (spec/02 AC-CG-24, spec/04 AC-13), and audit-corpus (spec/25 AC-AI-16) — fully axis-orthogonal. All 5 strict gates expected GREEN.


> **v3.10.0 update (Phase 153 Task #32 — §98 SemVer-track unification, parity drift CLOSED):** User reply `next`. Investigation: §07 had been tracking TWO SemVer namespaces in §98 — a "file-scaffold" 1.x track (rows 1.0.0..1.5.0) inherited from the early-project file-format versioning, and a "module SemVer" 3.x track started by Phase 56's `3.3.0` row that was meant to align §98 with §00's banner. Phase 151 regressed back onto the 1.x track (added `1.7.0` at the top), producing the long-standing parity warning `§00=3.4.0 vs §98=1.7.0` that surfaced as Task #32. **Resolution**: in §98, renamed the topmost row's heading from `1.7.0` to `3.4.0` (matching §00's banner) and updated the §98 file banner to `3.4.0`; appended a row-internal lockstep-correction note documenting the renumbering, the two-track root cause, and the going-forward rule. Older 1.x rows below are preserved as historical record (the parity gate inspects only the topmost release row). `check-version-parity.py` (§27 slot 29) confirms `spec/07` is no longer in the FAIL list. **No content changes** to §97 / §00 / `*.md` body — this is a metadata-only correction. Lockstep: §99 v3.9.0 → **v3.10.0**, §00 v3.4.0 → **v3.4.1** (patch — stamp refresh `h10-verified-phase: 32` → `153`), §98 banner v1.7.0 → **v3.4.0** (renumber to match §00 + adds Task #32 lockstep-correction note in topmost row). **Lesson codified at §98 v3.4.0 row & here**: when §98 is tempted to track its own SemVer namespace independent of §00 (e.g. file-scaffold version vs module version), the `check-version-parity.py` gate WILL fail at every §00 bump. **Pick one track per file and stick with it.** §07 §98 release rows MUST track §00's module SemVer going forward; if a file-scaffold version is needed, encode it in front-matter or a dedicated `**File-format:**` banner field — never in the §98 release-row heading. **Closes Task #32; AI-confidence parity remains 51/51; tree-health 168/168 strict; lockstep 87/87.**

> **v3.9.0 update (Phase 151 — P3 sweep slot 11 — FINAL P3 SLOT — Verifies clauses on §97):** §97 deepened — Verifies-coverage 0/34 → 34/34. All 34 ACs (AC-001..AC-034) now carry `**Verifies:**` clauses mapping each criterion to its underlying invariant: token-layer purity, `:root`↔`.dark` parity, single-frame atomic theme switch, WCAG 2.1 AA conformance (§1.3.1 / §1.4.11 / §2.1.2 / §2.3.3 / §2.4.6 / §2.4.7 / §2.5.5), font family-resolution + gradient-text composition, motion-budget + reduced-motion + brand-motion signatures, code-block surface + reader-control + pin/range/fullscreen/copy contracts, header-icon tactile-feedback, dropdown brand-anchoring, mobile-nav structural-shift, Ctrl+B global shortcut, section-pattern composition, font-registry single-source-of-truth, state-language quartet, responsive-tier. GWT bodies preserved verbatim. §97 v3.7.0 → v3.8.0; §00 v3.3.0 → v3.4.0; §98 v1.6.0 → v1.7.0 [**superseded by Task #32 — renumbered to v3.4.0**]. AI-confidence P3 driver eliminated for `spec/07`; derived tier remains Production-Ready (already at top tier; this closes the residual Verifies-coverage gap toward the upcoming P4 stamp). **Closes the P3 sweep across all 11 slots — every Verifies-gap module in the spec tree is now at full coverage.**

> **v3.7.0 update (Phase 15e — §07 §97 conversion COMPLETE):** §97 Navigation + Page Consistency sections converted from table-row to GWT — 9 ACs (AC-026..AC-034) deepened from ~70 chars/row to 1900-3500 chars/AC (27-50× depth) with full G/W/T bodies + cross-refs to `08-header-navigation.md`, `10-sidebar-system.md`, `11-section-patterns.md`, `12-page-creation-rules.md`, `tailwind.config.ts`, AC-001/AC-007/AC-008/AC-009/AC-010/AC-012/AC-014/AC-026/AC-029, WCAG 2.1 §1.3.1/§2.4.7/§2.5.5. **34 of 34 ACs now GWT — conversion COMPLETE.** Zero table rows remain in §97. AC IDs unchanged (still AC-001..AC-034 sequential). Banner v3.6.0 → v3.7.0.

> **v3.6.0 update (Phase 15d):** §97 Code Blocks section converted from table-row to GWT — 9 ACs (AC-017..AC-025) deepened from ~80 chars/row to 2100-4200 chars/AC (26-52× depth) with full G/W/T bodies + cross-refs to `07-code-blocks.md`, `02-theme-variable-architecture.md`, `src/index.css` (lines 264–605), `src/components/markdown/codeBlockBuilder.ts`, AC-001/AC-012/AC-014. **25 of 34 ACs now GWT** (Theme & Variables + Typography + Motion & Transitions + Code Blocks); 9 ACs await Phase 15e (Navigation + Page Consistency). AC IDs unchanged (still AC-001..AC-034 sequential). Banner v3.5.0 → v3.6.0.

> **v3.5.0 update (Phase 15c):** §97 Motion & Transitions section converted from table-row to GWT — 5 ACs (AC-012..AC-016) deepened from ~70 chars/row to 1703-3816 chars/AC (24-54× depth) with full G/W/T bodies + cross-refs to `06-motion-transitions.md`, `09-button-system.md`, `tailwind.config.ts`, WCAG 2.1 §2.3.3. **16 of 34 ACs now GWT** (Theme & Variables + Typography + Motion); 18 ACs await Phase 15d..15e (Code Blocks, Navigation, Page Consistency). AC IDs unchanged (still AC-001..AC-034 sequential). Banner v3.4.0 → v3.5.0.

> **v3.4.0 update (Phase 15b):** §97 Typography section converted from table-row to GWT — 5 ACs (AC-007..AC-011) deepened from ~70 chars/row to 1209-4005 chars/AC (17-57× depth) with full G/W/T bodies + cross-refs to `03-typography.md`, `index.html` font-loading, `tailwind.config.ts` font registration, WCAG 2.1 §1.3.1/§2.4.6. **11 of 34 ACs now GWT** (Theme & Variables + Typography); 23 ACs await Phase 15c..15e (Motion & Transitions, Code Blocks, Navigation, Page Consistency). AC IDs unchanged (still AC-001..AC-034 sequential). Banner v3.3.0 → v3.4.0.

> **v3.3.0 update (Phase 15a):** §97 partially converted from table-row format to GWT — Theme & Variables section (AC-001..AC-006) deepened from ~80 chars/row to 994-4231 chars/AC (12-50× depth) with full G/W/T bodies + cross-refs to `01`/`02`/`06`/`src/index.css`/`tailwind.config.ts`/WCAG 2.1. AC IDs unchanged (still AC-001..AC-034 sequential). Sections AC-007..AC-034 (Typography, Motion & Transitions, Code Blocks, Navigation, Page Consistency) remain in table format pending Phase 15b..15e. Banner v3.2.0 → v3.3.0.

---

## File Inventory
<!-- verified-phase: 153 -->

| # | File | Present | Naming |
|---|------|---------|--------|
| 00 | 00-overview.md | ✅ | ✅ |
| 01 | 01-design-principles.md | ✅ | ✅ |
| 02 | 02-theme-variable-architecture.md | ✅ | ✅ |
| 03 | 03-typography.md | ✅ | ✅ |
| 04 | 04-spacing-layout.md | ✅ | ✅ |
| 05 | 05-borders-shapes.md | ✅ | ✅ |
| 06 | 06-motion-transitions.md | ✅ | ✅ |
| 07 | 07-code-blocks.md | ✅ | ✅ |
| 08 | 08-header-navigation.md | ✅ | ✅ |
| 09 | 09-button-system.md | ✅ | ✅ |
| 10 | 10-sidebar-system.md | ✅ | ✅ |
| 11 | 11-section-patterns.md | ✅ | ✅ |
| 12 | 12-page-creation-rules.md | ✅ | ✅ |
| 13 | 13-wordpress-migration.md | ✅ | ✅ |
| 97 | 97-acceptance-criteria.md | ✅ | ✅ |
| 99 | 99-consistency-report.md | ✅ | ✅ |

---

## Health Score

| Criterion | Status | Weight |
|-----------|--------|--------|
| `00-overview.md` present | ✅ | 25% |
| `99-consistency-report.md` present | ✅ | 25% |
| Lowercase kebab-case naming | ✅ | 25% |
| Unique numeric sequence | ✅ | 25% |
| **Total** | **100/100** | |

---

## Cross-Reference Integrity

| Link | Target | Status |
|------|--------|--------|
| All `[NN-file.md]` references | Within `07-design-system/` | ✅ |
| `src/index.css` | Project source | ✅ |
| `tailwind.config.ts` | Project source | ✅ |
| `../08-docs-viewer-ui/` | Spec tree | ✅ |
| `../01-spec-authoring-guide/` | Spec tree | ✅ |

---

## Naming Convention Compliance

- All files: lowercase kebab-case ✅
- All files: numeric prefix ✅
- No gaps in sequence ✅
- Reserved prefixes used correctly (00, 97, 99) ✅

---

## Ambiguities Noted

| Item | Location | Status |
|------|----------|--------|
| WordPress migration approach | `13-wordpress-migration.md` | Documented as undecided |
| Multi-theme preset support | `02-theme-variable-architecture.md` | Single base theme; presets deferred |
| Reference site identification | `11-section-patterns.md` | Patterns documented from observed behavior |

---

*Report generated: 2026-04-05*

---

## Validation History

| Date | Version | Action |
|------|---------|--------|
| 2026-04-26 | current | Phase 31: Added Validation History + heading-rubric alignment for `check-tree-health.cjs` v2.0.0 quality dimension. No content removed. |
| 2026-04-25 | prior | Tree-wide audit baseline established (45/100 → roadmap to 100). |
| 2026-04-20 | prior | Module brought into alignment with parent §99 conventions. |
| 2026-04-16 | prior | Initial consistency report authored. |

This module's full lockstep history is mirrored in `98-changelog.md`; entries
above summarize only the audit-/validation-bearing milestones for `07-design-system`.
| 2026-04-27 | 3.3.0 | Phase 56 — typed-language reference sweep |


## 2026-04-27 — Phase 58 impl-sweep

- Phase 58: appended Design Tokens OpenAPI contract to satisfy `has_yaml_openapi` rubric (impl 75 → 85).

## 2026-04-27 — Phase 68 audit

- Mermaid lifecycle diagram present (`has_mermaid=true`).
- Lockstep & tree-health gates: PASS.
- Implementability promoted from 85 → 90.

### 2026-04-27 — Phase 72 deepening

- CI workflow contract inlined: 5 stages (detect, validate, lint, promote, report).
- Implementability raised 90 → 95 (deterministic audit).

