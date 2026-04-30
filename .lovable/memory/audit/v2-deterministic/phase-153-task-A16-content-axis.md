# Phase 153 Task A16 — `content_axis` Front-Matter Metadata

**Status:** CLOSED 2026-04-30
**Predecessor:** A15 (Rubric v7 design memo) — `mem://memory/audit/v2-deterministic/phase-153-task-A15-rubric-v7-design-memo.md`
**Successors:** A17 (rubric weights), A18 (per-axis dimension caps), A19 (auditor wiring), A20 (re-baseline)
**Lesson refs:** #11 (walker-tier), #29 (audit-corpus), #34 (cache freshness), #36 (cross-module link not restate)

---

## 1. Goal

Codify a 5-value `content_axis` enum in every top-level module's `00-overview.md` front-matter so Rubric v7 can apply axis-appropriate dimension weights instead of the current one-size-fits-all D1–D5 rubric. This closes the structural 75-point ceiling on modules `03`, `12`, `17`, `25` (per Phase 153 Task A8 v5 baseline) where current rubric penalizes legitimate non-contract content as "missing AC coverage."

## 2. Taxonomy

| `content_axis` | Definition | Members | Rubric v7 weight skew |
|---|---|---|---|
| `normative-contract` | Module declares enforceable rules/ACs that downstream code MUST satisfy. §97 is the primary surface. | 02, 04, 05, 06, 13, 14, 15, 16, 22, 23, 24, 28 | D2 (AC coverage) ×1.5; D3 (edge cases) ×1.2 |
| `process-guidance` | Module declares workflows/conventions for human authors. ACs are advisory checklists, not machine-checkable contracts. | 01, 07, 17, 18 | D1 (clarity) ×1.5; D2 ×0.7 |
| `integration-spec` | Module describes how this codebase connects to external systems. Surface includes example payloads + handshake protocols. | 11, 12 | D4 (examples) ×1.4; D5 (cross-refs) ×1.2 |
| `audit-corpus` | Module is *describing* other specs (post-mortems, issue trackers, deprecation registries). Bug-quotes are evidence, not contract. (Lesson #29) | 03, 25, 26, 10 | D2 ×0.5; D3 ×0.5; D5 ×1.5 (citation density matters) |
| `tooling-spec` | Module specs the spec's own toolchain/scripts. ACs are per-script behavioural contracts. | 27 | D2 ×1.3; D4 ×1.3 (script examples) |

Total: **23 top-level modules** assigned, all 5 axis values populated.

## 3. Front-matter schema (additive)

```yaml
---
kind: <existing>            # unchanged
content_axis: <one-of-5>    # NEW (A16)
axis_rationale: "<one-sentence why this axis was chosen>"  # NEW (A16)
---
```

For modules currently lacking any front-matter block (04, 06, 07, 13, 14, 17, 18), a minimal block is inserted. `kind:` is preserved where present; otherwise omitted (Rubric v7 derives kind from `content_axis` if missing).

## 4. Per-module assignments

| Module | `kind` (existing) | `content_axis` (new) | Rationale |
|---|---|---|---|
| 01-spec-authoring-guide | future-spec | process-guidance | Authoring conventions for human spec contributors |
| 02-coding-guidelines | future-spec | normative-contract | Per-language enforceable code rules across 5 languages |
| 03-error-manage | future-spec | audit-corpus | Catalogues error patterns observed across modules |
| 04-database-conventions | (none) | normative-contract | Schema/concurrency/boolean rules MUST be satisfied |
| 05-split-db-architecture | future-spec | normative-contract | Architecture invariants enforceable via runtime checks |
| 06-seedable-config-architecture | (none) | normative-contract | Config-loading contract (CW Config) |
| 07-design-system | (none) | process-guidance | Token/component conventions for designers |
| 10-research | index | audit-corpus | Routing-only; child specs document explorations |
| 11-powershell-integration | future-spec | integration-spec | Bridges CLI to external PowerShell pipeline |
| 12-cicd-pipeline-workflows | future-spec | integration-spec | Bridges repos to GitHub Actions / external CI |
| 13-generic-cli | (none) | normative-contract | CLI behavioural ACs (subcommand, exit codes, DB) |
| 14-update | (none) | normative-contract | Update-flow ACs (TUF, retries, atomic swap) |
| 15-distribution-and-runner | future-spec | normative-contract | Distribution + sandboxing invariants |
| 16-generic-release | future-spec | normative-contract | Release-process ACs (SemVer, signing, manifest) |
| 17-consolidated-guidelines | (none) | process-guidance | Cross-module process consolidation for authors |
| 18-wp-plugin-how-to | (none) | process-guidance | Step-by-step WP plugin authoring guide |
| 22-git-logs-v2 | future-spec | normative-contract | Git Logs plugin enforceable spec |
| 23-app-database | module | normative-contract | App tables + AppLink polymorphic resolution rules |
| 24-app-design-system-and-ui | module | normative-contract | App-only token extensions (additive contract) |
| 25-app-issues | index | audit-corpus | Routing parent of `kind:tracker` post-mortems (Lesson #29) |
| 26-gitlogs-diagrams | (none) | audit-corpus | Diagrams describing 22-git-logs-v2 architecture |
| 27-spec-toolchain | (none) | tooling-spec | Specs the linter-scripts/ contract |
| 28-universal-ci-cli | (none) | normative-contract | Universal CI binary ACs |

## 5. Lockstep impact

- §27 §00/§98 + §99: patch-bump (new front-matter convention codified)
- Each of the 23 modules: §00-overview.md only edited (front-matter), so per-module §00 patch-bump + §98 row + §99 banner
- Total: **23 modules × 3 files = 69 edits** + §27 cluster (3 files) = **72 file edits**
- New AC: deferred to A17 (Rubric v7 weight contract is the natural home for `content_axis` validation AC)

## 6. Out of scope (deferred to A17–A20)

- A17: define dimension weight cascades per axis (Section 2 Rubric v7 column)
- A18: per-axis dimension caps (e.g. audit-corpus capped at 95 to acknowledge inherent ceiling)
- A19: wire `content_axis` into `audit-ai-implementability.py` bundle context + prompt
- A20: re-baseline tree with v7 rubric; expected lift on 03/12/17/25 from 75 → 85+

## 7. Verification

- `grep -c "^content_axis:" spec/*/00-overview.md` → 23
- `grep -h "^content_axis:" spec/*/00-overview.md | sort -u | wc -l` → 5 (all axes used)
- Lockstep gate: 87/87 GREEN expected
- Tree-health: 168/168 strict expected

## 8. Lessons applied / new

- **Applied #29** — `audit-corpus` axis exists *because* of the Lesson #29 spec/25 precedent; Rubric v7 will give these modules appropriate dimension weights instead of penalising them as "missing contract."
- **Applied #36** — `axis_rationale` is one-sentence inline; does NOT restate Rubric v7 weight rules (those live in A17 §97 ACs).
- **NEW #41** (codify at A20 close): metadata foundations should ship as a separate phase BEFORE the rubric that consumes them — splitting A16 from A17 lets the foundation stabilise (and catch axis-mis-assignment via human review) before weight cascades amplify any errors.
