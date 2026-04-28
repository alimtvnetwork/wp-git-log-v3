# Phase H6 — Archive Isolation Audit (read-only)

**Date:** 2026-04-28
**Status:** CLOSED
**Predecessor:** Phase H3 (codified `_archive/` exclusion as standard pattern across spec-traversing linters)
**Successor:** none queued

## What

Read-only audit verifying that `spec/_archive/21-git-logs-v1/` is properly isolated:
1. No live spec content treats archive files as authoritative spec
2. All live links INTO the archive resolve (cross-link gate confirms)
3. All spec-traversing linters exclude `_archive/` at runtime (not just by code-reading)

This was Phase H4/H5's queued backlog item #4 — "Tree-health archive auditor: confirm nothing live still references `spec/_archive/21-git-logs-v1/`".

## Findings

### 1. Linter exclusions verified at RUNTIME (not just by source-reading)

| Linter | Total scanned | Archive-leaked | Status |
|---|---|---|---|
| `check-99-summary-freshness.py` (`find_99_files()`) | 87 | **0** | ✅ |
| `audit-spec-vs-code-v2.py` (`ALL_MODULES`) | 87 | **0** | ✅ |
| `generate-trace-map.py` (`collect_ac_ids()`) | 1315 ACs | **0** | ✅ |
| `check-99-stamp-bump.py` (lines 133, 213) | n/a | **0** | ✅ source-verified |
| `check-lockstep.cjs` (walker line 105) | 87 modules | **0** | ✅ confirmed in H3 |
| `check-tree-health.cjs` (`ARCHIVE_PREFIX` line 38, line 59) | 56 modules | **0** | ✅ source-verified |
| `deepen-consistency-reports.py` (line 162) | n/a | **0** | ✅ source-verified |
| `fill-missing-{consistency-reports,acceptance-criteria,changelogs}.cjs` | n/a | **0** | ✅ source-verified |

H3's audit was source-code-based; H6 elevates 3 of them (freshness, audit-v2, trace-map) to **runtime** verification by importing the modules and calling their public enumerator functions on the live tree.

### 2. Live links INTO `spec/_archive/21-git-logs-v1/` — all resolve

18 markdown links across 6 live spec files reference archive content (intentional historical anchors):

| Source | Purpose |
|---|---|
| `spec/00-overview.md` | Top-level deprecation banner pointing to legacy v1 |
| `spec/spec-index.md` | Generated index includes archive section (Phase 102 generator handles this) |
| `spec/22-git-logs-v2/00-overview.md` | "v1 verbatim brief" + "legacy v1 spec" anchor links (3 links) |
| `spec/22-git-logs-v2/{97,99}.md` + `36-why-v1-archived.md` | Archival rationale |
| `spec/25-app-issues/00-overview.md` + `01-phase-2-git-logs-audit/00-overview.md` + `02-consolidated-audit-findings/00-overview.md` | Audit anchors (12 links — these audits' subject IS the archived v1) |

Spot-checked 9 representative archive targets — all present on disk. Cross-link gate confirms all 18 resolve (`OK All internal spec cross-references resolve.`).

### 3. No live spec **inherits** archive files

No live module's §99 inventory, AC registry, or §00 module map lists archive files as constituent. Archive is referenced as **external context** (legacy v1 history, audit subject) only. This is the correct posture: archive ≠ active spec, but its history is preserved and addressable.

### 4. Spec-content references to `_archive` (30 files) are all intentional

Categories:
- §27 documentation describing the convention (`26-check-99-summary-freshness.md`, `27-check-99-stamp-bump.md`, `60-forbidden-strings-toml.md`, `62-spec-folder-refs-allowlist.md`, etc.) — describes filter behavior
- §22 `36-why-v1-archived.md` — the archival rationale itself
- §25 audit overviews — audit subject IS the archive
- `spec-folder-refs.allowlist` line 107 — archive entry

None require remediation.

## Conclusion

**Archive isolation is structurally sound.** All 8 spec-traversing linters exclude `_archive/` (3 verified at runtime, 5 by source); all 18 live links into the archive resolve; no live module inherits archive content. The Phase H3 standard pattern is fully realized in code.

## What did NOT change

- Read-only audit: no source code, no spec content, no §97/§98/§99/§00 files modified
- Trace-map baseline unchanged
- No CI workflow changes
- No new gates (audit confirms existing gates suffice)

## Lessons codified

1. **Runtime verification > source-reading verification** for "this filter excludes X" claims. H3 verified by reading source; H6 elevated 3 critical linters to runtime check by `importlib`-loading and calling enumerators on the live tree. New rule: when claiming a linter excludes a path, prefer a one-line runtime probe over a `grep` of the source.
2. **Audit-only phases skip §98/§99 banner bumps** (extends H1's "stamp-only edits don't bump banners" rule to read-only audits). This phase touches no spec content; only this memo + memory index.
3. **Archive references are first-class spec content when the archive IS the audit subject** — folder 25 (app-issues) holds 12 links into the archive precisely because its purpose is to audit v1. Don't treat archive references as "leakage" without checking the source's intent.

## Files touched

- `.lovable/memory/audit/v2-deterministic/phase-h6-archive-isolation-audit.md` (this memo)
- `.lovable/memory/index.md` (closed item #4 from H5 backlog)
