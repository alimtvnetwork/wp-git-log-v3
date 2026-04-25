# Acceptance Criteria — 05 Response Envelope

**Version:** 2.0.0  
**Updated:** 2026-04-25  
**Scope:** `spec/03-error-manage/02-error-architecture/05-response-envelope/`  
**Generated:** AI-extracted Given/When/Then from module body via `linter-scripts/generate-gwt-acceptance.py`

---

## Module Summary

Defines a universal API response envelope used across Go, PHP, and React. It standardizes PascalCase keys, mandates that results are always returned as arrays, and provides structured error/delegation metadata.

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

ENUMS & CONSTANTS:
- Key Convention: PascalCase
- Config Keys: 'pagination.defaultPerPage', 'responseDebug.includeErrors', 'responseDebug.includeStackTrace', 'responseDebug.includeDelegatedServerInfo', 'responseDebug.includeMethodsStack'

DB / JSON SCHEMA:
Top Level:
- Status (Object, Required)
- Attributes (Object, Required)
- Results (Array, Required)
- Navigation (Object, Optional/Pointer)
- Errors (Object, Optional/Pointer)
- MethodsStack (Object, Optional/Pointer)

Status Object:
- IsSuccess: bool
- IsFailed: bool
- Code: int (HTTP Status)
- Message: string
- Timestamp: string (ISO 8601)

DelegatedRequestServer Object:
- DelegatedEndpoint: string
- Method: string
- StatusCode: int
- RequestBody: object|null
- Response: object|null
- StackTrace: string[]
- AdditionalMessages: string

---

## Acceptance Criteria

### AC-01: Enforce PascalCase Key Convention  `[critical]`
- **Given** The PascalCase key convention is mandated by ADR 01-adr.md and the JSON schema referenced in 02-changelog.md
- **When** Any backend (Go or PHP) marshals a response envelope to JSON.
- **Then** The API must return JSON keys in PascalCase (e.g., 'IsSuccess', 'TotalRecords') and the Go implementation must utilize default marshaling without camelCase struct tags.
- **Verifies:** spec/03-error-manage/02-error-architecture/05-response-envelope/01-adr.md

### AC-02: Results Field Consistency as Array  `[high]`
- **Given** A request for a single resource (e.g., a specific user) is made.
- **When** The API processes a single-item response.
- **Then** The 'Results' field must still be returned as an array containing exactly one object ('Results': [ {...} ]) as per the ADR and reference docs.
- **Verifies:** spec/03-error-manage/02-error-architecture/05-response-envelope/04-response-envelope-reference.md

### AC-03: Conditional Errors Block Omission  `[medium]`
- **Given** The backend configuration has 'responseDebug.includeErrors' set to false.
- **When** A request fails but error reporting is disabled in config.json.
- **Then** The 'Errors' top-level key must be omitted entirely from the JSON response using Go's 'omitempty' pointer behavior.
- **Verifies:** spec/03-error-manage/02-error-architecture/05-response-envelope/03-configurability.md

### AC-04: Structured Delegated Error Capture  `[high]`
- **Given** The Go backend proxies a request to a WordPress/PHP companion plugin.
- **When** The delegated request returns an HTTP status code >= 400.
- **Then** The 'Errors.DelegatedRequestServer' object must contain 'DelegatedEndpoint', 'Method', 'StatusCode', 'RequestBody', and 'Response' from the downstream call.
- **Verifies:** spec/03-error-manage/02-error-architecture/05-response-envelope/03-configurability.md

### AC-05: Absolute URL Pagination Links  `[medium]`
- **Given** A list response contains enough items to span multiple pages (TotalPages > 1).
- **When** The 'Navigation' block is present in the response.
- **Then** The 'Navigation.NextPage' and 'Navigation.PrevPage' fields must contain fully qualified, absolute URL strings (e.g., 'http://localhost:8080/api/v1/plugins?page=2').
- **Verifies:** spec/03-error-manage/02-error-architecture/05-response-envelope/01-adr.md

### AC-06: Attribute Descriptor Correctness  `[low]`
- **Given** A response contains a successful single-item payload.
- **When** The response envelope is generated.
- **Then** In 'Attributes', 'IsSingle' must be true, 'IsMultiple' must be false, 'IsEmpty' must be false, and 'HasAnyErrors' must be false.
- **Verifies:** spec/03-error-manage/02-error-architecture/05-response-envelope/04-response-envelope-reference.md

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)