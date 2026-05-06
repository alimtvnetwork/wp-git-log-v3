# Spec AI-Implementability Audit (production)

**Generator:** `linter-scripts/audit-ai-implementability.py`  
**Modules scored:** 9  
**Overall:** **91.3 / 100** (EXCELLENT)  
**Severity tally:** CRITICAL 0 · HIGH 0 · MEDIUM 1 · LOW 2

| Dimension | Avg |
|---|---:|
| D1 Contract Clarity | 18.4/20 |
| D2 AC Coverage | 18.9/20 |
| D3 Edge/Error | 17.4/20 |
| D4 Examples | 18.3/20 |
| D5 Cross-Ref Closure | 18.0/20 |

## Per-module ranking (low → high)

| Rank | Module | Axis | Total (v7) | Raw (v6) | D1 | D2 | D3 | D4 | D5 | Files | KB | Band |
|---:|---|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---|
| 1 | `spec/03-error-manage` | normative-contract | **87** | 88 | 18 | 17 | 17 | 18 | 18 | 17/166 | 136 | GOOD |
| 2 | `spec/05-split-db-architecture` | normative-contract | **88** | 86 | 17 | 19 | 17 | 18 | 15 | 14/20 | 136 | GOOD |
| 3 | `spec/11-powershell-integration` | integration-spec | **89** | 90 | 19 | 18 | 18 | 16 | 19 | 18/19 | 136 | GOOD |
| 4 | `spec/04-database-conventions` | normative-contract | **91** | 91 | 18 | 19 | 17 | 20 | 17 | 8/11 | 136 | EXCELLENT |
| 5 | `spec/02-coding-guidelines` | normative-contract | **93** | 93 | 19 | 19 | 17 | 20 | 18 | 9/251 | 136 | EXCELLENT |
| 6 | `spec/06-seedable-config-architecture` | normative-contract | **93** | 92 | 19 | 19 | 19 | 18 | 17 | 16/21 | 136 | EXCELLENT |
| 7 | `spec/10-research` | audit-corpus | **93** | 92 | 18 | 19 | 17 | 18 | 20 | 8/8 | 41 | EXCELLENT |
| 8 | `spec/01-spec-authoring-guide` | process-guidance | **94** | 94 | 19 | 20 | 17 | 18 | 20 | 4/18 | 136 | EXCELLENT |
| 9 | `spec/07-design-system` | process-guidance | **94** | 94 | 19 | 20 | 18 | 19 | 18 | 7/18 | 136 | EXCELLENT |
