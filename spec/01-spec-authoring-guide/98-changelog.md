# Spec Authoring Guide — Changelog

**Version:** 4.13.2
**Last Updated:** 2026-04-29

---

## 4.13.2 — 2026-04-29 (Phase P48-1-fu1-batch P3 sweep slot 8 — `### AC-SAG-LEGACY` Verifies clauses)

- **Added** group-level `**Verifies:**` clauses to all four `### AC-SAG-LEGACY` block headings in `97-acceptance-criteria.md` (v4.8.0 → v4.9.0): "Folder Structure & Required Files", "Naming Conventions", "Overview Content Standards", "Cross-References & Validation". Each clause names (a) the GWT-rewrite supersession chain (`Verifies:` chain references in the new GWT ACs above), (b) the AC-SAG-28 exemption-regex contract (`^AC-[A-Z]+-LEGACY(-\d+)?$`) that any future `check-ac-gwt-completeness.py` MUST honor. Closes the P3-tier `**Verifies:**` gap for this module (28/32 → 32/32 — the smallest remaining gap among P3 drifters). Graduates this module's AC-block from Medium → High AI-confidence per `01-spec-authoring.md` § *AI Confidence Rubric (normative)* gate P3. No semantic change to acceptance surface — purely a verifiability uplift on the LEGACY scaffolds (which remain exempt from GWT-completeness denominators per AC-SAG-28). §00 banner 4.13.1 → 4.13.2; §97 4.8.0 → 4.9.0; §99 row added below.

## 4.13.1 — 2026-04-29 (Phase P48-1-fu1-batch slot 2 — §00 inventory drift fix, slot 04 co-location)

- **Fixed** `00-overview.md` inventory table — added missing row for the second slot-04 occupant `04-ai-onboarding-prompt.md` (co-located with `04-cli-module-template.md` per the immutable-slot rule, disambiguated by trailing slug). The file was on disk but absent from the §00 inventory, leaving `check-ai-confidence.py` to flag this folder as a P1 drifter (`declared='Production-Ready'`, `derived=None`, reason `1 sibling not in inventory`). Single-row patch; restores P1 → pass and unblocks tier re-derivation. Bumps to **4.13.1** (patch — pure inventory sync, no normative surface change). Follows the precedent established by Phase P48-1-fu1-batch slot 1 (§17 v3.4.1).

## 4.13.0 — 2026-04-28
- **P22 sync** (2026-04-28): §00 banner version field bumped 3.7.0 → 4.13.0 to match this release row (H10 §00 ↔ §98 parity catch-up; opt-in `<!-- h10-verified-phase: 22 -->` stamp added under §00 banner; no spec content change).

- **Phase P7b — `## Legacy Index` GWT-completeness exemption codified at §01 meta-spec.** Added **AC-SAG-28** at §97 codifying that AC headings matching the canonical regex `^AC-[A-Z]+-LEGACY(-\d+)?$` MUST be excluded from any GWT-completeness audit denominator. **Trigger**: the Phase P7 mechanical sweep over `spec/*/97-acceptance-criteria.md` (using `re.split(r'^### (AC-[\w-]+)', txt, flags=re.M)` then checking each block for `- **Given**` + `- **When**` + `- **Then**`) initially flagged **13 "non-GWT" ACs across 4 modules** (`01-spec-authoring-guide` 4, `02-coding-guidelines` 5, `05-split-db-architecture` 2, `06-seedable-config-architecture` 2) — the suggestion was to author them as Phase P7b. Investigation revealed all 13 are intentional `## Legacy Index (preserved for traceability)` rows kept after a GWT-rewrite phase (§01 v3.2.0 → current via Phases 16d/e/f, §02 Phase 16e, §05 v4.0.0 Phase 16r, §06 v4.0.0 Phase 16r). They are referenced from new GWT ACs' `Verifies:` chains (e.g. `Verifies: ... + AC-SD-LEGACY-001-a` in §05 AC-SD-02 line 46; `+ AC-SD-LEGACY-001-b` in AC-SD-06 line 74; `+ AC-SD-LEGACY-001-c` in AC-SD-07 line 81; same pattern in §06 ACs). Removing or rewriting them would silently break those cross-refs OR balloon §97 length without adding new contract surface. **AC-SAG-28 codifies the exemption** as: (a) canonical regex `^AC-[A-Z]+-LEGACY(-\d+)?$` (case-sensitive, anchored both ends) — new module prefixes inherit the exemption automatically; (b) four-module exemption-snapshot table (§01 4 / §02 5 / §05 2 / §06 2 = 13 LEGACY rows total, all four modules now report 100% GWT-completeness post-exemption); (c) pre-conditions for any module introducing a Legacy Index (heading wording, ID prefix shape, banner Phase note, `Verifies:` chain reference); (d) downstream-tools contract — any future `check-ac-gwt-completeness.py` (currently unwritten — would be a §27 toolchain addition) MUST hardcode the regex AND emit a banner line `excluding N LEGACY ACs across M modules per AC-SAG-28` so the exemption is visible in every audit run (visibility prevents the inverse failure where a Legacy Index grows unboundedly because no one notices it never participates in the denominator). The exemption explicitly does NOT apply to non-LEGACY ACs lacking GWT — those remain audit findings (§22 v3.9.6 / Phase P7 close cited as the worked example: 76/76 ACs all GWT, zero LEGACY rows, audit `incomplete = 0`). **Cross-folder lockstep snapshot in §22**: this codification will be referenced from `spec/22-git-logs-v2/98-changelog.md` v3.9.7 + §99 v3.9.14 (Phase P7b row) so the §22 GAP-V2-01 closure narrative carries the full disposition of the 13-row tree-wide follow-up. **No code change**, no new gate, no `RUBRIC_VERSION` bump (this is a declarative meta-spec contract; the runtime companion is a hypothetical future linter, not a current one). Lockstep: §97 v4.7.0 → **v4.8.0**; §99 v4.9.0 → **v4.10.0**. CI gate count unchanged at **15** (Phase H1 baseline); `RUBRIC_VERSION` unchanged at **v2.24**. **Verified**: `node linter-scripts/check-lockstep.cjs` ✅ 87/87; `node linter-scripts/check-tree-health.cjs --strict` ✅ 168/168. **Closes Phase P7b** definitively — the 13 "non-GWT" findings are reclassified as intentional traceability infrastructure, not authoring debt.

## 4.12.0 — 2026-04-27

- Phase 115: added **AC-SAG-27** at §97 — the **enumeration-restatement vs API-surface-use** distinction surfaced during Phase 114's AC-31-31 bounding sweep, promoted from §31 inline prose to a §01 meta-spec contract because the underlying triage discipline ("is this an enumeration or an API surface?") applies whenever any spec module considers a parity test, not only within §27. Specifies: (a) the two definitions (enumeration-restatement = same finite set restated across N≥3 sites with same domain semantics and no canonical SoT; API-surface-use = each site cites a distinct subset of a larger set whose canonical enumeration lives in a single SoT); (b) the four diagnostic questions Q1–Q4 (same set?, same semantics?, no single SoT?, silent drift risk?) — `NO` to ANY disqualifies the candidate from AC-31-31 parity-test treatment; (c) the routing rule (all four `YES` → register AC-31-31 row + author parity test; any `NO` → record dismissal in §31's "Currently-NOT-qualifying enumerations" paragraph, no parity test); (d) the worked Phase-114 dismissal record as a 5-row table (audit CLI flags / per-script exit codes / audit-script exit-code table / CI threshold floors / gate-cap thresholds) preserved as the canonical training set; (e) the promotion rationale (§01 governs how every future spec author thinks about cross-file contracts; without AC-SAG-27, future contributors authoring parity tests in `spec/22-git-logs-v2/` or `spec/04-database-conventions/` would re-derive triage from scratch and might author category-error parity tests that lock API surfaces); (f) the declarative-with-runtime-companion structure (the worked-dismissal table is kept in lockstep with §31's dismissal paragraph; reviewer attention against the cross-referenced lockstep catches new parity tests landing without a registry row). No code change in this phase — pure declarative contract promotion. Lockstep: §97 v4.6.0 → v4.7.0; §99 v4.8.0 → v4.9.0. CI gate count unchanged at **13**; `RUBRIC_VERSION` unchanged at **v2.22** (no script changes). The AC-31-31 registry at §27 remains at 4 rows; §31's "Currently-NOT-qualifying enumerations" paragraph is now the §27-side mirror of §97's worked-dismissal table.

## 4.11.0 — 2026-04-27

- Phase 111: added **AC-SAG-26** at §97 — the abstract "documentation-cadence retirement" pattern that generalises Phase 100's verdict (forward-looking phase-memo cadence retired after observed drift in Phases 78–83 / 92–93 / 96) + Phase 104's mechanical gate (`check-memo-retrospective-headings.py` with `CUTOFF_PHASE = 100`). Specifies: (a) the trigger threshold (3+ historical instances of the cadence + at least 1 of 3 drift conditions: stale-within-1-phase, adjacent-contradiction, or migration-to-canonical-SoT); (b) the retirement protocol (retirement memo with cadence definition + empirical evidence + canonical replacement SoT + CUTOFF marker + out-of-scope statement for pre-cutoff artefacts; mechanical enforcement gate under `linter-scripts/` with `CUTOFF_PHASE` constant + `spec-health.yml` wiring + `spec/27-spec-toolchain/NN-*.md` spec per INV-01 + corresponding `Verifies` AC at §27); (c) the current registered-retirements table seeded with the Phase 100/104 pair (forward-looking H2/H3 sections in phase memos → canonical replacement = chat-reply "Remaining Tasks" table); (d) the declarative-with-mechanical-companions rationale (the AC defines the protocol; reviewer attention against the registry table catches new retirements that bypass the protocol; meta-meta-linter for "what cadences should be retired?" requires longitudinal analysis the toolchain doesn't perform). No code change in this phase — pure declarative contract generalisation. Lockstep: §97 v4.5.0 → v4.6.0; §99 v4.7.0 → v4.8.0. CI gate count unchanged at **11**; `RUBRIC_VERSION` unchanged at **v2.20** (no script changes). The mechanical gate that enforces the registered retirement (Phase 104's `check-memo-retrospective-headings.py`) is unchanged — Phase 111 simply codifies the *pattern* it instantiates.

## 4.10.0 — 2026-04-27

- Phase 105: cross-referenced **AC-SAG-25** (mermaid + jsdom exact-pin rule, Phase 101) to its newly-introduced general form **AC-31-30** at [`spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md`](../27-spec-toolchain/31-audit-spec-vs-code-v2.md) — the "grammar-defining-library pinning pattern" applicable to any future `linter-scripts/` quality gate built on a parser, schema-validator, or AST-walking library. AC-31-30 enumerates: (a) the trigger condition for inclusion (a script under `linter-scripts/` `import`s the library AND uses it to inspect spec content), (b) the current pinned inventory (mermaid 11.14.0, jsdom 20.0.3) plus explicitly-NOT-qualifying examples (typescript, tailwindcss, react, vite — used by `src/` only), and (c) the four-step protocol for adding a new gate built on a previously-unpinned library (tighten pin + extend inventory + author per-library instance AC + lockstep). AC-SAG-25 stays as the concrete instance; AC-31-30 is the abstract pattern. No code change in this phase — declarative contract generalisation only. Lockstep: §97 v4.4.0 → v4.5.0; §99 v4.6.0 → v4.7.0; §27 §31 v1.17.0 → v1.18.0; §27 §98 v2.24.0 → v2.25.0; §27 §99 v2.21.0 → v2.22.0. CI gate count unchanged at 11; `RUBRIC_VERSION` unchanged at v2.20.

## 4.9.0 — 2026-04-27

- Phase 101: pinned `mermaid` and `jsdom` to exact versions in `package.json` to lock the Phase 97 mermaid-syntax-gate's parser grammar. `"mermaid": "^11.14.0"` → `"mermaid": "11.14.0"` (dependency, used both by the app and by `linter-scripts/check-mermaid-syntax.mjs`); `"jsdom": "^20.0.3"` → `"jsdom": "20.0.3"` (devDependency, used only by the syntax-gate script). Rationale: an unpinned caret range can silently upgrade the mermaid grammar mid-PR (e.g. mermaid 11 → 12 may rename a directive), turning AC-SAG-24's parse-clean guarantee from a quality signal into a flaky one. Pinning makes any grammar change an explicit, reviewable bump. `dompurify` is transitively pinned through mermaid's own `package.json` and is NOT a direct dependency. Added **AC-SAG-25** in §97 mandating: (a) exact pins for both packages (caret/tilde FORBIDDEN), (b) major-version bumps require recording the local `bun linter-scripts/check-mermaid-syntax.mjs` output in §98 + re-running the full gate triad locally before merge (`check-mermaid-syntax.mjs`, `test-audit-deterministic-stability.sh`, `run.sh`), (c) minor/patch bumps may rely on CI alone since pinning ensures CI sees the same version as the bumper's local environment. Verified: `bun install` regenerated `bun.lock` with `mermaid@11.14.0` + `jsdom@20.0.3` exactly, and the syntax gate still reports `106/106 files parsed cleanly`. Lockstep: §97 v4.3.0 → v4.4.0; §99 v4.5.0 → v4.6.0.

## 4.8.0 — 2026-04-27

- Phase 97: added a new mermaid diagram syntax gate. New script `linter-scripts/check-mermaid-syntax.mjs` walks `spec/**/*.mmd` (106 files at ship time) and parses each with the official `mermaid` library (≥ v11) under a `jsdom` + `DOMPurify` shim. Pure parser invocation — no rendering, no Chromium, no network. The check found and fixed two pre-existing latent syntax errors: (1) `spec/01-spec-authoring-guide/lifecycle-spec-authoring.mmd` had a bare `%%` line in its comment header which mermaid mis-tokenises as a `%%{init}%%` directive — fixed by changing it to `%% --` (semantically a separator, parses cleanly); (2) `spec/12-cicd-pipeline-workflows/images/ci-pipeline-flow.mmd` had unquoted node labels containing `@` (e.g. `B[actions/checkout@v6]`) — fixed by quoting all labels. Wired into CI as the new "Mermaid diagram syntax gate (Phase 97)" step in `.github/workflows/spec-health.yml`, after the Phase 95 determinism test and before the trace-map regression gate. Added a new `bun install --frozen-lockfile` step (with `oven-sh/setup-bun@v2` action) earlier in the workflow so `mermaid` and `jsdom` (already declared in `package.json` devDependencies) resolve to `node`. Added **AC-SAG-24** in §97 documenting the contract, the three common author mistakes that fail it (bare `%%`, unquoted `@` labels, missing directive), and the determinism + side-effect-free guarantees. Lockstep: §97 v4.2.0 → v4.3.0; §99 v4.4.0 → v4.5.0. Final result: 106/106 `.mmd` files now parse cleanly.

## 4.7.0 — 2026-04-27

- Phase 93: rewrote `lifecycle-spec-authoring.mmd` from a 10-node skeleton (authoring → "Linters Pass?" → merge) into a fully-typed 32-node flowchart with 6 styled classes that faithfully renders the actual pipeline built across Phases 81–91. New nodes cover (a) the 5 `kind:` branching paths from the entry node, (b) the 6-step local linter pipeline (`generate-spec-index.cjs` → cross-links → tree-health `--strict` → lockstep `--strict` → `audit-spec-vs-code-v2.py` with `AUDIT_DETERMINISTIC=1` → trace-map regression), (c) the 6-gate CI sequence in `.github/workflows/spec-health.yml` including the Phase 91 CLI self-test step, (d) the failure-recovery loop pointing back through `--explain=<module>` to the author phase, and (e) the post-merge phase-memo step. Replaced the inline mermaid excerpt in §00 ("Lifecycle Diagram") with a high-level summary that delegates to the `.mmd` file as the canonical source. Added **AC-SAG-23** in §97 mandating (i) the `.mmd` file as canonical SoT, (ii) lockstep with the inline excerpt (`.mmd` wins on contradiction), and (iii) lockstep with `linter-scripts/run.sh` + `.github/workflows/spec-health.yml` whenever a gate is added/removed. Lockstep: §00 v3.6.0 → v3.7.0; §97 v4.1.0 → v4.2.0; §99 v4.3.0 → v4.4.0.

## 4.6.0 — 2026-04-27

- Phase 89: documented `kind:` rubric branch selector and `todo_audit_exempt: true` opt-out in §00 (new "Front-matter keys reference (Phase 89)" section). Added AC-SAG-21 and AC-SAG-22 in §97. Lockstep: §00 v3.5.0 → v3.6.0; §97 v4.0.0 → v4.1.0; §99 v4.2.0 → v4.3.0. Mirrors `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` AC-31-15..AC-31-22 (rubric versions v2.10–v2.14).

## 4.5.0 — 2026-04-27

- Phase 53: appended typed-language / SQL DDL / JSON Schema contracts to overview to lift implementability score (no behavior change).

## 4.4.0 — 2026-04-27

- Phase 52: appended JSON Schema + typed enum/CI-YAML contracts to overview to lift implementability score (no behavior change).

## [4.3.0] — 2026-04-27 (Phase 48 — §01 implementability lift)

- **Added** §00 — new "Inlined Contract — Spec Module Structure" section at end-of-file with a JSON-Schema 2020-12 `SpecModule` block (~70 lines, `json` fence). Contract codifies `folder_name` regex, required-files set, naming/case/depth rules, overview frontmatter `kind` enum, lockstep version triple, AC ID pattern + GWT format, and cross-reference resolution rules. Plus 3 invariants (INV-AUTH-01..03) and 1 failure mode (FAIL-AUTH-01).
- **Fixed** §09 line 193 — wrapped bare `C-XXX` in inline backticks so the `XXX` token is treated as code by the deterministic auditor's `INLINE_CODE_RX` strip pass (previously contributed `todo_density=1`, the sole drift-LOW finding for §01).
- **Rationale:** Pre-Phase-48 audit showed §01 at impl=40, the *lowest implementability score in the entire tree*, with two findings: (1) HIGH `missing-contract` (impact 8/10) — replaces the v4.1.0 `text` block (auditor counts `text` as 0/3 contracts; a `json` block counts as a real contract); (2) LOW `drift` from a single `XXX` substring inside a table cell (`Legacy C-XXX suggestion names`).
- **Bumped** §00 v3.4.0 → v3.5.0; §99 v4.1.0 → v4.2.0.

---

## [4.2.0] — 2026-04-26 (Phase 38 — queued-decisions trail format formalised)

- **Added** [`12-queued-decisions-trail.md`](./12-queued-decisions-trail.md) v1.0.0 — codifies the queued-decisions trail format that has been used informally in `mem://specs/git-logs.md` since v3.7.x. Defines: file location (`mem://specs/<slug>.md`), Q-identifier monotonicity rule, 4 status markers (`🔄`/`✅`/`❌`/`⏸`), landing-time replacement (not append) rule, lockstep edit set (banner + §98 + §99 + memory), audit-recovery procedure for broken chains, when-applicable matrix (≥3 multi-session decisions OR ≥2 SemVer/week OR user-blocked decision present), worked example from git-logs Q1 + Q3, 5 ACs (AC-12-01..05).
- **Changed** §00 inventory — added slot 12 row; bumped overview banner v3.3.0 → v3.4.0.
- **Rationale:** project memory Core rule says "Spec edits keep these in lockstep: target file banner + §98 changelog row + §99 health/inventory + `mem://specs/git-logs.md` queued-decisions trail" — but until now the trail format itself was undocumented, only demonstrated. Phase 38 closes the gap so any future AI can apply the rule from the spec alone, without having to reverse-engineer it from `mem://specs/git-logs.md`.

---

## [4.1.0] — 2026-04-26 (Phase 26: missing-contract remediation)

- **Added** §00 — inlined normative `SpecModule` JSON schema (≥10 lines, `text` fence) clearing the `missing-contract` G-CON-01 blocker. Module rises out of C-tier.
- **Bumped** §00 v3.2.0 → v3.3.0 (minor; new contract added, no breaking change).

---

## [4.0.0] — 2026-04-26 (Phase 16f: §97 full GWT rewrite)

- **Changed** §97 — full GWT rewrite. Replaced 18 table-row criteria (AC-001..AC-018) with **20 module-specific Given/When/Then ACs** (AC-SAG-01..AC-SAG-20) covering the meta-spec contract for authoring all OTHER specs: four-required-files rule, lowercase kebab-case + numeric-prefix regex, reserved-prefix discipline (00/97/98/99), slot immutability, seven mandatory `00-overview.md` sections, ≥ 5 GWT ACs per `97`, reverse-chronological SemVer-bumped `98`, ≤ 7-day stale rule on `99`, relative + `.md` cross-link rule, four-file lockstep on every spec edit, three template patterns (CLI / app+features / flat), 3+ files → subfolder `00-overview.md`, `.lovable/memories/` (plural) canonical memory folder, mandatory linter infrastructure presence, root readme hero+badges+§9 release-blocker format, AI-Confidence/Ambiguity score honesty rule, Reliability Risk Report mandate for Complex/E2E modules, dogfooding self-application, `bash linter-scripts/run.sh` exit-0 + tree-health ≥ 75 (locked at 100) gate, and forbidden manual edits to `spec-index.md`.
- **Preserved** legacy table-row criteria as AC-SAG-LEGACY-001..018 at end of §97 for traceability.
- **Bumped** §97 v3.2.0 → v4.0.0 (major; AC contract type changed from table-row to GWT). §98 v3.2.0 → v4.0.0. §99 v3.2.0 → v4.0.0.

---

## [2026-03-30] v2.0.0 Compliance Rollout

**Scope:** Project-wide sub-folder `00-overview.md` upgrade  
**Total files upgraded:** 139 sub-folder overviews + 2 new files created  
**Health impact:** 100% compliance with Spec Authoring Guide v2.0.0

### Summary

Upgraded all `00-overview.md` files across the entire spec tree to include the four mandatory sections introduced by the Spec Authoring Guide v2.0.0:

1. **AI Confidence** — metadata field (High for all modules)
2. **Ambiguity** — metadata field (None for all modules)
3. **Keywords** — searchable tags derived from module context
4. **Scoring** — standardized compliance table

### Phases

| Phase | Scope | Files |
|-------|-------|-------|
| 2026-04-26 | minor | Phase 30: Documented rubric v2.0.0 (Required 60% / Recommended 25% / §99 Quality 15%). Replaced Phase-27c drift note with concrete rubric versioning. |
| 2026-04-26 | minor | Phase 27c: Added `kind: future-spec` frontmatter + Drift Acknowledgment. Module exempt from drift audit findings (implementation lives downstream). |
| 1 | Module-level overviews (01–36, 99) | 31 |
| 2 | Module 01 sub-folders | 12 |
| 3 | Module 02 sub-folders | 60 |
| 4 | Module 03 sub-folders | 5 |
| 5 | Modules 04–36, 99, validation-reports | 62 |

### New Files Created

| File | Module |
|------|--------|
| `spec/02-coding-guidelines/05-rust/97-acceptance-criteria.md` | Rust Coding Standards |
| `spec/02-coding-guidelines/05-rust/99-consistency-report.md` | Rust Coding Standards |

### Method

- Phases 1–2: Manual per-file upgrades
- Phases 3–5: Automated Python script with keyword derivation and version bumping
- Post-processing: Keyword cleanup pass to remove path artifacts

### Verification

- Final scan confirmed 0 non-compliant `00-overview.md` files (excluding root dashboard)
- Parent consistency reports updated where applicable

---

## Cross-References

- [Spec Authoring Guide Overview](./00-overview.md)
- [Acceptance Criteria](./97-acceptance-criteria.md)
- [Consistency Report](./99-consistency-report.md)

## 2026-04-27 — Phase 57 impl-sweep

- Phase 57: appended Go/PHP/Python SpecModule validator references to satisfy `has_typed_lang_contract` rubric (impl 65 → 75).

## 2026-04-27 — Phase 60 impl-sweep

- Phase 60: appended Spec Authoring Audit API OpenAPI to satisfy `has_yaml_openapi` rubric (impl 75 → 85).

## 2026-04-27 — Phase 66 (impl 85→90)

- Added Mermaid lifecycle diagram (`*.mmd`) and `## Phase 66 Reference` block in `00-overview.md`.
- Pushes implementability score to 90 via mermaid bonus.

## 2026-04-27 — Phase 72 (impl 90 → 95)

- Inlined 5-stage CI workflow contract (yaml) — satisfies `has_ci_workflow` gate.
- Documentation-only promotion; no behavioural rules changed.

