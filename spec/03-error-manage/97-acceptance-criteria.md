# Acceptance Criteria — 03 Error Manage

**Version:** 2.0.0  
**Updated:** 2026-04-25  
**Scope:** `spec/03-error-manage/`  
**Generated:** AI-extracted Given/When/Then from module body via `linter-scripts/generate-gwt-acceptance.py`

---

## Module Summary

Consolidated specification for cross-stack error management, defining a 3-tier architecture (PHP/Go/React), a universal response envelope for APIs, and a centralized master registry of error code ranges to prevent collisions.

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

ENUMS & FORMATS:
- Prefixed Code: ^[A-Z]{2,4}-[0-9]{3}-[0-9]{2}$
- Integer Code: ^[0-9]{4,5}$
- Response Envelope Keys: "Status" { "IsSuccess", "Code", "Message" }, "Attributes", "Results"

ERROR RANGES:
- GEN (Shared): 0-999
- SM (Spec Mgmt): 2000-2999
- GS (GSearch): 7000-7919
- AB (AI Bridge): 9000-9999, 19000-19049

ARCHITECTURE TIERS:
- Tier 1: Delegated Server (PHP/Other)
- Tier 2: Go Backend (apperror package)
- Tier 3: Frontend (React/Error Modal)

---

## Acceptance Criteria

### AC-01: Universal Response Envelope Compliance  `[critical]`
- **Given** A backend API response from a Go or PHP service
- **When** The API returns any data or error to the frontend
- **Then** The response MUST match the Universal Response Envelope containing 'Status', 'Attributes', and 'Results' keys as defined in 02-error-architecture/05-response-envelope/04-response-envelope-reference.md
- **Verifies:** 02-error-architecture/05-response-envelope/04-response-envelope-reference.md

### AC-02: HTTP Status Primary Indicator  `[high]`
- **Given** A frontend component processing an API response
- **When** Deciding whether to trigger the Global Error Modal or process results
- **Then** The detection logic MUST determine success or failure using HTTP status codes (2xx) and NOT fields within the JSON body (e.g., Status.IsSuccess)
- **Verifies:** 00-overview.md#3-http-status-as-primary-indicator

### AC-03: GSearch Error Code Range Allocation  `[medium]`
- **Given** A new error code for a Go CLI tool in the GSearch project
- **When** Assigning a new ID to a domain error
- **Then** The code MUST be an integer within the range 7000-7919 and registered in 03-error-code-registry/error-codes-master.json to avoid collisions
- **Verifies:** 03-error-code-registry/00-overview.md#registered-ranges-quick-reference

### AC-04: Prefixed Error Code Formatting  `[medium]`
- **Given** A PHP plugin or general specification error code
- **When** Defining error codes for non-Go backend services
- **Then** The code MUST follow the regex pattern ^[A-Z]{2,4}-[0-9]{3}-[0-9]{2}$ (e.g., SM-400-01)
- **Verifies:** 03-error-code-registry/00-overview.md#error-code-formats

### AC-05: Three-Tier Error Propagation  `[high]`
- **Given** An error occurring in Tier 1 (Delegated Server/PHP)
- **When** A cross-stack error requires frontend notification
- **Then** The error MUST be propagated through the Go Backend (Tier 2) using the apperror package and DelegatedRequestServer pattern before reaching the Tier 3 Frontend modal
- **Verifies:** 02-error-architecture/01-error-handling-reference.md

### AC-06: Notification Color Token Mapping  `[low]`
- **Given** The frontend application state
- **When** Displaying toast notifications or status indicators
- **Then** The notification system MUST apply specific color tokens as defined in 02-error-architecture/03-notification-colors.md based on the error code mapping
- **Verifies:** 02-error-architecture/03-notification-colors.md

### AC-07: AppError Struct and Type Enforcement  `[medium]`
- **Given** The Go backend 'apperror' package implementation
- **When** Constructing an error object in the Go service layer
- **Then** It MUST include specialized domain enums (AppErrType) and support stack trace serialization as defined in 02-error-architecture/06-apperror-package/01-apperror-reference/05-apperrtype-enums.md
- **Verifies:** 02-error-architecture/06-apperror-package/01-apperror-reference/05-apperrtype-enums.md

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)