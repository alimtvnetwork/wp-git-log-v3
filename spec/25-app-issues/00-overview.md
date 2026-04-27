---
kind: index
description: Top-level routing index for app issue analysis (parent of two child trackers). Exempt from missing-contract / untestable rubric findings — child trackers carry their own kind:tracker exemption.
---

# App Issues

**Version:** 3.3.0  
**Updated:** 2026-04-26
**AI Confidence:** Production-Ready  
**Ambiguity:** None

---

## Overview

App-specific issue analysis, root cause analysis, bug documentation, and solution guidance at the root spec level. This folder tracks problems encountered during application development, their diagnosis, and their resolution.

---

## Placement Rule

Any content that analyzes bugs, failures, root causes, or fixes for application-level work belongs here. General coding principle violations or cross-cutting concerns belong in the core fundamentals range (`01–20`).

---

## Contents

| # | Folder | Description |
|---|--------|-------------|
| 01 | [01-phase-2-git-logs-audit/](./01-phase-2-git-logs-audit/00-overview.md) | Phase-2 spec-only audit of `spec/_archive/21-git-logs-v1/` (25 findings) |
| 02 | [02-consolidated-audit-findings/](./02-consolidated-audit-findings/00-overview.md) | Single auditable findings document — 24 numbered items with file paths and evidence snippets |

---

## Cross-References

| Reference | Location |
|-----------|----------|
| App Specs (legacy v1 git-logs) | [../_archive/21-git-logs-v1/00-overview.md](../_archive/21-git-logs-v1/00-overview.md) |
| Spec Authoring Guide | [../01-spec-authoring-guide/00-overview.md](../01-spec-authoring-guide/00-overview.md) |

---

## Verification

_Auto-generated section — see `spec/25-app-issues/97-acceptance-criteria.md` for the full criteria index._

### AC-AI-000: App issues triage conformance: Overview

**Given** Audit issue write-ups for the required Reproduction / Cause / Fix / Prevention sections.  
**When** Run the verification command shown below.  
**Then** Every issue file contains all four sections and references at least one commit or PR.

**Verification command:**

```bash
python3 linter-scripts/check-spec-cross-links.py --root spec --repo-root .
```

**Expected:** exit 0. Any non-zero exit is a hard fail and blocks merge.

_Verification section last updated: 2026-04-21_

---

## Drift Acknowledgment

**Date:** 2026-04-26  
**Status:** Forward-looking spec — drift expected.

AC-AI-000 issue-file format is forward-looking; concrete issue files will be authored as work progresses.

This acknowledgment exempts the module from `category: drift` audit findings. See `.lovable/memory/index.md` Phase 27c note.

