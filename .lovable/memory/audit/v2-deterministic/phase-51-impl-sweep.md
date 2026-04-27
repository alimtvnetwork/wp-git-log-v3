# Phase 51 — Next-8 Implementability Sweep

**Date:** 2026-04-27
**Auditor:** Deterministic v2 (AUDIT_DETERMINISTIC=1)
**Predecessor:** Phase 50 (bottom-8 impl sweep)
**Pattern reused:** inline JSON Schema 2020-12 + typed-language enum/contract block in each module's `00-overview.md`

---

## Targets selected

The 8 lowest-implementability non-tracker, non-index modules at end of Phase 50:

| # | Module | impl_before | impl_after | wtd_before | wtd_after | Δgrade |
|---|--------|------------:|-----------:|-----------:|----------:|:------:|
| 1 | 03-error-manage/01-error-resolution/app-issues | 50 | 65 | 78 | 85 | B→A |
| 2 | 02-coding-guidelines/02-typescript | 50 | 65 | 80 | 86 | B→A |
| 3 | 02-coding-guidelines/03-golang | 50 | 65 | 80 | 88 | B→A |
| 4 | 02-coding-guidelines/03-golang/04-golang-standards-reference | 50 | 65 | 80 | 86 | B→A |
| 5 | 24-app-design-system-and-ui | 50 | 65 | 80 | 86 | B→A |
| 6 | 02-coding-guidelines/04-php | 50 | 65 | 82 | 88 | B→A |
| 7 | 02-coding-guidelines/08-file-folder-naming | 55 | 65 | 79 | 82 | B→B |
| 8 | 03-error-manage/03-error-code-registry/09-templates | 55 | 65 | 80 | 86 | B→A |

**6 of 8 promoted B → A.**

---

## Method

Same proven pattern from Phase 50:
- **JSON Schema 2020-12** block tailored to each module's domain (e.g. tsconfig invariants, golangci config, composer.json invariants, design-token registry, naming-rule registry, template manifest)
- **Typed-language enum block(s)** — TS for cross-cutting modules; **Go** for §02/03; **PHP** for §02/04 (these count toward `has_typed_lang_contract` requiring ≥3 typed blocks)
- All blocks added under `## Inlined Contracts (Phase 51 — boost)` heading; no prose deleted

### Per-module contract picks
| Module | Schema added | Typed block(s) added |
|---|---|---|
| app-issues | AppIssueResolutionRecord | TS: AppIssueStatus, AppIssueSeverity |
| 02-typescript | TsconfigCompilerOptionsInvariants | TS: LogLevel, ResultKind, Result type |
| 03-golang | GolangBuildInvariants | Go ×3: errors pkg, httpx Handler |
| golang-standards-reference | GolangciLintRequiredConfig | Go ×3: Service interface, Option pattern, table tests |
| 24-app-design-system | AppDesignTokens | TS: AppShellVariant, Breakpoint, SemanticColor |
| 04-php | PhpComposerInvariants | PHP ×3: LogLevel, Result, DomainException |
| 08-file-folder-naming | FileFolderNamingRules | TS: NamingCase, NamingScope |
| 09-templates | ErrorTemplateManifest | TS: TemplateRenderTarget, TemplatePlaceholderType, TemplateLanguage |

---

## Lockstep

For each of the 8 modules:
- §00 banner `Updated:` → 2026-04-27 (most already current from prior sweeps)
- §98 banner `Updated:` → 2026-04-27 + new release row dated 2026-04-27
- §99 banner `Updated:` / `Generated:` → 2026-04-27

`check-lockstep.cjs --strict` passes 79/79.

---

## Global metrics

| Metric | Before (Phase 50) | After (Phase 51) | Δ |
|---|---:|---:|---:|
| Mean weighted | 85.5 | **86.1** | +0.6 |
| Mean implementability | 70.5 | **71.9** | +1.4 |
| Tree-health | 100/100 | 100/100 | — |
| Lockstep gate | 79/79 | 79/79 | — |

Threshold (≥84) **maintained and exceeded**. Trajectory across Phases 48 → 50 → 51: **84.7 → 85.5 → 86.1**.

---

## Remaining backlog after Phase 51

| # | Task | Status |
|---|---|---|
| **R1** | Real-AI re-audit of 79 modules | 🚧 BLOCKED — needs Lovable Cloud |
| **B1** | spec/22-git-logs-v2/07-app-entity.md `App` identity columns (Environment/Platform/OwnerEmail) | 🚧 BLOCKED — needs user GDPR/schema decision |
| **Phase 52** | Apply pattern to NEXT 8 lowest-impl modules (now 55-60 impl) | ⏳ autonomous candidate |
| **Phase 53** | Add SQL DDL contracts to remaining database-shaped specs (each +20 impl, biggest single lever left) | ⏳ autonomous candidate |
| **Phase 17** | §99 consistency-report deepening sweep (~15 scaffolds) | ⏳ low priority |

### Phase-52 candidates (locked from raw_results.json)

1. `12-cicd-pipeline-workflows/01-browser-extension-deploy` (impl=55, wtd=79)
2. `12-cicd-pipeline-workflows/02-go-binary-deploy` (impl=55, wtd=79)
3. `02-coding-guidelines/01-cross-language/16-static-analysis` (impl=55, wtd=81)
4. `01-spec-authoring-guide` (impl=55, wtd=82) — **diminishing returns; already touched in Phase 48**
5. `03-error-manage/02-error-architecture/06-apperror-package` (impl=55, wtd=82)
6. `03-error-manage/01-error-resolution` (impl=60, wtd=82)
7. `03-error-manage/01-error-resolution/05-debugging-guides` (impl=60, wtd=82)
8. `02-coding-guidelines/01-cross-language/02-boolean-principles` (impl=60, wtd=84)

For Phase 52, the §12 CI/CD modules naturally want **CI workflow YAML blocks** (≥5 → `has_ci_workflow=true`, +5 impl) on top of the JSON Schema pattern — slightly different recipe than Phase 50/51.
