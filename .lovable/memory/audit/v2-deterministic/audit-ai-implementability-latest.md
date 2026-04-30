# Spec AI-Implementability Audit (production)

**Generator:** `linter-scripts/audit-ai-implementability.py`  
**Modules scored:** 23  
**Overall:** **87.7 / 100** (GOOD)  
**Severity tally:** CRITICAL 4 · HIGH 18 · MEDIUM 24 · LOW 23

| Dimension | Avg |
|---|---:|
| D1 Contract Clarity | 18.4/20 |
| D2 AC Coverage | 18.0/20 |
| D3 Edge/Error | 16.6/20 |
| D4 Examples | 17.5/20 |
| D5 Cross-Ref Closure | 16.1/20 |

## Per-module ranking (low → high)

| Rank | Module | Axis | Total (v7) | Raw (v6) | D1 | D2 | D3 | D4 | D5 | Files | KB | Band |
|---:|---|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---|
| 1 | `spec/25-app-issues` | audit-corpus | **79** | 78 | 18 | 14 | 15 | 12 | 19 | 9/12 | 117 | GOOD |
| 2 | `spec/17-consolidated-guidelines` | process-guidance | **80** | 78 | 18 | 14 | 15 | 12 | 19 | 6/39 | 117 | GOOD |
| 3 | `spec/27-spec-toolchain` | tooling-spec | **80** | 80 | 18 | 14 | 19 | 17 | 12 | 3/50 | 117 | GOOD |
| 4 | `spec/02-coding-guidelines` | normative-contract | **82** | 82 | 18 | 16 | 15 | 19 | 14 | 10/251 | 117 | GOOD |
| 5 | `spec/01-spec-authoring-guide` | process-guidance | **83** | 83 | 18 | 19 | 15 | 17 | 14 | 3/17 | 117 | GOOD |
| 6 | `spec/22-git-logs-v2` | normative-contract | **83** | 79 | 18 | 19 | 16 | 14 | 12 | 3/36 | 117 | GOOD |
| 7 | `spec/03-error-manage` | audit-corpus | **84** | 81 | 18 | 14 | 15 | 18 | 16 | 17/166 | 117 | GOOD |
| 8 | `spec/12-cicd-pipeline-workflows` | integration-spec | **84** | 83 | 18 | 14 | 15 | 17 | 19 | 15/49 | 117 | GOOD |
| 9 | `spec/06-seedable-config-architecture` | normative-contract | **87** | 85 | 18 | 19 | 16 | 17 | 15 | 9/21 | 117 | GOOD |
| 10 | `spec/11-powershell-integration` | integration-spec | **87** | 87 | 19 | 18 | 17 | 18 | 15 | 18/19 | 117 | GOOD |
| 11 | `spec/14-update` | normative-contract | **87** | 84 | 18 | 20 | 17 | 15 | 14 | 11/54 | 117 | GOOD |
| 12 | `spec/18-wp-plugin-how-to` | process-guidance | **88** | 88 | 18 | 19 | 17 | 18 | 16 | 15/35 | 117 | GOOD |
| 13 | `spec/26-gitlogs-diagrams` | audit-corpus | **88** | 89 | 18 | 20 | 17 | 19 | 15 | 9/9 | 67 | GOOD |
| 14 | `spec/04-database-conventions` | normative-contract | **89** | 90 | 19 | 18 | 15 | 20 | 18 | 9/11 | 117 | GOOD |
| 15 | `spec/05-split-db-architecture` | normative-contract | **89** | 87 | 18 | 19 | 17 | 18 | 15 | 7/20 | 117 | GOOD |
| 16 | `spec/13-generic-cli` | normative-contract | **89** | 87 | 18 | 19 | 17 | 18 | 15 | 17/24 | 117 | GOOD |
| 17 | `spec/16-generic-release` | normative-contract | **91** | 89 | 18 | 20 | 17 | 19 | 15 | 13/13 | 117 | EXCELLENT |
| 18 | `spec/07-design-system` | process-guidance | **92** | 92 | 20 | 20 | 18 | 19 | 15 | 5/17 | 117 | EXCELLENT |
| 19 | `spec/10-research` | audit-corpus | **93** | 92 | 18 | 19 | 17 | 18 | 20 | 8/8 | 41 | EXCELLENT |
| 20 | `spec/15-distribution-and-runner` | normative-contract | **93** | 92 | 18 | 20 | 17 | 19 | 18 | 8/8 | 64 | EXCELLENT |
| 21 | `spec/24-app-design-system-and-ui` | normative-contract | **95** | 93 | 20 | 20 | 18 | 20 | 15 | 4/4 | 38 | EXCELLENT |
| 22 | `spec/23-app-database` | normative-contract | **97** | 97 | 19 | 20 | 18 | 20 | 20 | 4/4 | 60 | EXCELLENT |
| 23 | `spec/28-universal-ci-cli` | normative-contract | **97** | 97 | 20 | 20 | 18 | 19 | 20 | 15/15 | 117 | EXCELLENT |
