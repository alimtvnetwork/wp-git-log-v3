---
phase: 126
title: AC-SAG-27 enumeration sweep — spec/17-consolidated-guidelines/ (36 files)
mode: discovery-only
predecessor: phase-125-folders-02-03-05-06-07-10-11-24-enumeration-sweep.md
successor: TBD (Phase 117 mechanization or Phase 127 catalog creation)
date: 2026-04-27
---

# Phase 126 — Folder 17 dedicated enumeration sweep

## Scope & method

`spec/17-consolidated-guidelines/` contains 36 files: 33 thematic rollups (`01-*` … `32-*`)
plus `97-acceptance-criteria.md`, `98-changelog.md`, `99-consistency-report.md`. It is the
audit/governance hub of the spec, distinct from content folders.

ALLCAPS-token frequency ranked the following enumeration namespaces:

| Token namespace | Top entries | Cross-folder sites | Has canonical SoT? |
|---|---|---|---|
| Lint rule IDs (`<DOMAIN>-<NAME>-NNN`) | `MISSING-DESC-001` (48), `DB-FREETEXT-001` (13), `WAIVER-MALFORMED-001` (3), `MIG-{TARGET,NULLABLE,NAMING,HEADERS}-001` (2 each) | 17 files across §02, §04, §05, §06, §17 + `linter-scripts/forbidden-strings.toml` | **No** — forbidden-strings.toml only catalogs `STALE-*` rename guards, NOT lint rules |
| Audit verdict bands | `CRITICAL-1..3`, `HIGH-1..3`, `MEDIUM-1..3` | §17 only (3 files: 25/26/29) | Self-contained inside audit reports |
| `SM-*` gate IDs (`SM-GS`, `SM-PE`, `SM-CG`) | 4/2/2 occurrences | §03 error registry + §17 §03 rollup | §03 error-codes-master.json is canonical |
| `AC-*` IDs | `AC-01..AC-20` | §17 audits + every folder's §97 | Each folder's §97 is local-canonical (per AC-SAG-25) |

## Surfaced candidate

### Candidate **O — Lint rule IDs** (CONTAINMENT, qualified)

- **Pattern:** `<DOMAIN>-<NAME>-<NNN>` (e.g. `MISSING-DESC-001`, `DB-FREETEXT-001`,
  `WAIVER-MALFORMED-001`, `MIG-TARGET-001`, `MIG-NULLABLE-001`, `MIG-NAMING-001`,
  `MIG-HEADERS-001`, `CODE-RED-009`).
- **Sites:** ≥ 17 spec files across §02, §04, §05, §06, §17, plus `linter-scripts/`
  references and the `linter-waive:` SQL-comment syntax used in 30+ schema examples.
- **Why it qualifies:** No canonical catalog file enumerates these rule IDs.
  - `linter-scripts/forbidden-strings.toml` only holds `STALE-*` rename guards.
  - `linter-scripts/check-forbidden-strings.py` does not declare them either.
  - Each rule is invoked in `linter-waive:` comments without anchor to a defining doc.
  - High silent-drift risk: if a rule ID is renamed (e.g. `MISSING-DESC-001` →
    `SCHEMA-DESC-MISSING-001`), waivers across 30+ files would silently stop matching.
- **Recommended remedy (Phase 128):** Create
  `spec/03-error-manage/03-error-code-registry/05-lint-rule-catalog.md` (or co-locate
  inside the existing error-code-registry folder) as the canonical SoT, then run a
  containment harness asserting every `linter-waive: <ID>` and every cross-doc citation
  resolves to a row in the catalog.

## Dismissals

### Dismissal P1 — Audit verdict bands (`CRITICAL-N`, `HIGH-N`, `MEDIUM-N`)
Used only inside `25-blind-ai-implementability-audit.md`, `26-blind-ai-audit-v2.md`,
`29-blind-ai-audit-v3.md`. Each audit report defines its own band scheme inline; the
bands are tied to specific finding lists, not a reusable taxonomy. **Single-folder,
self-contained.** No drift surface.

### Dismissal P2 — `SM-*` gate IDs
The §03 error-code-registry (`error-codes-master.json` + `01-registry.md`) is the
machine-readable source of truth. §17/03-error-management.md cites them by reference.
This is **API-surface-use** per the Phase 120 corollary (machine SoT exists).

### Dismissal P3 — `AC-NN` IDs (per-folder)
Already governed by AC-SAG-25 (each folder's §97 is its own local AC namespace).
Numbering is intentionally per-folder; not a cross-folder enum.

### Dismissal P4 — SQL keywords (`CREATE`, `TABLE`, `PRIMARY`, `INTEGER`, `TEXT`, `NULL`, etc.)
SQL grammar tokens, not spec-defined enums. Out of scope.

## Pattern observation — the "audit folder" exception

§17 is the **first folder** where an enumeration namespace surfaced that is genuinely
**cross-folder** (used in 5 sibling folders) yet has **no canonical SoT** — exactly the
shape AC-SAG-27 was designed to detect. Earlier sweeps (Phase 121, 125) found that
content folders self-contain via §97; §17 surfaces the gap because it is downstream of
§02–§06 and inherits their citations without owning them.

**Implication for Phase 126:** AC-SAG-27 should always include "rollup/audit folders"
in its sweep set — they aggregate citations and reveal upstream definitional gaps.

## AC-31-31 backlog (updated)

| ID | Domain | Type | Sites | Pre-req |
|---|---|---|---|---|
| A | §22 `GL-*` codes | Containment | 8+ | None |
| B | §22 `AppStatus` enum | Uniform-parity | 6 | None |
| E | §14 GOOS/GOARCH tuples | Uniform-parity | 5 | None |
| H | §28 `GLCI-*` codes | Containment | 7 | None |
| K | §28 output buckets | Uniform-parity | 7 | None |
| L | §13 wrapper activation states | Uniform-parity | 4+ | None |
| N | §16 placeholder tokens | Containment | 4 | Phase 123 catalog |
| **O** | **Lint rule IDs (cross-folder)** | **Containment** | **17+** | **Phase 128 catalog** |

**Total: 8 candidates** (4 containment, 4 uniform-parity).

## Recommended next phases

| Phase | Action | Mode |
|---|---|---|
| **117** | Mechanize the now-8-candidate AC-31-31 backlog. Two reusable harnesses: containment (A+H, defers N+O until catalogs exist) + uniform-parity (B+E+K+L). | 🚧 Decision |
| **122** | §17 OpenAPI: enumerate `GLCI-*` codes (parity with §22) or leave code-free. | 🚧 Decision |
| **123** | Create `spec/16-generic-release/09-placeholder-tokens.md` catalog. | 🤖 Autonomous (after 117) |
| **124** | Audit §14 GOOS/GOARCH cite to §16 generic source (reframed Phase 121 work). | 🚧 Decision |
| **127** | AC-SAG-27 sweep of `spec/27-spec-toolchain/` and `spec/29-app-issues-cli/` (remaining unsweept folders). | 🤖 Autonomous |
| **128** | Create `spec/03-error-manage/03-error-code-registry/05-lint-rule-catalog.md` canonical SoT for Candidate O, then mechanize containment harness. | 🤖 Autonomous (after 117) |

## Completion certification

- ✅ 36 files in §17 triaged
- ✅ 1 new candidate (O — Lint rule IDs) added to backlog (8 total)
- ✅ 4 dismissals documented with rationale
- ✅ Audit-folder exception observation recorded
- ✅ Discovery-only — zero spec files modified
