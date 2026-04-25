# Audit v2 — `spec/24-app-design-system-and-ui`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **54/100 (D)**  
**Blast radius:** 5/10

> This module provides a good overview and clear acceptance criteria for a design system. However, its implementability is severely hampered by the lack of inlined contracts and concrete definitions for key concepts like 'standard containers' and 'app-specific overrides.'


**Score justification:** The implementability is low because the spec relies on an external design system without inlining relevant contracts. Completeness is impacted by undefined conventions.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 1818,
  "ac_chars": 2823,
  "ac_count": 6,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 1,
  "code_blocks_by_lang": {
    "bash": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 9,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- Lack of inlined DDL/schemas for the design system (Core)
- Undefined 'standard containers' for layout conformance
- Absence of concrete examples or specifications for 'app-specific overrides'

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 7/10 | The spec references a 'Design System (Core)' without inlining any of its contracts or components. |
| 2 | missing-contract | medium | 5/10 | The 'standard layout conformance' AC mentions 'standard containers' but does not define what those containers are. |
| 3 | missing-contract | medium | 4/10 | The spec alludes to 'app-specific overrides' without providing concrete examples or constraints. |
| 4 | missing-contract | low | 2/10 | The 'Purpose' section mentions 'theming decisions' but these decisions are not detailed within the spec. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec references a 'Design System (Core)' without inlining any of its contracts or components.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** Cross-References: [Design System (Core)]
- **Proposed correction:** Inline critical contracts (e.g., component definitions, semantic tokens) from the core design system directly into this spec.

#### 2. [MEDIUM] The 'standard layout conformance' AC mentions 'standard containers' but does not define what those containers are.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** AC-ADS-03: Standard Layout Conformance: 'The layout must adhere to the \'layout conventions\' mentioned in the Purpose section, specifically ensuring standard containers are used.'
- **Proposed correction:** Define or provide examples of the 'standard containers' that should be used for layout conformance.

#### 3. [MEDIUM] The spec alludes to 'app-specific overrides' without providing concrete examples or constraints.
- **Category:** missing-contract  |  **Impact:** 4/10
- **Evidence:** AC-ADS-05: Core vs App Design System Hierarchy: 'The component must inherit styles from the Cross-Referenced \'Design System (Core)\' while applying \'app-specific\' overrides.'
- **Proposed correction:** Provide examples or a clear specification of what constitutes 'app-specific overrides' and where they should be applied.

#### 4. [LOW] The 'Purpose' section mentions 'theming decisions' but these decisions are not detailed within the spec.
- **Category:** missing-contract  |  **Impact:** 2/10
- **Evidence:** Purpose: 'Covers component patterns, theming decisions, layout conventions, and visual standards specific to this application.'
- **Proposed correction:** Add a dedicated section detailing specific theming decisions for the application (e.g., color palette, typography scales).
