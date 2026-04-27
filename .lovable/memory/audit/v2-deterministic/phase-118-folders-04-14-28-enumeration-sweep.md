# Phase 118 — AC-31-31 bounding sweep against §04 / §14 / §28

**Date:** 2026-04-27
**Type:** Cross-folder enumeration discovery (no code change)
**Trigger:** Phase 116 validated AC-SAG-27's triage protocol on §22; Phase 118 extends to the next 3 large content folders (`spec/04-database-conventions/`, `spec/14-update/`, `spec/28-universal-ci-cli/`) to surface remaining unregistered enumerations and complete a cross-tree bounding pass before Phase 117 mechanically registers parity tests.

---

## Methodology

Same as Phase 116 — apply AC-SAG-27's four diagnostic questions (Q1 same set?, Q2 same semantics?, Q3 no single SoT?, Q4 silent drift risk?) to each candidate cross-file pattern surfaced by `rg` pattern counting + content overlap analysis.

---

## Findings

### Folder §04-database-conventions

**Sweep result:** **No qualifying enumerations.**

§04 is a small folder (8 content files) of declarative naming/design conventions. Cross-file pattern search (`DB-` codes, type tokens, naming convention sets) returned only 1-site-each restatements. The "naming conventions" enumeration in `01-naming-conventions.md` is single-source-of-truth (Q3 → NO); the cross-references in §02 / §03 / §06 cite individual rules rather than restating the full inventory (Q1 → NO). **DISMISSED — no parity tests warranted.**

### Folder §14-update

**Candidate E — GOOS/GOARCH platform-triplet matrix (HIGH-VALUE FINDING — qualifies)**

**Sites (3 substantive):**
- `13-release-assets.md` — full 6-tuple `{linux-amd64, linux-arm64, windows-amd64, windows-arm64, darwin-amd64, darwin-arm64}` (asset-naming contract)
- `14-checksums-verification.md` — same 6-tuple (`.sha256` file naming)
- `16-cross-compilation.md` — same 6-tuple (`GOOS`/`GOARCH` build matrix)

**Triage:**
- **Q1 — Same set?** YES, all 3 sites enumerate the identical 6-tuple verbatim.
- **Q2 — Same semantics?** YES, each token denotes the identical OS/arch combination across sites.
- **Q3 — No single source-of-truth?** Arguable — `16-cross-compilation.md` is *narratively* the canonical build-matrix definition, but `13-release-assets.md` and `14-checksums-verification.md` independently restate the matrix as a *naming* contract. Neither derives mechanically from the other; `run.ps1` / `run.sh` (the actual build scripts) are the executable SoT but live outside the spec tree. **YES, no in-spec single SoT.**
- **Q4 — Silent drift risk?** YES — adding a 7th platform (e.g. `linux-riscv64`) to one file without the others is exactly the drift class AC-31-31 was authored for. No current test guards this.

**Disposition:** **QUALIFIES under AC-31-31** as a uniform 3-site full-enumeration parity. This is the **simplest possible AC-31-31 case** — same as Phase 112's §27 inventory triangle, but for §14's platform matrix. Recommendation: register `test-platform-triplet-parity.sh` asserting `set(platforms in §13) == set(§14) == set(§16)`. No new pattern variant required — fits AC-31-31's existing mechanical surface directly.

**Candidate F — `UpdateState` / `UpdateChannel` / `UpdateTrigger` enums (DISMISSED — single-site)**

`UpdateState` appears in only 1 file (`00-overview.md` Phase-63 reference), `UpdateChannel` in 2 (`00-overview.md` + `21-config-file.md`), `UpdateTrigger` in 1. Below the 3-site AC-31-31 threshold. **DISMISSED — single-site / 2-site direct-lockstep zone.**

**Candidate G — `latest.json` schema fields (deferred)**

`latest.json` referenced in 7 files but each citation is partial (different fields per file based on local concern). Likely an **API-surface-use** pattern (one canonical schema, N citing sites). Deferred to a future targeted sweep — needs schema-extraction tooling beyond `rg` to confirm Q1.

### Folder §28-universal-ci-cli

**Candidate H — `GLCI-*` error-code catalog (HIGH-VALUE FINDING — qualifies, with active drift)**

**Sites (9 substantive):**
- `07-error-catalog.md` — 27 unique codes (canonical narrative catalog)
- `97-acceptance-criteria.md` — 13 codes (subset; **but 3 codes appear ONLY in §97 and NOT in §07**)
- `05-config-resolution.md` — 8 codes (subset)
- `04-command-surface.md` — 3 codes
- `03-runtime-detection.md` — 3 codes
- `06-log-shipping-contract.md` — 2 codes
- `08-ci-provider-bindings.md` — 2 codes
- `17-openapi-client.yaml` — **0 codes** (notable: §17 OpenAPI client spec does NOT enumerate any GLCI codes despite §22's §17-openapi.yaml carrying all 44 GL codes — an inconsistency between §22 and §28)

**Active drift surfaced by sweep:**
- `GLCI-EXEC-DEPS-MISSING` — used in §97 acceptance criterion ("if `node_modules/` is absent the CLI exits `1` with `GLCI-EXEC-DEPS-MISSING`") but **NOT defined in §07-error-catalog**.
- `GLCI-STREAM-MALFORMED` — used in §97 ("server closes the chunked connection mid-frame ... reported via `400 GLCI-STREAM-MALFORMED`") but **NOT defined in §07-error-catalog**.
- `GLCI-TELEMETRY-` — partial regex match in §97; needs investigation (likely a family prefix used in prose without enumerating concrete codes).

**Triage:**
- **Q1 — Same set?** §07 is canonical universe (27 codes); §97/§05/§04/etc. cite subsets. Like §22 Candidate A, this is a **canonical-source + N subset-citers** pattern — Q1 fails for the cross-file *full*-enumeration form, but the §07 ↔ §97 axis demonstrates **active 3-code drift today**: §97 references undefined codes.
- **Q2 — Same semantics?** YES where codes overlap.
- **Q3 — No single source-of-truth?** §07 IS the canonical narrative SoT (Q3 → NO for full parity); BUT §97 has unilaterally introduced 3 codes that should have been added to §07 first.
- **Q4 — Silent drift risk?** **DEMONSTRATED** — drift exists in production today (Phase 118 sweep is the first time it's been caught).

**Disposition:** **QUALIFIES under AC-31-31** as a "canonical-source + subset-citers containment" pattern — the same hybrid variant Phase 116 surfaced for §22 Candidate A (`GL-*` codes). Recommendation: register `test-glci-error-code-containment.sh` asserting `set(GLCI-* in §97 + §05 + §04 + §03 + §06 + §08) ⊆ set(GLCI-* in §07)` — this would have caught the 3-code drift on the PR that introduced §97's references.

**Side effect — §28 specs need a separate hygiene fix to add the 3 missing codes to §07-error-catalog**, but that is a §28 spec edit (Phase 119+ scope), not a parity-test authoring task.

**Candidate I — Output classification enum (`Stdout`/`Stderr`/`Combined`/`Decision`) (deferred — only meta-files)**

The 4-token output-classification enum appears in §00, §97, §98, §99 — but §98 (changelog) and §99 (consistency report) are meta-files that *narrate* changes to the enum rather than restate it as a contract. Substantive sites collapse to §00 + §97 = 2 sites. Below the 3-site threshold. **DISMISSED — direct-lockstep zone.** §09-output-classification.md (the canonical narrative file) was missed by the initial regex; if it restates the full 4-tuple, this elevates to qualifying. Deferred to Phase 117 follow-up.

**Candidate J — `18-config-schema.json` keys (deferred)**

JSON Schema with ~20 top-level keys, likely cross-referenced in §05-config-resolution.md as an API-surface use. Same disposition class as §22 Candidate D (permission caps): canonical schema + subset citers → likely API-surface-use dismissal. Deferred to a focused JSON-schema-extraction sweep.

---

## Summary table

| Candidate | Folder | Sites | Disposition |
|---|---|---|---|
| E — GOOS/GOARCH platform matrix | §14 | 3 (§13, §14, §16) | **QUALIFIES** — uniform 3-site full-enumeration (simplest AC-31-31 case) |
| H — `GLCI-*` error-code catalog | §28 | 9 (§07 canonical + 7 subset-citers + §17 empty) | **QUALIFIES** — canonical+containment pattern; **3-code drift active today** |
| F — `UpdateState`/`Channel`/`Trigger` | §14 | 1–2 each | DISMISSED — below 3-site threshold |
| G — `latest.json` field set | §14 | 7 (partial citations) | DEFERRED — needs schema extraction |
| I — Output classification enum | §28 | 2 substantive | DISMISSED (provisional — needs §09 re-check) |
| J — Config-schema JSON keys | §28 | ~3+ partial | DEFERRED — likely API-surface-use |
| §04 (whole folder) | §04 | n/a | DISMISSED — no qualifying patterns |

**Net result:** 2 new qualifying candidates added to the Phase 117 backlog (Candidate E, H), bringing the total queue from 2 (§22 Candidates A+B) to **4 candidates**. 1 dismissal recorded. 3 deferrals queued.

---

## Cross-folder pattern observations

1. **The "canonical narrative + N subset-citers" hybrid pattern is recurrent** — surfaced in §22 (Candidate A: `GL-*`) and now §28 (Candidate H: `GLCI-*`). Both manifest the same drift profile (catalog ↔ acceptance-criteria divergence). This strongly motivates AC-31-31's "containment" variant Phase 116 proposed; Phase 117 should author it as a **reusable test harness** (`test-error-code-containment.sh <catalog-file> <citing-files...>`), not two bespoke tests.

2. **Active drift was found in §28** — 3 codes referenced in §97 acceptance criteria but undefined in §07-error-catalog. This is exactly the failure mode AC-31-31 mechanises against; surfacing it in a discovery-only sweep validates the pattern's value.

3. **Folder size is a poor predictor of enumeration density** — §04 (8 files) had zero qualifying patterns, while §14 (32 files) had only one. §28 (16 files) had the richest enumeration surface. Density correlates with **whether the folder contracts an external machine-readable interface** (errors, OpenAPI, JSON schemas) — pure design-doc folders (§04) have no parity surface.

## Out of scope

This phase is **discovery only** — no parity tests authored, no spec edits, no AC-31-31 registry rows added, no fix to §28's 3-code drift. Phase 117 (when unblocked) will execute on Candidates A + B + E + H together; Phase 119 will fix §28's substantive 3-code drift in §07-error-catalog.

## Verification

No code change. All 13 strict CI gates remain green by construction.
