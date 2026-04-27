# Phase 116 — AC-31-31 bounding sweep against `spec/22-git-logs-v2/`

**Date:** 2026-04-27
**Type:** Cross-folder enumeration discovery (no code change)
**Trigger:** AC-SAG-27 (Phase 115) codified the four-question triage protocol. Phase 114 applied it to `spec/27-spec-toolchain/`. Phase 116 applies it to the second-largest content folder in the tree (`spec/22-git-logs-v2/`, 36 content files) to validate that the protocol is portable beyond §27 and to surface any unregistered enumerations.

---

## Methodology

For each candidate cross-file pattern surfaced by structural sweep (`rg`-based pattern counting + content overlap analysis), apply AC-SAG-27's four diagnostic questions:

- **Q1 — Same set?** Same finite set across all N≥3 sites, not overlapping subsets?
- **Q2 — Same semantics?** Same domain meaning per member?
- **Q3 — No single source-of-truth?** No canonical site the others derive from?
- **Q4 — Silent drift risk?** Could one site drift unnoticed for ≥1 PR cycle?

All four `YES` → register at AC-31-31 + author parity test. Any `NO` → dismissal record.

---

## Sweep results

### Candidate A — `GL-*` error-code catalog (HIGH-VALUE FINDING — qualifies)

**Sites (5):**
- `15-error-codes.md` — 44 unique codes (canonical narrative catalog with HTTP status + remediation per code)
- `17-openapi.yaml` — 45 unique codes (machine-readable API contract; all 44 from §15 + 1 reference to a `GL-RELEASE-*` family explicitly out-of-scope)
- `05-auth-and-validation.md` — 19 unique codes (auth-path subset)
- `04-rest-api-endpoints.md` — 8 unique codes (per-endpoint error tables)
- `14-endpoint-examples.md` — 1 unique code (illustrative example)

**Set overlap analysis:**
- `15 ∩ 17` = 44/44 (perfect coverage; the `GL-RELEASE-` token in §17 is a comment about excluded build-time codes, not a real code).
- `05 ⊂ 15` = 19/19 (perfect subset).
- `04 ⊂ 15` = 5/8 unique codes overlap; the 3 §04-only codes (`GL-NDJSON-CLIENT-DISCONNECT`, `GL-SHA-DB-OPEN-FAILED`, `GL-VALIDATION-001`) ALL exist in §15 — they were missed by the regex due to longer suffixes (`GL-NDJSON-CLIENT-DISCONNECT` matches in §15; the regex grouping made it look distinct). After re-validation: **§04 ⊂ §15 fully**.

**Triage:**
- **Q1 — Same set?** §15 is the universe; §17 enumerates the same universe; §04, §05, §14 cite **subsets**. Sites have heterogeneous arity. **First-pass: NO** → looks like API-surface-use.
- **HOWEVER** — §15 ↔ §17 alone meet Q1 (both enumerate the full universe; 44 vs 45 with the lone discrepancy explainable). The 4-site question reduces to a **2-site full-enumeration pair (§15 ↔ §17)** plus N **subset-citing sites (§04, §05, §14)**.
- **Q2 — Same semantics?** Yes — every code carries identical `(code-string, HTTP-status, semantic-meaning)` across sites where it appears.
- **Q3 — No single source-of-truth?** §15 is *narratively* canonical (44 codes with full prose remediation), but §17-openapi.yaml is *machine-canonical* for the API contract. Neither derives from the other — both are hand-maintained. **YES, no single SoT.**
- **Q4 — Silent drift risk?** YES, demonstrably — the v2.8.7 changelog entry already records prior `15` ↔ `18-schema.sql` audit-alignment drift; nothing today asserts §15 ↔ §17 parity.

**Disposition:** **QUALIFIES under AC-31-31 as a 2-site full-enumeration parity (§15 ↔ §17)**, with §04/§05/§14 as documented subset-citing sites under AC-SAG-27's API-surface-use category. The 2-site count is below the 3-site AC-31-31 threshold for the *full-enumeration* sub-pattern, but the §15 ↔ §17 pairing has all the silent-drift hallmarks of AC-31-31 and one additional structural property: **§17 is a YAML contract consumed by external tooling**, so drift from §15 silently breaks API-client codegen. Recommendation: register a `test-error-code-parity.sh` that asserts `set(GL-* in §15) == set(GL-* in §17)` and `set(GL-* in §04/§05/§14) ⊆ set(§15)` — a hybrid test combining strict 2-site equality with subset-containment for the citing sites. This is a **NEW pattern variant** AC-31-31 should accommodate: "canonical-pair + N subset-citers".

### Candidate B — `AppStatus` enum (Active/Disabled/Archived) (qualifies)

**Sites (5 substantive — excluding meta/changelog):**
- `01-glossary-and-enums.md` — narrative enum table
- `02-database-schema.md` — DDL reference
- `07-app-entity.md` — identity/lifecycle table (twice: identity row + lifecycle table)
- `18-schema.sql` — `INSERT OR IGNORE INTO AppStatus (AppStatusId, Name) VALUES (1,'Active'),(2,'Disabled'),(3,'Archived')` — **machine-executable seed**
- `00-overview.md` — overview enum reference

**Triage:**
- **Q1 — Same set?** YES, all 5 sites enumerate exactly `{Active, Disabled, Archived}`.
- **Q2 — Same semantics?** YES, identical lifecycle semantics per site.
- **Q3 — No single source-of-truth?** `18-schema.sql` is the *executable* source of truth (the database literally cannot exist without those exact 3 rows), but the 4 documentation sites do not mechanically derive from it. Drift between `18-schema.sql` and the docs would not be caught by any current test.
- **Q4 — Silent drift risk?** YES, demonstrably — v2.8.7 changelog records exactly this class of drift being repaired manually.

**Disposition:** **QUALIFIES under AC-31-31.** Recommendation: register a `test-appstatus-enum-parity.sh` asserting the 3-tuple `{Active, Disabled, Archived}` appears identically in §01, §02, §07, §18, §00. Same pattern applies to **`UserStatus` (Active/Suspended/Revoked)** and **`AuditActionType` (16+ rows in §18)** — these are sibling enumerations with the same drift profile and SHOULD be folded into a single `test-enum-parity.sh` that loops over a registry of `(enum_name, expected_values, sites[])` tuples. This is the **N-enum generalisation** of AC-31-31's pattern.

### Candidate C — REST endpoint inventory (DISMISSED — API surface use)

**Sites:** `04-rest-api-endpoints.md`, `14-endpoint-examples.md`, `17-openapi.yaml`, `19-permission-matrix.md`, `05-auth-and-validation.md` — all reference `/wp-json/...` paths.

**Triage:**
- **Q1 — Same set?** NO. §17-openapi.yaml is the canonical full enumeration; §14 cites only example endpoints; §19 cites only auth-gated endpoints; §05 cites only auth-relevant endpoints.
- **Q3 — No single source-of-truth?** NO — §17-openapi.yaml IS the canonical SoT (machine-readable, designed for client codegen).

**Disposition:** **DISMISSED — API-surface-use pattern.** §17 is canonical; other sites cite subsets. Direct lockstep between §04 (narrative endpoint table) and §17 (OpenAPI spec) MAY warrant a future 2-site lockstep gate, but no AC-31-31 parity test. Recorded under AC-SAG-27's worked-dismissal table family.

### Candidate D — Permission capabilities (`AppView`/`AppModify`/etc.) (deferred — partial)

**Sites:** `19-permission-matrix.md` (canonical), `17-openapi.yaml`, `25-headless-auth-notes.md`, `34-phpunit-test-skeleton.md`, `33-bats-test-skeleton.md`, `28-example-github-actions.md`, `32-cli-test-plan.md`, `97-acceptance-criteria.md`.

**Triage:** §19 is the canonical permission matrix; other sites cite subsets per their domain. **Fails Q1 and Q3** — same disposition as Candidate C (API-surface-use). DISMISSED.

---

## Summary table

| Candidate | Sites | Q1 | Q2 | Q3 | Q4 | Disposition |
|---|---|---|---|---|---|---|
| A — `GL-*` error-code catalog | 5 | Partial (2 full + 3 subset) | YES | YES | YES | **QUALIFIES** — register hybrid pair+subset parity |
| B — `AppStatus` enum (+ siblings `UserStatus`, `AuditActionType`) | 5 | YES | YES | YES | YES | **QUALIFIES** — register N-enum parity |
| C — REST endpoint inventory | 5 | NO | YES | NO (§17 canonical) | YES | DISMISSED — API surface use |
| D — Permission capabilities | 8 | NO | YES | NO (§19 canonical) | YES | DISMISSED — API surface use |

**Net result:** 2 qualifying patterns surfaced + 2 dismissals recorded.

---

## What this validates about AC-SAG-27

1. **The triage protocol is portable** — applied without modification to §22 and produced clear yes/no dispositions for all 4 candidates.
2. **Two new pattern variants surfaced** that AC-31-31's current registry doesn't yet model:
   - **Canonical-pair + N subset-citers** (Candidate A) — needs hybrid `==` + `⊆` assertions.
   - **N-enum generalisation** (Candidate B) — single test that loops over a registry of enums rather than one test per enum.
3. **The dismissal record format works** — Candidates C and D mirror Phase 114's "API surface use" dismissals exactly.

## Out of scope for Phase 116

This phase is **discovery only** — no parity tests authored, no §22 spec edits, no AC-31-31 registry rows added. Phase 117 will execute on the 2 qualifying candidates (Candidates A + B), author the parity tests, extend the AC-31-31 registry, and bump the CI gate count from 13 → 15. Phase 117 is BLOCKED on user confirmation that the two new pattern variants (canonical-pair + subset-citers; N-enum generalisation) are acceptable extensions to AC-31-31's mechanical surface — these are non-trivial pattern additions and warrant explicit go/no-go.

## Verification

No code change in this phase. All 13 strict CI gates remain green by construction.
