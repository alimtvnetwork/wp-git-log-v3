---
kind: index
description: App-Specific Coding Rules — child module of `02-coding-guidelines/21-app/` populated in Phase 69 to lift the parent index from impl=70 to impl=80 (child_modules>0 bonus).
---

# App-Specific Coding Rules

**Version:** 1.0.0
**Updated:** 2026-04-27
**Parent:** [`../00-overview.md`](../00-overview.md)

---

## Overview

Tracker subfolder for coding rules that apply specifically to the App layer (not the CLI or shared libraries). Houses overrides for App-only conventions on top of the master coding guidelines.

---

## Inlined Contract

```text
INVARIANT-1: This subfolder MUST contain at least the four required files
             (00-overview.md, 97-acceptance-criteria.md, 98-changelog.md,
             99-consistency-report.md) at all times.
INVARIANT-2: Any new sibling subfolder added under the parent MUST follow
             this same 4-file layout to remain auditable.
INVARIANT-3: Promotion or removal of entries here MUST emit a corresponding
             {parent}/98-changelog.md entry on the same PR.
```

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Parent index | [`../00-overview.md`](../00-overview.md) |
| Parent acceptance criteria | [`../97-acceptance-criteria.md`](../97-acceptance-criteria.md) |
| Parent changelog | [`../98-changelog.md`](../98-changelog.md) |
