# Spec-vs-Code Audit **v2** — Summary

**Date:** 2026-04-25  
**Modules audited:** 78  
**Code files indexed:** 30  
**Mean weighted score:** **72.8/100**  
**Mean implementability:** **52.2/100**

## Methodology v2

Weights: implementability=35%, completeness=20%, alignment=15%, consistency=10%, clarity=10%, testability=7%, maintainability=3%.
Implementability = can a mediocre AI ship from spec alone, no human help.
Deterministic metrics (waffle ratio, contract presence, broken links, GWT count) are computed before AI scoring and bound the AI's grades.

## Grade distribution
**A+** = 1, **A** = 4, **B** = 26, **C** = 41, **D** = 6

## Findings by category
| Category | Count |
|---|---:|
| broken-link | 41 |
| missing-contract | 33 |
| untestable | 23 |
| drift | 9 |

## Findings by severity
| Severity | Count |
|---|---:|
| high | 87 |
| medium | 10 |
| low | 9 |

## 🎯 High blast-radius fixes (fix these FIRST)
| Rank | Module | Score | Grade | Blast | Top blocker |
|---:|---|---:|:-:|:-:|---|
| 1 | [`03-error-manage/01-error-resolution`](./03-error-manage__01-error-resolution.md) | 74 | C | 10 | 1 broken cross-spec link(s) |
| 2 | [`02-coding-guidelines`](./02-coding-guidelines.md) | 79 | B | 10 | 1 broken cross-spec link(s) |
| 3 | [`03-error-manage/02-error-architecture`](./03-error-manage__02-error-architecture.md) | 80 | B | 10 | 1 broken cross-spec link(s) |
| 4 | [`03-error-manage/03-error-code-registry`](./03-error-manage__03-error-code-registry.md) | 80 | B | 10 | 1 broken cross-spec link(s) |
| 5 | [`03-error-manage/02-error-architecture/04-error-modal`](./03-error-manage__02-error-architecture__04-error-modal.md) | 81 | B | 10 | 1 broken cross-spec link(s) |
| 6 | [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 84 | B | 10 | 1 broken cross-spec link(s) |
| 7 | [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 85 | A | 10 | _none_ |
| 8 | [`12-cicd-pipeline-workflows`](./12-cicd-pipeline-workflows.md) | 88 | A | 10 | _none_ |
| 9 | [`05-split-db-architecture`](./05-split-db-architecture.md) | 82 | B | 9 | _none_ |
| 10 | [`18-wp-plugin-how-to`](./18-wp-plugin-how-to.md) | 95 | A+ | 9 | _none_ |
| 11 | [`03-error-manage`](./03-error-manage.md) | 81 | B | 8 | _none_ |
| 12 | [`17-consolidated-guidelines`](./17-consolidated-guidelines.md) | 84 | B | 7 | 1 broken cross-spec link(s) |
| 13 | [`04-database-conventions`](./04-database-conventions.md) | 89 | A | 7 | _none_ |
| 14 | [`14-update`](./14-update.md) | 84 | B | 6 | _none_ |
| 15 | [`14-update/24-update-check-mechanism`](./14-update__24-update-check-mechanism.md) | 75 | B | 5 | No acceptance criteria found |

## Bottom 15 (lowest implementability)
| Rank | Module | Overall | Impl | Grade | Top finding |
|---:|---|---:|---:|:-:|---|
| 1 | [`05-split-db-architecture/03-issues`](./05-split-db-architecture__03-issues.md) | 54 | 10 | D | 1 broken cross-spec link(s) |
| 2 | [`06-seedable-config-architecture/03-issues`](./06-seedable-config-architecture__03-issues.md) | 54 | 10 | D | 1 broken cross-spec link(s) |
| 3 | [`.`](./..md) | 59 | 30 | D | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |
| 4 | [`02-coding-guidelines/10-research`](./02-coding-guidelines__10-research.md) | 63 | 30 | C | 1 broken cross-spec link(s) |
| 5 | [`02-coding-guidelines/22-app-issues`](./02-coding-guidelines__22-app-issues.md) | 63 | 30 | C | 1 broken cross-spec link(s) |
| 6 | [`03-error-manage/03-error-code-registry/07-schemas`](./03-error-manage__03-error-code-registry__07-schemas.md) | 63 | 30 | C | 1 broken cross-spec link(s) |
| 7 | [`03-error-manage/03-error-code-registry/08-linter-scripts`](./03-error-manage__03-error-code-registry__08-linter-scripts.md) | 63 | 30 | C | 1 broken cross-spec link(s) |
| 8 | [`25-app-issues/01-phase-2-git-logs-audit`](./25-app-issues__01-phase-2-git-logs-audit.md) | 64 | 30 | C | 1 broken cross-spec link(s) |
| 9 | [`02-coding-guidelines/21-app`](./02-coding-guidelines__21-app.md) | 65 | 30 | C | 1 broken cross-spec link(s) |
| 10 | [`02-coding-guidelines/23-app-database`](./02-coding-guidelines__23-app-database.md) | 67 | 30 | C | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |
| 11 | [`02-coding-guidelines/24-app-design-system-and-ui`](./02-coding-guidelines__24-app-design-system-and-ui.md) | 67 | 30 | C | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |
| 12 | [`02-coding-guidelines/09-powershell-integration`](./02-coding-guidelines__09-powershell-integration.md) | 68 | 30 | C | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |
| 13 | [`03-error-manage/02-error-architecture/06-apperror-package`](./03-error-manage__02-error-architecture__06-apperror-package.md) | 69 | 30 | C | 1 broken cross-spec link(s) |
| 14 | [`24-app-design-system-and-ui`](./24-app-design-system-and-ui.md) | 69 | 30 | C | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |
| 15 | [`10-research`](./10-research.md) | 70 | 30 | C | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |

## Top 10 (gold standards)
| Rank | Module | Overall | Impl | Grade |
|---:|---|---:|---:|:-:|
| 1 | [`18-wp-plugin-how-to`](./18-wp-plugin-how-to.md) | 95 | 95 | A+ |
| 2 | [`04-database-conventions`](./04-database-conventions.md) | 89 | 85 | A |
| 3 | [`12-cicd-pipeline-workflows`](./12-cicd-pipeline-workflows.md) | 88 | 75 | A |
| 4 | [`02-coding-guidelines/06-cicd-integration`](./02-coding-guidelines__06-cicd-integration.md) | 85 | 75 | A |
| 5 | [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 85 | 85 | A |
| 6 | [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 84 | 95 | B |
| 7 | [`14-update`](./14-update.md) | 84 | 65 | B |
| 8 | [`17-consolidated-guidelines`](./17-consolidated-guidelines.md) | 84 | 95 | B |
| 9 | [`05-split-db-architecture`](./05-split-db-architecture.md) | 82 | 75 | B |
| 10 | [`11-powershell-integration`](./11-powershell-integration.md) | 82 | 65 | B |

## Full ranking
| Module | Impl | Comp | Align | Cons | Clar | Test | Maint | **Overall** | Grade | Blast |
|---|---:|---:|---:|---:|---:|---:|---:|---:|:-:|:-:|
| [`01-spec-authoring-guide`](./01-spec-authoring-guide.md) | 40 | 60 | 40 | 50 | 100 | 60 | 90 | **54** | D | 0 |
| [`05-split-db-architecture/03-issues`](./05-split-db-architecture__03-issues.md) | 10 | 55 | 90 | 70 | 100 | 90 | 100 | **54** | D | 0 |
| [`06-seedable-config-architecture/03-issues`](./06-seedable-config-architecture__03-issues.md) | 10 | 55 | 90 | 70 | 100 | 90 | 100 | **54** | D | 0 |
| [`.`](./..md) | 30 | 50 | 100 | 100 | 100 | 10 | 100 | **59** | D | 0 |
| [`25-app-issues/02-consolidated-audit-findings`](./25-app-issues__02-consolidated-audit-findings.md) | 40 | 75 | 40 | 50 | 100 | 90 | 100 | **59** | D | 0 |
| [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 35 | 40 | 100 | 100 | 100 | 10 | 100 | **59** | D | 0 |
| [`02-coding-guidelines/03-golang/01-enum-specification`](./02-coding-guidelines__03-golang__01-enum-specification.md) | 40 | 50 | 100 | 100 | 100 | 10 | 100 | **63** | C | 0 |
| [`02-coding-guidelines/10-research`](./02-coding-guidelines__10-research.md) | 30 | 65 | 90 | 70 | 100 | 90 | 100 | **63** | C | 0 |
| [`02-coding-guidelines/22-app-issues`](./02-coding-guidelines__22-app-issues.md) | 30 | 65 | 90 | 70 | 100 | 90 | 100 | **63** | C | 0 |
| [`03-error-manage/03-error-code-registry/07-schemas`](./03-error-manage__03-error-code-registry__07-schemas.md) | 30 | 60 | 90 | 70 | 100 | 96 | 100 | **63** | C | 0 |
| [`03-error-manage/03-error-code-registry/08-linter-scripts`](./03-error-manage__03-error-code-registry__08-linter-scripts.md) | 30 | 60 | 90 | 70 | 100 | 96 | 100 | **63** | C | 0 |
| [`02-coding-guidelines/01-cross-language/16-static-analysis`](./02-coding-guidelines__01-cross-language__16-static-analysis.md) | 50 | 40 | 100 | 100 | 100 | 10 | 90 | **64** | C | 0 |
| [`03-error-manage/02-error-architecture/04-error-modal/04-color-themes`](./03-error-manage__02-error-architecture__04-error-modal__04-color-themes.md) | 50 | 40 | 100 | 100 | 100 | 10 | 100 | **64** | C | 2 |
| [`25-app-issues/01-phase-2-git-logs-audit`](./25-app-issues__01-phase-2-git-logs-audit.md) | 30 | 70 | 90 | 70 | 100 | 90 | 90 | **64** | C | 0 |
| [`02-coding-guidelines/21-app`](./02-coding-guidelines__21-app.md) | 30 | 70 | 90 | 70 | 100 | 96 | 100 | **65** | C | 0 |
| [`14-update/diagrams`](./14-update__diagrams.md) | 35 | 65 | 90 | 70 | 100 | 90 | 100 | **65** | C | 0 |
| [`03-error-manage/02-error-architecture/04-error-modal/02-react-components`](./03-error-manage__02-error-architecture__04-error-modal__02-react-components.md) | 50 | 50 | 100 | 100 | 100 | 10 | 100 | **66** | C | 2 |
| [`03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference`](./03-error-manage__02-error-architecture__06-apperror-package__01-apperror-reference.md) | 55 | 40 | 100 | 100 | 100 | 10 | 100 | **66** | C | 2 |
| [`02-coding-guidelines/23-app-database`](./02-coding-guidelines__23-app-database.md) | 30 | 60 | 100 | 100 | 100 | 96 | 100 | **67** | C | 0 |
| [`02-coding-guidelines/24-app-design-system-and-ui`](./02-coding-guidelines__24-app-design-system-and-ui.md) | 30 | 60 | 100 | 100 | 100 | 96 | 100 | **67** | C | 0 |
| [`02-coding-guidelines/03-golang`](./02-coding-guidelines__03-golang.md) | 40 | 60 | 100 | 100 | 100 | 52 | 100 | **68** | C | 4 |
| [`02-coding-guidelines/09-powershell-integration`](./02-coding-guidelines__09-powershell-integration.md) | 30 | 65 | 100 | 100 | 100 | 100 | 100 | **68** | C | 0 |
| [`03-error-manage/03-error-code-registry/09-templates`](./03-error-manage__03-error-code-registry__09-templates.md) | 40 | 70 | 90 | 70 | 100 | 100 | 100 | **68** | C | 2 |
| [`02-coding-guidelines/06-ai-optimization`](./02-coding-guidelines__06-ai-optimization.md) | 65 | 40 | 100 | 100 | 100 | 10 | 100 | **69** | C | 4 |
| [`02-coding-guidelines/08-file-folder-naming`](./02-coding-guidelines__08-file-folder-naming.md) | 40 | 75 | 90 | 70 | 100 | 90 | 100 | **69** | C | 0 |
| [`03-error-manage/02-error-architecture/06-apperror-package`](./03-error-manage__02-error-architecture__06-apperror-package.md) | 30 | 90 | 90 | 70 | 100 | 100 | 100 | **69** | C | 2 |
| [`18-wp-plugin-how-to/02-enums-and-coding-style`](./18-wp-plugin-how-to__02-enums-and-coding-style.md) | 40 | 75 | 90 | 70 | 100 | 90 | 100 | **69** | C | 0 |
| [`24-app-design-system-and-ui`](./24-app-design-system-and-ui.md) | 30 | 70 | 100 | 100 | 100 | 96 | 100 | **69** | C | 0 |
| [`02-coding-guidelines/02-typescript`](./02-coding-guidelines__02-typescript.md) | 50 | 55 | 100 | 100 | 100 | 52 | 90 | **70** | C | 2 |
| [`10-research`](./10-research.md) | 30 | 75 | 100 | 100 | 100 | 90 | 100 | **70** | C | 0 |
| [`12-cicd-pipeline-workflows/01-browser-extension-deploy`](./12-cicd-pipeline-workflows__01-browser-extension-deploy.md) | 50 | 65 | 90 | 70 | 100 | 90 | 100 | **70** | C | 0 |
| [`02-coding-guidelines/07-csharp`](./02-coding-guidelines__07-csharp.md) | 40 | 75 | 100 | 100 | 100 | 60 | 100 | **71** | C | 0 |
| [`02-coding-guidelines/11-security/01-axios-version-control`](./02-coding-guidelines__11-security__01-axios-version-control.md) | 65 | 50 | 100 | 100 | 100 | 10 | 100 | **71** | C | 2 |
| [`03-error-manage/02-error-architecture/04-error-modal/01-copy-formats`](./03-error-manage__02-error-architecture__04-error-modal__01-copy-formats.md) | 65 | 50 | 100 | 100 | 100 | 10 | 100 | **71** | C | 4 |
| [`07-design-system`](./07-design-system.md) | 65 | 50 | 100 | 100 | 100 | 10 | 100 | **71** | C | 4 |
| [`23-app-database`](./23-app-database.md) | 30 | 80 | 100 | 100 | 100 | 96 | 100 | **71** | C | 0 |
| [`02-coding-guidelines/01-cross-language/04-code-style`](./02-coding-guidelines__01-cross-language__04-code-style.md) | 50 | 70 | 90 | 70 | 100 | 100 | 90 | **72** | C | 2 |
| [`02-coding-guidelines/03-golang/04-golang-standards-reference`](./02-coding-guidelines__03-golang__04-golang-standards-reference.md) | 40 | 90 | 90 | 70 | 100 | 100 | 100 | **72** | C | 0 |
| [`12-cicd-pipeline-workflows/02-go-binary-deploy`](./12-cicd-pipeline-workflows__02-go-binary-deploy.md) | 50 | 75 | 90 | 70 | 100 | 90 | 100 | **72** | C | 0 |
| [`25-app-issues`](./25-app-issues.md) | 30 | 85 | 100 | 100 | 100 | 90 | 100 | **72** | C | 4 |
| [`02-coding-guidelines/11-security`](./02-coding-guidelines__11-security.md) | 30 | 90 | 100 | 100 | 100 | 96 | 100 | **73** | C | 2 |
| [`03-error-manage/01-error-resolution/app-issues`](./03-error-manage__01-error-resolution__app-issues.md) | 50 | 75 | 90 | 70 | 100 | 100 | 100 | **73** | C | 2 |
| [`27-spec-toolchain`](./27-spec-toolchain.md) | 40 | 70 | 100 | 100 | 100 | 100 | 90 | **73** | C | 0 |
| [`02-coding-guidelines/01-cross-language/02-boolean-principles`](./02-coding-guidelines__01-cross-language__02-boolean-principles.md) | 50 | 90 | 80 | 70 | 100 | 100 | 100 | **74** | C | 2 |
| [`02-coding-guidelines/01-cross-language/15-master-coding-guidelines`](./02-coding-guidelines__01-cross-language__15-master-coding-guidelines.md) | 50 | 80 | 90 | 70 | 100 | 100 | 100 | **74** | C | 2 |
| [`03-error-manage/01-error-resolution`](./03-error-manage__01-error-resolution.md) | 50 | 85 | 90 | 70 | 100 | 90 | 100 | **74** | C | 10 |
| [`03-error-manage/01-error-resolution/05-debugging-guides`](./03-error-manage__01-error-resolution__05-debugging-guides.md) | 50 | 80 | 90 | 70 | 100 | 100 | 100 | **74** | C | 2 |
| [`03-error-manage/02-error-architecture/05-response-envelope`](./03-error-manage__02-error-architecture__05-response-envelope.md) | 55 | 75 | 90 | 70 | 100 | 100 | 100 | **75** | B | 2 |
| [`14-update/24-update-check-mechanism`](./14-update__24-update-check-mechanism.md) | 75 | 50 | 100 | 100 | 100 | 10 | 100 | **75** | B | 5 |
| [`22-git-logs-v2`](./22-git-logs-v2.md) | 85 | 40 | 100 | 100 | 100 | 10 | 90 | **76** | B | 5 |
| [`06-seedable-config-architecture/02-features`](./06-seedable-config-architecture__02-features.md) | 75 | 60 | 90 | 70 | 100 | 90 | 90 | **78** | B | 5 |
| [`12-cicd-pipeline-workflows/03-reusable-ci-guards`](./12-cicd-pipeline-workflows__03-reusable-ci-guards.md) | 65 | 75 | 90 | 70 | 100 | 90 | 100 | **78** | B | 2 |
| [`15-distribution-and-runner`](./15-distribution-and-runner.md) | 65 | 75 | 90 | 70 | 100 | 90 | 100 | **78** | B | 2 |
| [`02-coding-guidelines`](./02-coding-guidelines.md) | 70 | 85 | 90 | 70 | 100 | 60 | 100 | **79** | B | 10 |
| [`02-coding-guidelines/04-php`](./02-coding-guidelines__04-php.md) | 40 | 100 | 100 | 100 | 100 | 100 | 100 | **79** | B | 2 |
| [`02-coding-guidelines/05-rust`](./02-coding-guidelines__05-rust.md) | 60 | 80 | 100 | 100 | 100 | 60 | 100 | **79** | B | 3 |
| [`03-error-manage/01-error-resolution/03-retrospectives`](./03-error-manage__01-error-resolution__03-retrospectives.md) | 65 | 80 | 90 | 70 | 100 | 100 | 100 | **79** | B | 4 |
| [`03-error-manage/02-error-architecture/07-logging-and-diagnostics`](./03-error-manage__02-error-architecture__07-logging-and-diagnostics.md) | 65 | 80 | 90 | 70 | 100 | 100 | 100 | **79** | B | 4 |
| [`05-split-db-architecture/02-features`](./05-split-db-architecture__02-features.md) | 75 | 65 | 90 | 70 | 100 | 90 | 100 | **79** | B | 5 |
| [`03-error-manage/01-error-resolution/04-verification-patterns`](./03-error-manage__01-error-resolution__04-verification-patterns.md) | 75 | 65 | 90 | 70 | 100 | 100 | 100 | **80** | B | 4 |
| [`03-error-manage/02-error-architecture`](./03-error-manage__02-error-architecture.md) | 65 | 85 | 90 | 70 | 100 | 90 | 100 | **80** | B | 10 |
| [`03-error-manage/03-error-code-registry`](./03-error-manage__03-error-code-registry.md) | 65 | 85 | 90 | 70 | 100 | 90 | 100 | **80** | B | 10 |
| [`02-coding-guidelines/04-php/07-php-standards-reference`](./02-coding-guidelines__04-php__07-php-standards-reference.md) | 65 | 90 | 90 | 70 | 100 | 100 | 100 | **81** | B | 4 |
| [`03-error-manage`](./03-error-manage.md) | 45 | 100 | 100 | 100 | 100 | 100 | 100 | **81** | B | 8 |
| [`03-error-manage/02-error-architecture/04-error-modal`](./03-error-manage__02-error-architecture__04-error-modal.md) | 65 | 90 | 90 | 70 | 100 | 100 | 100 | **81** | B | 10 |
| [`03-error-manage/02-error-architecture/04-error-modal/03-error-modal-reference`](./03-error-manage__02-error-architecture__04-error-modal__03-error-modal-reference.md) | 65 | 90 | 90 | 70 | 100 | 100 | 100 | **81** | B | 4 |
| [`13-generic-cli`](./13-generic-cli.md) | 75 | 75 | 90 | 70 | 100 | 90 | 100 | **81** | B | 5 |
| [`05-split-db-architecture`](./05-split-db-architecture.md) | 75 | 70 | 100 | 100 | 100 | 52 | 100 | **82** | B | 9 |
| [`11-powershell-integration`](./11-powershell-integration.md) | 65 | 75 | 100 | 100 | 100 | 90 | 100 | **82** | B | 2 |
| [`16-generic-release`](./16-generic-release.md) | 65 | 75 | 100 | 100 | 100 | 90 | 100 | **82** | B | 2 |
| [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 95 | 70 | 90 | 70 | 100 | 52 | 100 | **84** | B | 10 |
| [`14-update`](./14-update.md) | 65 | 85 | 100 | 100 | 100 | 90 | 100 | **84** | B | 6 |
| [`17-consolidated-guidelines`](./17-consolidated-guidelines.md) | 95 | 55 | 90 | 70 | 100 | 90 | 90 | **84** | B | 7 |
| [`02-coding-guidelines/06-cicd-integration`](./02-coding-guidelines__06-cicd-integration.md) | 75 | 85 | 100 | 100 | 100 | 60 | 100 | **85** | A | 4 |
| [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 85 | 70 | 100 | 100 | 100 | 52 | 100 | **85** | A | 10 |
| [`12-cicd-pipeline-workflows`](./12-cicd-pipeline-workflows.md) | 75 | 85 | 100 | 100 | 100 | 90 | 100 | **88** | A | 10 |
| [`04-database-conventions`](./04-database-conventions.md) | 85 | 75 | 100 | 100 | 100 | 90 | 100 | **89** | A | 7 |
| [`18-wp-plugin-how-to`](./18-wp-plugin-how-to.md) | 95 | 85 | 100 | 100 | 100 | 90 | 100 | **95** | A+ | 9 |