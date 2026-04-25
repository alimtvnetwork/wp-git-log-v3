# Spec-vs-Code Audit **v2** — Summary

**Date:** 2026-04-25  
**Modules audited:** 1  
**Code files indexed:** 26  
**Mean weighted score:** **58.0/100**  
**Mean implementability:** **45.0/100**

## Methodology v2

Weights: implementability=35%, completeness=20%, alignment=15%, consistency=10%, clarity=10%, testability=7%, maintainability=3%.
Implementability = can a mediocre AI ship from spec alone, no human help.
Deterministic metrics (waffle ratio, contract presence, broken links, GWT count) are computed before AI scoring and bound the AI's grades.

## Grade distribution
**D** = 1

## Findings by category
| Category | Count |
|---|---:|
| missing-contract | 2 |
| untestable | 1 |

## Findings by severity
| Severity | Count |
|---|---:|
| high | 2 |
| medium | 1 |

## 🎯 High blast-radius fixes (fix these FIRST)
| Rank | Module | Score | Grade | Blast | Top blocker |
|---:|---|---:|:-:|:-:|---|
| 1 | [`22-git-logs-v2`](./22-git-logs-v2.md) | 58 | D | 7 | DDL for database schema is not inlined as code, but as markdown. |

## Bottom 15 (lowest implementability)
| Rank | Module | Overall | Impl | Grade | Top finding |
|---:|---|---:|---:|:-:|---|
| 1 | [`22-git-logs-v2`](./22-git-logs-v2.md) | 58 | 45 | D | Database DDL and JSON schemas are described in markdown but not provided as inli |

## Top 10 (gold standards)
| Rank | Module | Overall | Impl | Grade |
|---:|---|---:|---:|:-:|
| 1 | [`22-git-logs-v2`](./22-git-logs-v2.md) | 58 | 45 | D |

## Full ranking
| Module | Impl | Comp | Align | Cons | Clar | Test | Maint | **Overall** | Grade | Blast |
|---|---:|---:|---:|---:|---:|---:|---:|---:|:-:|:-:|
| [`22-git-logs-v2`](./22-git-logs-v2.md) | 45 | 90 | 0 | 100 | 100 | 20 | 100 | **58** | D | 7 |