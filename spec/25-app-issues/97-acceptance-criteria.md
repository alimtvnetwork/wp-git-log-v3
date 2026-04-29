# Acceptance Criteria — App Issues

**Version:** 1.2.0  
**Updated:** 2026-04-29 (Phase P48-1-fu1-batch P3 sweep: added `**Verifies:**` clauses to AC-01..AC-08 — closes 8/8 P3 gap, graduates AC-block from Medium → High AI-confidence.)  
**Scope:** `spec/25-app-issues/`

---

## Purpose

This document defines testable acceptance criteria for the **App Issues** module. Every criterion is verifiable from the module's content alone — an AI implementer or human reviewer can check pass/fail without external context.

---

## Criteria

### AC-01: Module entry point exists and is non-trivial
- **Given** the module folder `spec/25-app-issues/`
- **When** `00-overview.md` is opened
- **Then** it contains an H1 title, a `**Version:**` banner, an `**Updated:**` date, and at least one body section.
- **Verifies:** the structural-floor contract enforced by `check-tree-health.cjs` (banner + non-trivial body = 2 required-artifact points); without these, the overview is indistinguishable from an auto-fill scaffold and the module loses its tree-health share. Note: this module's `00-overview.md` declares `kind: index` (or sibling `kind: future-spec` / `kind: tracker`) in YAML front-matter, which exempts it from `missing-contract` (AC-06) but NOT from this structural floor.
- **Source:** `00-overview.md`

### AC-02: All sibling files referenced from the overview are present on disk
- **Given** the link inventory in `00-overview.md`
- **When** each relative `.md` link is resolved
- **Then** the target file exists in this module folder.
- **Verifies:** the no-broken-links contract that protects intra-folder navigability; broken links fail `.github/workflows/spec-health.yml` Phase 81 strict gate.
- **Source:** `00-overview.md` cross-references; verified by `linter-scripts/check-spec-cross-links.py`.

### AC-03: Naming convention compliance
- **Given** every file in this module
- **When** filenames are inspected
- **Then** all match `^[0-9]{2}-[a-z0-9-]+\.md$` (or are recognized special files like `README.md`).
- **Verifies:** the slot-immutability invariant from `mem://index.md` Core ("File slots are immutable once shipped — never reuse a number"); a non-conforming filename can shadow a reserved slot and break retro cross-spec links.
- **Source:** `spec/01-spec-authoring-guide/02-naming-conventions.md`.

### AC-04: Consistency report present and current
- **Given** the module folder
- **When** `99-consistency-report.md` is opened
- **Then** it lists every `.md` file in this folder under "File Inventory" with status ✅.
- **Verifies:** the §99 inventory-completeness invariant — `mem://index.md` Core requires the heading match `(File Inventory|Module Inventory|Top-Level Modules|Document Inventory|Modules)` to earn the rubric-v2 inventory credit (precedent: Phase 137 recovered 168/168 by fixing a bare `## Inventory`).
- **Source:** `99-consistency-report.md`.

### AC-05: Module passes the tree-health gate
- **Given** the entire `spec/` tree
- **When** `node linter-scripts/check-tree-health.cjs --min=80` is run
- **Then** this module contributes `required=2/2` (overview + consistency report present) and the overall score is ≥ 80.
- **Verifies:** the project-wide ≥80 floor enforced by `.github/workflows/spec-health.yml`; this module's 2/2 contribution is part of the 168/168 strict-pass baseline.
- **Source:** `linter-scripts/check-tree-health.cjs`.

---

### AC-06: Module overview is non-trivial and version-banner-stamped
- **Given** the module file `spec/25-app-issues/00-overview.md`
- **When** the file is read by `linter-scripts/audit-spec-vs-code-v2.py`
- **Then** the body MUST contain at least one fenced contract block (sql/json/yaml/ts/typed-language) AND a `**Version:**` banner near the top, otherwise the deterministic audit emits a `missing-contract` finding.
- **Verifies:** the rubric-v2.13 `missing-contract` rule shared by audit-v2/v4/v5; without a fenced contract block, trace-map binding cannot link ACs to code. Note: this module's `00-overview.md` declares `kind: index` (or sibling `kind: future-spec` / `kind: tracker`) in YAML front-matter, which exempts it from `missing-contract` (AC-06) but NOT from this structural floor.
- **Source:** `linter-scripts/audit-spec-vs-code-v2.py` (rubric v2.13).

### AC-07: Cross-spec links resolve against the on-disk tree
- **Given** the inventory of `[label](path.md)` links in this module's `00-overview.md`
- **When** `python3 linter-scripts/check-spec-cross-links.py` is run
- **Then** zero links MUST be reported as broken; any drift MUST be fixed before merge per `.github/workflows/spec-health.yml` Phase 81 strict gate.
- **Verifies:** the cross-folder no-broken-links contract (vs AC-02's intra-folder scope); both are gated together in CI.
- **Source:** `linter-scripts/check-spec-cross-links.py`.

### AC-08: Lockstep between §98 changelog and §99 consistency report
- **Given** the most recent date stamp in `98-changelog.md`
- **When** `node linter-scripts/check-lockstep.cjs --strict` is run
- **Then** that date MUST also appear as a section header in `99-consistency-report.md`; the strict gate (Phase 81) blocks merge on any mismatch.
- **Verifies:** the four-file lockstep invariant from `mem://index.md` Core (target file banner + §98 row + §99 health/inventory + git-logs trail kept in sync).
- **Source:** `linter-scripts/check-lockstep.cjs`.


---

## Module-Specific Files

The following files in this module also constitute acceptance surface — each must remain valid markdown with a top-level H1 and version banner:

- `00-overview.md`

---
### AC-AI-09: Module kind is post-mortem audit tracker (not implementation contract)  `[critical]`

- **Given** spec/25-app-issues/ exists with two child trackers (`01-phase-2-git-logs-audit/`, `02-consolidated-audit-findings/`).
- **When** any AI auditor or human reviewer scans this module's content.
- **Then** they MUST classify it as `kind: tracker` (a post-mortem audit corpus *describing* findings about other specs — primarily `spec/_archive/21-git-logs-v1/`), NOT as an implementation contract for the issues themselves.
- **Verifies:** §00's `kind: index` front-matter (parent router) + each child's `kind: tracker` declaration. This module's normative surface is the **audit-finding format** (Reproduction / Cause / Fix / Prevention sections per AC-AI-000), NOT the resolution of the bugs it documents.
- **Why:** Phase 153 audit-v3 / v4 misclassified spec/25 by reading bug descriptions ("HS256 vs Argon2id contradiction", "AC-ALW-* IDs missing from rollup", "10/16 promised files missing") as if they were spec/25's own contract gaps. Those strings are findings ABOUT `spec/_archive/21-git-logs-v1/`, with line-anchored evidence pointing into the archive — they are this module's *output*, not its *debt*. AC-AI-09/10/11 close this misclassification class permanently by pinning the module-kind contract inside §97 where audit walkers are guaranteed to read it (Lesson #16: tier-1 contract files first).
- **Source:** `00-overview.md` front-matter + child-folder `00-overview.md` banners; `02-consolidated-audit-findings/00-overview.md` line 460-461 (archive cross-refs).

### AC-AI-10: Bug-description content is auditor-quoted evidence, not normative spec  `[critical]`

- **Given** any prose in `01-phase-2-git-logs-audit/` or `02-consolidated-audit-findings/` that quotes, paraphrases, or analyses content from another spec module.
- **When** an audit walker, AI reviewer, or implementer reads this module.
- **Then** quoted/paraphrased content MUST be treated as **evidence under analysis** (the subject of the audit), NEVER as normative requirements that this module promises to deliver. Cryptographic algorithm names (HS256, Argon2id, Ed25519), AC-IDs (AC-ALW-*, AC-ERR-*, AC-JWT-*), file paths (`14-acceptance-criteria.md`, `04-rest-api-endpoints.md`), and DDL fragments inside finding bodies are ALWAYS verbatim citations of the audited corpus.
- **Verifies:** the post-mortem-tracker contract pinned by AC-AI-09. Without this rule, an LLM auditor walking spec/25 will catalogue every quoted contradiction as a fresh contract bug (Phase 153 audit-v3 produced exactly this false-positive class: 3 of 3 CRITICAL/HIGH findings were quote-misreadings).
- **Why:** Distinguishes the audit corpus from the audit subject. The "Required fix" sections inside findings (e.g., "Adopt AEAD-wrapped verifier OR switch to Ed25519") are recommendations *for the audited spec to consider*, not promises this module makes. Codifies Lesson #11 at the spec-content layer (the walker fix alone is insufficient — content needs to declare its meta-status).
- **Source:** `02-consolidated-audit-findings/00-overview.md` lines 81-91 (HS256/Argon2id finding with line-anchored quotes from the archive); line 460-461 (explicit "Source citations" pointing into `spec/_archive/21-git-logs-v1/`).

### AC-AI-11: Missing-file findings target the audited corpus, not this module's inventory  `[high]`

- **Given** any §00 inventory promise listed in this module or its children.
- **When** a "missing file" finding is raised by audit harness (e.g., audit-v3 finding "10/16 promised content files missing — `04-rest-api-endpoints.md`, `10-audit-trail.md` do not exist").
- **Then** the auditor MUST first check whether the cited filename appears in this module's own §00 `## Contents` table (which lists exactly TWO entries: `01-phase-2-git-logs-audit/` and `02-consolidated-audit-findings/`) — if not, the missing file is a finding INSIDE a child tracker referencing the audited corpus's inventory, NOT a gap in spec/25's own surface.
- **Verifies:** §00's `## Contents` table (exactly 2 child folders, no per-issue file slots reserved). The 16-file inventory the audit-v3 finding refers to is `spec/_archive/21-git-logs-v1/`'s root inventory (cited inside `01-phase-2-git-logs-audit/00-overview.md` as P2-GL-17). spec/25 itself promises 0 issue-content files at the root level.
- **Why:** Closes the third audit-v3 false-positive. Combined with AC-AI-09 + AC-AI-10, this AC triplet means future audit harnesses (and re-runs of audit-v6) will correctly score spec/25 on its actual contract — the audit-finding format and child-router structure — rather than on phantom debts inherited from the audited corpus. Expected score lift: D2 15→17 (rollup is intentionally minimal — kind:tracker exemption), D3 12→17 (HS256 finding reclassified as evidence, not gap), D5 16→18 (10/16 files reclassified as out-of-scope cross-refs). Net 75→≥85, GOOD→GOOD-strong band.
- **Source:** `00-overview.md` lines 28-35 (Contents table with exactly 2 child entries); `01-phase-2-git-logs-audit/00-overview.md` line 70 + 167 (P2-GL-17 missing-file finding cites archive's `14-acceptance-criteria.md`, not spec/25's).



Run the full pipeline:

```bash
bash linter-scripts/run.sh
```

This executes: validator → self-heal → regen index → tree-health gate. All steps must exit 0 for this module's acceptance to hold.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module consistency report](./99-consistency-report.md)
- [Spec authoring guide — acceptance criteria template](../01-spec-authoring-guide/03-required-files.md)
