# Phase 153 Task S25-02 — spec/25 LOW D1 `Ambiguous 'Phase 153' references` link-don't-restate close-out

**Date:** 2026-05-03  
**Module:** `spec/25-app-issues/`  
**Status:** CLOSED (Lesson #36 link-don't-restate compliance pin)

## Audit finding (cache: `.lovable/cache/audit-ai/25-app-issues.json`)

```
[D1 LOW] Ambiguous 'Phase 153' references
why: The spec frequently references 'Phase 153' and 'Lesson #NN' which
     are external process artifacts not fully defined in the provided context.
fix: Add a brief glossary or link to a 'Process Fundamentals' module that
     defines Phase and Lesson terminology.
```

## Resolution

Two-part fix per Lesson #36 (link-don't-restate):

1. **`## Process Terminology` glossary in `00-overview.md`** — Term × Form × Authority table covering `Phase NN`, `Lesson #NN`, `Task XNN`, and `AC-NN` with one-hop pointers to `mem://index.md`, `mem://process/phase-153-lessons`, and `.lovable/memory/audit/v2-deterministic/`.

2. **AC-AI-17 `[low]`** in §97 codifies the link-don't-restate contract + enumerates 3 forbidden remediation patterns:
   - Inlining the full Phase/Lesson catalogue (creates dual-source drift)
   - Stripping all `Phase NN` / `Lesson #NN` references (loses contributor-process audit trail)
   - Promoting above LOW severity (these references are intentional bidirectional spec↔memory links)

Restating Phase/Lesson definitions inside spec/25 would violate Lesson #36 — the contributor-process memos are the authoritative source.

## Spec lockstep

| File | Before | After | Reason |
|------|--------|-------|--------|
| §97 acceptance-criteria | v1.5.0 | **v1.6.0** | New AC-AI-17 (count 16 → 17) |
| §00 overview | v3.5.1 | **v3.5.2** | New `## Process Terminology` section |
| §98 changelog | v3.5.1 | **v3.5.2** | Banner + release row |
| §99 consistency | v1.4.1 | **v1.4.2** | Banner + audit row |

**No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no gate-count change.**

## Gate verification

- Lockstep: 87/87 ✅
- Tree-health: 168/168 strict ✅
- Version-parity: 74/74 ✅

## Lesson reinforcement

This is the canonical link-don't-restate pattern (Lesson #36) applied to the **spec↔memory axis** (vs the cross-spec-module axis where Lesson #36 was originally codified at spec/04 §4.3 referencing spec/13 AC-22). Pattern: when an audit finding asks for a "glossary" of terms whose authority lives outside `spec/`, the resolution is a one-hop pointer table + a low-severity AC declaring the link-don't-restate contract — NOT inlining the external catalogue.
