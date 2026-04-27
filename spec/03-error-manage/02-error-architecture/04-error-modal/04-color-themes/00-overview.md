# Color Theme & Design Token Reference (Index)

> **Parent:** [Error Modal Spec](../00-overview.md)  
> **Version:** 2.1.0  
> **Updated:** 2026-04-27  
> **Purpose:** Definitive color mapping for every error-related UI element.

---

## File Index

| # | File | Description |
|---|------|-------------|
| 01 | [01-design-tokens.md](./01-design-tokens.md) | CSS custom properties (light/dark) + error level color mapping |
| 02 | [02-backend-tab-colors.md](./02-backend-tab-colors.md) | Backend section tab-specific colors (Overview, Stack, Session, Request, Traversal, Execution) |
| 03 | [03-frontend-and-ui-colors.md](./03-frontend-and-ui-colors.md) | Frontend section color themes + UI element colors (section toggle, error history drawer, queue badge, error boundary) |

---

## Two-Tier Color System

| Tier | Icon Color | Background | Text Color | Used For |
|------|-----------|------------|-----------|----------|
| **Go Backend** | — (Server icon) | `bg-muted` | `text-blue-500 dark:text-blue-400` (session frames) | Go stack traces, methods stack |
| **PHP / Delegated** | `text-orange-500` (AlertTriangle) | `bg-orange-500/5` | `text-orange-500/600/700` | PHP frames, delegated service errors |

> ⚠ There is **no purple theme** in the current codebase. All delegated/PHP-related UI uses orange.

---

---

- [Error Modal Reference](../03-error-modal-reference/00-overview.md)
- [LogLevel Enum](../../../../02-coding-guidelines/02-typescript/10-log-level-enum.md)

---

*Color theme index — updated: 2026-03-31*

---

## Normative Contract (Phase 50)

```text
CONTRACT: error-modal/color-themes
PURPOSE: define the semantic color contract for the error-modal across light/dark themes
SCOPE: token names + WCAG contrast invariants; concrete HSL values live in design-system

INV-01  every severity MUST map to exactly one token: --error-fatal, --error-error, --error-warn, --error-info
INV-02  every token MUST have a paired --*-foreground token with WCAG AA ≥ 4.5:1 contrast
INV-03  every token MUST be defined in BOTH light and dark theme blocks of index.css
INV-04  no component MAY hardcode hex/rgb/hsl literals for error chrome
INV-05  hover/active/focus variants MUST derive from the base token via opacity or HSL shift only
INV-06  token names MUST match the pattern --error-{severity}[-foreground|-muted|-border]

FAIL-01 hardcoded color literal in error-modal component → lint fails (severity=major)
FAIL-02 contrast ratio below 4.5:1 in either theme → a11y gate blocks PR
FAIL-03 token defined only in one theme → lockstep gate fails

DEL-01  concrete HSL values are owned by §07-design-system
DEL-02  the modal layout/markup is owned by sibling §03/02/04-error-modal/02-react-components
DEL-03  copy localization is owned by §03/01-error-resolution
```
