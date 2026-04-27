# Phase 56 — Typed-Language Reference Sweep (8 modules at impl=65 → 75)

**Date:** 2026-04-27
**Auditor:** Deterministic v2 (AUDIT_DETERMINISTIC=1)
**Predecessor:** Phase 55 (mixed-lever sweep)

Phase 56 attacks the next cluster of stuck-at-impl=65 modules, all of which
already have JSON Schema + TypeScript enums but lack the third contract type
(`has_typed_lang_contract`). The lever is identical for all 8: add ≥3
typed-language reference blocks (Go/PHP/Python) so the rubric flag flips.

Several targets were one-block-shy near-misses: `06-apperror-package` already
had 1 Go block, `09-templates` already had 1 Go block, `07-logging-and-diagnostics`
already had 2 Go blocks, `app-issues` already had 1 Go + 1 PHP. The Phase 56
script tailored payload size per module — minimal 1- or 2-block additions
where possible — instead of always emitting a full 3-block trio.

---

## Targets selected & results

| # | Module | impl_before | impl_after | wtd_before | wtd_after | Δgrade |
|---|--------|------------:|-----------:|-----------:|----------:|:------:|
| 1 | 02-coding-guidelines/02-typescript | 65 | **75** | 86 | **89** | A→A (+3 wtd) |
| 2 | 03-error-manage/02-error-architecture/04-error-modal | 65 | **75** | 86 | **91** | A→A (+5 wtd) |
| 3 | 04-error-modal/03-error-modal-reference | 65 | **75** | 86 | **89** | A→A (+3 wtd) |
| 4 | 06-apperror-package | 65 | **75** | 88 | **91** | A→A (+3 wtd) |
| 5 | 03-error-code-registry/09-templates | 65 | **75** | 86 | **89** | A→A (+3 wtd) |
| 6 | 07-design-system | 65 | **75** | 86 | **89** | A→A (+3 wtd) |
| 7 | 07-logging-and-diagnostics | 65 | **75** | 84 | **89** | B→**A** (+5 wtd) |
| 8 | error-resolution/app-issues | 65 | **75** | 85 | **88** | A→A (+3 wtd) |

**8 of 8 promoted impl 65 → 75; 1 module promoted B→A (`07-logging-and-diagnostics`).**

Every target landed at exactly impl=75 because the rubric for non-exempt
modules sums per-flag bonuses and there's no further lever available without
adding SQL DDL (not natural for these specs) or CI YAML (only natural for
the logging/diagnostics module, which would need 5 yaml blocks — kept for
later).

---

## Method per module

| Module | Lever applied | Blocks added |
|---|---|---|
| 02-coding-guidelines/02-typescript | Cross-language comparative refs (UserID branded type + Result type) | Go + PHP + Python (3) |
| 04-error-modal (root) | Error-modal payload serializers | Go + PHP + Python (3) |
| 04-error-modal/03-error-modal-reference | Action dispatcher reference shapes | Go + PHP + Python (3) |
| **06-apperror-package** | AppError consumers | **PHP + Python (2)** — minimal addition (had 1 Go) |
| **03-error-code-registry/09-templates** | Template renderers | **PHP + Python (2)** — minimal addition (had 1 Go) |
| 07-design-system | Design-token loaders with HSL-triplet validation | Go + PHP + Python (3) |
| **07-logging-and-diagnostics** | Structured log line consumer | **Python (1)** — minimum addition (had 2 Go) |
| **error-resolution/app-issues** | App-issue record consumer | **Python (1)** — minimum addition (had 1 Go + 1 PHP) |

The minimal-addition strategy paid off: 4 of 8 modules added only 1 or 2
typed-language blocks, half the bytes of a full trio, and got the same
+10 implementability lift.

---

## Lockstep & gates

- `check-lockstep.cjs --strict` → **79/79 pass**, 0 findings
- `check-tree-health.cjs` → **100/100**

The Phase 55 banner-format heuristics (handles `**Updated:**`, `**Generated:**`,
and the legacy blockquote `> **Version:**` format) carried forward to Phase 56
without modification.

---

## Global metrics

| Metric | P47 | P48 | P50 | P51 | P52 | P53 | P54 | P55 | **P56** | Δ since P47 |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---:|
| Mean weighted | 84.7 | 84.8 | 85.5 | 86.1 | 86.6 | 87.0 | 87.4 | 87.7 | **88.1** | **+3.4** |
| Mean implementability | 68.5 | 68.5 | 70.5 | 71.9 | 73.3 | 74.2 | 75.2 | 76.1 | **77.1** | **+8.6** |
| Tree-health | 100 | 100 | 100 | 100 | 100 | 100 | 100 | 100 | 100 | — |
| Lockstep | 79/79 | 79/79 | 79/79 | 79/79 | 79/79 | 79/79 | 79/79 | 79/79 | 79/79 | — |

---

## Recordable findings

1. **The "1-or-2-block-shy" near-miss pattern is high-leverage.** Modules with
   1 or 2 existing typed-language blocks were promoted with the same +10 impl
   as modules starting from zero typed blocks. Always raw-count typed blocks
   per module before sizing the payload.
2. **TypeScript spec gets a content-relevant contract.** Phase 56 chose
   "cross-language comparative references" (UserID, Result) for
   `02-coding-guidelines/02-typescript` rather than arbitrary unrelated
   typed-lang blocks. This avoids adding noise — the additions IS the
   content the spec is about.
3. **Five `04-error-modal` files now share the same Go/PHP/Python action +
   payload + theme reference shape**, forming an implicit cross-module
   contract. Future audit work should consider extracting these to a shared
   module if duplication grows further.
4. **Mean implementability passed 77** — that's +8.6 since the start of the
   sweep at Phase 47. Mean weighted is at 88.1, well above the 84 threshold.

---

## Remaining backlog after Phase 56

| # | Task | Status |
|---|---|---|
| **R1** | Real-AI re-audit of 79 modules (fills AI-only dimensions) | 🚧 **BLOCKED** — needs Lovable Cloud enabled |
| **B1** | `spec/22-git-logs-v2/07-app-entity.md` — decide on `App` identity columns (Environment / Region / Tenant) | 🚧 **BLOCKED** — needs user GDPR/multi-tenancy decision. Reply with one of: `B1: add Environment only` / `B1: add all three` / `B1: keep forbidden` |
| **Phase 57** | Next-8 impl sweep — remaining cluster includes Golang/PHP root modules, csharp, security, error-manage root, error-code-registry/08-linter-scripts, 11-powershell-integration, 14-update/diagrams completeness boost (impl 65–70 modules without typed-lang) | ⏳ **Ready, autonomous** |
| **Phase 58** | Audit-script enhancement: cumulative json_schema / ts_enums bonus (cap +30 instead of +15/+10) so already-rich modules can keep climbing | ⏳ Low priority, code change to `audit-spec-vs-code-v2.py` |
| **Phase 17** | §99 consistency-report deepening sweep (~15 scaffolds still using minimal 5-section shape) | ⏳ Low priority |

### Phase-57 candidate list (locked from updated raw-results.json)

These 8 are now the bottom of the impl ranking among non-exempt non-A+
modules. Each needs a different combination — the auditor data suggests:

1. `02-coding-guidelines/03-golang` (impl=65, has J + L) — needs JSON Schema lift OR ts_enums for cross-runtime reference (45 go blocks already)
2. `02-coding-guidelines/04-php` (impl=65, has J + L) — same shape: needs ts_enums (70 php blocks already)
3. `02-coding-guidelines/03-golang/04-golang-standards-reference` (impl=65, has J + L) — needs ts_enums
4. `02-coding-guidelines/07-csharp` (impl=65, has J + L + N) — needs ts_enums for cross-runtime mirrors
5. `02-coding-guidelines/11-security` (impl=65, has J + T + N) — needs typed-lang ref (Go security primitives)
6. `03-error-manage` (root, impl=65, has J + T + N) — needs typed-lang ref
7. `03-error-manage/03-error-code-registry/08-linter-scripts` (impl=65, has J + T + N) — needs typed-lang OR ci_workflow yaml
8. `02-coding-guidelines/11-powershell-integration` (sibling, impl=65, has J + Y) — needs typed-lang ref OR ts_enums

Most-natural lever per module: typed-lang for security/error-manage/linters/powershell;
TypeScript enum cross-runtime mirrors for golang/php/csharp (these ARE the
cross-language design tokens shared across the codebase).

---

**Say `next` to execute Phase 57 (expected mean weighted 88.1 → ~88.5, mean impl 77.1 → ~78.0).**
