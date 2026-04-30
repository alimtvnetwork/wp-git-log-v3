# Spec AI-Implementability Audit (production)

**Generator:** `linter-scripts/audit-ai-implementability.py`  
**Modules scored:** 23  
**Overall:** **83.7 / 100** (GOOD)  
**Severity tally:** CRITICAL 5 · HIGH 20 · MEDIUM 26 · LOW 18

| Dimension | Avg |
|---|---:|
| D1 Contract Clarity | 18.1/20 |
| D2 AC Coverage | 17.8/20 |
| D3 Edge/Error | 15.9/20 |
| D4 Examples | 16.3/20 |
| D5 Cross-Ref Closure | 14.8/20 |

## Per-module ranking (low → high)

| Rank | Module | Axis | Total (v7) | Raw (v6) | D1 | D2 | D3 | D4 | D5 | Files | KB | Band |
|---:|---|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---|
| 1 | `spec/03-error-manage` | audit-corpus | **74** | 75 | 18 | 15 | 14 | 16 | 12 | 11/166 | 87 | NEEDS_WORK |
| 2 | `spec/04-database-conventions` | normative-contract | **74** | 76 | 18 | 14 | 12 | 17 | 15 | 7/11 | 87 | NEEDS_WORK |
| 3 | `spec/12-cicd-pipeline-workflows` | integration-spec | **75** | 75 | 18 | 14 | 15 | 16 | 12 | 14/49 | 87 | GOOD |
| 4 | `spec/14-update` | normative-contract | **76** | 75 | 18 | 16 | 14 | 12 | 15 | 8/54 | 87 | GOOD |
| 5 | `spec/27-spec-toolchain` | tooling-spec | **76** | 75 | 18 | 16 | 14 | 15 | 12 | 3/50 | 87 | GOOD |
| 6 | `spec/17-consolidated-guidelines` | process-guidance | **78** | 78 | 18 | 19 | 15 | 14 | 12 | 5/38 | 87 | GOOD |
| 7 | `spec/25-app-issues` | audit-corpus | **79** | 78 | 18 | 14 | 15 | 12 | 19 | 9/12 | 87 | GOOD |
| 8 | `spec/07-design-system` | process-guidance | **80** | 80 | 18 | 20 | 16 | 14 | 12 | 3/17 | 87 | GOOD |
| 9 | `spec/18-wp-plugin-how-to` | process-guidance | **80** | 78 | 18 | 12 | 15 | 19 | 14 | 11/35 | 87 | GOOD |
| 10 | `spec/26-gitlogs-diagrams` | audit-corpus | **80** | 83 | 18 | 20 | 15 | 16 | 14 | 9/9 | 61 | GOOD |
| 11 | `spec/11-powershell-integration` | integration-spec | **81** | 81 | 18 | 15 | 16 | 18 | 14 | 10/19 | 87 | GOOD |
| 12 | `spec/01-spec-authoring-guide` | process-guidance | **82** | 82 | 18 | 19 | 15 | 16 | 14 | 2/17 | 87 | GOOD |
| 13 | `spec/02-coding-guidelines` | normative-contract | **86** | 83 | 18 | 19 | 17 | 15 | 14 | 6/251 | 87 | GOOD |
| 14 | `spec/22-git-logs-v2` | normative-contract | **86** | 83 | 18 | 19 | 17 | 14 | 15 | 3/36 | 87 | GOOD |
| 15 | `spec/10-research` | audit-corpus | **87** | 88 | 18 | 19 | 17 | 18 | 16 | 8/8 | 35 | GOOD |
| 16 | `spec/28-universal-ci-cli` | normative-contract | **87** | 84 | 18 | 20 | 17 | 15 | 14 | 10/15 | 87 | GOOD |
| 17 | `spec/05-split-db-architecture` | normative-contract | **89** | 87 | 18 | 19 | 17 | 18 | 15 | 7/20 | 87 | GOOD |
| 18 | `spec/06-seedable-config-architecture` | normative-contract | **89** | 87 | 18 | 19 | 17 | 18 | 15 | 8/21 | 87 | GOOD |
| 19 | `spec/16-generic-release` | normative-contract | **90** | 89 | 18 | 20 | 17 | 15 | 19 | 11/13 | 87 | EXCELLENT |
| 20 | `spec/13-generic-cli` | normative-contract | **92** | 90 | 18 | 20 | 18 | 19 | 15 | 11/24 | 87 | EXCELLENT |
| 21 | `spec/15-distribution-and-runner` | normative-contract | **93** | 92 | 18 | 20 | 17 | 19 | 18 | 8/8 | 64 | EXCELLENT |
| 22 | `spec/24-app-design-system-and-ui` | normative-contract | **95** | 93 | 20 | 20 | 18 | 20 | 15 | 4/4 | 38 | EXCELLENT |
| 23 | `spec/23-app-database` | normative-contract | **97** | 97 | 19 | 20 | 18 | 20 | 20 | 4/4 | 60 | EXCELLENT |
