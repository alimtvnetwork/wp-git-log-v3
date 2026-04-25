# Acceptance Criteria — 03 Error Modal Reference

**Version:** 2.0.0  
**Updated:** 2026-04-25  
**Scope:** `spec/03-error-manage/02-error-architecture/04-error-modal/03-error-modal-reference/`  
**Generated:** AI-extracted Given/When/Then from module body via `linter-scripts/generate-gwt-acceptance.py`

---

## Module Summary

This module defines the data model, capture pipeline, and UI structure for the Global Error Modal. It focuses on rendering complex 3-hop diagnostic data (React -> Go -> PHP) into a tabbed interface with automated session fetching and markdown report generation.

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

// CapturedError Interface Snippet (src/components/errors/types.ts)
// id: string, code: string, level: 'error'|'warn'|'info', message: string, sessionId: string
// requestedAt: string (Go path), requestDelegatedAt: string (PHP URL)

// RawEnvelope Shape
// Status: { IsSuccess: boolean, Code: number, Message: string }
// Attributes: { RequestedAt, RequestDelegatedAt, SessionId, HasAnyErrors }
// Errors: { BackendMessage, DelegatedServiceErrorStack, Backend, DelegatedRequestServer }
// MethodsStack: { Backend: Array<{Method, File, LineNumber}> }

// DelegatedRequestServer Object
// { DelegatedEndpoint, Method, StatusCode, Response, StackTrace, AdditionalMessages }

// HTTP Status Badge Logic:
// responseStatus >= 400 ? 'destructive' : 'secondary'

// Section Constants:
// SECTIONS: ['backend', 'frontend', 'delegated']
// TABS_BACKEND: ['overview', 'log', 'execution', 'stack', 'session', 'request', 'traversal']
// TABS_FRONTEND: ['overview', 'stack', 'context', 'fixes']

---

## Acceptance Criteria

### AC-01: Conditional Delegated Logs Section Visibility  `[critical]`
- **Given** A `CapturedError` object populated after a failed API call where `envelopeErrors.DelegatedRequestServer` is present.
- **When** The `GlobalErrorModal` is opened.
- **Then** The modal must render the 'Delegated Logs' pill-style button in the Section Toggle with a green globe icon.
- **Verifies:** 04-modal-structure.md

### AC-02: Backend Message Banner Mapping  `[high]`
- **Given** A backend error response containing `envelopeErrors.BackendMessage`.
- **When** The user selects the 'Backend' section and 'Overview' tab.
- **Then** The 'Overview' tab in the Backend section must display a red-themed banner containing that specific message.
- **Verifies:** 05-backend-tabs.md

### AC-03: Session Diagnostics Auto-fetch Trigger  `[medium]`
- **Given** A captured error with a valid `sessionId` and an active `BackendSection`.
- **When** The backend section is rendered.
- **Then** The `useSessionDiagnostics` hook must trigger a fetch to `/api/v1/sessions/{id}/logs` and `/api/v1/sessions/{id}/diagnostics` and display the 'Session' tab.
- **Verifies:** 09-session-diagnostics.md

### AC-04: Go Call Chain Table Rendering  `[medium]`
- **Given** The modal is open with an error containing `envelopeMethodsStack.Backend` data.
- **When** The user navigates to Backend -> Execution.
- **Then** The 'Execution' tab must render a sortable table with columns: '#', 'Method', 'File', and 'Line'.
- **Verifies:** 03-envelope-parsing.md

### AC-05: 3-Hop Request Chain Visualization  `[high]`
- **Given** The 'Request' tab in the Backend section is active.
- **When** The error contains both `requestedAt` and `requestDelegatedAt`.
- **Then** A 3-node visualization must appear showing 'React -> Go', 'Go -> Delegated', and 'Delegated Response' with their respective HTTP status badges and URLs.
- **Verifies:** 07-request-chain.md

### AC-06: Compact Report Scrubbing Rules  `[medium]`
- **Given** The 'Compact Report' copy action is triggered.
- **When** The user clicks the primary 'Copy' split-button.
- **Then** The generated markdown must strip timestamps and base API URLs from the execution chain while retaining the relative paths.
- **Verifies:** 10-report-generation.md

### AC-07: Multi-Error Queue Navigation UI  `[medium]`
- **Given** An error in the queue is currently displayed in the modal.
- **When** `errorQueue.length` is greater than 1.
- **Then** The header must show a badge with the index (e.g., '1/3') and Chevron buttons that invoke `navigateQueue`.
- **Verifies:** 11-queue-navigation.md

### AC-08: PHP Error Stack Theming in Traversal  `[low]`
- **Given** The Traversal tab is active and `envelopeErrors.DelegatedServiceErrorStack` contains data.
- **When** Rendering Traversal details.
- **Then** The output must be rendered in an orange-themed `ScrollArea` labeled as PHP error lines.
- **Verifies:** 08-traversal-details.md

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)