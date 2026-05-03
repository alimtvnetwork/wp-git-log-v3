# Spec AI-Implementability Audit (production)

**Generator:** `linter-scripts/audit-ai-implementability.py`  
**Modules scored:** 23  
**Overall:** **89.9 / 100** (GOOD)  
**Severity tally:** CRITICAL 0 · HIGH 14 · MEDIUM 23 · LOW 32

| Dimension | Avg |
|---|---:|
| D1 Contract Clarity | 18.4/20 |
| D2 AC Coverage | 18.7/20 |
| D3 Edge/Error | 16.9/20 |
| D4 Examples | 18.0/20 |
| D5 Cross-Ref Closure | 17.2/20 |

## Per-module ranking (low → high)

| Rank | Module | Axis | Total (v7) | Raw (v6) | D1 | D2 | D3 | D4 | D5 | Files | KB | Band |
|---:|---|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---|
| 1 | `spec/12-cicd-pipeline-workflows` | integration-spec | **76** | 76 | 18 | 14 | 15 | 17 | 12 | 17/49 | 136 | GOOD |
| 2 | `spec/03-error-manage` | normative-contract | **81** | 81 | 18 | 16 | 14 | 18 | 15 | 20/166 | 136 | GOOD |
| 3 | `spec/05-split-db-architecture` | normative-contract | **82** | 80 | 16 | 18 | 17 | 15 | 14 | 9/20 | 136 | GOOD |
| 4 | `spec/11-powershell-integration` | integration-spec | **86** | 85 | 18 | 16 | 15 | 19 | 17 | 19/19 | 125 | GOOD |
| 5 | `spec/18-wp-plugin-how-to` | process-guidance | **86** | 85 | 18 | 16 | 17 | 15 | 19 | 16/35 | 136 | GOOD |
| 6 | `spec/22-git-logs-v2` | normative-contract | **87** | 84 | 18 | 20 | 17 | 15 | 14 | 5/38 | 136 | GOOD |
| 7 | `spec/06-seedable-config-architecture` | normative-contract | **88** | 88 | 19 | 18 | 16 | 18 | 17 | 10/21 | 136 | GOOD |
| 8 | `spec/17-consolidated-guidelines` | process-guidance | **88** | 88 | 18 | 19 | 17 | 19 | 15 | 6/39 | 136 | GOOD |
| 9 | `spec/27-spec-toolchain` | tooling-spec | **88** | 89 | 18 | 19 | 17 | 15 | 20 | 17/57 | 136 | GOOD |
| 10 | `spec/01-spec-authoring-guide` | process-guidance | **89** | 89 | 18 | 19 | 15 | 17 | 20 | 4/18 | 136 | GOOD |
| 11 | `spec/04-database-conventions` | normative-contract | **89** | 88 | 19 | 18 | 17 | 18 | 16 | 9/11 | 136 | GOOD |
| 12 | `spec/14-update` | normative-contract | **91** | 89 | 18 | 20 | 17 | 19 | 15 | 13/54 | 136 | EXCELLENT |
| 13 | `spec/26-gitlogs-diagrams` | normative-contract | **91** | 89 | 18 | 20 | 17 | 19 | 15 | 9/9 | 71 | EXCELLENT |
| 14 | `spec/02-coding-guidelines` | normative-contract | **92** | 92 | 18 | 19 | 17 | 20 | 18 | 9/251 | 136 | EXCELLENT |
| 15 | `spec/10-research` | audit-corpus | **93** | 92 | 18 | 19 | 17 | 18 | 20 | 8/8 | 41 | EXCELLENT |
| 16 | `spec/13-generic-cli` | normative-contract | **93** | 92 | 18 | 20 | 19 | 17 | 18 | 18/24 | 136 | EXCELLENT |
| 17 | `spec/15-distribution-and-runner` | normative-contract | **93** | 92 | 18 | 20 | 17 | 19 | 18 | 8/8 | 64 | EXCELLENT |
| 18 | `spec/25-app-issues` | audit-corpus | **93** | 92 | 18 | 19 | 17 | 20 | 18 | 11/12 | 136 | EXCELLENT |
| 19 | `spec/07-design-system` | process-guidance | **95** 🔒 | 96 | 20 | 20 | 18 | 18 | 20 | 7/18 | 136 | EXCELLENT |
| 20 | `spec/24-app-design-system-and-ui` | normative-contract | **95** | 93 | 20 | 20 | 18 | 20 | 15 | 4/4 | 38 | EXCELLENT |
| 21 | `spec/23-app-database` | normative-contract | **97** | 97 | 19 | 20 | 18 | 20 | 20 | 4/4 | 60 | EXCELLENT |
| 22 | `spec/28-universal-ci-cli` | normative-contract | **97** | 97 | 20 | 20 | 18 | 19 | 20 | 15/15 | 117 | EXCELLENT |
| 23 | `spec/16-generic-release` | normative-contract | **98** | 98 | 20 | 20 | 18 | 20 | 20 | 13/13 | 119 | EXCELLENT |
