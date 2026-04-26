# Hard Gate Report

**Generated:** 2026-04-25  
**Modules analysed:** 78  
**Modules capped by gates:** 42 (53.8%)  
**Active gate firings:** 45  
**Passive gate firings:** 56 _(rule triggered but score already at/below cap)_

## How to read

Hard gates are non-negotiable score ceilings applied AFTER the rubric. When a gate's predicate fires, the named dimension cannot exceed `cap`.
An **active** gate actually reduced the score; a **passive** one fired but the rubric was already at/below the cap.

## Gate firing leaderboard

| Gate | Dimension | Cap | Active fires | Passive fires | Total dimension-points lost | Rationale |
|---|---|---:|---:|---:|---:|---|
| `G-LINK-01` | consistency | 70 | 39 | 2 | 850 | Any broken cross-spec link caps consistency at 70 — readers cannot trust references. |
| `G-AC-02` | testability | 60 | 5 | 5 | 70 | ACs exist but none use Given/When/Then — testability degraded. |
| `G-TODO-01` | completeness | 70 | 1 | 2 | 5 | ≥3 TODO/TBD/FIXME markers — module is explicitly incomplete. |
| `G-CON-01` | implementability | 50 | 0 | 30 | 0 | No inlined contract block (DDL / JSON schema / TS enum / OpenAPI) — an AI cannot generate code from prose alone. |
| `G-AC-01` | testability | 20 | 0 | 13 | 0 | Zero acceptance criteria → nothing is objectively verifiable. |
| `G-CON-02` | implementability | 30 | 0 | 2 | 0 | Overview <500 chars is a stub; no AI can implement from this. |
| `G-LINK-02` | alignment | 60 | 0 | 2 | 0 | ≥3 broken links suggests structural drift; alignment with the wider spec tree is unreliable. |

## Dimension-loss totals

| Dimension | Total points lost to gates |
|---|---:|
| consistency | 850 |
| testability | 70 |
| completeness | 5 |

## Per-module gate detail (most-capped first)

| Module | Raw | Final | Δ | Active gates |
|---|---:|---:|---:|---|
| `02-coding-guidelines` | 82 | 79 | −3 | `G-AC-02`, `G-LINK-01` |
| `02-coding-guidelines/01-cross-language` | 87 | 84 | −3 | `G-LINK-01` |
| `02-coding-guidelines/01-cross-language/04-code-style` | 75 | 72 | −3 | `G-LINK-01`, `G-TODO-01` |
| `02-coding-guidelines/03-golang/04-golang-standards-reference` | 75 | 72 | −3 | `G-LINK-01` |
| `02-coding-guidelines/10-research` | 66 | 63 | −3 | `G-LINK-01` |
| `02-coding-guidelines/22-app-issues` | 66 | 63 | −3 | `G-LINK-01` |
| `03-error-manage/03-error-code-registry/09-templates` | 71 | 68 | −3 | `G-LINK-01` |
| `02-coding-guidelines/01-cross-language/02-boolean-principles` | 76 | 74 | −2 | `G-LINK-01` |
| `02-coding-guidelines/01-cross-language/15-master-coding-guidelines` | 76 | 74 | −2 | `G-LINK-01` |
| `02-coding-guidelines/04-php/07-php-standards-reference` | 83 | 81 | −2 | `G-LINK-01` |
| `02-coding-guidelines/06-cicd-integration` | 87 | 85 | −2 | `G-AC-02` |
| `02-coding-guidelines/07-csharp` | 73 | 71 | −2 | `G-AC-02` |
| `02-coding-guidelines/08-file-folder-naming` | 71 | 69 | −2 | `G-LINK-01` |
| `02-coding-guidelines/21-app` | 67 | 65 | −2 | `G-LINK-01` |
| `03-error-manage/01-error-resolution` | 76 | 74 | −2 | `G-LINK-01` |
| `03-error-manage/01-error-resolution/03-retrospectives` | 81 | 79 | −2 | `G-LINK-01` |
| `03-error-manage/01-error-resolution/04-verification-patterns` | 82 | 80 | −2 | `G-LINK-01` |
| `03-error-manage/01-error-resolution/05-debugging-guides` | 76 | 74 | −2 | `G-LINK-01` |
| `03-error-manage/01-error-resolution/app-issues` | 75 | 73 | −2 | `G-LINK-01` |
| `03-error-manage/02-error-architecture` | 82 | 80 | −2 | `G-LINK-01` |
| `03-error-manage/02-error-architecture/04-error-modal` | 83 | 81 | −2 | `G-LINK-01` |
| `03-error-manage/02-error-architecture/04-error-modal/03-error-modal-reference` | 83 | 81 | −2 | `G-LINK-01` |
| `03-error-manage/02-error-architecture/05-response-envelope` | 77 | 75 | −2 | `G-LINK-01` |
| `03-error-manage/02-error-architecture/06-apperror-package` | 71 | 69 | −2 | `G-LINK-01` |
| `03-error-manage/02-error-architecture/07-logging-and-diagnostics` | 81 | 79 | −2 | `G-LINK-01` |
| `03-error-manage/03-error-code-registry` | 82 | 80 | −2 | `G-LINK-01` |
| `03-error-manage/03-error-code-registry/07-schemas` | 65 | 63 | −2 | `G-LINK-01` |
| `03-error-manage/03-error-code-registry/08-linter-scripts` | 65 | 63 | −2 | `G-LINK-01` |
| `05-split-db-architecture/02-features` | 81 | 79 | −2 | `G-LINK-01` |
| `05-split-db-architecture/03-issues` | 56 | 54 | −2 | `G-LINK-01` |
| `06-seedable-config-architecture/02-features` | 80 | 78 | −2 | `G-LINK-01` |
| `06-seedable-config-architecture/03-issues` | 56 | 54 | −2 | `G-LINK-01` |
| `12-cicd-pipeline-workflows/01-browser-extension-deploy` | 72 | 70 | −2 | `G-LINK-01` |
| `12-cicd-pipeline-workflows/02-go-binary-deploy` | 74 | 72 | −2 | `G-LINK-01` |
| `12-cicd-pipeline-workflows/03-reusable-ci-guards` | 80 | 78 | −2 | `G-LINK-01` |
| `13-generic-cli` | 83 | 81 | −2 | `G-LINK-01` |
| `14-update/diagrams` | 67 | 65 | −2 | `G-LINK-01` |
| `15-distribution-and-runner` | 80 | 78 | −2 | `G-LINK-01` |
| `17-consolidated-guidelines` | 86 | 84 | −2 | `G-LINK-01` |
| `18-wp-plugin-how-to/02-enums-and-coding-style` | 71 | 69 | −2 | `G-LINK-01` |
| `25-app-issues/01-phase-2-git-logs-audit` | 66 | 64 | −2 | `G-LINK-01` |
| `02-coding-guidelines/05-rust` | 80 | 79 | −1 | `G-AC-02` |
| `.` | 59 | 59 | −0 | _none_ |
| `01-spec-authoring-guide` | 54 | 54 | −0 | `G-AC-02` |
| `02-coding-guidelines/01-cross-language/16-static-analysis` | 64 | 64 | −0 | _none_ |
| `02-coding-guidelines/02-typescript` | 70 | 70 | −0 | _none_ |
| `02-coding-guidelines/03-golang` | 68 | 68 | −0 | _none_ |
| `02-coding-guidelines/03-golang/01-enum-specification` | 63 | 63 | −0 | _none_ |
| `02-coding-guidelines/04-php` | 79 | 79 | −0 | _none_ |
| `02-coding-guidelines/06-ai-optimization` | 69 | 69 | −0 | _none_ |
| `02-coding-guidelines/09-powershell-integration` | 68 | 68 | −0 | _none_ |
| `02-coding-guidelines/11-security` | 73 | 73 | −0 | _none_ |
| `02-coding-guidelines/11-security/01-axios-version-control` | 71 | 71 | −0 | _none_ |
| `02-coding-guidelines/23-app-database` | 67 | 67 | −0 | _none_ |
| `02-coding-guidelines/24-app-design-system-and-ui` | 67 | 67 | −0 | _none_ |
| `03-error-manage` | 81 | 81 | −0 | _none_ |
| `03-error-manage/02-error-architecture/04-error-modal/01-copy-formats` | 71 | 71 | −0 | _none_ |
| `03-error-manage/02-error-architecture/04-error-modal/02-react-components` | 66 | 66 | −0 | _none_ |
| `03-error-manage/02-error-architecture/04-error-modal/04-color-themes` | 64 | 64 | −0 | _none_ |
| `03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference` | 66 | 66 | −0 | _none_ |
| `04-database-conventions` | 89 | 89 | −0 | _none_ |
| `05-split-db-architecture` | 82 | 82 | −0 | _none_ |
| `06-seedable-config-architecture` | 85 | 85 | −0 | _none_ |
| `07-design-system` | 71 | 71 | −0 | _none_ |
| `10-research` | 70 | 70 | −0 | _none_ |
| `11-powershell-integration` | 82 | 82 | −0 | _none_ |
| `12-cicd-pipeline-workflows` | 88 | 88 | −0 | _none_ |
| `14-update` | 84 | 84 | −0 | _none_ |
| `14-update/24-update-check-mechanism` | 75 | 75 | −0 | _none_ |
| `16-generic-release` | 82 | 82 | −0 | _none_ |
| `18-wp-plugin-how-to` | 95 | 95 | −0 | _none_ |
| `22-git-logs-v2` | 76 | 76 | −0 | _none_ |
| `23-app-database` | 71 | 71 | −0 | _none_ |
| `24-app-design-system-and-ui` | 69 | 69 | −0 | _none_ |
| `25-app-issues` | 72 | 72 | −0 | _none_ |
| `25-app-issues/02-consolidated-audit-findings` | 59 | 59 | −0 | _none_ |
| `26-gitlogs-diagrams` | 59 | 59 | −0 | _none_ |
| `27-spec-toolchain` | 73 | 73 | −0 | _none_ |

## Per-module breakdown

### `spec/02-coding-guidelines` — raw 82 → final 79 (Δ −3)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 70 | **70** |  |
| completeness | 85 | **85** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 70 | **60** ⬇ | `G-AC-02` |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-AC-02` capped `testability` from 70 → 60: ACs exist but none use Given/When/Then — testability degraded.
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/02-coding-guidelines/01-cross-language` — raw 87 → final 84 (Δ −3)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 95 | **95** |  |
| completeness | 70 | **70** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 52 | **52** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-AC-02` (testability cap=60, current=52)

### `spec/02-coding-guidelines/01-cross-language/04-code-style` — raw 75 → final 72 (Δ −3)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 75 | **70** ⬇ | `G-TODO-01` |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 90 | **90** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.
- `G-TODO-01` capped `completeness` from 75 → 70: ≥3 TODO/TBD/FIXME markers — module is explicitly incomplete.

### `spec/02-coding-guidelines/03-golang/04-golang-standards-reference` — raw 75 → final 72 (Δ −3)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 90 | **90** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=40)

### `spec/02-coding-guidelines/10-research` — raw 66 → final 63 (Δ −3)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 65 | **65** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/02-coding-guidelines/22-app-issues` — raw 66 → final 63 (Δ −3)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 65 | **65** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/03-error-manage/03-error-code-registry/09-templates` — raw 71 → final 68 (Δ −3)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 70 | **70** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/02-coding-guidelines/01-cross-language/02-boolean-principles` — raw 76 → final 74 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 90 | **90** |  |
| alignment | 80 | **80** |  |
| consistency | 84 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 84 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/02-coding-guidelines/01-cross-language/15-master-coding-guidelines` — raw 76 → final 74 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 80 | **80** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/02-coding-guidelines/04-php/07-php-standards-reference` — raw 83 → final 81 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 90 | **90** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/02-coding-guidelines/06-cicd-integration` — raw 87 → final 85 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 75 | **75** |  |
| completeness | 85 | **85** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 80 | **60** ⬇ | `G-AC-02` |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-AC-02` capped `testability` from 80 → 60: ACs exist but none use Given/When/Then — testability degraded.

### `spec/02-coding-guidelines/07-csharp` — raw 73 → final 71 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 75 | **75** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 80 | **60** ⬇ | `G-AC-02` |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-AC-02` capped `testability` from 80 → 60: ACs exist but none use Given/When/Then — testability degraded.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=40)

### `spec/02-coding-guidelines/08-file-folder-naming` — raw 71 → final 69 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 75 | **75** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=40)

### `spec/02-coding-guidelines/21-app` — raw 67 → final 65 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 70 | **70** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 96 | **96** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/03-error-manage/01-error-resolution` — raw 76 → final 74 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 85 | **85** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/01-error-resolution/03-retrospectives` — raw 81 → final 79 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 80 | **80** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/01-error-resolution/04-verification-patterns` — raw 82 → final 80 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 75 | **75** |  |
| completeness | 65 | **65** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/01-error-resolution/05-debugging-guides` — raw 76 → final 74 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 80 | **80** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/01-error-resolution/app-issues` — raw 75 → final 73 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 75 | **75** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/02-error-architecture` — raw 82 → final 80 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 85 | **85** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/02-error-architecture/04-error-modal` — raw 83 → final 81 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 90 | **90** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/02-error-architecture/04-error-modal/03-error-modal-reference` — raw 83 → final 81 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 90 | **90** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/02-error-architecture/05-response-envelope` — raw 77 → final 75 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 55 | **55** |  |
| completeness | 75 | **75** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/02-error-architecture/06-apperror-package` — raw 71 → final 69 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 90 | **90** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/03-error-manage/02-error-architecture/07-logging-and-diagnostics` — raw 81 → final 79 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 80 | **80** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/03-error-code-registry` — raw 82 → final 80 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 85 | **85** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/03-error-manage/03-error-code-registry/07-schemas` — raw 65 → final 63 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 60 | **60** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 96 | **96** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/03-error-manage/03-error-code-registry/08-linter-scripts` — raw 65 → final 63 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 60 | **60** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 96 | **96** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/05-split-db-architecture/02-features` — raw 81 → final 79 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 75 | **75** |  |
| completeness | 65 | **65** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/05-split-db-architecture/03-issues` — raw 56 → final 54 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 10 | **10** |  |
| completeness | 55 | **55** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=10)
- `G-CON-02` (implementability cap=30, current=10)

### `spec/06-seedable-config-architecture/02-features` — raw 80 → final 78 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 75 | **75** |  |
| completeness | 60 | **60** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 90 | **90** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/06-seedable-config-architecture/03-issues` — raw 56 → final 54 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 10 | **10** |  |
| completeness | 55 | **55** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=10)
- `G-CON-02` (implementability cap=30, current=10)

### `spec/12-cicd-pipeline-workflows/01-browser-extension-deploy` — raw 72 → final 70 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 65 | **65** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/12-cicd-pipeline-workflows/02-go-binary-deploy` — raw 74 → final 72 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 75 | **75** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/12-cicd-pipeline-workflows/03-reusable-ci-guards` — raw 80 → final 78 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 75 | **75** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/13-generic-cli` — raw 83 → final 81 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 75 | **75** |  |
| completeness | 75 | **75** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/14-update/diagrams` — raw 67 → final 65 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 35 | **35** |  |
| completeness | 65 | **65** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=35)

### `spec/15-distribution-and-runner` — raw 80 → final 78 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 75 | **75** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

### `spec/17-consolidated-guidelines` — raw 86 → final 84 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 95 | **95** |  |
| completeness | 55 | **55** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 90 | **90** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-TODO-01` (completeness cap=70, current=55)

### `spec/18-wp-plugin-how-to/02-enums-and-coding-style` — raw 71 → final 69 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 75 | **75** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=40)

### `spec/25-app-issues/01-phase-2-git-logs-audit` — raw 66 → final 64 (Δ −2)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 70 | **70** |  |
| alignment | 90 | **90** |  |
| consistency | 92 | **70** ⬇ | `G-LINK-01` |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 90 | **90** |  |

**Active gates fired (this module):**
- `G-LINK-01` capped `consistency` from 92 → 70: Any broken cross-spec link caps consistency at 70 — readers cannot trust references.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/02-coding-guidelines/05-rust` — raw 80 → final 79 (Δ −1)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 60 | **60** |  |
| completeness | 80 | **80** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 76 | **60** ⬇ | `G-AC-02` |
| maintainability | 100 | **100** |  |

**Active gates fired (this module):**
- `G-AC-02` capped `testability` from 76 → 60: ACs exist but none use Given/When/Then — testability degraded.

### `spec/.` — raw 59 → final 59 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 50 | **50** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)
- `G-CON-01` (implementability cap=50, current=30)

### `spec/01-spec-authoring-guide` — raw 54 → final 54 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 60 | **60** |  |
| alignment | 40 | **40** |  |
| consistency | 50 | **50** |  |
| clarity | 100 | **100** |  |
| testability | 64 | **60** ⬇ | `G-AC-02` |
| maintainability | 90 | **90** |  |

**Active gates fired (this module):**
- `G-AC-02` capped `testability` from 64 → 60: ACs exist but none use Given/When/Then — testability degraded.

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=40)
- `G-LINK-01` (consistency cap=70, current=50)
- `G-LINK-02` (alignment cap=60, current=40)

### `spec/02-coding-guidelines/01-cross-language/16-static-analysis` — raw 64 → final 64 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 40 | **40** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 90 | **90** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)

### `spec/02-coding-guidelines/02-typescript` — raw 70 → final 70 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 55 | **55** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 52 | **52** |  |
| maintainability | 90 | **90** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-02` (testability cap=60, current=52)

### `spec/02-coding-guidelines/03-golang` — raw 68 → final 68 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 60 | **60** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 52 | **52** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-02` (testability cap=60, current=52)
- `G-CON-01` (implementability cap=50, current=40)

### `spec/02-coding-guidelines/03-golang/01-enum-specification` — raw 63 → final 63 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 50 | **50** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)
- `G-CON-01` (implementability cap=50, current=40)

### `spec/02-coding-guidelines/04-php` — raw 79 → final 79 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 100 | **100** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=40)

### `spec/02-coding-guidelines/06-ai-optimization` — raw 69 → final 69 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 40 | **40** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)

### `spec/02-coding-guidelines/09-powershell-integration` — raw 68 → final 68 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 65 | **65** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/02-coding-guidelines/11-security` — raw 73 → final 73 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 90 | **90** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 96 | **96** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/02-coding-guidelines/11-security/01-axios-version-control` — raw 71 → final 71 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 50 | **50** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)

### `spec/02-coding-guidelines/23-app-database` — raw 67 → final 67 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 60 | **60** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 96 | **96** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/02-coding-guidelines/24-app-design-system-and-ui` — raw 67 → final 67 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 60 | **60** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 96 | **96** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/03-error-manage/02-error-architecture/04-error-modal/01-copy-formats` — raw 71 → final 71 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 50 | **50** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)

### `spec/03-error-manage/02-error-architecture/04-error-modal/02-react-components` — raw 66 → final 66 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 50 | **50** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)

### `spec/03-error-manage/02-error-architecture/04-error-modal/04-color-themes` — raw 64 → final 64 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 50 | **50** |  |
| completeness | 40 | **40** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)

### `spec/03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference` — raw 66 → final 66 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 55 | **55** |  |
| completeness | 40 | **40** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)

### `spec/05-split-db-architecture` — raw 82 → final 82 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 75 | **75** |  |
| completeness | 70 | **70** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 52 | **52** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-02` (testability cap=60, current=52)

### `spec/06-seedable-config-architecture` — raw 85 → final 85 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 85 | **85** |  |
| completeness | 70 | **70** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 52 | **52** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-02` (testability cap=60, current=52)

### `spec/07-design-system` — raw 71 → final 71 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 65 | **65** |  |
| completeness | 50 | **50** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)

### `spec/10-research` — raw 70 → final 70 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 75 | **75** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/14-update/24-update-check-mechanism` — raw 75 → final 75 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 75 | **75** |  |
| completeness | 50 | **50** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)

### `spec/22-git-logs-v2` — raw 76 → final 76 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 85 | **85** |  |
| completeness | 40 | **40** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 90 | **90** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)

### `spec/23-app-database` — raw 71 → final 71 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 80 | **80** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 96 | **96** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/24-app-design-system-and-ui` — raw 69 → final 69 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 70 | **70** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 96 | **96** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/25-app-issues` — raw 72 → final 72 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 30 | **30** |  |
| completeness | 85 | **85** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=30)

### `spec/25-app-issues/02-consolidated-audit-findings` — raw 59 → final 59 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 75 | **75** |  |
| alignment | 40 | **40** |  |
| consistency | 50 | **50** |  |
| clarity | 100 | **100** |  |
| testability | 90 | **90** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=40)
- `G-LINK-01` (consistency cap=70, current=50)
- `G-LINK-02` (alignment cap=60, current=40)

### `spec/26-gitlogs-diagrams` — raw 59 → final 59 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 35 | **35** |  |
| completeness | 40 | **40** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 10 | **10** |  |
| maintainability | 100 | **100** |  |

**Passive gates (already at/below cap, no score change):**
- `G-AC-01` (testability cap=20, current=10)
- `G-CON-01` (implementability cap=50, current=35)

### `spec/27-spec-toolchain` — raw 73 → final 73 (Δ −0)

| Dimension | Raw | Final | Capped by |
|---|---:|---:|---|
| implementability | 40 | **40** |  |
| completeness | 70 | **70** |  |
| alignment | 100 | **100** |  |
| consistency | 100 | **100** |  |
| clarity | 100 | **100** |  |
| testability | 100 | **100** |  |
| maintainability | 90 | **90** |  |

**Passive gates (already at/below cap, no score change):**
- `G-CON-01` (implementability cap=50, current=40)
- `G-TODO-01` (completeness cap=70, current=70)
