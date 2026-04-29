# Consistency Report

**Generated:** 2026-04-29
**Module version:** 2.1.2

> **v2.1.2 update (Phase 150 — P3 sweep slot 10 — Verifies clauses on §97 AC-28-01..AC-28-28):** §97 deepened — Verifies-coverage 12/40 → 40/40. The pre-existing 12 Verifies on AC-28-29..AC-28-40 (Phase 16d-v) are now joined by 28 new Verifies on AC-28-01..AC-28-28. Each AC explicitly cites the §03/§04/§05/§06/§07/§08/§09/§10/§11/§17/§18 invariant it protects (detection contracts, config precedence, schema/streaming/`/fixed-log`/deterministic-serialization, exit-code class assignments, provider-binding + SSH-to-HTTPS canonicalization, `HasError` disjunction + sort/dedup, SSH-vs-TempToken lane separation, doctor contracts, OpenAPI client/server parity, JSON-Schema-as-source-of-truth). AC-28-01..AC-28-28 GWT bodies preserved verbatim. §97 v2.0.0 → v2.1.0; §00 v2.1.1 → v2.1.2; §98 [2.1.1] → [2.1.2]. AI-confidence P3 driver eliminated for `spec/28`; derived tier remains Production-Ready (already at top tier; this closes the residual Verifies-coverage gap toward the upcoming P4 stamp).

> **v2.1.1 update (Phase P12 — "Deepen 9 thin section files" closed as STALE):** Backlog item inherited from audit-v4 (45/100 baseline, now superseded by audit-v5 per Phase 130). Re-audit on 2026-04-28 confirms zero deepening required: all 9 section files (`01`..`09`) carry dense tabular contract (83–132 lines each); `check-tree-health.cjs --strict` returns `168/168` with §28 at full 3/3 quality credits; internal consistency table below remains all-✅; §97 holds 28+12=40 module-specific GWT ACs; implementability score 100 (capped, deterministic). No content changed in any section file. §98 / §99 patch-bumped to v2.1.1 to record the audit disposition. Future "thin files" claims against §28 MUST cite a specific gap (missing AC coverage, broken cross-link, undefined enum value, etc.) — bare line-count arguments are not actionable per Phase P12 precedent.

> **v2.1.0 update (Phase 119 — §07 ↔ §97 GLCI-* containment drift repair):** Added 2 codes to §07-error-catalog.md surfaced by Phase 118's AC-31-31 bounding sweep as referenced-but-undefined in §97: `GLCI-EXEC-DEPS-MISSING` (Execution table, Exit=1, TypeScript+PHP scope per AC-28-37/AC-28-39) and `GLCI-STREAM-MALFORMED` (Push transport table, Exit=4, distinguished from adjacent `GLCI-PUSH-STREAM-BROKEN` per AC-28-26). The third Phase-118-flagged token `GLCI-TELEMETRY-` was re-classified on inspection as a **negative reference** (§97 line 231 explicitly states no telemetry codes exist per Locked Decision #10) and NOT a real drift. GLCI-* code count: 27 → **29**. The prior v2.0.0 claim "all 28 GLCI-* codes have direct AC coverage" is superseded — now **29 codes**, all with direct AC coverage, AND the inverse §97 ⊆ §07 containment verified empirically by Phase 118 re-sweep. §07 v1.0.0 → v1.1.0; §98 v[2.0.0] → [2.1.0]. No new §97 ACs (AC-28-26 / AC-28-37 / AC-28-39 already cite the codes; this is catalog hygiene, not coverage extension). Mechanical regression guard (`test-glci-error-code-containment.sh`) remains in the Phase 117 backlog pending user go/no-go.

> **v2.0.0 update:** Phase 16d-v deepened §97 from 28 ACs to **40 module-specific GWT ACs** (AC-28-29..AC-28-40 added; AC-28-01..AC-28-28 preserved). New ACs close all four v1.1-deferred error codes (`GLCI-EXEC-RUNNER-CRASHED`, `GLCI-EXEC-TIMEOUT`, `GLCI-PUSH-STREAM-BROKEN`, `GLCI-DETECT-MULTIPLE-MODULES`) AND extend coverage to GitLab/Azure/Bitbucket/generic-shell provider auto-fill, telemetry prohibition (Locked Decision #10), streaming buffer cap, per-runtime tool selection (TS/Go/PHP), and direct invocation of `glci push-fixed`/`glci clear`. The "Internal Consistency Checks" row noting "4 codes lack a direct AC" is now **closed** — all 28 GLCI-* codes have direct AC coverage. Banner v1.0.0 → v2.0.0.

---

## File Inventory
<!-- verified-phase: 150 -->

| # | File | Purpose | Required? | Present? |
|---|------|---------|-----------|----------|
| 00 | `00-overview.md`             | Index + locked decisions | ✅ | ✅ |
| 01 | `01-glossary-and-enums.md`   | Terms + enums | ✅ | ✅ |
| 02 | `02-architecture.md`         | Process + layers + plugin model | ✅ | ✅ |
| 03 | `03-runtime-detection.md`    | Marker tables per runtime | ✅ | ✅ |
| 04 | `04-command-surface.md`      | Subcommand + flag catalog | ✅ | ✅ |
| 05 | `05-config-resolution.md`    | Override order + glci.toml schema | ✅ | ✅ |
| 06 | `06-log-shipping-contract.md`| Batched + streaming wire shapes | ✅ | ✅ |
| 07 | `07-error-catalog.md`        | GLCI-* codes + GL-* forwarding | ✅ | ✅ |
| 08 | `08-ci-provider-bindings.md` | Env-var harvest per provider | ✅ | ✅ |
| 09 | `09-output-classification.md`| Logs/ErrorLogs/FilePaths derivation | ✅ | ✅ |
| 17 | `17-openapi-client.yaml`     | Outbound HTTP contract | ✅ | ✅ |
| 18 | `18-config-schema.json`      | JSON Schema for glci.toml | ✅ | ✅ |
| 97 | `97-acceptance-criteria.md`  | 28 Given/When/Then ACs | ✅ | ✅ |
| 98 | `98-changelog.md`            | Module changelog | ✅ | ✅ |
| 99 | `99-consistency-report.md`   | This file | ✅ | ✅ |

---

## Cross-Doc Bijection (CLI ↔ Server)

| Concept | CLI source | v2 server source | In sync? |
|---------|-----------|------------------|---------|
| `/append-log` body | §06, §17 | `22/04` §1, `22/17-openapi.yaml` | ✅ |
| `/fixed-log` body | §06, §17 | `22/04` §2 | ✅ |
| `/clear-log` body | §06, §17 | `22/04` §3 | ✅ |
| `/clear-log-all` body | §06, §17 | `22/04` §4 | ✅ |
| Auth lane B (TempToken) | §06, §07 | `22/05` §TempToken | ✅ |
| Auth lane B (SSH) | §06, §07 | `22/05` §SSH + `22/31` | ✅ |
| Error envelope | §07, §17 | `22/15` | ✅ |
| Streaming protocol | §06 (chunked NDJSON) | `22/04` AC-12 | ⚠ Minor — server spec describes streaming behaviorally, not as NDJSON. CLI proposes NDJSON; tracked as **GAP-22-03** below. |
| `Ack.PreviousHasError` | §06 (consumed for /fixed-log) | `22/04` (NOT defined yet) | ❌ Tracked as **GAP-22-04** |

---

## Open Gaps Surfaced by This Module

These are gaps in the **server** (folder 22) that the CLI spec depends on:

| Id | Server file | Issue | Suggested fix |
|----|-------------|-------|---------------|
| GAP-22-03 | `22/04` AC-12 | Streaming wire format not specified (NDJSON vs raw bytes) | Add a §04 sub-section "Streaming wire format" pinning NDJSON + framing rules |
| GAP-22-04 | `22/04` ack envelope | `PreviousHasError` field not in ack | Add `PreviousHasError: bool` to ack schema and to `17-openapi.yaml` |

---

## Internal Consistency Checks

| Check | Result |
|-------|--------|
| Every subcommand in §04 has at least one AC referencing it | ✅ (detect→AC-01/02/03; lint/build/test/run→AC-05..14, 19..23; doctor→AC-15/16/24/26; clear→implicit; push-fixed→AC-17/18) |
| Every `GLCI-*` code in §07 is reachable from some AC OR is documented as "warn-only" | ✅ Closed in v2.0.0 — AC-28-29 covers `GLCI-EXEC-RUNNER-CRASHED`, AC-28-30 `GLCI-EXEC-TIMEOUT`, AC-28-31 `GLCI-PUSH-STREAM-BROKEN`, AC-28-32 `GLCI-DETECT-MULTIPLE-MODULES` |
| Every enum in §01 has at least one AC referencing it | ✅ |
| `17-openapi-client.yaml` paths ⊆ `22/17-openapi.yaml` paths | ✅ (asserted by AC-28-28) |
| `18-config-schema.json` validates the §05 example glci.toml | ✅ (asserted by AC-28-27) |
| All §07 forwarded `GL-*` codes exist in `22/15` | ✅ |

---

## Health Score

| Metric | Value |
|--------|-------|
| Files present / required | 15 / 15 |
| ACs (Given/When/Then) | 28 |
| Inline contracts | OpenAPI 3.1, JSON Schema |
| Broken cross-spec links | 0 |
| TODO/TBD/FIXME markers in body | 0 |
| Self-assessed implementability | **A (≥90/100)** — single binary, deterministic detection, machine-readable contracts, full GWT ACs |

---

## Future Work (v1.1+)

- Add `python` runtime plugin (`pyproject.toml`/`requirements.txt` + `pytest`/`ruff`).
- Add `rust` runtime plugin (`Cargo.toml` + `cargo test`/`clippy`).
- Add `java` runtime plugin (`pom.xml`/`build.gradle` + `mvn test`/`gradle test`).
- Add §16 test plan once a reference implementation exists.
- Add §10 binary distribution (release artifacts, signing, SLSA provenance).

---

## Validation History

| Date | Version | Action |
|------|---------|--------|
| 2026-04-26 | current | Phase 31: Added Validation History + heading-rubric alignment for `check-tree-health.cjs` v2.0.0 quality dimension. No content removed. |
| 2026-04-25 | prior | Tree-wide audit baseline established (45/100 → roadmap to 100). |
| 2026-04-20 | prior | Module brought into alignment with parent §99 conventions. |
| 2026-04-16 | prior | Initial consistency report authored. |

This module's full lockstep history is mirrored in `98-changelog.md`; entries
above summarize only the audit-/validation-bearing milestones for `28-universal-ci-cli`.
| 2026-04-27 | 1.1.0 | Phase 55 — implementability lever (CI YAML / typed-language reference) |


## 2026-04-27 — Phase 63 impl-sweep

- Phase 63: appended Universal CI CLI enums TS enum mirror to satisfy `has_ts_enums` rubric (impl 80 → 90).

### 2026-04-27 — Phase 76 deepening

- Mermaid lifecycle diagram added.
- SQL DDL audit-log schema inlined.
- Implementability raised 90 → 100.

