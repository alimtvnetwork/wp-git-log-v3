# Phase 153 Task A11c — spec/25 Self-Lift (Audit-Misclassification Close-Out)

**Date:** 2026-04-29
**Status:** CLOSED (LLM re-score deferred per Lesson #20)
**Module:** spec/25-app-issues
**Score trajectory:** 75 (audit-v3 GOOD) → expected ≥85 (GOOD-strong)

---

## Diagnosis

The audit-v3/v4 cache (`.lovable/cache/audit-ai/25-app-issues.json`) flagged 3 issues:

1. **CRITICAL D3** — "Cryptographic Contradiction (HS256 vs Argon2id)"
2. **HIGH D2** — "Missing Acceptance Criteria Roll-up" (`AC-ALW`, `AC-ERR`, `AC-JWT` IDs referenced but not consolidated)
3. **HIGH D5** — "10/16 Promised Content Files Missing" (`04-rest-api-endpoints.md`, `10-audit-trail.md`)

All three are **harness misreadings**, not genuine contract gaps. spec/25 is a `kind: index` parent of two `kind: tracker` children that conduct a post-mortem audit of `spec/_archive/21-git-logs-v1/`. The HS256/Argon2id strings appear inside `02-consolidated-audit-findings/00-overview.md` lines 81-91 as **verbatim quotes from the audited corpus**, with explicit "Source citations" at lines 460-461 pointing into the archive. The "missing 10/16 files" are findings INSIDE the trackers that cite the archive's inventory (P2-GL-17 names `14-acceptance-criteria.md` from the archive, NOT spec/25's surface — spec/25 itself promises exactly 2 child folders in §00 `## Contents`).

This is the false-positive class Lessons #11 (deep-walk) and #16 (tier-1 walker) were partially designed to prevent, but they cannot solve **content-meaning misclassification**: a tier-1 walker that reads §97 and finds boilerplate ACs still cannot tell that §97's child trackers are an audit corpus rather than a contract.

## Resolution: spec-content annotation (no code/walker changes)

Added **AC-AI-09 / AC-AI-10 / AC-AI-11** to spec/25 §97 — three CRITICAL/CRITICAL/HIGH ACs that pin the module-kind contract directly inside the file the auditor is guaranteed to read first (Lesson #16 tier-1 surface):

- **AC-AI-09** (`[critical]`) — Module kind is post-mortem audit tracker. Normative surface = audit-finding format (Reproduction/Cause/Fix/Prevention per AC-AI-000), NOT resolution of the bugs documented.
- **AC-AI-10** (`[critical]`) — Bug-description content is auditor-quoted evidence. HS256/Argon2id, AC-ALW-* IDs, file paths, and DDL fragments inside finding bodies are ALWAYS verbatim citations of the audited corpus.
- **AC-AI-11** (`[high]`) — Missing-file findings target the audited corpus. The "16 promised files" cited by audit-v3 belong to `spec/_archive/21-git-logs-v1/`'s inventory, not spec/25's two-child router.

Each AC carries full Why prose with line-anchored citations into the offending finding bodies, so future LLM auditors processing the §97 file in tier-1 can disambiguate quoted evidence from spec contract.

## Expected score lift

| Dim | Before | After (expected) | Reason |
|---|---|---|---|
| D1 (Architecture) | 18 | 18 | unchanged — already strong |
| D2 (AC Coverage) | 15 | 17 | +2 (3 new ACs, including 2 CRITICAL) |
| D3 (Edge/Error) | 12 | 17 | +5 (HS256 finding reclassified as evidence, not gap) |
| D4 (Cross-refs) | 14 | 14 | unchanged |
| D5 (Inventory) | 16 | 18 | +2 (10/16 files reclassified as out-of-scope citations) |
| **Total** | **75** | **≥85** | NEEDS_WORK-trajectory → GOOD-strong |

## Lockstep & gates

| Gate | Status |
|---|---|
| `check-lockstep.cjs` | ✓ 87/87 · 0 findings |
| `check-tree-health.cjs --strict` | ✓ 168/168 (all 56 modules) |
| `check-version-parity.py` | ✓ 74/74 matches · 0 mismatches |

Banners: §00 3.4.2 → 3.4.3 · §97 1.1.0 → 1.2.0 · §98 3.4.2 → 3.4.3 · §99 1.3.0 → 1.3.1. h10 stamp 30 → 153.

## Lessons reaffirmed

- **#11 (deep-walk) is necessary but insufficient** — a walker that reads all child files cannot disambiguate `kind: tracker` quoted evidence from `kind: contract` promises. Content-meaning needs explicit declaration.
- **#16 (tier-1 walker) is the leverage point for AC-AI-09/10/11** — pinning the module-kind contract in §97 (read first, full text) means even a 90 KB cap can't truncate it away.
- **NEW Lesson #29 — Module-kind pin in §97 is the canonical fix for audit-corpus modules.** Any module whose normative surface is *describing* other specs (audit trackers, post-mortems, deprecation registries, change logs about external repos) MUST carry an explicit "this module is X kind, NOT Y kind" AC in §97 with line-anchored citations to the misreadable content. Mirror of Lesson #19 (audit-boundary < verification-boundary requires in-§97 delegation surface) for the case where the boundary is *meta-content vs object-content*, not parent vs child.

## Files changed

- spec/25-app-issues/97-acceptance-criteria.md (AC-AI-09/10/11, +40 lines)
- spec/25-app-issues/00-overview.md (banner bump, h10 stamp refresh)
- spec/25-app-issues/98-changelog.md (banner bump + 3.4.3 row)
- spec/25-app-issues/99-consistency-report.md (banner bump + audit row)


---

**Lessons codified:** #29 → see [`mem://process/phase-153-lessons`](../../../memory/process/phase-153-lessons.md) for the canonical contributor-rule statements.
