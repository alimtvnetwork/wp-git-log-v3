---
description: App-Specific Coding Rules — content child module of `02-coding-guidelines/21-app/`. Carries an inlined contract, Mermaid lifecycle diagram, and full GWT acceptance criteria.
---

# App-Specific Coding Rules

**Version:** 2.0.0
**Updated:** 2026-04-27
**Parent:** [`../00-overview.md`](../00-overview.md)

---

## Overview

Catalog of App-layer coding overrides on top of the master coding guidelines. Each override declares the master rule it modifies and the rationale.

---

## Inlined Contract

```ts
// App coding-rule override entry
export type RuleStatus = "active" | "superseded" | "withdrawn";

export interface AppCodingOverride {
  id: string;                 // ^APP-COD-\d{3}$
  overrides: string;          // path to master guideline rule, e.g. "02-coding-guidelines/01-cross-language/04-code-style#max-line-length"
  rule: string;               // human-readable override text
  rationale: string;          // why App diverges
  status: RuleStatus;
  supersededBy?: string;      // id of replacement override
  authoredAt: string;         // ISO-8601 date
}

export const APP_CODING_RULE_PREFIX = "APP-COD-";
```

---

## Lifecycle Diagram

See [`lifecycle-coding-override.mmd`](./lifecycle-coding-override.mmd) for the complete authoring → validation → publication lifecycle.

```mermaid
flowchart TD
    A[App-Specific Need Identified] --> B[Author Override APP-COD-NNN]
    B --> C[Cross-link to Master Rule]
    C --> D{PR Review}
    D -- Approved --> E[Active]
    D -- Rejected --> F[Withdrawn]
    E --> G{Master Rule Updated?}
    G -- Yes, reconciled --> H[Superseded]
    G -- No --> E
```

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Parent index | [`../00-overview.md`](../00-overview.md) |
| Acceptance criteria | [`./97-acceptance-criteria.md`](./97-acceptance-criteria.md) |
| Lifecycle diagram source | [`./lifecycle-coding-override.mmd`](./lifecycle-coding-override.mmd) |
| Changelog | [`./98-changelog.md`](./98-changelog.md) |
| Consistency report | [`./99-consistency-report.md`](./99-consistency-report.md) |
