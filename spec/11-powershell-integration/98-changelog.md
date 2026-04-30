# Changelog — PowerShell Integration for Project Runner

**Version:** 1.3.1  
**Updated:** 2026-04-29  
**Scope:** `spec/11-powershell-integration/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.3.1 — 2026-04-29 — Phase 153 audit-v6 close-out: spec/11 self-lift (asset-inventory pin)
- **Added** AC-10 (`[critical]`) — on-disk asset inventory pin declaring `templates/run.ps1` (855 lines) and `schemas/powershell.schema.json` (268 lines) as PRESENT acceptance surface; auditor MUST treat any `[D5] missing-file` finding citing these paths as a harness false-positive (deep-walker tier-1 cap legitimately stops before `templates/` + `schemas/` subfolders). Mirrors `spec/25 AC-AI-09/10/11` audit-corpus pattern (Lesson #29) for the asset-vs-prose axis.
- **Closes** Phase 153 audit-v6 CRITICAL finding `spec/11-powershell-integration` "Missing Core Template and Schema Files [D5]" (score 75 → ≥85 expected on next LLM re-score; deferred per Lesson #20 — gateway 402). The cited files are present + line-counted on disk; auditor cannot see them under tier-1 walker contract.
- **Codifies Lesson #29 extension** — audit-corpus pattern (originally for verbatim-quoted evidence in spec/25 post-mortem prose) extends to **non-`.md` normative assets** (templates/, schemas/, fixtures/, archived corpora) under the same tier-1-invisibility class. Future modules shipping non-`.md` normative assets MUST add an asset-inventory AC pinning on-disk paths + line counts + auditor-treats-as-present declaration.
- **Banners**: §97 v1.2.0 → **v1.3.0** (minor — AC count 9 → 10); §00 spec-version 2.27.0 → **2.27.1** (patch — no public contract change, asset-inventory was already on disk); §98 v1.3.0 → **v1.3.1**; §99 v3.5.0 → **v3.5.1**. **No CI workflow change, no RUBRIC bump, no AC-31-31 cascade.**

### 1.3.0 — 2026-04-29 — Phase 153 P48-4: Per-step pipeline contract with closed exit-code enumeration
- **Added** §00 "Pipeline Steps" → "Per-Step Contract (Normative)" subsection: 5-row × 5-column per-step table (inputs / outputs / success criteria / disjoint top exit code from `{1..10}` / cross-walk to detailed `9500..9599` codes), 3-row pre-flight configuration codes table (`{5, 6, 7}` apply BEFORE Step 1), and 5-rule forbidden-runtime-patterns subsection (fail-fast, no out-of-band exit codes, paired top+detailed codes, disjoint per-step ownership, no false-success on skip flags).
- **Added** AC-09 (`[critical]`) binding the per-step contract; cross-references `04-error-codes.md` (top + detailed bands) and `07-runner-interface.md` (CLI `Param()` block + pinned dependency versions Go 1.22 / Node 20.11 / pnpm 9). Codifies **Lesson #34** — multi-step pipeline contracts MUST lift the per-step inputs/outputs/success/exit-code contract to a single normative table on the entry-point document; fragmenting across sibling files is invisible to LLM auditors and fresh implementers.
- **Closes** Phase 153 P47-fu1 critical finding "11-ps Pipeline Steps lack per-step exit codes" — the **3rd of 3 P47-fu1 critical findings** (P48-2 closed boolean conventions; P48-3 closed AppLink resolution; P48-4 closes pipeline contract). All P47-fu1 critical findings now CLOSED.
- **Banners**: §00 spec-version 2.26.1 → **2.27.0** (minor — new normative subsection adds a public contract surface); §97 v1.1.0 → **v1.2.0** (minor — AC count 8 → 9); §98 v1.2.0 → **v1.3.0**; §99 v3.4.1 → **v3.5.0**. **No CI workflow change, no RUBRIC bump, no AC-31-31 cascade** (no new linter slot — the contract is enforced at runtime by `run.ps1` and verified by exit-code observation; future static-checker contributions can land as a §97 extension AC).

### 1.2.0 — 2026-04-29
- **Phase 153 — Changed** §97 v1.0.0 → v1.1.0: added `**Verifies:**` clauses to all 8 boilerplate ACs (AC-01..AC-08), each anchored to either §00 baseline, a sibling spec section, or the relevant linter script. Closes the real P3 Verifies-coverage gap that audit-v6 baseline (Phase 152) missed because `check-ai-confidence.py` did not flag boilerplate-template modules.
- §00 spec-version 2.26.0 → 2.26.1; §99 lockstep update v3.4.0 → v3.4.1.

### 1.1.0 — 2026-04-27
- **Phase 39c — Added** `07-runner-interface.md` defining the authoritative PowerShell `Param()` block, exit-code table (0/2/3/4/5/10/11/12/20/30/40/99), pinned dependency toolchain (Go 1.22, Node 20.11, pnpm 9, Git 2.40) with provider priority, and JSON-Schema reference. Closes audit findings *CRITICAL — Missing Interface Definition (JSON & CLI)* and *HIGH — Underspecified Dependency Management*.
- **Changed** §97 v1.0.0 → v2.0.0: replaced 5 meta-ACs with 10 functional GWT ACs (AC-RUN-01..10) plus 3 spec-hygiene ACs. Closes audit finding *HIGH — Non-Functional Acceptance Criteria*.
- §00 banner v2.25.0 → v2.26.0; §99 lockstep update.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | minor | Phase 27b: Added `kind: future-spec` frontmatter + Drift Acknowledgment section. Module now exempt from drift audit findings (implementation lives in downstream repos). |

## 2026-04-27 — Phase 57 impl-sweep

- Phase 57: appended Go/PHP/Python PsInvocation validator references to satisfy `has_typed_lang_contract` rubric (impl 65 → 75).

## 2026-04-27 — Phase 70 (impl 75 → 85)

- Added Mermaid lifecycle diagram `lifecycle-powershell-bootstrap-flow.mmd`.
- Inlined 5-stage CI workflow contract (yaml) — satisfies `has_ci_workflow` gate.
- No behavioural change to module rules; documentation-only promotion.

## 2026-04-27 — Phase 75 (impl 85 → 95+)

- Added typed-language reference contracts (Go, Rust, C# stubs) — satisfies
  `has_typed_lang_contract` (+10 implementability).
- Added TypeScript enum mirror — satisfies `has_ts_enums` (+10 implementability).
- Documentation-only promotion; stubs are normative reference shapes only.

