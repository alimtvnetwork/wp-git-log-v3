# Spec-vs-Code Audit **v2** — Summary

**Date:** 2026-04-25  
**Modules audited:** 77  
**Code files indexed:** 26  
**Mean weighted score:** **61.5/100**  
**Mean implementability:** **47.3/100**

## Methodology v2

Weights: implementability=35%, completeness=20%, alignment=15%, consistency=10%, clarity=10%, testability=7%, maintainability=3%.
Implementability = can a mediocre AI ship from spec alone, no human help.
Deterministic metrics (waffle ratio, contract presence, broken links, GWT count) are computed before AI scoring and bound the AI's grades.

## Grade distribution
**A** = 2, **B** = 4, **C** = 35, **D** = 36

## Findings by category
| Category | Count |
|---|---:|
| missing-contract | 141 |
| broken-link | 41 |
| missing-spec | 32 |
| untestable | 19 |
| ambiguity | 13 |
| drift | 13 |
| inconsistency | 5 |

## Findings by severity
| Severity | Count |
|---|---:|
| critical | 39 |
| high | 103 |
| medium | 103 |
| low | 19 |

## 🎯 High blast-radius fixes (fix these FIRST)
| Rank | Module | Score | Grade | Blast | Top blocker |
|---:|---|---:|:-:|:-:|---|
| 1 | [`.`](./..md) | 51 | D | 10 | No SQL DDL provided for database specs. |
| 2 | [`17-consolidated-guidelines`](./17-consolidated-guidelines.md) | 65 | C | 10 | No inlined contracts (DDL, JSON schema, Protobuf, etc.) despite claiming their e |
| 3 | [`03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference`](./03-error-manage__02-error-architecture__06-apperror-package__01-apperror-reference.md) | 43 | D | 9 | No explicit SQL DDL for database specs, requiring inference. |
| 4 | [`14-update`](./14-update.md) | 82 | B | 9 | Lack of inlined code contracts (e.g., Go structs for specified JSON schemas, det |
| 5 | [`03-error-manage/02-error-architecture/04-error-modal/01-copy-formats`](./03-error-manage__02-error-architecture__04-error-modal__01-copy-formats.md) | 47 | D | 8 | No explicit contracts (schemas, enums) are inlined in the overview for any of th |
| 6 | [`02-coding-guidelines`](./02-coding-guidelines.md) | 50 | D | 8 | No inlined DDL for database conventions (requires external lookup). |
| 7 | [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 52 | D | 8 | Lack of inline, actionable DDL/schemas/code for direct implementation of the gui |
| 8 | [`02-coding-guidelines/03-golang/04-golang-standards-reference`](./02-coding-guidelines__03-golang__04-golang-standards-reference.md) | 53 | D | 8 | Full Go enum definition (code) for AC-09 is missing. |
| 9 | [`02-coding-guidelines/04-php`](./02-coding-guidelines__04-php.md) | 54 | D | 8 | No DDL for enums (only method signature) |
| 10 | [`22-git-logs-v2`](./22-git-logs-v2.md) | 54 | D | 8 | Missing enum definitions in a machine-readable format (e.g., TypeScript or equiv |
| 11 | [`03-error-manage/03-error-code-registry/09-templates`](./03-error-manage__03-error-code-registry__09-templates.md) | 55 | D | 8 | Missing formal schema (e.g., JSON schema, OpenAPI) for error code structure and  |
| 12 | [`01-spec-authoring-guide`](./01-spec-authoring-guide.md) | 56 | D | 8 | Lack of explicit schema or contracts for the overview metadata block, e.g., 'Ver |
| 13 | [`02-coding-guidelines/07-csharp`](./02-coding-guidelines__07-csharp.md) | 56 | D | 8 | No concrete C# code examples provided within the spec for the AI to learn from. |
| 14 | [`13-generic-cli`](./13-generic-cli.md) | 56 | D | 8 | No executable code or contracts provided for the generic CLI, only guidelines an |
| 15 | [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 57 | D | 8 | Go structs for various config objects are referenced but not inlined, requiring  |

## Bottom 15 (lowest implementability)
| Rank | Module | Overall | Impl | Grade | Top finding |
|---:|---|---:|---:|:-:|---|
| 1 | [`03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference`](./03-error-manage__02-error-architecture__06-apperror-package__01-apperror-reference.md) | 43 | 30 | D | Missing concrete Go struct definitions for AppError and related types. |
| 2 | [`03-error-manage/02-error-architecture/04-error-modal/01-copy-formats`](./03-error-manage__02-error-architecture__04-error-modal__01-copy-formats.md) | 47 | 30 | D | No inlined contracts for data structures and API endpoints. |
| 3 | [`02-coding-guidelines/06-ai-optimization`](./02-coding-guidelines__06-ai-optimization.md) | 49 | 30 | D | The spec provides coding guidelines but lacks machine-executable contracts (e.g. |
| 4 | [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 49 | 30 | D | The spec describes a set of diagrams but does not include the mermaid code for t |
| 5 | [`02-coding-guidelines/09-powershell-integration`](./02-coding-guidelines__09-powershell-integration.md) | 50 | 30 | D | The overview explicitly states 'No content yet.' making the spec effectively a p |
| 6 | [`02-coding-guidelines/21-app`](./02-coding-guidelines__21-app.md) | 50 | 30 | D | The spec provides guidelines for placing app-specific content but lacks concrete |
| 7 | [`06-seedable-config-architecture/02-features`](./06-seedable-config-architecture__02-features.md) | 50 | 30 | D | This index module itself does not contain any inlined contracts (DDL, JSON schem |
| 8 | [`.`](./..md) | 51 | 30 | D | Absence of acceptance criteria (AC) |
| 9 | [`02-coding-guidelines/04-php`](./02-coding-guidelines__04-php.md) | 54 | 30 | D | Missing concrete PHP code for enums and ResultHelper. |
| 10 | [`06-seedable-config-architecture/03-issues`](./06-seedable-config-architecture__03-issues.md) | 55 | 30 | D | One broken link detected in the Acceptance Criteria. |
| 11 | [`02-coding-guidelines/07-csharp`](./02-coding-guidelines__07-csharp.md) | 56 | 30 | D | The spec provides coding guidelines but lacks concrete, inlined C# code examples |
| 12 | [`25-app-issues/01-phase-2-git-logs-audit`](./25-app-issues__01-phase-2-git-logs-audit.md) | 57 | 30 | D | One broken link detected in the spec. |
| 13 | [`02-coding-guidelines/10-research`](./02-coding-guidelines__10-research.md) | 63 | 30 | C | `[../00-overview.md](../00-overview.md)` in the overview file is broken. |
| 14 | [`02-coding-guidelines/23-app-database`](./02-coding-guidelines__23-app-database.md) | 63 | 30 | C | No SQL DDL is provided for any database tables or structures. |
| 15 | [`10-research`](./10-research.md) | 64 | 30 | C | The spec states 'No research documents added yet.', indicating a lack of actual  |

## Top 10 (gold standards)
| Rank | Module | Overall | Impl | Grade |
|---:|---|---:|---:|:-:|
| 1 | [`18-wp-plugin-how-to`](./18-wp-plugin-how-to.md) | 90 | 85 | A |
| 2 | [`02-coding-guidelines/11-security`](./02-coding-guidelines__11-security.md) | 87 | 80 | A |
| 3 | [`14-update`](./14-update.md) | 82 | 80 | B |
| 4 | [`04-database-conventions`](./04-database-conventions.md) | 80 | 85 | B |
| 5 | [`02-coding-guidelines/06-cicd-integration`](./02-coding-guidelines__06-cicd-integration.md) | 79 | 70 | B |
| 6 | [`16-generic-release`](./16-generic-release.md) | 76 | 80 | B |
| 7 | [`03-error-manage`](./03-error-manage.md) | 74 | 70 | C |
| 8 | [`03-error-manage/02-error-architecture/05-response-envelope`](./03-error-manage__02-error-architecture__05-response-envelope.md) | 74 | 80 | C |
| 9 | [`02-coding-guidelines/05-rust`](./02-coding-guidelines__05-rust.md) | 73 | 80 | C |
| 10 | [`03-error-manage/01-error-resolution/app-issues`](./03-error-manage__01-error-resolution__app-issues.md) | 72 | 80 | C |

## Full ranking
| Module | Impl | Comp | Align | Cons | Clar | Test | Maint | **Overall** | Grade | Blast |
|---|---:|---:|---:|---:|---:|---:|---:|---:|:-:|:-:|
| [`03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference`](./03-error-manage__02-error-architecture__06-apperror-package__01-apperror-reference.md) | 30 | 60 | 0 | 100 | 60 | 20 | 100 | **43** | D | 9 |
| [`03-error-manage/01-error-resolution`](./03-error-manage__01-error-resolution.md) | 40 | 60 | 5 | 70 | 90 | 20 | 80 | **47** | D | 6 |
| [`03-error-manage/02-error-architecture/04-error-modal/01-copy-formats`](./03-error-manage__02-error-architecture__04-error-modal__01-copy-formats.md) | 30 | 60 | 0 | 100 | 100 | 20 | 100 | **47** | D | 8 |
| [`03-error-manage/02-error-architecture`](./03-error-manage__02-error-architecture.md) | 40 | 80 | 5 | 70 | 70 | 20 | 50 | **48** | D | 7 |
| [`02-coding-guidelines/06-ai-optimization`](./02-coding-guidelines__06-ai-optimization.md) | 30 | 80 | 0 | 100 | 90 | 20 | 70 | **49** | D | 7 |
| [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 30 | 70 | 0 | 100 | 100 | 20 | 100 | **49** | D | 6 |
| [`02-coding-guidelines`](./02-coding-guidelines.md) | 40 | 80 | 0 | 70 | 90 | 20 | 80 | **50** | D | 8 |
| [`02-coding-guidelines/09-powershell-integration`](./02-coding-guidelines__09-powershell-integration.md) | 30 | 60 | 0 | 100 | 100 | 70 | 100 | **50** | D | 6 |
| [`02-coding-guidelines/21-app`](./02-coding-guidelines__21-app.md) | 30 | 60 | 20 | 70 | 100 | 70 | 100 | **50** | D | 6 |
| [`03-error-manage/02-error-architecture/04-error-modal/02-react-components`](./03-error-manage__02-error-architecture__04-error-modal__02-react-components.md) | 40 | 60 | 0 | 100 | 100 | 20 | 100 | **50** | D | 5 |
| [`03-error-manage/02-error-architecture/04-error-modal/04-color-themes`](./03-error-manage__02-error-architecture__04-error-modal__04-color-themes.md) | 40 | 60 | 0 | 100 | 100 | 20 | 100 | **50** | D | 5 |
| [`06-seedable-config-architecture/02-features`](./06-seedable-config-architecture__02-features.md) | 30 | 60 | 40 | 70 | 100 | 20 | 100 | **50** | D | 5 |
| [`.`](./..md) | 30 | 60 | 50 | 70 | 100 | 20 | 100 | **51** | D | 10 |
| [`02-coding-guidelines/03-golang`](./02-coding-guidelines__03-golang.md) | 40 | 60 | 5 | 100 | 100 | 20 | 100 | **51** | D | 7 |
| [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 45 | 80 | 20 | 60 | 70 | 20 | 80 | **52** | D | 8 |
| [`02-coding-guidelines/03-golang/01-enum-specification`](./02-coding-guidelines__03-golang__01-enum-specification.md) | 45 | 60 | 0 | 100 | 100 | 20 | 100 | **52** | D | 6 |
| [`02-coding-guidelines/03-golang/04-golang-standards-reference`](./02-coding-guidelines__03-golang__04-golang-standards-reference.md) | 40 | 80 | 0 | 70 | 90 | 70 | 80 | **53** | D | 8 |
| [`03-error-manage/01-error-resolution/05-debugging-guides`](./03-error-manage__01-error-resolution__05-debugging-guides.md) | 40 | 80 | 0 | 70 | 90 | 70 | 80 | **53** | D | 5 |
| [`02-coding-guidelines/04-php`](./02-coding-guidelines__04-php.md) | 30 | 80 | 0 | 100 | 100 | 70 | 100 | **54** | D | 8 |
| [`22-git-logs-v2`](./22-git-logs-v2.md) | 45 | 80 | 0 | 100 | 90 | 20 | 70 | **54** | D | 8 |
| [`24-app-design-system-and-ui`](./24-app-design-system-and-ui.md) | 40 | 60 | 0 | 100 | 100 | 70 | 100 | **54** | D | 5 |
| [`03-error-manage/03-error-code-registry/09-templates`](./03-error-manage__03-error-code-registry__09-templates.md) | 40 | 80 | 0 | 70 | 100 | 70 | 100 | **55** | D | 8 |
| [`06-seedable-config-architecture/03-issues`](./06-seedable-config-architecture__03-issues.md) | 30 | 80 | 50 | 70 | 100 | 20 | 100 | **55** | D | 6 |
| [`14-update/24-update-check-mechanism`](./14-update__24-update-check-mechanism.md) | 45 | 80 | 0 | 100 | 90 | 20 | 100 | **55** | D | 5 |
| [`01-spec-authoring-guide`](./01-spec-authoring-guide.md) | 40 | 60 | 80 | 70 | 70 | 20 | 100 | **56** | D | 8 |
| [`02-coding-guidelines/07-csharp`](./02-coding-guidelines__07-csharp.md) | 30 | 90 | 0 | 100 | 100 | 70 | 100 | **56** | D | 8 |
| [`03-error-manage/01-error-resolution/03-retrospectives`](./03-error-manage__01-error-resolution__03-retrospectives.md) | 40 | 80 | 20 | 70 | 90 | 70 | 80 | **56** | D | 5 |
| [`03-error-manage/02-error-architecture/06-apperror-package`](./03-error-manage__02-error-architecture__06-apperror-package.md) | 40 | 80 | 0 | 70 | 100 | 80 | 100 | **56** | D | 7 |
| [`13-generic-cli`](./13-generic-cli.md) | 45 | 80 | 0 | 70 | 90 | 80 | 90 | **56** | D | 8 |
| [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 45 | 70 | 20 | 100 | 100 | 20 | 80 | **57** | D | 8 |
| [`12-cicd-pipeline-workflows/03-reusable-ci-guards`](./12-cicd-pipeline-workflows__03-reusable-ci-guards.md) | 40 | 80 | 20 | 70 | 90 | 70 | 90 | **57** | D | 8 |
| [`25-app-issues/01-phase-2-git-logs-audit`](./25-app-issues__01-phase-2-git-logs-audit.md) | 30 | 60 | 90 | 70 | 100 | 20 | 80 | **57** | D | 8 |
| [`02-coding-guidelines/01-cross-language/15-master-coding-guidelines`](./02-coding-guidelines__01-cross-language__15-master-coding-guidelines.md) | 40 | 80 | 50 | 70 | 100 | 20 | 100 | **59** | D | 7 |
| [`02-coding-guidelines/04-php/07-php-standards-reference`](./02-coding-guidelines__04-php__07-php-standards-reference.md) | 45 | 85 | 20 | 70 | 90 | 70 | 80 | **59** | D | 7 |
| [`03-error-manage/02-error-architecture/07-logging-and-diagnostics`](./03-error-manage__02-error-architecture__07-logging-and-diagnostics.md) | 45 | 80 | 20 | 70 | 90 | 80 | 100 | **59** | D | 8 |
| [`05-split-db-architecture/02-features`](./05-split-db-architecture__02-features.md) | 50 | 80 | 0 | 70 | 100 | 80 | 100 | **59** | D | 6 |
| [`03-error-manage/03-error-code-registry`](./03-error-manage__03-error-code-registry.md) | 45 | 60 | 90 | 70 | 90 | 20 | 70 | **61** | C | 8 |
| [`02-coding-guidelines/01-cross-language/16-static-analysis`](./02-coding-guidelines__01-cross-language__16-static-analysis.md) | 40 | 80 | 60 | 100 | 90 | 20 | 80 | **62** | C | 8 |
| [`02-coding-guidelines/10-research`](./02-coding-guidelines__10-research.md) | 30 | 80 | 90 | 70 | 100 | 40 | 100 | **63** | C | 0 |
| [`02-coding-guidelines/23-app-database`](./02-coding-guidelines__23-app-database.md) | 30 | 50 | 100 | 100 | 100 | 70 | 100 | **63** | C | 5 |
| [`02-coding-guidelines/08-file-folder-naming`](./02-coding-guidelines__08-file-folder-naming.md) | 40 | 80 | 60 | 70 | 100 | 70 | 100 | **64** | C | 8 |
| [`05-split-db-architecture`](./05-split-db-architecture.md) | 50 | 80 | 20 | 100 | 100 | 70 | 100 | **64** | C | 8 |
| [`10-research`](./10-research.md) | 30 | 60 | 90 | 100 | 100 | 70 | 100 | **64** | C | 5 |
| [`12-cicd-pipeline-workflows/01-browser-extension-deploy`](./12-cicd-pipeline-workflows__01-browser-extension-deploy.md) | 40 | 80 | 60 | 70 | 90 | 80 | 100 | **64** | C | 5 |
| [`02-coding-guidelines/01-cross-language/02-boolean-principles`](./02-coding-guidelines__01-cross-language__02-boolean-principles.md) | 45 | 80 | 60 | 70 | 90 | 80 | 75 | **65** | C | 7 |
| [`02-coding-guidelines/02-typescript`](./02-coding-guidelines__02-typescript.md) | 70 | 80 | 5 | 100 | 90 | 20 | 100 | **65** | C | 8 |
| [`02-coding-guidelines/22-app-issues`](./02-coding-guidelines__22-app-issues.md) | 30 | 80 | 90 | 70 | 100 | 70 | 100 | **65** | C | 0 |
| [`07-design-system`](./07-design-system.md) | 70 | 90 | 0 | 100 | 90 | 20 | 80 | **65** | C | 8 |
| [`14-update/diagrams`](./14-update__diagrams.md) | 30 | 80 | 90 | 70 | 100 | 75 | 90 | **65** | C | 5 |
| [`17-consolidated-guidelines`](./17-consolidated-guidelines.md) | 40 | 90 | 60 | 70 | 90 | 70 | 90 | **65** | C | 10 |
| [`25-app-issues/02-consolidated-audit-findings`](./25-app-issues__02-consolidated-audit-findings.md) | 40 | 60 | 90 | 70 | 100 | 80 | 90 | **65** | C | 7 |
| [`02-coding-guidelines/11-security/01-axios-version-control`](./02-coding-guidelines__11-security__01-axios-version-control.md) | 40 | 80 | 90 | 100 | 90 | 20 | 80 | **66** | C | 5 |
| [`03-error-manage/03-error-code-registry/08-linter-scripts`](./03-error-manage__03-error-code-registry__08-linter-scripts.md) | 45 | 80 | 60 | 70 | 100 | 80 | 100 | **66** | C | 6 |
| [`05-split-db-architecture/03-issues`](./05-split-db-architecture__03-issues.md) | 40 | 80 | 90 | 70 | 100 | 40 | 100 | **66** | C | 5 |
| [`12-cicd-pipeline-workflows`](./12-cicd-pipeline-workflows.md) | 40 | 80 | 60 | 100 | 90 | 80 | 90 | **66** | C | 7 |
| [`12-cicd-pipeline-workflows/02-go-binary-deploy`](./12-cicd-pipeline-workflows__02-go-binary-deploy.md) | 45 | 80 | 60 | 70 | 100 | 80 | 90 | **66** | C | 8 |
| [`18-wp-plugin-how-to/02-enums-and-coding-style`](./18-wp-plugin-how-to__02-enums-and-coding-style.md) | 70 | 90 | 0 | 70 | 90 | 70 | 100 | **66** | C | 6 |
| [`23-app-database`](./23-app-database.md) | 40 | 60 | 80 | 100 | 100 | 70 | 100 | **66** | C | 7 |
| [`02-coding-guidelines/01-cross-language/04-code-style`](./02-coding-guidelines__01-cross-language__04-code-style.md) | 70 | 90 | 0 | 70 | 100 | 70 | 90 | **67** | C | 8 |
| [`03-error-manage/02-error-architecture/04-error-modal`](./03-error-manage__02-error-architecture__04-error-modal.md) | 70 | 90 | 0 | 70 | 100 | 70 | 100 | **67** | C | 5 |
| [`15-distribution-and-runner`](./15-distribution-and-runner.md) | 45 | 80 | 70 | 70 | 90 | 80 | 90 | **67** | C | 7 |
| [`03-error-manage/01-error-resolution/04-verification-patterns`](./03-error-manage__01-error-resolution__04-verification-patterns.md) | 70 | 90 | 0 | 70 | 100 | 80 | 100 | **68** | C | 7 |
| [`03-error-manage/03-error-code-registry/07-schemas`](./03-error-manage__03-error-code-registry__07-schemas.md) | 40 | 80 | 90 | 70 | 100 | 70 | 100 | **68** | C | 7 |
| [`25-app-issues`](./25-app-issues.md) | 40 | 80 | 70 | 100 | 100 | 80 | 90 | **69** | C | 6 |
| [`02-coding-guidelines/24-app-design-system-and-ui`](./02-coding-guidelines__24-app-design-system-and-ui.md) | 70 | 80 | 20 | 100 | 90 | 80 | 90 | **71** | C | 5 |
| [`03-error-manage/02-error-architecture/04-error-modal/03-error-modal-reference`](./03-error-manage__02-error-architecture__04-error-modal__03-error-modal-reference.md) | 80 | 90 | 5 | 70 | 100 | 70 | 90 | **71** | C | 7 |
| [`11-powershell-integration`](./11-powershell-integration.md) | 45 | 80 | 80 | 100 | 90 | 80 | 90 | **71** | C | 7 |
| [`03-error-manage/01-error-resolution/app-issues`](./03-error-manage__01-error-resolution__app-issues.md) | 80 | 90 | 0 | 70 | 100 | 80 | 100 | **72** | C | 7 |
| [`02-coding-guidelines/05-rust`](./02-coding-guidelines__05-rust.md) | 80 | 90 | 0 | 100 | 90 | 70 | 100 | **73** | C | 7 |
| [`03-error-manage`](./03-error-manage.md) | 70 | 90 | 20 | 100 | 100 | 80 | 100 | **74** | C | 8 |
| [`03-error-manage/02-error-architecture/05-response-envelope`](./03-error-manage__02-error-architecture__05-response-envelope.md) | 80 | 90 | 20 | 70 | 95 | 80 | 90 | **74** | C | 8 |
| [`16-generic-release`](./16-generic-release.md) | 80 | 90 | 20 | 100 | 90 | 70 | 100 | **76** | B | 7 |
| [`02-coding-guidelines/06-cicd-integration`](./02-coding-guidelines__06-cicd-integration.md) | 70 | 90 | 60 | 100 | 90 | 80 | 90 | **79** | B | 8 |
| [`04-database-conventions`](./04-database-conventions.md) | 85 | 95 | 20 | 100 | 100 | 80 | 90 | **80** | B | 7 |
| [`14-update`](./14-update.md) | 80 | 90 | 60 | 100 | 90 | 80 | 90 | **82** | B | 9 |
| [`02-coding-guidelines/11-security`](./02-coding-guidelines__11-security.md) | 80 | 90 | 80 | 100 | 100 | 85 | 100 | **87** | A | 8 |
| [`18-wp-plugin-how-to`](./18-wp-plugin-how-to.md) | 85 | 90 | 90 | 100 | 100 | 80 | 100 | **90** | A | 8 |