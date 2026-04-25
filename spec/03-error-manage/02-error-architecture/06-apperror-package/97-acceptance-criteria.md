# Acceptance Criteria — 06 Apperror Package

**Version:** 2.0.0  
**Updated:** 2026-04-25  
**Scope:** `spec/03-error-manage/02-error-architecture/06-apperror-package/`  
**Generated:** AI-extracted Given/When/Then from module body via `linter-scripts/generate-gwt-acceptance.py`

---

## Module Summary

The AppError package provides a unified error handling system for the Go application, including structured error types with stack traces, generic Result[T] containers for return values, and standardized domain-specific error codes (E1xxx-E14xxx).

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

// Error Code Domains (from 05-apperrtype-enums.md)
// E1xxx: System/Internal
// E2xxx: Validation/Request
// E3xxx: Authentication
// E4xxx: Authorization
// ... up to E14xxx (Domain Specific)

// Result Types (from 03-result-types.md)
type Result[T any] struct {
    Value T
    Error *AppError
}

type ResultSlice[T any] struct {
    Items []T
    Error *AppError
}

type ResultMap[K comparable, V any] struct {
    Data  map[K]V
    Error *AppError
}

// AppError Struct (from 02-apperror-struct.md)
type AppError struct {
    Code    AppErrType `json:"code"`
    Message string     `json:"message"`
    Stack   StackTrace `json:"stack"`
    Ref     string     `json:"ref"` // Trace ID or similar
}

// Enum Pattern (from cross-refs)
// Using byte-based enums with custom MarshalJSON/UnmarshalJSON.


---

## Acceptance Criteria

### AC-01: StackTrace Injection in AppError Constructors  `[critical]`
- **Given** A call to a constructor in 02-apperror-struct.md to create an AppError
- **When** An AppError instance is instantiated using the package's factory methods.
- **Then** The returned object must contain a StackTrace as defined in 01-overview-and-stack.md with accurate file and line information.
- **Verifies:** 01-overview-and-stack.md, 02-apperror-struct.md

### AC-02: Generic Result Type Container Integrity  `[critical]`
- **Given** A generic type T and the Result[T] definition in 03-result-types.md
- **When** A function returns a Result[T] to encapsulate failure or success.
- **Then** The Result type must provide access to either the value of type T or an AppError, but never both in a valid 'success' state.
- **Verifies:** 03-result-types.md

### AC-03: Domain-Specific Error Code Mapping  `[high]`
- **Given** The error code conventions in 04-codes-and-policy.md
- **When** An AppErrType enum value is assigned to an AppError.
- **Then** The error code must follow the E1xxx–E14xxx domain mapping defined in 05-apperrtype-enums.md.
- **Verifies:** 04-codes-and-policy.md, 05-apperrtype-enums.md

### AC-04: Adapter Pattern Error Conversion  `[medium]`
- **Given** The service adapter unwrap pattern from 05-usage-and-adapters.md
- **When** An external service error is processed by the AppError adapter.
- **Then** The adapter must correctly extract the inner error or convert a standard Go 'error' into an AppError before returning.
- **Verifies:** 05-usage-and-adapters.md

### AC-05: AppError JSON Serialization Masking  `[high]`
- **Given** The JSON serialization requirements in 06-serialization-and-guards.md and the Enum Specification cross-reference
- **When** The AppError is marshaled for delivery over an API boundary.
- **Then** The AppError and its internal AppErrType must be serialized to JSON using the byte-based enum pattern with mandatory JSON marshaling.
- **Verifies:** 06-serialization-and-guards.md, 05-apperrtype-enums.md

### AC-06: Result Guard Rule Enforcement  `[medium]`
- **Given** The Result guard rule in 06-serialization-and-guards.md
- **When** A programmer attempts to access Result[T].Value when Result[T].Error is present.
- **Then** The Result object must prevent access to the data payload if the internal AppError is non-nil/contains a failure code.
- **Verifies:** 06-serialization-and-guards.md

### AC-07: StackTrace Frame Skipping Logic  `[low]`
- **Given** The stack trace skip rules in 04-codes-and-policy.md
- **When** The runtime stack is captured during AppError creation.
- **Then** The StackTrace must correctly skip the internal apperror package frames so the caller's location is the head of the trace.
- **Verifies:** 04-codes-and-policy.md, 01-overview-and-stack.md

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)