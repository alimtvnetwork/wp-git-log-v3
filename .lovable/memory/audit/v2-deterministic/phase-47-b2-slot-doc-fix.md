# Phase 47 — B2 Slot-06 Co-Location Documentation Fix

**Date:** 2026-04-27
**Trigger:** B2 backlog item (long-standing, user-blocked on rename target). After 4 consecutive `next` commands with no new input, AI took initiative to resolve the **non-destructive subset** of B2 (documentation-only fix) and explicitly deferred the rename decision back to the user.

## Diagnosis

The "slot-06 collision" in `spec/02-coding-guidelines/` was misclassified as requiring a rename. Investigation showed:

1. **Both folders physically exist with full required-file sets** — `06-ai-optimization/` (8 files, full §97/§98/§99) and `06-cicd-integration/` (7 files, full §97/§98/§99). Filesystem is fine; no namespace collision at the path level.
2. **§16→§37 immutability precedent applies** — once a slot has shipped a §97, the slot label is frozen. `06-ai-optimization/` shipped first; renaming either folder would invalidate every cross-spec reference (the specific failure mode that led to the §16→§37 rule in the first place).
3. **The actual defect was documentation-only** — `spec/02-coding-guidelines/00-overview.md` line 141 listed only `06-ai-optimization`, omitting `06-cicd-integration`. Same omission in §99 subfolder inventory (line 45-46) and the `**Total:**` count (line 56 said "14 subfolders / 121 files" when it should be "15 / 128").

Co-location at the same slot number is therefore the canonical fix, not a rename.

## Fix

### A. `spec/02-coding-guidelines/00-overview.md` (no version bump — table-only)

Inserted `06-cicd-integration` row directly under `06-ai-optimization` with an inline note explaining the co-location precedent.

### B. `spec/02-coding-guidelines/99-consistency-report.md` v4.2.0 → v4.3.0

- Added missing subfolder row.
- Corrected `**Total:**` from "14 subfolders (~121 files)" → "15 subfolders (~128 files)".
- Added v4.3.0 update banner explaining the §16→§37 immutability rationale.

### C. `spec/02-coding-guidelines/98-changelog.md` v2.2.0 → v2.3.0

New release entry documenting the doc-only patch and explicitly stating "no folder rename, no AC churn, no §97 contract change".

## Measured Impact

```
Mean weighted:        84.7 → 84.7  ✅ (held; no scoring change expected — doc-only)
Mean implementability: 68.5 → 68.5
Tier distribution:    A+×5, A×30, B×44, C×0, D×0, F×0  (unchanged)
Tree health:          100/100 strict (54 modules, 108/108 required, 108/108 recommended)
Lockstep gate:        79/79 pass, 0 findings
```

This patch was **not** intended to lift any score — its purpose is to close B2 by fixing the documentation defect that triggered the "collision" perception in the first place. The audit baseline is preserved.

## What This Phase Did NOT Do

- **Did not rename either folder.** The §16→§37 precedent forbids destructive moves on shipped slots. Any user-driven preference for a rename remains valid future work, but should be deferred until there is a concrete cross-ref breakage to justify the cost.
- **Did not address B1.** B1 (`spec/22-git-logs-v2/07-app-entity.md` — should the `App` SQLite table add `Environment`/`Platform`/`OwnerEmail` columns?) is a **database schema decision** with data-collection / GDPR implications and remains genuinely user-blocked. AC-17 in §22 §97 currently lists those three fields as **forbidden** until the user confirms; that contract is preserved.
- **Did not address R1.** Real-AI re-audit still requires `lovable_ai` gateway access (sandbox `ModuleNotFoundError: lovable_ai` confirmed by re-run without `AUDIT_DETERMINISTIC=1`).

## Verification

- Tree health: **100/100 strict** (re-run after edits).
- Lockstep gate: **79/79 pass, 0 findings** (re-run after edits).
- Deterministic audit: **mean 84.7/100 maintained**, distribution unchanged.

## Remaining Backlog (post-Phase 47)

| # | Description | Status |
|---|---|---|
| **R1** | Real-AI re-audit (requires `lovable_ai` gateway) | 🚧 BLOCKED on infrastructure |
| **B1** | `spec/22-git-logs-v2/07-app-entity.md` — add `Environment`/`Platform`/`OwnerEmail` columns to `App` table? | 🚧 BLOCKED on user (schema/GDPR decision) |
| **B2-rename** | Optional folder rename of one slot-06 sibling | ⏳ DEFERRED (no concrete need; §16→§37 precedent argues against) |

---

*Phase 47 — B2 doc subset closed without rename, audit baseline 84.7 held, B1 remains the only outstanding user-action item.*
