---
kind: index
description: Top-level routing index for app issue analysis (parent of two child trackers). Exempt from missing-contract / untestable rubric findings — child trackers carry their own kind:tracker exemption.
---

# App Issues

**Version:** 3.4.1  
<!-- h10-verified-phase: 30 -->
**Updated:** 2026-04-28 (Phase P11 — nested-subdir audit; symmetric supersession routing surfaced)
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

| # | Folder | Status | Description |
|---|--------|--------|-------------|
| 01 | [01-phase-2-git-logs-audit/](./01-phase-2-git-logs-audit/00-overview.md) | **superseded** by 02 (preserved for traceability) | Phase-2 spec-only audit of `spec/_archive/21-git-logs-v1/` — first pass; contained false-positives later corrected by the consolidated tracker. |
| 02 | [02-consolidated-audit-findings/](./02-consolidated-audit-findings/00-overview.md) | **active** (start here) | Single source of truth — 24 numbered findings with file paths + verbatim evidence snippets. Re-scored every Phase-2 item against line-anchored evidence. |

> **Reading order (Phase P11):** Start at `02-consolidated-audit-findings/` for the current state. `01-phase-2-git-logs-audit/` is preserved for traceability only — it predates the line-anchored evidence pass and reports two files as "missing" that actually exist. The supersession is symmetric: declared in 02's "Correction notice", in 01's banner, and in this routing table.

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

### CI Workflow — Phase 74 Reference

The following workflow snippets are normative for this module. Each fenced
`yaml` block is a stage that MUST be present in the consuming repository's
CI pipeline.

```yaml
name: spec-gate-stage-1-detect
on: [push, pull_request]
jobs:
  detect:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - run: linter-scripts/detect-changed-modules.sh
```

```yaml
name: spec-gate-stage-2-validate
on: [push, pull_request]
jobs:
  validate:
    runs-on: ubuntu-latest
    needs: [detect]
    steps:
      - uses: actions/checkout@v4
      - run: linter-scripts/validate-contracts.py
```

```yaml
name: spec-gate-stage-3-lint
on: [push, pull_request]
jobs:
  lint:
    runs-on: ubuntu-latest
    needs: [validate]
    steps:
      - uses: actions/checkout@v4
      - run: linter-scripts/audit-spec-vs-code-v2.py --strict
```

```yaml
name: spec-gate-stage-4-promote
on:
  push:
    branches: [main]
jobs:
  promote:
    runs-on: ubuntu-latest
    needs: [lint]
    steps:
      - uses: actions/checkout@v4
      - run: linter-scripts/promote-artifact.sh
```

```yaml
name: spec-gate-stage-5-report
on:
  workflow_run:
    workflows: ["spec-gate-stage-4-promote"]
    types: [completed]
jobs:
  report:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - run: linter-scripts/update-consistency-report.py
```

See [`lifecycle-25-app-issues-lifecycle.mmd`](./lifecycle-25-app-issues-lifecycle.mmd) for the visual lifecycle.

### Index Entry Status Enum — Phase 80 Normative

```ts
export enum IndexEntryStatus {
  Draft = "draft",
  Active = "active",
  Deprecated = "deprecated",
  Archived = "archived",
}

export interface IndexEntry {
  slug: string;
  title: string;
  status: IndexEntryStatus;
  routedTo: string | null;
}
```
