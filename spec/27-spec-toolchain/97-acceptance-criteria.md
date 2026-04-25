# Acceptance Criteria — Spec Toolchain

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Scope:** `spec/27-spec-toolchain/`

---

## Module-level criteria

### AC-T-01 — Bijection between code and spec
- **Given** the set of executable files under `linter-scripts/` (extensions `.py`, `.cjs`, `.sh`, `.ps1`, `.go`) plus configuration files (`.toml`, `.allowlist`, `readme-cross-links.md`) and the set of workflows under `.github/workflows/*.yml`,
- **When** the spec sections in this module are enumerated,
- **Then** every code artifact MUST appear in exactly one spec section, and every spec section MUST point to exactly one code artifact that exists on disk.

### AC-T-02 — Inventory matches numbering convention
- **Given** the inventory tables in [`00-overview.md`](./00-overview.md),
- **When** each spec filename is checked against its category range (01–09 validators, 10–19 generators, 20–29 fillers, 30–39 auditors, 40–49 runners, 50–59 source validators, 60–69 configs, 70–79 CI),
- **Then** every entry MUST sit in the documented range for its category.

### AC-T-03 — Exit-code contract
- **Given** any spec file in slots 01–09 or 50–59 (validators),
- **When** the file is read,
- **Then** it MUST contain a section titled "Exit codes" listing at minimum `0` (pass), `1` (fail), and where applicable `2` (error / invocation problem).

### AC-T-04 — Idempotency declaration on fillers
- **Given** any spec file in slots 20–29,
- **When** the file is read,
- **Then** it MUST contain the literal phrase `idempotent` and explicitly state that re-runs on a satisfied tree are no-ops.

### AC-T-05 — Each spec lists the script's CLI surface
- **Given** any per-artifact spec section in this module,
- **When** the file is read,
- **Then** it MUST include a "Usage" code block showing the canonical invocation, and a list of supported CLI flags (or `_(none)_` if there are none).

### AC-T-06 — Each spec links back to the source file with a relative repo path
- **Given** any per-artifact spec section,
- **When** the file is read,
- **Then** it MUST link to the source file using a markdown link whose label is `` `linter-scripts/<name>` `` and whose target is `../../linter-scripts/<name>` (or `../../.github/workflows/<name>` for CI workflows).

### AC-T-07 — Slot immutability
- **Given** the §99 consistency report,
- **When** a script is deleted,
- **Then** its slot number MUST be marked "retired" in [`99-consistency-report.md`](./99-consistency-report.md) and MUST NOT be reused for a new artifact.

### AC-T-08 — Spec-health workflow trigger paths cover this module
- **Given** the workflow file `.github/workflows/spec-health.yml`,
- **When** its `on.push.paths` and `on.pull_request.paths` are inspected,
- **Then** they MUST include `spec/27-spec-toolchain/**` (so changes here re-run the gate) — see [`70-spec-health-yml.md`](./70-spec-health-yml.md) §3.

### AC-T-09 — Tree-health gate
- **Given** the entire `spec/` tree,
- **When** `node linter-scripts/check-tree-health.cjs --min=80` is run,
- **Then** this module MUST contribute `required=2/2` (overview + consistency report present) and the overall score MUST be ≥ 80.

### AC-T-10 — All cross-links resolve
- **Given** every relative `.md` link inside this module,
- **When** `python3 linter-scripts/check-spec-cross-links.py` is run,
- **Then** every link MUST resolve to an existing file (no entries from this module in the broken-links report).

---

## Per-artifact criteria

Per-script acceptance criteria live inside each per-artifact spec file (slots 01–79) under their own "Acceptance criteria" section. The §97 file aggregates only module-level rules.

---

## Validation

```bash
bash linter-scripts/run.sh                          # full pipeline
node linter-scripts/check-tree-health.cjs --min=80  # gate
python3 linter-scripts/check-spec-cross-links.py    # link check
```

All commands MUST exit `0` for this module's acceptance to hold.
