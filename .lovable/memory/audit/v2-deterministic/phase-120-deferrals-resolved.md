# Phase 120 — Resolve Phase 118 deferrals (latest.json + output-buckets + config-schema)

**Date:** 2026-04-27
**Type:** Cross-folder enumeration discovery (no code change)
**Trigger:** Phase 118 sweep against §04/§14/§28 surfaced 3 deferred candidates that needed deeper schema-extraction or full-content inspection beyond initial `rg` pattern counting. Phase 120 closes each deferral with an AC-SAG-27 disposition.

---

## Methodology

For each deferred candidate, perform targeted content extraction (JSON Schema parsing for §28 18-config-schema, bucket-vocabulary scan for §28 09-output-classification, field-token analysis for §14 latest.json) and apply AC-SAG-27's four diagnostic questions.

---

## Deferral 1 — `latest.json` schema (§14)

**Sites:** 7 files reference `latest.json` (`00-overview.md`, `10-last-release-detection.md`, `12-code-signing.md`, `15-release-versioning.md`, `28-update-interface-contract.md`, `98-changelog.md`, `99-consistency-report.md`).

**Deeper inspection:**
- `28-update-interface-contract.md` carries the **canonical JSON Schema (Draft-07)** for `latest.json` (§1, line 26 onwards) — the only site that enumerates the field set as a contract.
- `15-release-versioning.md`: 2 field-token mentions (`version`, `sha256`) — partial citation.
- `10-last-release-detection.md`: 1 field-token mention (`version`) — partial citation in a procedural example.
- `00-overview.md`, `12-code-signing.md`, `98-changelog.md`, `99-consistency-report.md`: zero field-token mentions; reference `latest.json` only by filename, not by schema content.

**Triage:**
- **Q1 — Same set?** **NO.** Only §28 enumerates the full field set; §15 and §10 cite single fields; the other 4 sites cite only the filename. Heterogeneous arity.
- **Q3 — No single source-of-truth?** **NO.** §28-update-interface-contract.md IS the canonical SoT — explicitly written as "Closes the audit gap HIGH — Self-Update relies on undefined `latest.json`" and ships a Draft-07 JSON Schema.

**Disposition:** **DISMISSED — API-surface-use pattern.** §28-update-interface-contract.md is canonical; other sites cite subsets (or just the filename). Same disposition class as §22 Candidate D (permission caps) and §28 Candidate I (REST endpoints). No parity test warranted. Direct lockstep between §28 and §15 (the only other site enumerating ≥ 2 fields) MAY be considered if §15 grows to enumerate the full contract; not today.

## Deferral 2 — Output-classification buckets (`Logs` / `ErrorLogs` / `FilePaths`) — §28

**Sites (7 substantive):**
- `09-output-classification.md` — canonical narrative (the 3-bucket vocabulary is the entire purpose of this file)
- `00-overview.md` — table-row mention enumerating the 3 buckets verbatim (line 75: "How a runner's stdout is split into `Logs[]`, `ErrorLogs[]`, `FilePaths[]`")
- `01-glossary-and-enums.md` — vocabulary entries
- `02-architecture.md` — process-layer references
- `04-command-surface.md` — command output references
- `06-log-shipping-contract.md` — wire-shape references
- `97-acceptance-criteria.md` — AC verifications cite the buckets
- (`98-changelog.md`, `99-consistency-report.md` — meta-files, narrative mentions only)

**Triage:**
- **Q1 — Same set?** YES — every substantive site enumerates exactly the 3-tuple `{Logs, ErrorLogs, FilePaths}`. The set is small (3 members) and stable across sites.
- **Q2 — Same semantics?** YES — each bucket carries identical wire-shape semantics across all 7 sites (Logs[] = arbitrary, ErrorLogs[] = error-classified, FilePaths[] = derived from any line).
- **Q3 — No single source-of-truth?** §09 is *narratively* canonical (the file's entire purpose), but §06-log-shipping-contract.md is *wire-canonical* (the JSON payload schema lives there). Neither mechanically derives from the other; both are hand-maintained. **YES, no in-spec single SoT.**
- **Q4 — Silent drift risk?** YES — adding a 4th bucket (e.g. `Decisions[]`, `Metrics[]`) to §09 without back-propagating to §06's wire shape OR §00's overview table would silently break server-side parsing while passing all current tests. No current guard.

**Disposition:** **QUALIFIES under AC-31-31** as a uniform multi-site full-enumeration parity. Same simple variant as §14 Candidate E (GOOS/GOARCH triplet). Recommendation: **add as Candidate K** to the Phase 117 backlog. The Phase 117 "uniform-parity harness" proposed for B (`AppStatus`-family) + E (GOOS/GOARCH) extends naturally to cover K — the same N-tuple parity-across-N-sites pattern.

## Deferral 3 — `18-config-schema.json` top-level keys (§28)

**Top-level keys (8):** `auth`, `ci_provider`, `classify`, `pipeline`, `push`, `repo`, `runtime`, `server`.

**Sites referencing schema keys:**
- `18-config-schema.json` — canonical JSON Schema (machine source-of-truth).
- `05-config-resolution.md` — references all 8 keys: `server` (7), `runtime` (7), `auth` (6), `push` (4), `repo` (3), `pipeline` (2), `classify` (1), `ci_provider` (1).
- `04-command-surface.md`, `02-architecture.md` — partial references (3 keys each).

**Triage:**
- **Q1 — Same set?** §05 is the only file that mentions all 8 keys with substantial frequency, but it does so as **prose discussion of resolution order**, not as an enumerated contract. §04 and §02 cite partial subsets.
- **Q3 — No single source-of-truth?** **NO.** `18-config-schema.json` is THE canonical machine-readable schema (the JSON Schema file IS the contract; everything else is documentation that references it). This is exactly the API-surface-use pattern AC-SAG-27 dismisses.

**Disposition:** **DISMISSED — API-surface-use pattern.** §18 is the canonical schema; §05/§04/§02 are usage documentation. Same disposition class as Deferral 1 (§14 latest.json) and §22 Candidate C (REST endpoints). No parity test. If a contributor adds a 9th top-level key to §18, the schema-validator (whatever consumes the JSON Schema downstream) is the appropriate enforcement layer — not a cross-doc parity test.

---

## Summary table

| Deferral | Sites | Q1 | Q2 | Q3 | Q4 | Disposition |
|---|---|---|---|---|---|---|
| 1 — `latest.json` schema (§14) | 7 (only §28-update-interface canonical) | NO | n/a | NO (§28 canonical) | n/a | **DISMISSED** — API-surface-use |
| 2 — Output-classification buckets (§28) | 7 substantive | YES | YES | YES | YES | **QUALIFIES** — Candidate K, uniform multi-site parity |
| 3 — Config-schema top-level keys (§28) | 4 substantive (only §18 enumerates contract) | NO | n/a | NO (§18 canonical) | n/a | **DISMISSED** — API-surface-use |

**Net result:** 1 new qualifying candidate (K — output-classification buckets) added to Phase 117 backlog, bringing the total queue from 4 → **5 candidates**. 2 dismissals recorded.

---

## Cross-folder pattern observations

1. **AC-SAG-27 Q3 ("no single source-of-truth?") is the discriminator that fires most often.** Both Phase 120 dismissals (`latest.json` schema, config-schema keys) failed at Q3 — when a JSON Schema or YAML contract exists, that artefact IS the canonical enumeration and parity testing other documentation against it would lock the docs to the schema in a way that prevents legitimate documentation evolution (e.g. summarising 8 keys as "configurable across server, auth, pipeline, …" without listing all of them).

2. **The "machine contract" heuristic generalises.** Combining Phase 116/118/120 dismissals: any candidate where the canonical site is a `*.json` (JSON Schema), `*.yaml` (OpenAPI), or `*.sql` (DDL) file ALMOST ALWAYS dismisses at Q3 — these are by definition single-source-of-truth artefacts. The exceptions are §22 Candidate B (`AppStatus` enum: §18-schema.sql IS the executable SoT but the enum's 3 values are simple enough that 4 doc sites do enumerate them verbatim, satisfying Q1 with low arity) and Phase 113's WEIGHTS dimension table (Python dict → markdown table → second Python dict, no machine schema involved). This pattern variant deserves a future AC-SAG-27 refinement: **"Q3 corollary — when canonical site is a machine-readable schema, presume API-surface-use unless the cited subset has cardinality ≤ 5 AND every site enumerates the full set verbatim"**. Out of scope for Phase 120; queued as a candidate AC-SAG-27 amendment for future work.

3. **Cumulative AC-31-31 candidate ledger after Phases 116 + 118 + 120:**

   | # | Folder | Candidate | Pattern variant | Status |
   |---|---|---|---|---|
   | A | §22 | `GL-*` error catalog | canonical+containment hybrid | qualifies |
   | B | §22 | `AppStatus`/`UserStatus`/`AuditActionType` enum family | uniform N-enum loop | qualifies |
   | E | §14 | GOOS/GOARCH 6-tuple | uniform 3-site parity | qualifies |
   | H | §28 | `GLCI-*` error catalog | canonical+containment hybrid | qualifies |
   | **K** | **§28** | **`Logs`/`ErrorLogs`/`FilePaths` buckets** | **uniform 7-site parity** | **qualifies (NEW from Phase 120)** |

   **5 qualifying candidates total.** Recommendation for Phase 117 (when unblocked): **2 reusable harnesses** cover all 5 — one containment harness (A + H), one uniform-parity harness (B + E + K). CI gate count would bump 13 → 15 (one new gate per harness, regardless of how many candidates each harness covers).

## Out of scope

This phase is **discovery only** — no parity tests authored, no spec edits, no AC-31-31 registry rows added, no AC-SAG-27 amendments. Phase 117 (when unblocked) executes on the now-5-candidate backlog.

## Verification

No code change. All 13 strict CI gates remain green by construction.
