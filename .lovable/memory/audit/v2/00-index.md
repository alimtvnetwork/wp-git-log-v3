# Spec-vs-Code Audit **v2** — Summary

**Date:** 2026-04-25  
**Modules audited:** 1  
**Code files indexed:** 26  
**Mean weighted score:** **65.0/100**  
**Mean implementability:** **30.0/100**

## Methodology v2

Weights: implementability=35%, completeness=20%, alignment=15%, consistency=10%, clarity=10%, testability=7%, maintainability=3%.
Implementability = can a mediocre AI ship from spec alone, no human help.
Deterministic metrics (waffle ratio, contract presence, broken links, GWT count) are computed before AI scoring and bound the AI's grades.

## Grade distribution
**C** = 1

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
| 1 | [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 65 | C | 6 | No SQL DDL provided for the database schema described in 01-er-diagram.mmd. This |

## Bottom 15 (lowest implementability)
| Rank | Module | Overall | Impl | Grade | Top finding |
|---:|---|---:|---:|:-:|---|
| 1 | [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 65 | 30 | C | The ER diagram (01-er-diagram.mmd) describes a database schema but lacks the cor |

## Top 10 (gold standards)
| Rank | Module | Overall | Impl | Grade |
|---:|---|---:|---:|:-:|
| 1 | [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 65 | 30 | C |

## Full ranking
| Module | Impl | Comp | Align | Cons | Clar | Test | Maint | **Overall** | Grade | Blast |
|---|---:|---:|---:|---:|---:|---:|---:|---:|:-:|:-:|
| [`26-gitlogs-diagrams`](./26-gitlogs-diagrams.md) | 30 | 80 | 100 | 100 | 100 | 20 | 80 | **65** | C | 6 |