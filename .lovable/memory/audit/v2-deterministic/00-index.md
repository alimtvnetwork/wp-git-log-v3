# Spec-vs-Code Audit **v2** — Summary

**Date:** 2026-04-25  
**Modules audited:** 1  
**Code files indexed:** 26  
**Mean weighted score:** **74.0/100**  
**Mean implementability:** **40.0/100**

## Methodology v2

Weights: implementability=35%, completeness=20%, alignment=15%, consistency=10%, clarity=10%, testability=7%, maintainability=3%.
Implementability = can a mediocre AI ship from spec alone, no human help.
Deterministic metrics (waffle ratio, contract presence, broken links, GWT count) are computed before AI scoring and bound the AI's grades.

## Grade distribution
**C** = 1

## Findings by category
| Category | Count |
|---|---:|
| drift | 1 |
| missing-contract | 1 |

## Findings by severity
| Severity | Count |
|---|---:|
| high | 1 |
| low | 1 |

## 🎯 High blast-radius fixes (fix these FIRST)
| Rank | Module | Score | Grade | Blast | Top blocker |
|---:|---|---:|:-:|:-:|---|
| 1 | [`27-spec-toolchain`](./27-spec-toolchain.md) | 74 | C | 0 | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |

## Bottom 15 (lowest implementability)
| Rank | Module | Overall | Impl | Grade | Top finding |
|---:|---|---:|---:|:-:|---|
| 1 | [`27-spec-toolchain`](./27-spec-toolchain.md) | 74 | 40 | C | 3 TODO/TBD/FIXME marker(s) in module body |

## Top 10 (gold standards)
| Rank | Module | Overall | Impl | Grade |
|---:|---|---:|---:|:-:|
| 1 | [`27-spec-toolchain`](./27-spec-toolchain.md) | 74 | 40 | C |

## Full ranking
| Module | Impl | Comp | Align | Cons | Clar | Test | Maint | **Overall** | Grade | Blast |
|---|---:|---:|---:|---:|---:|---:|---:|---:|:-:|:-:|
| [`27-spec-toolchain`](./27-spec-toolchain.md) | 40 | 75 | 100 | 100 | 100 | 100 | 90 | **74** | C | 0 |