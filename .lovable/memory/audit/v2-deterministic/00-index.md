# Spec-vs-Code Audit **v2** — Summary

**Date:** 2026-04-25  
**Modules audited:** 79  
**Code files indexed:** 32  
**Mean weighted score:** **81.2/100**  
**Mean implementability:** **62.2/100**

## Methodology v2

Weights: implementability=35%, completeness=20%, alignment=15%, consistency=10%, clarity=10%, testability=7%, maintainability=3%.
Implementability = can a mediocre AI ship from spec alone, no human help.
Deterministic metrics (waffle ratio, contract presence, broken links, GWT count) are computed before AI scoring and bound the AI's grades.

## Grade distribution
**A+** = 5, **A** = 23, **B** = 38, **C** = 12, **D** = 1

## Findings by category
| Category | Count |
|---|---:|
| drift | 12 |
| broken-link | 8 |
| missing-contract | 6 |
| untestable | 1 |

## Findings by severity
| Severity | Count |
|---|---:|
| high | 15 |
| low | 12 |

## 🎯 High blast-radius fixes (fix these FIRST)
| Rank | Module | Score | Grade | Blast | Top blocker |
|---:|---|---:|:-:|:-:|---|
| 1 | [`03-error-manage/01-error-resolution`](./03-error-manage__01-error-resolution.md) | 82 | B | 10 | _none_ |
| 2 | [`03-error-manage/02-error-architecture/04-error-modal`](./03-error-manage__02-error-architecture__04-error-modal.md) | 86 | A | 10 | _none_ |
| 3 | [`03-error-manage/02-error-architecture`](./03-error-manage__02-error-architecture.md) | 88 | A | 10 | _none_ |
| 4 | [`03-error-manage/03-error-code-registry`](./03-error-manage__03-error-code-registry.md) | 88 | A | 10 | _none_ |
| 5 | [`12-cicd-pipeline-workflows`](./12-cicd-pipeline-workflows.md) | 93 | A | 10 | _none_ |
| 6 | [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 95 | A+ | 10 | _none_ |
| 7 | [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 96 | A+ | 10 | 1 broken cross-spec link(s) |
| 8 | [`02-coding-guidelines`](./02-coding-guidelines.md) | 100 | A+ | 10 | _none_ |
| 9 | [`05-split-db-architecture`](./05-split-db-architecture.md) | 95 | A+ | 9 | _none_ |
| 10 | [`18-wp-plugin-how-to`](./18-wp-plugin-how-to.md) | 96 | A+ | 9 | _none_ |
| 11 | [`03-error-manage`](./03-error-manage.md) | 81 | B | 8 | _none_ |
| 12 | [`17-consolidated-guidelines`](./17-consolidated-guidelines.md) | 90 | A | 7 | _none_ |
| 13 | [`04-database-conventions`](./04-database-conventions.md) | 93 | A | 7 | _none_ |
| 14 | [`14-update/24-update-check-mechanism`](./14-update__24-update-check-mechanism.md) | 93 | A | 7 | _none_ |
| 15 | [`03-error-manage/02-error-architecture/06-apperror-package`](./03-error-manage__02-error-architecture__06-apperror-package.md) | 82 | B | 6 | _none_ |

## Bottom 15 (lowest implementability)
| Rank | Module | Overall | Impl | Grade | Top finding |
|---:|---|---:|---:|:-:|---|
| 1 | [`.`](./..md) | 59 | 30 | D | No inlined contract (SQL DDL / JSON schema / TS enum / OpenAPI / typed-language  |
| 2 | [`05-split-db-architecture/03-issues`](./05-split-db-architecture__03-issues.md) | 65 | 30 | C | _no findings_ |
| 3 | [`06-seedable-config-architecture/03-issues`](./06-seedable-config-architecture__03-issues.md) | 65 | 30 | C | _no findings_ |
| 4 | [`03-error-manage/03-error-code-registry/08-linter-scripts`](./03-error-manage__03-error-code-registry__08-linter-scripts.md) | 67 | 30 | C | No inlined contract (SQL DDL / JSON schema / TS enum / OpenAPI / typed-language  |
| 5 | [`25-app-issues/01-phase-2-git-logs-audit`](./25-app-issues__01-phase-2-git-logs-audit.md) | 68 | 30 | C | 1 TODO/TBD/FIXME marker(s) in module body |
| 6 | [`02-coding-guidelines/11-security`](./02-coding-guidelines__11-security.md) | 73 | 30 | C | No inlined contract (SQL DDL / JSON schema / TS enum / OpenAPI / typed-language  |
| 7 | [`01-spec-authoring-guide`](./01-spec-authoring-guide.md) | 61 | 40 | C | 11 broken cross-spec link(s) |
| 8 | [`02-coding-guidelines/08-file-folder-naming`](./02-coding-guidelines__08-file-folder-naming.md) | 73 | 40 | C | No inlined contract (SQL DDL / JSON schema / TS enum / OpenAPI / typed-language  |
| 9 | [`03-error-manage/03-error-code-registry/09-templates`](./03-error-manage__03-error-code-registry__09-templates.md) | 73 | 40 | C | _no findings_ |
| 10 | [`03-error-manage/03-error-code-registry/07-schemas`](./03-error-manage__03-error-code-registry__07-schemas.md) | 76 | 45 | B | _no findings_ |
| 11 | [`03-error-manage`](./03-error-manage.md) | 81 | 45 | B | _no findings_ |
| 12 | [`25-app-issues/02-consolidated-audit-findings`](./25-app-issues__02-consolidated-audit-findings.md) | 62 | 50 | C | 13 broken cross-spec link(s) |
| 13 | [`02-coding-guidelines/10-research`](./02-coding-guidelines__10-research.md) | 74 | 50 | C | _no findings_ |
| 14 | [`02-coding-guidelines/22-app-issues`](./02-coding-guidelines__22-app-issues.md) | 74 | 50 | C | _no findings_ |
| 15 | [`14-update/diagrams`](./14-update__diagrams.md) | 74 | 50 | C | _no findings_ |

## Top 10 (gold standards)
| Rank | Module | Overall | Impl | Grade |
|---:|---|---:|---:|:-:|
| 1 | [`02-coding-guidelines`](./02-coding-guidelines.md) | 100 | 100 | A+ |
| 2 | [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 96 | 100 | A+ |
| 3 | [`18-wp-plugin-how-to`](./18-wp-plugin-how-to.md) | 96 | 100 | A+ |
| 4 | [`05-split-db-architecture`](./05-split-db-architecture.md) | 95 | 85 | A+ |
| 5 | [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 95 | 85 | A+ |
| 6 | [`02-coding-guidelines/06-cicd-integration`](./02-coding-guidelines__06-cicd-integration.md) | 94 | 90 | A |
| 7 | [`04-database-conventions`](./04-database-conventions.md) | 93 | 95 | A |
| 8 | [`12-cicd-pipeline-workflows`](./12-cicd-pipeline-workflows.md) | 93 | 90 | A |
| 9 | [`14-update`](./14-update.md) | 93 | 80 | A |
| 10 | [`14-update/24-update-check-mechanism`](./14-update__24-update-check-mechanism.md) | 93 | 85 | A |

## Full ranking
| Module | Impl | Comp | Align | Cons | Clar | Test | Maint | **Overall** | Grade | Blast |
|---|---:|---:|---:|---:|---:|---:|---:|---:|:-:|:-:|
| [`.`](./..md) | 30 | 50 | 100 | 100 | 100 | 10 | 100 | **59** | D | 0 |
| [`01-spec-authoring-guide`](./01-spec-authoring-guide.md) | 40 | 80 | 40 | 50 | 100 | 100 | 90 | **61** | C | 0 |
| [`25-app-issues/02-consolidated-audit-findings`](./25-app-issues__02-consolidated-audit-findings.md) | 50 | 75 | 40 | 50 | 100 | 80 | 100 | **62** | C | 0 |
| [`05-split-db-architecture/03-issues`](./05-split-db-architecture__03-issues.md) | 30 | 55 | 100 | 100 | 100 | 80 | 100 | **65** | C | 0 |
| [`06-seedable-config-architecture/03-issues`](./06-seedable-config-architecture__03-issues.md) | 30 | 55 | 100 | 100 | 100 | 80 | 100 | **65** | C | 0 |
| [`03-error-manage/03-error-code-registry/08-linter-scripts`](./03-error-manage__03-error-code-registry__08-linter-scripts.md) | 30 | 60 | 100 | 100 | 100 | 96 | 100 | **67** | C | 0 |
| [`25-app-issues/01-phase-2-git-logs-audit`](./25-app-issues__01-phase-2-git-logs-audit.md) | 30 | 70 | 100 | 100 | 100 | 90 | 90 | **68** | C | 0 |
| [`02-coding-guidelines/08-file-folder-naming`](./02-coding-guidelines__08-file-folder-naming.md) | 40 | 75 | 100 | 100 | 100 | 90 | 100 | **73** | C | 0 |
| [`02-coding-guidelines/11-security`](./02-coding-guidelines__11-security.md) | 30 | 90 | 100 | 100 | 100 | 96 | 100 | **73** | C | 2 |
| [`03-error-manage/03-error-code-registry/09-templates`](./03-error-manage__03-error-code-registry__09-templates.md) | 40 | 70 | 100 | 100 | 100 | 100 | 100 | **73** | C | 2 |
| [`02-coding-guidelines/10-research`](./02-coding-guidelines__10-research.md) | 50 | 65 | 100 | 100 | 100 | 80 | 100 | **74** | C | 0 |
| [`02-coding-guidelines/22-app-issues`](./02-coding-guidelines__22-app-issues.md) | 50 | 65 | 100 | 100 | 100 | 80 | 100 | **74** | C | 0 |
| [`14-update/diagrams`](./14-update__diagrams.md) | 50 | 65 | 100 | 100 | 100 | 80 | 100 | **74** | C | 0 |
| [`02-coding-guidelines/21-app`](./02-coding-guidelines__21-app.md) | 50 | 70 | 100 | 100 | 100 | 80 | 100 | **75** | B | 0 |
| [`02-coding-guidelines/23-app-database`](./02-coding-guidelines__23-app-database.md) | 50 | 70 | 100 | 100 | 100 | 80 | 100 | **75** | B | 0 |
| [`02-coding-guidelines/24-app-design-system-and-ui`](./02-coding-guidelines__24-app-design-system-and-ui.md) | 50 | 70 | 100 | 100 | 100 | 80 | 100 | **75** | B | 0 |
| [`03-error-manage/02-error-architecture/04-error-modal/04-color-themes`](./03-error-manage__02-error-architecture__04-error-modal__04-color-themes.md) | 50 | 65 | 100 | 100 | 100 | 90 | 100 | **75** | B | 2 |
| [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 50 | 90 | 90 | 70 | 100 | 80 | 100 | **75** | B | 0 |
| [`02-coding-guidelines/09-powershell-integration`](./02-coding-guidelines__09-powershell-integration.md) | 50 | 75 | 100 | 100 | 100 | 80 | 100 | **76** | B | 0 |
| [`03-error-manage/03-error-code-registry/07-schemas`](./03-error-manage__03-error-code-registry__07-schemas.md) | 45 | 80 | 100 | 100 | 100 | 96 | 100 | **76** | B | 2 |
| [`10-research`](./10-research.md) | 50 | 75 | 100 | 100 | 100 | 80 | 100 | **76** | B | 0 |
| [`03-error-manage/02-error-architecture/04-error-modal/02-react-components`](./03-error-manage__02-error-architecture__04-error-modal__02-react-components.md) | 50 | 75 | 100 | 100 | 100 | 90 | 100 | **77** | B | 2 |
| [`12-cicd-pipeline-workflows/01-browser-extension-deploy`](./12-cicd-pipeline-workflows__01-browser-extension-deploy.md) | 55 | 65 | 100 | 100 | 100 | 90 | 100 | **77** | B | 0 |
| [`23-app-database`](./23-app-database.md) | 50 | 80 | 100 | 100 | 100 | 80 | 100 | **77** | B | 0 |
| [`24-app-design-system-and-ui`](./24-app-design-system-and-ui.md) | 50 | 80 | 100 | 100 | 100 | 80 | 100 | **77** | B | 0 |
| [`02-coding-guidelines/07-csharp`](./02-coding-guidelines__07-csharp.md) | 50 | 80 | 100 | 100 | 100 | 100 | 100 | **78** | B | 0 |
| [`03-error-manage/01-error-resolution/app-issues`](./03-error-manage__01-error-resolution__app-issues.md) | 50 | 75 | 100 | 100 | 100 | 100 | 100 | **78** | B | 2 |
| [`25-app-issues`](./25-app-issues.md) | 50 | 85 | 100 | 100 | 100 | 80 | 100 | **78** | B | 4 |
| [`27-spec-toolchain`](./27-spec-toolchain.md) | 55 | 70 | 100 | 100 | 100 | 100 | 90 | **78** | B | 2 |
| [`02-coding-guidelines/02-typescript`](./02-coding-guidelines__02-typescript.md) | 50 | 85 | 100 | 100 | 100 | 100 | 90 | **79** | B | 2 |
| [`02-coding-guidelines/03-golang`](./02-coding-guidelines__03-golang.md) | 50 | 85 | 100 | 100 | 100 | 100 | 90 | **79** | B | 4 |
| [`12-cicd-pipeline-workflows/02-go-binary-deploy`](./12-cicd-pipeline-workflows__02-go-binary-deploy.md) | 55 | 75 | 100 | 100 | 100 | 90 | 100 | **79** | B | 0 |
| [`02-coding-guidelines/01-cross-language/02-boolean-principles`](./02-coding-guidelines__01-cross-language__02-boolean-principles.md) | 60 | 90 | 90 | 70 | 100 | 100 | 100 | **80** | B | 2 |
| [`02-coding-guidelines/01-cross-language/04-code-style`](./02-coding-guidelines__01-cross-language__04-code-style.md) | 60 | 70 | 100 | 100 | 100 | 100 | 90 | **80** | B | 2 |
| [`02-coding-guidelines/01-cross-language/16-static-analysis`](./02-coding-guidelines__01-cross-language__16-static-analysis.md) | 55 | 80 | 100 | 100 | 100 | 100 | 90 | **80** | B | 0 |
| [`02-coding-guidelines/03-golang/04-golang-standards-reference`](./02-coding-guidelines__03-golang__04-golang-standards-reference.md) | 50 | 90 | 100 | 100 | 100 | 100 | 100 | **80** | B | 0 |
| [`03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference`](./03-error-manage__02-error-architecture__06-apperror-package__01-apperror-reference.md) | 65 | 65 | 100 | 100 | 100 | 90 | 100 | **80** | B | 2 |
| [`03-error-manage`](./03-error-manage.md) | 45 | 100 | 100 | 100 | 100 | 100 | 100 | **81** | B | 8 |
| [`15-distribution-and-runner`](./15-distribution-and-runner.md) | 65 | 90 | 90 | 70 | 100 | 100 | 100 | **81** | B | 2 |
| [`28-universal-ci-cli`](./28-universal-ci-cli.md) | 65 | 70 | 100 | 100 | 100 | 100 | 90 | **81** | B | 2 |
| [`02-coding-guidelines/01-cross-language/15-master-coding-guidelines`](./02-coding-guidelines__01-cross-language__15-master-coding-guidelines.md) | 60 | 80 | 100 | 100 | 100 | 100 | 100 | **82** | B | 2 |
| [`02-coding-guidelines/04-php`](./02-coding-guidelines__04-php.md) | 50 | 100 | 100 | 100 | 100 | 100 | 100 | **82** | B | 2 |
| [`02-coding-guidelines/11-security/01-axios-version-control`](./02-coding-guidelines__11-security__01-axios-version-control.md) | 65 | 75 | 100 | 100 | 100 | 90 | 100 | **82** | B | 2 |
| [`03-error-manage/01-error-resolution`](./03-error-manage__01-error-resolution.md) | 60 | 85 | 100 | 100 | 100 | 90 | 100 | **82** | B | 10 |
| [`03-error-manage/01-error-resolution/05-debugging-guides`](./03-error-manage__01-error-resolution__05-debugging-guides.md) | 60 | 80 | 100 | 100 | 100 | 100 | 100 | **82** | B | 2 |
| [`03-error-manage/02-error-architecture/04-error-modal/01-copy-formats`](./03-error-manage__02-error-architecture__04-error-modal__01-copy-formats.md) | 65 | 75 | 100 | 100 | 100 | 90 | 100 | **82** | B | 4 |
| [`03-error-manage/02-error-architecture/06-apperror-package`](./03-error-manage__02-error-architecture__06-apperror-package.md) | 55 | 90 | 100 | 100 | 100 | 100 | 100 | **82** | B | 6 |
| [`11-powershell-integration`](./11-powershell-integration.md) | 65 | 75 | 100 | 100 | 100 | 90 | 100 | **82** | B | 2 |
| [`03-error-manage/02-error-architecture/05-response-envelope`](./03-error-manage__02-error-architecture__05-response-envelope.md) | 65 | 75 | 100 | 100 | 100 | 100 | 100 | **83** | B | 4 |
| [`03-error-manage/01-error-resolution/04-verification-patterns`](./03-error-manage__01-error-resolution__04-verification-patterns.md) | 75 | 65 | 100 | 100 | 100 | 100 | 100 | **84** | B | 4 |
| [`03-error-manage/02-error-architecture/07-logging-and-diagnostics`](./03-error-manage__02-error-architecture__07-logging-and-diagnostics.md) | 65 | 80 | 100 | 100 | 100 | 100 | 100 | **84** | B | 4 |
| [`02-coding-guidelines/03-golang/01-enum-specification`](./02-coding-guidelines__03-golang__01-enum-specification.md) | 75 | 75 | 100 | 100 | 100 | 90 | 100 | **86** | A | 4 |
| [`02-coding-guidelines/05-rust`](./02-coding-guidelines__05-rust.md) | 70 | 85 | 100 | 100 | 100 | 100 | 90 | **86** | A | 3 |
| [`03-error-manage/02-error-architecture/04-error-modal`](./03-error-manage__02-error-architecture__04-error-modal.md) | 65 | 90 | 100 | 100 | 100 | 100 | 100 | **86** | A | 10 |
| [`03-error-manage/02-error-architecture/04-error-modal/03-error-modal-reference`](./03-error-manage__02-error-architecture__04-error-modal__03-error-modal-reference.md) | 65 | 90 | 100 | 100 | 100 | 100 | 100 | **86** | A | 4 |
| [`06-seedable-config-architecture/02-features`](./06-seedable-config-architecture__02-features.md) | 85 | 60 | 100 | 100 | 100 | 90 | 90 | **86** | A | 5 |
| [`07-design-system`](./07-design-system.md) | 65 | 90 | 100 | 100 | 100 | 100 | 100 | **86** | A | 4 |
| [`18-wp-plugin-how-to/02-enums-and-coding-style`](./18-wp-plugin-how-to__02-enums-and-coding-style.md) | 75 | 75 | 100 | 100 | 100 | 90 | 100 | **86** | A | 4 |
| [`02-coding-guidelines/06-ai-optimization`](./02-coding-guidelines__06-ai-optimization.md) | 75 | 80 | 100 | 100 | 100 | 100 | 100 | **87** | A | 4 |
| [`03-error-manage/01-error-resolution/03-retrospectives`](./03-error-manage__01-error-resolution__03-retrospectives.md) | 75 | 80 | 100 | 100 | 100 | 100 | 100 | **87** | A | 4 |
| [`05-split-db-architecture/02-features`](./05-split-db-architecture__02-features.md) | 85 | 65 | 100 | 100 | 100 | 90 | 100 | **87** | A | 5 |
| [`12-cicd-pipeline-workflows/03-reusable-ci-guards`](./12-cicd-pipeline-workflows__03-reusable-ci-guards.md) | 80 | 75 | 100 | 100 | 100 | 90 | 100 | **87** | A | 2 |
| [`03-error-manage/02-error-architecture`](./03-error-manage__02-error-architecture.md) | 75 | 85 | 100 | 100 | 100 | 90 | 100 | **88** | A | 10 |
| [`03-error-manage/03-error-code-registry`](./03-error-manage__03-error-code-registry.md) | 75 | 85 | 100 | 100 | 100 | 90 | 100 | **88** | A | 10 |
| [`13-generic-cli`](./13-generic-cli.md) | 85 | 90 | 90 | 70 | 100 | 100 | 100 | **88** | A | 5 |
| [`16-generic-release`](./16-generic-release.md) | 70 | 90 | 100 | 100 | 100 | 100 | 100 | **88** | A | 2 |
| [`02-coding-guidelines/04-php/07-php-standards-reference`](./02-coding-guidelines__04-php__07-php-standards-reference.md) | 75 | 90 | 100 | 100 | 100 | 100 | 100 | **89** | A | 4 |
| [`22-git-logs-v2`](./22-git-logs-v2.md) | 100 | 70 | 90 | 70 | 100 | 100 | 90 | **89** | A | 5 |
| [`17-consolidated-guidelines`](./17-consolidated-guidelines.md) | 100 | 55 | 100 | 100 | 100 | 90 | 90 | **90** | A | 7 |
| [`04-database-conventions`](./04-database-conventions.md) | 95 | 75 | 100 | 100 | 100 | 90 | 100 | **93** | A | 7 |
| [`12-cicd-pipeline-workflows`](./12-cicd-pipeline-workflows.md) | 90 | 85 | 100 | 100 | 100 | 90 | 100 | **93** | A | 10 |
| [`14-update`](./14-update.md) | 80 | 100 | 100 | 100 | 100 | 100 | 100 | **93** | A | 6 |
| [`14-update/24-update-check-mechanism`](./14-update__24-update-check-mechanism.md) | 85 | 90 | 100 | 100 | 100 | 100 | 100 | **93** | A | 7 |
| [`02-coding-guidelines/06-cicd-integration`](./02-coding-guidelines__06-cicd-integration.md) | 90 | 90 | 100 | 100 | 100 | 100 | 100 | **94** | A | 4 |
| [`05-split-db-architecture`](./05-split-db-architecture.md) | 85 | 100 | 100 | 100 | 100 | 100 | 100 | **95** | A+ | 9 |
| [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 85 | 100 | 100 | 100 | 100 | 100 | 100 | **95** | A+ | 10 |
| [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 100 | 100 | 90 | 70 | 100 | 100 | 100 | **96** | A+ | 10 |
| [`18-wp-plugin-how-to`](./18-wp-plugin-how-to.md) | 100 | 85 | 100 | 100 | 100 | 90 | 100 | **96** | A+ | 9 |
| [`02-coding-guidelines`](./02-coding-guidelines.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |