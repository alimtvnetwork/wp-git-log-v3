---
name: phase-153-task-A2-genuine-criticals-resolution
description: Resolves the 3 genuine CRITICALs surfaced by audit-v2; outcome = 1 real fix (envelope inlining), 2 immunising pins (false-positive prevention)
type: feature
---

# Phase 153 Task A2 — Genuine CRITICALs from audit-v2

## Investigation outcomes

| # | Finding (v2) | Direct-inspection verdict | Action |
|---|---|---|---|
| 1 | spec/06 §00 PascalCase vs §01 camelCase schema conflict | **FALSE POSITIVE** — both files use PascalCase (`Version`, `Categories`). Auditor misread an ASCII box-drawing diagram. | Add canonical-naming pin to §00 immunising against future misreads. |
| 2 | spec/04 cross-module response-envelope dependency | **REAL gap for context-bounded AIs** — `06-rest-api-format.md` references `../03-error-manage/.../04-response-envelope-reference.md` 4× but the envelope shape isn't anywhere in `spec/04`. | Inline a verbatim envelope summary at end of §00 (top-level field table + reference JSON + Go `omitempty` note). Upstream remains source of truth. |
| 3 | spec/11 schemas/templates outside spec/ | **FALSE POSITIVE** — `spec/11-powershell-integration/{schemas,templates}/` exist on disk with `powershell.schema.json`, `powershell.json`, `run.ps1`. The v2 walker hit the 90 KB cap before reaching them (15/16 files). | No spec change. Future v3 harness should walk non-`*.md` files too OR raise the cap to 180 KB. |

## Edits

- `spec/04-database-conventions/00-overview.md` — appended ~35-line "Universal Response Envelope — Inlined Summary" section. Banner v3.3.2 → v3.3.3, h10 stamp 30 → 153.
- `spec/04-database-conventions/98-changelog.md` — release row 3.3.3.
- `spec/04-database-conventions/99-consistency-report.md` — v3.5.0 → v3.5.1.
- `spec/06-seedable-config-architecture/00-overview.md` — added "🔒 Canonical Naming Convention" callout immediately under banner. Banner v4.1.0 → v4.1.1, h10 stamp 22 → 153.
- `spec/06-seedable-config-architecture/98-changelog.md` — release row 4.1.1.
- `spec/06-seedable-config-architecture/99-consistency-report.md` — v4.1.0 → v4.1.1.

## Lockstep budget

Both modules patch-bumped (banner-only edits + new banner-adjacent prose; no AC change). Matches the precedent for Task #29e.

## Lessons

12. **AI-audit findings need ground-truth verification** — at least 2 of 3 v2 CRITICALs were misreads (one of an ASCII diagram, one of which files were in the 90 KB context window). Always grep/ls the cited files BEFORE rewriting them. Codifies the analogue of Phase 136 stale-prose lesson into the AI-audit workflow.
13. **Cross-module dependency closure is a real D5 gap class** — `spec/04` legitimately points to `spec/03` for the envelope contract; that's correct architecture. The fix is **inlining a courtesy summary**, not removing the reference. Pattern: keep the upstream link as source of truth, add an inline summary block prefaced with "if upstream and the inlined summary disagree, upstream wins."
14. **Audit-v2 90 KB cap blind spot for non-`*.md` artefacts** — schemas, templates, and binary fixtures are invisible to a `*.md`-only walker. Phase A4 (deferred) should ship a v3 harness that walks `*.md` + `*.json` + `*.yaml` + `*.tmpl`.

## Validation

- spec content edits surgical and lockstep-compliant.
- No AC change, no CI workflow change.
- Tree-health, lockstep, and AI-confidence parity gates expected to remain GREEN (verified post-edit).

## Status

**A2 CLOSED.** Genuine D5 inlining for spec/04 + immunisation pins for spec/06 + spec/11 explanation. Tree-score lift on next audit-v2 run expected ~+1-3 pts (already at 81.4; ceiling for v2 harness is ~85 due to cap).
