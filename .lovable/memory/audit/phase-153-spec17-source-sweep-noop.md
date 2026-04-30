---
name: Phase 153 — spec/17 `**Source:**` sweep CLOSED no-op
description: Lesson #40 candidate dissolved on inspection — all 78 `**Source:**` lines in spec/17 are provenance citations on document banners and section headings, not on AC blocks. `**Source:**` ≠ `**Verifies:**` rule (Core memory) is intact tree-wide. Zero AC↔Source adjacency.
phase: 153
status: closed-noop
---

# Phase 153 — spec/17 `**Source:**` sweep CLOSED no-op

## Investigation

```
$ grep -rn '^\*\*Source:\*\*' spec/17-consolidated-guidelines/ | wc -l
78

$ grep -B2 '^\*\*Source:\*\*' spec/17-consolidated-guidelines/02-coding-guidelines.md
# Consolidated: Coding Guidelines — Complete Reference
**Source:** [`../02-coding-guidelines/`](../02-coding-guidelines/)
--
## 1. Naming Conventions — Zero-Underscore Policy
**Source:** `01-cross-language/11-key-naming-pascalcase.md`, ...
--
## 2. Boolean Principles (P1–P8)
**Source:** `01-cross-language/02-boolean-principles/` (5 files)
...
```

All 78 lines anchor on `# H1` document banners or `## H2` section headings — never on `### AC-XX-NN` AC blocks (adjacency check empty).

## Verdict

`**Source:**` is the correct provenance marker for spec/17 (consolidated-guidelines) — it points readers back to the canonical authoring module. This is **fundamentally different** from `**Verifies:**`, which binds an AC to its protected invariant. Conflating them would corrupt the audit-confidence walker (Phase 153 Task #29d's `INVENTORY_BARE_RE` widening explicitly relies on the distinction).

## Lesson #44 — provenance-marker disambiguation

`**Source:**` (provenance, points BACKWARD to canonical authoring module) and `**Verifies:**` (audit binding, points TO an invariant the AC protects) are orthogonal markers. Future "Source→Verifies sweep" suggestions MUST first run:

```bash
grep -B2 '^\*\*Source:\*\*' <module>/*.md | grep -E '^### AC-'
```

If empty → no-op (Source lines are provenance on headings, not AC bindings). Suggesting the sweep without this check wastes a phase.

## No spec edits

Pure verification phase. No banner bumps, no §97/§98/§99 changes.
