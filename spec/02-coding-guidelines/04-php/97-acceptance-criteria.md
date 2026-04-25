# Acceptance Criteria — 04 Php

**Version:** 2.0.0  
**Updated:** 2026-04-25  
**Scope:** `spec/02-coding-guidelines/04-php/`  
**Generated:** AI-extracted Given/When/Then from module body via `linter-scripts/generate-gwt-acceptance.py`

---

## Module Summary

Defines PHP coding standards for the RiseupAsia namespace, focusing on PHP 8.1+ backed enums, camelCase naming, strict spacing/import rules, and standardized service result structures using ResultHelper and ResponseKeyType.

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

# Domain Enums and Helpers
- Namespace: `RiseupAsia\Enums`
- Namespace: `RiseupAsia\Helpers`
- Path: `includes/Enums/`
- Path: `includes/Helpers/`

# Required Enum Method: isEqual
public function isEqual(self $other): bool {
    return $this === $other;
}

# ResultHelper Methods
- ResultHelper::ok(array $extra = [])
- ResultHelper::failed(array $extra = [])
- ResultHelper::error(string $msg, array $extra = [])
- ResultHelper::errorWithCode(string $msg, string $code, array $extra = [])
- ResultHelper::errorFromException(Throwable $e, array $extra = [])

# Standard Enums (Partial List)
- HookType (cases: Init, PluginsLoaded, RestApiInit, AdminInit, etc.)
- ResponseKeyType (cases: Success, Error, Message, Data, Code, Valid, Rows, Size, etc.)
- UploadSourceType (cases: Script, RestApi, AdminUi, WpCli)
- CapabilityType
- HttpMethodType

# ResponseKeyType Value Casing
- All enum case string values MUST be PascalCase (e.g., 'Success', 'SnapshotId').

# Identifier Casing Summary
- Class/Enum/Interface/Trait: PascalCase
- Enum Cases: PascalCase
- Methods/Functions: camelCase
- Variables: camelCase (Boolean prefix: is / has)
- Constants: UPPER_SNAKE_CASE
- Log Context Keys: camelCase (reusable ones use ResponseKeyType)


---

## Acceptance Criteria

### AC-01: Enum Naming and Required Structure  `[critical]`
- **Given** A new PHP enum being created for 'Upload Status' in `includes/Enums/`
- **When** Defining the enum name and class body
- **Then** The enum MUST be named `UploadStatusType`, use the `RiseupAsia\Enums` namespace, be string-backed, and implement the `isEqual(self $other): bool` method.
- **Verifies:** 01-enums.md

### AC-02: Enum Case Casing Convention  `[high]`
- **Given** Any PHP backed enum in the `RiseupAsia` namespace
- **When** Declaring enum cases
- **Then** Individual cases MUST use PascalCase (e.g., `case RestApi`), NOT SCREAMING_SNAKE_CASE.
- **Verifies:** 03-naming-conventions.md

### AC-03: Internal Service Result Standardization  `[critical]`
- **Given** An internal service method returning a result in the `RiseupAsia` namespace
- **When** Returning data or status from a service method
- **Then** It MUST return a structured array via `ResultHelper::ok()`, `ResultHelper::error()`, or `ResultHelper::failed()`, and any array keys MUST use `ResponseKeyType` cases (e.g., `ResponseKeyType::Rows->value`).
- **Verifies:** 05-response-array-standard.md

### AC-04: Spacing for Control Flow and Exceptions  `[medium]`
- **Given** A line of code inside a class method using the `if` or `throw` keywords
- **When** Writing control flow logic
- **Then** There MUST be a blank line before the `if` or `throw` if preceded by other statements, unless it is at the very start of the function or immediately follows a closing brace `}`.
- **Verifies:** 08-spacing-and-imports.md

### AC-05: No Leading Backslash for Global Types  `[high]`
- **Given** A PHP file within the `RiseupAsia` namespace using global PHP types like `Throwable` or `RuntimeException`
- **When** Referencing global PHP classes or exceptions
- **Then** The code MUST use `use` imports at the top and reference the class without a leading backslash (e.g., `catch (Throwable $e)` not `catch (\Throwable $e)`).
- **Verifies:** 08-spacing-and-imports.md

### AC-06: REST Error Handling Guardrails  `[critical]`
- **Given** A REST API handler in a WordPress companion plugin context
- **When** Implementing endpoint logic
- **Then** The handler MUST NOT use `wp_die()` and MUST wrap execution in `$this->safeExecute(fn() => ...)` to ensure structured JSON error responses.
- **Verifies:** 02-forbidden-patterns.md

### AC-07: Boolean Variable Naming Convention  `[medium]`
- **Given** A boolean variable being declared in a PHP context
- **When** Declaring variables
- **Then** It MUST use camelCase with an `is` or `has` prefix (e.g., `$isActive`, `$hasErrors`), specifically forbidding snake_case (e.g., `$is_active`).
- **Verifies:** 03-naming-conventions.md

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)