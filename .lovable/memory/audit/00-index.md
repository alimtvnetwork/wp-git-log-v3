# Spec-vs-Code Audit — Summary

**Date:** 2026-04-25  
**Modules audited:** 77  
**Code files indexed:** 24  
**Mean weighted score:** **59.1/100**

## Grade distribution
**A+** = 1, **A** = 9, **B** = 11, **C** = 10, **D** = 12, **F** = 34

## Findings by category
| Category | Count |
|---|---:|
| untestable | 65 |
| orphan-spec | 55 |
| drift | 47 |
| missing-spec | 38 |
| ambiguity | 33 |
| inconsistency | 22 |

## Findings by severity
| Severity | Count |
|---|---:|
| critical | 37 |
| high | 42 |
| medium | 95 |
| low | 86 |

## Bottom 15 (worst alignment/health)
| Rank | Module | Score | Grade | Top finding |
|---:|---|---:|:-:|---|
| 1 | [`03-error-manage/02-error-architecture/04-error-modal/01-copy-formats`](./03-error-manage__02-error-architecture__04-error-modal__01-copy-formats.md) | 21 | F | The entire spec module is orphaned; no implementation code for error reporting exists in t |
| 2 | [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 25 | F | Spec describes a production-ready system that does not exist in the code index. |
| 3 | [`02-coding-guidelines/04-php`](./02-coding-guidelines__04-php.md) | 28 | F | Spec describes an entire PHP framework/library that is missing from the provided code inde |
| 4 | [`24-app-design-system-and-ui`](./24-app-design-system-and-ui.md) | 28 | F | Spec describes an application UI and design system that do not exist in the code index. |
| 5 | [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 28 | F | Spec inventory and AC describe 8 Mermaid diagram files that are missing from the file inde |
| 6 | [`03-error-manage/02-error-architecture/04-error-modal/04-color-themes`](./03-error-manage__02-error-architecture__04-error-modal__04-color-themes.md) | 31 | F | Spec lacks Acceptance Criteria entirely. |
| 7 | [`02-coding-guidelines/24-app-design-system-and-ui`](./02-coding-guidelines__24-app-design-system-and-ui.md) | 33 | D | Spec folder is an empty shell with no actual design system definitions. |
| 8 | [`05-split-db-architecture`](./05-split-db-architecture.md) | 33 | F | Document Inventory references missing files. |
| 9 | [`02-coding-guidelines/02-typescript`](./02-coding-guidelines__02-typescript.md) | 38 | F | Spec focuses on ESLint while the repository uses custom linter scripts for enforcement. |
| 10 | [`03-error-manage/02-error-architecture/04-error-modal/02-react-components`](./03-error-manage__02-error-architecture__04-error-modal__02-react-components.md) | 38 | F | Spec describes a complete React error-handling UI system that does not exist in the codeba |
| 11 | [`23-app-database`](./23-app-database.md) | 39 | F | Spec is an empty shell with no defined data model or schema details. |
| 12 | [`02-coding-guidelines/23-app-database`](./02-coding-guidelines__23-app-database.md) | 40 | D | The spec module is a shell with no actual database design content despite being versioned  |
| 13 | [`03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference`](./03-error-manage__02-error-architecture__06-apperror-package__01-apperror-reference.md) | 40 | F | Spec describes a core Golang package that is entirely missing from the provided code index |
| 14 | [`03-error-manage/03-error-code-registry/07-schemas`](./03-error-manage__03-error-code-registry__07-schemas.md) | 40 | F | The spec describes JSON schemas that do not exist in the codebase. |
| 15 | [`03-error-manage/03-error-code-registry/08-linter-scripts`](./03-error-manage__03-error-code-registry__08-linter-scripts.md) | 40 | F | The spec describes a specific 'Error Code Registry' linter that is absent from the codebas |

## Top 10 (gold standards)
| Rank | Module | Score | Grade |
|---:|---|---:|:-:|
| 1 | [`17-consolidated-guidelines`](./17-consolidated-guidelines.md) | 98 | A+ |
| 2 | [`02-coding-guidelines/01-cross-language/04-code-style`](./02-coding-guidelines__01-cross-language__04-code-style.md) | 94 | A |
| 3 | [`05-split-db-architecture/02-features`](./05-split-db-architecture__02-features.md) | 94 | A |
| 4 | [`25-app-issues/01-phase-2-git-logs-audit`](./25-app-issues__01-phase-2-git-logs-audit.md) | 94 | A |
| 5 | [`25-app-issues/02-consolidated-audit-findings`](./25-app-issues__02-consolidated-audit-findings.md) | 92 | A |
| 6 | [`06-seedable-config-architecture/03-issues`](./06-seedable-config-architecture__03-issues.md) | 91 | A |
| 7 | [`02-coding-guidelines/01-cross-language/15-master-coding-guidelines`](./02-coding-guidelines__01-cross-language__15-master-coding-guidelines.md) | 90 | A |
| 8 | [`01-spec-authoring-guide`](./01-spec-authoring-guide.md) | 89 | A |
| 9 | [`02-coding-guidelines`](./02-coding-guidelines.md) | 88 | A |
| 10 | [`02-coding-guidelines/08-file-folder-naming`](./02-coding-guidelines__08-file-folder-naming.md) | 88 | A |

## Full ranking
| Module | Comp | Cons | Align | Clar | Maint | Test | **Overall** | Grade |
|---|---:|---:|---:|---:|---:|---:|---:|:-:|
| [`03-error-manage/02-error-architecture/04-error-modal/01-copy-formats`](./03-error-manage__02-error-architecture__04-error-modal__01-copy-formats.md) | 20 | 80 | 0 | 40 | 70 | 0 | **21** | F |
| [`06-seedable-config-architecture`](./06-seedable-config-architecture.md) | 30 | 40 | 0 | 60 | 70 | 20 | **25** | F |
| [`02-coding-guidelines/04-php`](./02-coding-guidelines__04-php.md) | 40 | 50 | 0 | 70 | 60 | 30 | **28** | F |
| [`24-app-design-system-and-ui`](./24-app-design-system-and-ui.md) | 20 | 50 | 0 | 40 | 60 | 20 | **28** | F |
| [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 30 | 50 | 0 | 70 | 60 | 40 | **28** | F |
| [`03-error-manage/02-error-architecture/04-error-modal/04-color-themes`](./03-error-manage__02-error-architecture__04-error-modal__04-color-themes.md) | 20 | 90 | 0 | 60 | 70 | 0 | **31** | F |
| [`02-coding-guidelines/24-app-design-system-and-ui`](./02-coding-guidelines__24-app-design-system-and-ui.md) | 20 | 50 | 0 | 60 | 70 | 30 | **33** | D |
| [`05-split-db-architecture`](./05-split-db-architecture.md) | 60 | 40 | 0 | 70 | 50 | 30 | **33** | F |
| [`02-coding-guidelines/02-typescript`](./02-coding-guidelines__02-typescript.md) | 80 | 70 | 0 | 60 | 70 | 30 | **38** | F |
| [`03-error-manage/02-error-architecture/04-error-modal/02-react-components`](./03-error-manage__02-error-architecture__04-error-modal__02-react-components.md) | 40 | 80 | 0 | 85 | 70 | 0 | **38** | F |
| [`23-app-database`](./23-app-database.md) | 5 | 90 | 10 | 70 | 80 | 60 | **39** | F |
| [`02-coding-guidelines/23-app-database`](./02-coding-guidelines__23-app-database.md) | 30 | 60 | 0 | 70 | 80 | 50 | **40** | D |
| [`03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference`](./03-error-manage__02-error-architecture__06-apperror-package__01-apperror-reference.md) | 40 | 80 | 0 | 85 | 90 | 0 | **40** | F |
| [`03-error-manage/03-error-code-registry/07-schemas`](./03-error-manage__03-error-code-registry__07-schemas.md) | 60 | 90 | 0 | 70 | 80 | 95 | **40** | F |
| [`03-error-manage/03-error-code-registry/08-linter-scripts`](./03-error-manage__03-error-code-registry__08-linter-scripts.md) | 40 | 80 | 10 | 70 | 60 | 30 | **40** | F |
| [`03-error-manage/01-error-resolution/03-retrospectives`](./03-error-manage__01-error-resolution__03-retrospectives.md) | 60 | 90 | 0 | 70 | 80 | 40 | **41** | F |
| [`03-error-manage/01-error-resolution/04-verification-patterns`](./03-error-manage__01-error-resolution__04-verification-patterns.md) | 90 | 80 | 0 | 85 | 70 | 60 | **43** | F |
| [`03-error-manage/03-error-code-registry/09-templates`](./03-error-manage__03-error-code-registry__09-templates.md) | 85 | 80 | 0 | 90 | 70 | 40 | **46** | D |
| [`02-coding-guidelines/03-golang`](./02-coding-guidelines__03-golang.md) | 40 | 80 | 20 | 70 | 80 | 30 | **48** | D |
| [`02-coding-guidelines/03-golang/01-enum-specification`](./02-coding-guidelines__03-golang__01-enum-specification.md) | 30 | 90 | 5 | 80 | 70 | 20 | **48** | F |
| [`02-coding-guidelines/04-php/07-php-standards-reference`](./02-coding-guidelines__04-php__07-php-standards-reference.md) | 90 | 85 | 0 | 80 | 70 | 80 | **48** | F |
| [`02-coding-guidelines/05-rust`](./02-coding-guidelines__05-rust.md) | 30 | 85 | 5 | 90 | 80 | 60 | **48** | F |
| [`03-error-manage/01-error-resolution/05-debugging-guides`](./03-error-manage__01-error-resolution__05-debugging-guides.md) | 90 | 85 | 0 | 80 | 70 | 10 | **48** | F |
| [`03-error-manage/01-error-resolution/app-issues`](./03-error-manage__01-error-resolution__app-issues.md) | 90 | 95 | 0 | 85 | 80 | 40 | **48** | F |
| [`03-error-manage/02-error-architecture/06-apperror-package`](./03-error-manage__02-error-architecture__06-apperror-package.md) | 80 | 70 | 0 | 60 | 80 | 75 | **49** | F |
| [`12-cicd-pipeline-workflows/01-browser-extension-deploy`](./12-cicd-pipeline-workflows__01-browser-extension-deploy.md) | 40 | 90 | 0 | 85 | 80 | 50 | **49** | F |
| [`18-wp-plugin-how-to/02-enums-and-coding-style`](./18-wp-plugin-how-to__02-enums-and-coding-style.md) | 80 | 90 | 0 | 90 | 85 | 30 | **49** | F |
| [`02-coding-guidelines/09-powershell-integration`](./02-coding-guidelines__09-powershell-integration.md) | 40 | 90 | 20 | 85 | 70 | 60 | **51** | D |
| [`03-error-manage/02-error-architecture`](./03-error-manage__02-error-architecture.md) | 85 | 95 | 0 | 90 | 80 | 70 | **51** | F |
| [`03-error-manage/02-error-architecture/04-error-modal`](./03-error-manage__02-error-architecture__04-error-modal.md) | 95 | 95 | 0 | 90 | 85 | 40 | **51** | F |
| [`03-error-manage/02-error-architecture/04-error-modal/03-error-modal-reference`](./03-error-manage__02-error-architecture__04-error-modal__03-error-modal-reference.md) | 85 | 95 | 0 | 90 | 90 | 80 | **51** | F |
| [`03-error-manage/02-error-architecture/05-response-envelope`](./03-error-manage__02-error-architecture__05-response-envelope.md) | 80 | 85 | 0 | 70 | 80 | 90 | **51** | F |
| [`03-error-manage/02-error-architecture/07-logging-and-diagnostics`](./03-error-manage__02-error-architecture__07-logging-and-diagnostics.md) | 60 | 90 | 0 | 70 | 80 | 40 | **51** | F |
| [`04-database-conventions`](./04-database-conventions.md) | 95 | 90 | 0 | 90 | 85 | 30 | **51** | F |
| [`07-design-system`](./07-design-system.md) | 95 | 95 | 0 | 90 | 90 | 85 | **51** | F |
| [`11-powershell-integration`](./11-powershell-integration.md) | 70 | 65 | 30 | 85 | 80 | 20 | **51** | D |
| [`12-cicd-pipeline-workflows/02-go-binary-deploy`](./12-cicd-pipeline-workflows__02-go-binary-deploy.md) | 95 | 100 | 0 | 90 | 90 | 80 | **51** | F |
| [`14-update`](./14-update.md) | 95 | 85 | 10 | 90 | 80 | 30 | **51** | D |
| [`14-update/24-update-check-mechanism`](./14-update__24-update-check-mechanism.md) | 95 | 95 | 0 | 90 | 90 | 70 | **51** | F |
| [`14-update/diagrams`](./14-update__diagrams.md) | 40 | 60 | 20 | 80 | 90 | 90 | **51** | D |
| [`03-error-manage`](./03-error-manage.md) | 85 | 95 | 15 | 90 | 80 | 70 | **54** | D |
| [`06-seedable-config-architecture/02-features`](./06-seedable-config-architecture__02-features.md) | 85 | 80 | 20 | 90 | 85 | 95 | **54** | D |
| [`22-git-logs-v2`](./22-git-logs-v2.md) | 90 | 80 | 0 | 85 | 85 | 70 | **54** | F |
| [`18-wp-plugin-how-to`](./18-wp-plugin-how-to.md) | 90 | 85 | 0 | 95 | 90 | 40 | **58** | C |
| [`25-app-issues`](./25-app-issues.md) | 50 | 60 | 40 | 90 | 80 | 95 | **58** | D |
| [`.`](./..md) | 50 | 60 | 80 | 85 | 85 | 0 | **60** | C |
| [`02-coding-guidelines/06-cicd-integration`](./02-coding-guidelines__06-cicd-integration.md) | 90 | 80 | 20 | 85 | 70 | 60 | **60** | F |
| [`12-cicd-pipeline-workflows/03-reusable-ci-guards`](./12-cicd-pipeline-workflows__03-reusable-ci-guards.md) | 60 | 80 | 40 | 70 | 85 | 50 | **60** | C |
| [`12-cicd-pipeline-workflows`](./12-cicd-pipeline-workflows.md) | 60 | 55 | 70 | 85 | 80 | 40 | **63** | C |
| [`02-coding-guidelines/07-csharp`](./02-coding-guidelines__07-csharp.md) | 95 | 95 | 0 | 90 | 90 | 60 | **64** | C |
| [`15-distribution-and-runner`](./15-distribution-and-runner.md) | 90 | 80 | 30 | 85 | 90 | 95 | **64** | D |
| [`03-error-manage/03-error-code-registry`](./03-error-manage__03-error-code-registry.md) | 70 | 80 | 60 | 85 | 90 | 40 | **70** | C |
| [`02-coding-guidelines/01-cross-language/16-static-analysis`](./02-coding-guidelines__01-cross-language__16-static-analysis.md) | 85 | 90 | 40 | 90 | 80 | 70 | **72** | C |
| [`02-coding-guidelines/10-research`](./02-coding-guidelines__10-research.md) | 40 | 95 | 100 | 90 | 100 | 80 | **72** | C |
| [`02-coding-guidelines/11-security`](./02-coding-guidelines__11-security.md) | 75 | 70 | 60 | 90 | 85 | 80 | **72** | C |
| [`16-generic-release`](./16-generic-release.md) | 85 | 95 | 40 | 90 | 90 | 60 | **72** | C |
| [`02-coding-guidelines/01-cross-language`](./02-coding-guidelines__01-cross-language.md) | 75 | 70 | 85 | 80 | 85 | 60 | **76** | B |
| [`02-coding-guidelines/11-security/01-axios-version-control`](./02-coding-guidelines__11-security__01-axios-version-control.md) | 80 | 90 | 70 | 95 | 85 | 60 | **79** | B |
| [`02-coding-guidelines/21-app`](./02-coding-guidelines__21-app.md) | 70 | 75 | 80 | 85 | 90 | 95 | **79** | B |
| [`02-coding-guidelines/22-app-issues`](./02-coding-guidelines__22-app-issues.md) | 70 | 80 | 90 | 85 | 90 | 85 | **80** | B |
| [`02-coding-guidelines/01-cross-language/02-boolean-principles`](./02-coding-guidelines__01-cross-language__02-boolean-principles.md) | 80 | 90 | 75 | 85 | 85 | 70 | **81** | B |
| [`03-error-manage/01-error-resolution`](./03-error-manage__01-error-resolution.md) | 80 | 85 | 75 | 90 | 85 | 80 | **82** | B |
| [`02-coding-guidelines/03-golang/04-golang-standards-reference`](./02-coding-guidelines__03-golang__04-golang-standards-reference.md) | 85 | 95 | 70 | 90 | 90 | 80 | **83** | B |
| [`02-coding-guidelines/06-ai-optimization`](./02-coding-guidelines__06-ai-optimization.md) | 90 | 90 | 75 | 85 | 80 | 70 | **83** | B |
| [`05-split-db-architecture/03-issues`](./05-split-db-architecture__03-issues.md) | 80 | 75 | 100 | 90 | 85 | 80 | **83** | B |
| [`10-research`](./10-research.md) | 85 | 70 | 95 | 80 | 90 | 80 | **83** | B |
| [`13-generic-cli`](./13-generic-cli.md) | 95 | 85 | 100 | 90 | 90 | 40 | **86** | B |
| [`02-coding-guidelines`](./02-coding-guidelines.md) | 85 | 85 | 90 | 90 | 95 | 80 | **88** | A |
| [`02-coding-guidelines/08-file-folder-naming`](./02-coding-guidelines__08-file-folder-naming.md) | 90 | 80 | 90 | 85 | 95 | 90 | **88** | A |
| [`01-spec-authoring-guide`](./01-spec-authoring-guide.md) | 85 | 90 | 90 | 95 | 95 | 80 | **89** | A |
| [`02-coding-guidelines/01-cross-language/15-master-coding-guidelines`](./02-coding-guidelines__01-cross-language__15-master-coding-guidelines.md) | 90 | 95 | 85 | 95 | 90 | 80 | **90** | A |
| [`06-seedable-config-architecture/03-issues`](./06-seedable-config-architecture__03-issues.md) | 95 | 80 | 100 | 85 | 100 | 90 | **91** | A |
| [`25-app-issues/02-consolidated-audit-findings`](./25-app-issues__02-consolidated-audit-findings.md) | 90 | 100 | 90 | 95 | 100 | 75 | **92** | A |
| [`02-coding-guidelines/01-cross-language/04-code-style`](./02-coding-guidelines__01-cross-language__04-code-style.md) | 95 | 100 | 90 | 90 | 100 | 85 | **94** | A |
| [`05-split-db-architecture/02-features`](./05-split-db-architecture__02-features.md) | 95 | 85 | 100 | 90 | 95 | 90 | **94** | A |
| [`25-app-issues/01-phase-2-git-logs-audit`](./25-app-issues__01-phase-2-git-logs-audit.md) | 90 | 95 | 100 | 95 | 90 | 90 | **94** | A |
| [`17-consolidated-guidelines`](./17-consolidated-guidelines.md) | 100 | 95 | 100 | 95 | 100 | 95 | **98** | A+ |