# Error Modal — Reusable React Components (Index)

> **Parent:** [Error Modal Spec](../00-overview.md)  
> **Version:** 4.0.0  
> **Updated:** 2026-04-27
> **AI Confidence:** 95%  
> **Ambiguity Score:** 5%  
> **Purpose:** Portable React code for rebuilding the Global Error Modal in any project.

---

## File Index

| # | File | Section | Description |
|---|------|---------|-------------|
| 01 | [01-typescript-interfaces.md](./01-typescript-interfaces.md) | TypeScript Interfaces | CapturedError, SessionDiagnostics, shared props |
| 02 | [02-error-store.md](./02-error-store.md) | Error Store (Zustand) | Store interface, key behaviors, stack trace parser |
| 03 | [03-api-types.md](./03-api-types.md) | API Types & Methods | Required API endpoints |
| 04 | [04-hooks.md](./04-hooks.md) | Hooks | useSessionDiagnostics |
| 05 | [05-component-hierarchy.md](./05-component-hierarchy.md) | Component Hierarchy | File structure + component props summary |
| 06 | [06-component-source.md](./06-component-source.md) | Component Source Code | All 7 major components with code patterns |
| 07 | [07-report-generator.md](./07-report-generator.md) | Error Report Generator | generateErrorReport + suggested fixes |
| 08 | [08-integration-guide.md](./08-integration-guide.md) | Integration Guide | Setup, React Query, utilities, adaptation |

---

## Architecture Overview

```
GlobalErrorModal (Dialog shell)
├── Header (error code, timestamp, queue navigation)
├── Section Toggle: Backend | Frontend
├── BackendSection (primary diagnostic view)
│   ├── Overview Tab
│   ├── Log Tab (error.log.txt viewer)
│   ├── Execution Tab (Go call chain + backend logs)
│   ├── Stack Tab (Go/PHP/Delegated stack frames)
│   ├── Session Tab (SessionLogsTab — 4 sub-tabs)
│   ├── Request Tab (RequestDetails — 3-hop chain)
│   └── Traversal Tab (TraversalDetails — endpoint flow)
├── FrontendSection
│   ├── Overview Tab (trigger, click path, call chain)
│   ├── Stack Tab (parsed/raw JS stack frames)
│   ├── Context Tab (JSON viewer)
│   └── Fixes Tab (suggested fixes by error code)
├── Footer
│   ├── DownloadDropdown (ZIP, error.log, log.txt, .md)
│   └── CopyDropdown (full report, with backend, logs)
```

**Dependencies:** React 18+, Zustand, Tailwind CSS, shadcn/ui (Dialog, Tabs, Badge, Button, ScrollArea, DropdownMenu), Lucide React icons.

---

## Document Inventory

| File |
|------|
| 99-consistency-report.md |


## Cross-References

- [Error Modal Spec](../03-error-modal-reference/00-overview.md) — Full modal structure, data model, and UX specification
- [Copy Format Samples](../01-copy-formats/00-overview.md) — Complete samples for all copy/export formats
- [Error Handling Spec](../../01-error-handling-reference.md) — Cross-stack error architecture
- [Response Envelope Schema](../../05-response-envelope/envelope.schema.json) — JSON Schema source of truth

---

*React components index — updated: 2026-03-31*

---

## Drift Acknowledgment

**Date:** 2026-04-26  
**Severity:** Low — doc-hygiene drift.

Header `Updated` vs footer `updated` timestamp drift is a known dual-source artifact; canonical source is the header banner.

Tracked under Phase 27d. See `.lovable/memory/index.md`.


---

## Normative Contract (Phase 50)

```text
CONTRACT: error-modal/react-components
PURPOSE: define the React component surface and props shape for rendering error events
SCOPE: TSX components consumed by every error-surfacing page in the app

INV-01  the public component MUST be named <ErrorModal> exported from index.ts
INV-02  required props: code:string, severity:'fatal'|'error'|'warn'|'info', message:string
INV-03  optional props: details?:string, actions?:Action[], onDismiss?:()=>void, traceId?:string
INV-04  the modal MUST trap focus while open and restore focus on close
INV-05  the modal MUST be dismissible via Escape unless severity === 'fatal'
INV-06  every action button MUST carry a stable testid: error-modal-action-<slug>
INV-07  the component MUST consume color tokens from the §03/02/04/04-color-themes contract

FAIL-01 hardcoded color literal in component → lint fails
FAIL-02 missing aria-modal / role="alertdialog" → a11y gate fails
FAIL-03 escape closes a fatal modal → unit test fails (regression)
FAIL-04 focus escapes the modal while open → e2e gate fails

DEL-01  color values delegated to §03/02/04/04-color-themes
DEL-02  copy/i18n delegated to §03/01-error-resolution
DEL-03  registry lookup of codes delegated to §03/03-error-code-registry
```
