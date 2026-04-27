# Spec-vs-Code Audit **v2** — Summary

**Date:** 2026-04-25  
**Modules audited:** 87  
**Code files indexed:** 35  
**Mean weighted score:** **98.0/100**  
**Mean implementability:** **99.8/100**

## Methodology v2

Weights: implementability=35%, completeness=20%, alignment=15%, consistency=10%, clarity=10%, testability=7%, maintainability=3%.
Implementability = can a mediocre AI ship from spec alone, no human help.
Deterministic metrics (waffle ratio, contract presence, broken links, GWT count) are computed before AI scoring and bound the AI's grades.

## Grade distribution
**A+** = 87

## Findings by category
| Category | Count |
|---|---:|
| drift | 3 |

## Findings by severity
| Severity | Count |
|---|---:|
| low | 3 |

## 🎯 High blast-radius fixes (fix these FIRST)
| Rank | Module | Score | Grade | Blast | Top blocker |
|---:|---|---:|:-:|:-:|---|
| 1 | [`.`](./..md) | 99 | A+ | 10 | _none_ |
| 2 | [`02-coding-guidelines`](./02-coding-guidelines.md) | 99 | A+ | 10 | _none_ |
| 3 | [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 100 | A+ | 10 | _none_ |
| 4 | [`02-coding-guidelines/03-golang`](./02-coding-guidelines__03-golang.md) | 100 | A+ | 10 | _none_ |
| 5 | [`03-error-manage`](./03-error-manage.md) | 100 | A+ | 10 | _none_ |
| 6 | [`03-error-manage/01-error-resolution`](./03-error-manage__01-error-resolution.md) | 100 | A+ | 10 | _none_ |
| 7 | [`03-error-manage/02-error-architecture`](./03-error-manage__02-error-architecture.md) | 100 | A+ | 10 | _none_ |
| 8 | [`03-error-manage/02-error-architecture/04-error-modal`](./03-error-manage__02-error-architecture__04-error-modal.md) | 100 | A+ | 10 | _none_ |
| 9 | [`03-error-manage/03-error-code-registry`](./03-error-manage__03-error-code-registry.md) | 100 | A+ | 10 | _none_ |
| 10 | [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 100 | A+ | 10 | _none_ |
| 11 | [`12-cicd-pipeline-workflows`](./12-cicd-pipeline-workflows.md) | 100 | A+ | 10 | _none_ |
| 12 | [`14-update`](./14-update.md) | 100 | A+ | 10 | _none_ |
| 13 | [`02-coding-guidelines/11-security`](./02-coding-guidelines__11-security.md) | 98 | A+ | 9 | _none_ |
| 14 | [`02-coding-guidelines/04-php`](./02-coding-guidelines__04-php.md) | 100 | A+ | 9 | _none_ |
| 15 | [`03-error-manage/02-error-architecture/06-apperror-package`](./03-error-manage__02-error-architecture__06-apperror-package.md) | 100 | A+ | 9 | _none_ |

## Bottom 15 (lowest implementability)
| Rank | Module | Overall | Impl | Grade | Top finding |
|---:|---|---:|---:|:-:|---|
| 1 | [`05-split-db-architecture/03-issues`](./05-split-db-architecture__03-issues.md) | 95 | 95 | A+ | _no findings_ |
| 2 | [`06-seedable-config-architecture/03-issues`](./06-seedable-config-architecture__03-issues.md) | 95 | 95 | A+ | _no findings_ |
| 3 | [`25-app-issues/02-consolidated-audit-findings`](./25-app-issues__02-consolidated-audit-findings.md) | 95 | 95 | A+ | _no findings_ |
| 4 | [`02-coding-guidelines/10-research/01-research-index`](./02-coding-guidelines__10-research__01-research-index.md) | 96 | 100 | A+ | _no findings_ |
| 5 | [`02-coding-guidelines/21-app/01-app-coding-rules`](./02-coding-guidelines__21-app__01-app-coding-rules.md) | 96 | 100 | A+ | _no findings_ |
| 6 | [`02-coding-guidelines/22-app-issues/01-app-issue-templates`](./02-coding-guidelines__22-app-issues__01-app-issue-templates.md) | 96 | 100 | A+ | _no findings_ |
| 7 | [`02-coding-guidelines/23-app-database/01-app-database-conventions`](./02-coding-guidelines__23-app-database__01-app-database-conventions.md) | 96 | 100 | A+ | _no findings_ |
| 8 | [`02-coding-guidelines/24-app-design-system-and-ui/01-app-ui-conventions`](./02-coding-guidelines__24-app-design-system-and-ui__01-app-ui-conventions.md) | 96 | 100 | A+ | _no findings_ |
| 9 | [`03-error-manage/03-error-code-registry/07-schemas`](./03-error-manage__03-error-code-registry__07-schemas.md) | 96 | 100 | A+ | _no findings_ |
| 10 | [`03-error-manage/03-error-code-registry/08-linter-scripts`](./03-error-manage__03-error-code-registry__08-linter-scripts.md) | 96 | 100 | A+ | _no findings_ |
| 11 | [`10-research/01-research-index`](./10-research__01-research-index.md) | 96 | 100 | A+ | _no findings_ |
| 12 | [`14-update/diagrams/01-diagram-conventions`](./14-update__diagrams__01-diagram-conventions.md) | 96 | 100 | A+ | _no findings_ |
| 13 | [`26-gitlogs-diagrams/01-diagram-conventions`](./26-gitlogs-diagrams__01-diagram-conventions.md) | 96 | 100 | A+ | _no findings_ |
| 14 | [`01-spec-authoring-guide`](./01-spec-authoring-guide.md) | 97 | 100 | A+ | 1 TODO/TBD/FIXME marker(s) in module body |
| 15 | [`02-coding-guidelines/09-powershell-integration`](./02-coding-guidelines__09-powershell-integration.md) | 97 | 100 | A+ | _no findings_ |

## Top 10 (gold standards)
| Rank | Module | Overall | Impl | Grade |
|---:|---|---:|---:|:-:|
| 1 | [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 100 | 100 | A+ |
| 2 | [`02-coding-guidelines/03-golang`](./02-coding-guidelines__03-golang.md) | 100 | 100 | A+ |
| 3 | [`02-coding-guidelines/04-php`](./02-coding-guidelines__04-php.md) | 100 | 100 | A+ |
| 4 | [`03-error-manage`](./03-error-manage.md) | 100 | 100 | A+ |
| 5 | [`03-error-manage/01-error-resolution`](./03-error-manage__01-error-resolution.md) | 100 | 100 | A+ |
| 6 | [`03-error-manage/02-error-architecture`](./03-error-manage__02-error-architecture.md) | 100 | 100 | A+ |
| 7 | [`03-error-manage/02-error-architecture/04-error-modal`](./03-error-manage__02-error-architecture__04-error-modal.md) | 100 | 100 | A+ |
| 8 | [`03-error-manage/02-error-architecture/06-apperror-package`](./03-error-manage__02-error-architecture__06-apperror-package.md) | 100 | 100 | A+ |
| 9 | [`03-error-manage/03-error-code-registry`](./03-error-manage__03-error-code-registry.md) | 100 | 100 | A+ |
| 10 | [`05-split-db-architecture`](./05-split-db-architecture.md) | 100 | 100 | A+ |

## Full ranking
| Module | Impl | Comp | Align | Cons | Clar | Test | Maint | **Overall** | Grade | Blast |
|---|---:|---:|---:|---:|---:|---:|---:|---:|:-:|:-:|
| [`05-split-db-architecture/03-issues`](./05-split-db-architecture__03-issues.md) | 95 | 90 | 100 | 100 | 100 | 80 | 100 | **95** | A+ | 3 |
| [`06-seedable-config-architecture/03-issues`](./06-seedable-config-architecture__03-issues.md) | 95 | 90 | 100 | 100 | 100 | 80 | 100 | **95** | A+ | 3 |
| [`25-app-issues/02-consolidated-audit-findings`](./25-app-issues__02-consolidated-audit-findings.md) | 95 | 90 | 100 | 100 | 100 | 80 | 100 | **95** | A+ | 3 |
| [`02-coding-guidelines/10-research/01-research-index`](./02-coding-guidelines__10-research__01-research-index.md) | 100 | 80 | 100 | 100 | 100 | 96 | 100 | **96** | A+ | 7 |
| [`02-coding-guidelines/21-app/01-app-coding-rules`](./02-coding-guidelines__21-app__01-app-coding-rules.md) | 100 | 80 | 100 | 100 | 100 | 96 | 100 | **96** | A+ | 7 |
| [`02-coding-guidelines/22-app-issues/01-app-issue-templates`](./02-coding-guidelines__22-app-issues__01-app-issue-templates.md) | 100 | 80 | 100 | 100 | 100 | 96 | 100 | **96** | A+ | 7 |
| [`02-coding-guidelines/23-app-database/01-app-database-conventions`](./02-coding-guidelines__23-app-database__01-app-database-conventions.md) | 100 | 80 | 100 | 100 | 100 | 96 | 100 | **96** | A+ | 7 |
| [`02-coding-guidelines/24-app-design-system-and-ui/01-app-ui-conventions`](./02-coding-guidelines__24-app-design-system-and-ui__01-app-ui-conventions.md) | 100 | 80 | 100 | 100 | 100 | 96 | 100 | **96** | A+ | 7 |
| [`03-error-manage/03-error-code-registry/07-schemas`](./03-error-manage__03-error-code-registry__07-schemas.md) | 100 | 80 | 100 | 100 | 100 | 96 | 100 | **96** | A+ | 7 |
| [`03-error-manage/03-error-code-registry/08-linter-scripts`](./03-error-manage__03-error-code-registry__08-linter-scripts.md) | 100 | 80 | 100 | 100 | 100 | 96 | 100 | **96** | A+ | 7 |
| [`10-research/01-research-index`](./10-research__01-research-index.md) | 100 | 80 | 100 | 100 | 100 | 96 | 100 | **96** | A+ | 7 |
| [`14-update/diagrams/01-diagram-conventions`](./14-update__diagrams__01-diagram-conventions.md) | 100 | 80 | 100 | 100 | 100 | 96 | 100 | **96** | A+ | 7 |
| [`26-gitlogs-diagrams/01-diagram-conventions`](./26-gitlogs-diagrams__01-diagram-conventions.md) | 100 | 80 | 100 | 100 | 100 | 96 | 100 | **96** | A+ | 7 |
| [`01-spec-authoring-guide`](./01-spec-authoring-guide.md) | 100 | 85 | 100 | 100 | 100 | 100 | 90 | **97** | A+ | 7 |
| [`02-coding-guidelines/09-powershell-integration`](./02-coding-guidelines__09-powershell-integration.md) | 100 | 85 | 100 | 100 | 100 | 100 | 100 | **97** | A+ | 7 |
| [`02-coding-guidelines/21-app`](./02-coding-guidelines__21-app.md) | 100 | 90 | 100 | 100 | 100 | 80 | 100 | **97** | A+ | 4 |
| [`02-coding-guidelines/23-app-database`](./02-coding-guidelines__23-app-database.md) | 100 | 90 | 100 | 100 | 100 | 80 | 100 | **97** | A+ | 4 |
| [`02-coding-guidelines/24-app-design-system-and-ui`](./02-coding-guidelines__24-app-design-system-and-ui.md) | 100 | 90 | 100 | 100 | 100 | 80 | 100 | **97** | A+ | 4 |
| [`03-error-manage/01-error-resolution/04-verification-patterns`](./03-error-manage__01-error-resolution__04-verification-patterns.md) | 100 | 85 | 100 | 100 | 100 | 100 | 100 | **97** | A+ | 7 |
| [`03-error-manage/01-error-resolution/app-issues`](./03-error-manage__01-error-resolution__app-issues.md) | 100 | 85 | 100 | 100 | 100 | 100 | 100 | **97** | A+ | 7 |
| [`03-error-manage/02-error-architecture/05-response-envelope`](./03-error-manage__02-error-architecture__05-response-envelope.md) | 100 | 85 | 100 | 100 | 100 | 100 | 100 | **97** | A+ | 7 |
| [`17-consolidated-guidelines`](./17-consolidated-guidelines.md) | 100 | 85 | 100 | 100 | 100 | 100 | 90 | **97** | A+ | 7 |
| [`27-spec-toolchain`](./27-spec-toolchain.md) | 100 | 90 | 100 | 100 | 100 | 80 | 100 | **97** | A+ | 2 |
| [`02-coding-guidelines/01-cross-language/02-boolean-principles`](./02-coding-guidelines__01-cross-language__02-boolean-principles.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/01-cross-language/04-code-style`](./02-coding-guidelines__01-cross-language__04-code-style.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/01-cross-language/15-master-coding-guidelines`](./02-coding-guidelines__01-cross-language__15-master-coding-guidelines.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/01-cross-language/16-static-analysis`](./02-coding-guidelines__01-cross-language__16-static-analysis.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/02-typescript`](./02-coding-guidelines__02-typescript.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/03-golang/01-enum-specification`](./02-coding-guidelines__03-golang__01-enum-specification.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/03-golang/04-golang-standards-reference`](./02-coding-guidelines__03-golang__04-golang-standards-reference.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/04-php/07-php-standards-reference`](./02-coding-guidelines__04-php__07-php-standards-reference.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/05-rust`](./02-coding-guidelines__05-rust.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 5 |
| [`02-coding-guidelines/06-ai-optimization`](./02-coding-guidelines__06-ai-optimization.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/06-cicd-integration`](./02-coding-guidelines__06-cicd-integration.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/07-csharp`](./02-coding-guidelines__07-csharp.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/08-file-folder-naming`](./02-coding-guidelines__08-file-folder-naming.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`02-coding-guidelines/11-security`](./02-coding-guidelines__11-security.md) | 100 | 90 | 100 | 100 | 100 | 96 | 100 | **98** | A+ | 9 |
| [`02-coding-guidelines/11-security/01-axios-version-control`](./02-coding-guidelines__11-security__01-axios-version-control.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`03-error-manage/01-error-resolution/03-retrospectives`](./03-error-manage__01-error-resolution__03-retrospectives.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`03-error-manage/01-error-resolution/05-debugging-guides`](./03-error-manage__01-error-resolution__05-debugging-guides.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`03-error-manage/02-error-architecture/04-error-modal/01-copy-formats`](./03-error-manage__02-error-architecture__04-error-modal__01-copy-formats.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`03-error-manage/02-error-architecture/04-error-modal/02-react-components`](./03-error-manage__02-error-architecture__04-error-modal__02-react-components.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`03-error-manage/02-error-architecture/04-error-modal/03-error-modal-reference`](./03-error-manage__02-error-architecture__04-error-modal__03-error-modal-reference.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`03-error-manage/02-error-architecture/04-error-modal/04-color-themes`](./03-error-manage__02-error-architecture__04-error-modal__04-color-themes.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference`](./03-error-manage__02-error-architecture__06-apperror-package__01-apperror-reference.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`03-error-manage/02-error-architecture/07-logging-and-diagnostics`](./03-error-manage__02-error-architecture__07-logging-and-diagnostics.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`03-error-manage/03-error-code-registry/09-templates`](./03-error-manage__03-error-code-registry__09-templates.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`04-database-conventions`](./04-database-conventions.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`05-split-db-architecture/02-features`](./05-split-db-architecture__02-features.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 5 |
| [`06-seedable-config-architecture/02-features`](./06-seedable-config-architecture__02-features.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 5 |
| [`07-design-system`](./07-design-system.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`11-powershell-integration`](./11-powershell-integration.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`12-cicd-pipeline-workflows/01-browser-extension-deploy`](./12-cicd-pipeline-workflows__01-browser-extension-deploy.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`12-cicd-pipeline-workflows/02-go-binary-deploy`](./12-cicd-pipeline-workflows__02-go-binary-deploy.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`12-cicd-pipeline-workflows/03-reusable-ci-guards`](./12-cicd-pipeline-workflows__03-reusable-ci-guards.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`13-generic-cli`](./13-generic-cli.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 5 |
| [`14-update/24-update-check-mechanism`](./14-update__24-update-check-mechanism.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`15-distribution-and-runner`](./15-distribution-and-runner.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`16-generic-release`](./16-generic-release.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`18-wp-plugin-how-to/02-enums-and-coding-style`](./18-wp-plugin-how-to__02-enums-and-coding-style.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`22-git-logs-v2`](./22-git-logs-v2.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 5 |
| [`23-app-database`](./23-app-database.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`24-app-design-system-and-ui`](./24-app-design-system-and-ui.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`25-app-issues/01-phase-2-git-logs-audit`](./25-app-issues__01-phase-2-git-logs-audit.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`28-universal-ci-cli`](./28-universal-ci-cli.md) | 100 | 90 | 100 | 100 | 100 | 100 | 100 | **98** | A+ | 7 |
| [`.`](./..md) | 100 | 100 | 100 | 100 | 100 | 80 | 100 | **99** | A+ | 10 |
| [`02-coding-guidelines`](./02-coding-guidelines.md) | 100 | 95 | 100 | 100 | 100 | 100 | 90 | **99** | A+ | 10 |
| [`02-coding-guidelines/10-research`](./02-coding-guidelines__10-research.md) | 100 | 100 | 100 | 100 | 100 | 80 | 100 | **99** | A+ | 4 |
| [`02-coding-guidelines/22-app-issues`](./02-coding-guidelines__22-app-issues.md) | 100 | 100 | 100 | 100 | 100 | 80 | 100 | **99** | A+ | 4 |
| [`10-research`](./10-research.md) | 100 | 100 | 100 | 100 | 100 | 80 | 100 | **99** | A+ | 4 |
| [`14-update/diagrams`](./14-update__diagrams.md) | 100 | 100 | 100 | 100 | 100 | 80 | 100 | **99** | A+ | 6 |
| [`25-app-issues`](./25-app-issues.md) | 100 | 100 | 100 | 100 | 100 | 80 | 100 | **99** | A+ | 6 |
| [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 100 | 100 | 100 | 100 | 100 | 80 | 100 | **99** | A+ | 6 |
| [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |
| [`02-coding-guidelines/03-golang`](./02-coding-guidelines__03-golang.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |
| [`02-coding-guidelines/04-php`](./02-coding-guidelines__04-php.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 9 |
| [`03-error-manage`](./03-error-manage.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |
| [`03-error-manage/01-error-resolution`](./03-error-manage__01-error-resolution.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |
| [`03-error-manage/02-error-architecture`](./03-error-manage__02-error-architecture.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |
| [`03-error-manage/02-error-architecture/04-error-modal`](./03-error-manage__02-error-architecture__04-error-modal.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |
| [`03-error-manage/02-error-architecture/06-apperror-package`](./03-error-manage__02-error-architecture__06-apperror-package.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 9 |
| [`03-error-manage/03-error-code-registry`](./03-error-manage__03-error-code-registry.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |
| [`05-split-db-architecture`](./05-split-db-architecture.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 9 |
| [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |
| [`12-cicd-pipeline-workflows`](./12-cicd-pipeline-workflows.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |
| [`14-update`](./14-update.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 10 |
| [`18-wp-plugin-how-to`](./18-wp-plugin-how-to.md) | 100 | 100 | 100 | 100 | 100 | 100 | 100 | **100** | A+ | 9 |