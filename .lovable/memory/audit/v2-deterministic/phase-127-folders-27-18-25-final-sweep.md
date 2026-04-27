---
phase: 127
title: AC-SAG-27 enumeration sweep — §27 spec-toolchain, §18 wp-plugin, §25 app-issues (final discovery sweep)
mode: discovery-only
predecessor: phase-126-folder-17-enumeration-sweep.md
successor: TBD (Phase 117 mechanization or Phase 128 catalog creation)
date: 2026-04-27
---

# Phase 127 — Final discovery sweep across §27, §18, §25

## Scope & method

Closes the AC-SAG-27 discovery campaign. These were the last unsweept content folders:

| Folder | Files | Top-token signals | Verdict |
|---|---|---|---|
| §27 spec-toolchain | 41 | `MUST` (314), `RUBRIC_VERSION` (48), `WEIGHTS` (29), `AC-T-NN`, `G-TODO-NN` | Dismissed — all SoTs are machine artifacts in `linter-scripts/` |
| §18 wp-plugin-how-to | 13 | `REST`, `ABSPATH` (71), `PLUGIN_SLUG` (18), SQL keywords | Dismissed — WP built-ins + per-plugin templating placeholders |
| §25 app-issues | 6 | `P2-GL-NN`, `OI-ALLOW-NN`, `F-NN` | Dismissed — §25-internal audit findings; only `_archive/21` cross-references |

§29 does not exist as a folder. All other content folders previously covered by
Phase 118, 121, 125, 126.

## Dismissals

### Dismissal Q1 — §27 `RUBRIC_VERSION` / `WEIGHTS` constants
- **Cross-folder hits:** §27 (12 files), §01 (3 files), `linter-scripts/` (7 files).
- **Canonical SoT:** `linter-scripts/audit-spec-vs-code-v2.py` (Python module-level
  constants) + `linter-scripts/trace-map.toml` (TOML weights table).
- **Verdict:** **API-surface-use** per Phase 120 machine-contract corollary. The §27
  documentation cites these constants by name; the machine artifacts are the SoT.
- Test coverage already exists: `linter-scripts/test/test-weights-parity.sh`,
  `linter-scripts/test/test-qa-baseline-footer.sh`.

### Dismissal Q2 — §27 `AC-T-NN` and `G-TODO-NN` namespaces
- **Cross-folder hits:** §27 only (6 files), §01 (1 cross-link).
- **Canonical SoT:** §27 §97 acceptance criteria.
- **Verdict:** Single-folder per AC-SAG-25. Local-canonical. The §01 cross-link is
  a citation-by-reference, not restatement.

### Dismissal Q3 — §18 `ABSPATH`, `PLUGIN_SLUG`
- `ABSPATH`: WordPress built-in PHP constant defined by WP core. Not a spec enum.
- `PLUGIN_SLUG`: Per-plugin templating placeholder (each WP plugin substitutes its
  own slug at scaffold time). Not a cross-spec enum.
- **Verdict:** External-platform convention + scaffold-time substitution. No drift
  surface inside `spec/`.

### Dismissal Q4 — §25 `P2-GL-NN`, `OI-ALLOW-NN`, `F-NN`
- All three namespaces are confined to:
  - `spec/25-app-issues/01-phase-2-git-logs-audit/` (origination)
  - `spec/25-app-issues/02-consolidated-audit-findings/` (rollup)
  - `spec/_archive/21-git-logs-v1/` (intentionally archived, no maintenance)
- **Verdict:** §25 is a self-contained audit-finding folder; same shape as §17
  audit reports. Local-canonical per AC-SAG-25.

## Surfaced candidates

**Zero new candidates.** AC-31-31 backlog remains at **8** (A, B, E, H, K, L, N, O).

## Discovery campaign closure

With Phase 127, the AC-SAG-27 enumeration sweep is **complete across all content
folders in `spec/`**:

| Phase | Coverage |
|---|---|
| 116 | §22 git-logs-v2 |
| 118 | §04, §14, §28 |
| 121 | §12, §13, §15, §16 |
| 125 | §02, §03, §05, §06, §07, §10, §11, §24 |
| 126 | §17 (audit/rollup folder) |
| **127** | **§18, §25, §27 (final closure)** |

Folders not swept by design: §00 (root meta), §01 (authoring guide — own §97
governs itself), §08 (docs-viewer-ui — UI only, no enums), §09 (code-block-system
— UI only), §19 (gap-analysis — narrative only), §20 (wp-plugin-conventions —
covered transitively via §18), §21 (`_archive`, frozen), §23 (app-database — own
§97), §26 (Mermaid diagrams), §97/§98/§99 root governance.

## Final AC-31-31 backlog (frozen for mechanization)

| ID | Domain | Type | Sites | Pre-req |
|---|---|---|---|---|
| A | §22 `GL-*` codes | Containment | 8+ | None |
| B | §22 `AppStatus` enum | Uniform-parity | 6 | None |
| E | §14 GOOS/GOARCH tuples | Uniform-parity | 5 | None |
| H | §28 `GLCI-*` codes | Containment | 7 | None |
| K | §28 output buckets | Uniform-parity | 7 | None |
| L | §13 wrapper activation states | Uniform-parity | 4+ | None |
| N | §16 placeholder tokens | Containment | 4 | Phase 123 catalog |
| O | Lint rule IDs (cross-folder) | Containment | 17+ | Phase 128 catalog |

**4 containment + 4 uniform-parity. Two reusable harnesses suffice.**

## Pattern observations (cumulative)

1. **Content folders self-contain via §97** (Phase 121/125). AC-SAG-25 deference
   discipline is working.
2. **CLI/CI/release toolchain folders concentrate enumeration drift** — §12, §13,
   §14, §16, §22, §28 produced 7 of 8 candidates.
3. **Audit/rollup folders surface upstream definitional gaps** (Phase 126). §17
   produced Candidate O — lint rule IDs without a catalog.
4. **Machine-readable SoTs (JSON Schema, TOML, Python constants) are API surfaces**,
   not drift surfaces (Phase 120 + 127 corollary). Trust the machine.

## Recommended next phases

| Phase | Action | Mode |
|---|---|---|
| **117** | Mechanize 8-candidate AC-31-31 backlog. Two harnesses: containment (A+H, defer N+O) + uniform-parity (B+E+K+L). | 🚧 Decision |
| **122** | §17 OpenAPI: enumerate `GLCI-*` codes (parity with §22) or leave code-free. | 🚧 Decision |
| **123** | Create `spec/16-generic-release/09-placeholder-tokens.md` catalog (Candidate N pre-req). | 🤖 Autonomous (after 117) |
| **124** | Audit §14 GOOS/GOARCH AC-20 cite to §16 generic source. | 🚧 Decision |
| **128** | Create `spec/03-error-manage/03-error-code-registry/05-lint-rule-catalog.md` canonical SoT (Candidate O pre-req). | 🤖 Autonomous (after 117) |

## Completion certification

- ✅ §27 (41 files), §18 (13 files), §25 (6 files) all triaged
- ✅ 4 dismissals documented with rationale
- ✅ 0 new candidates — backlog frozen at 8
- ✅ Discovery campaign **closed** across all content folders in `spec/`
- ✅ Cumulative pattern observations recorded
- ✅ Discovery-only — zero spec files modified
