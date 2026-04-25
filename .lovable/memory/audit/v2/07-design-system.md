# Audit v2 — `spec/07-design-system`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **65/100 (C)**  
**Blast radius:** 8/10

> This module provides a very good conceptual overview of a design system. However, its implementability is severely hampered by the lack of direct, executable code and structured, testable acceptance criteria, making it challenging for an AI to independently build the system.


**Score justification:** The ac_count is 0, which severely limits the testability score to 20. The implementability is capped due to the absence of specific code, even though the JSON schemas and TS enums are present.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 70 | 24.5 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 80 | 2.4 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 17,
  "overview_chars": 7892,
  "ac_chars": 3783,
  "ac_count": 0,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 77,
  "code_blocks_by_lang": {
    "plain": 18,
    "css": 50,
    "bash": 1,
    "html": 5,
    "typescript": 2,
    "json": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 58,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.19,
  "child_modules": 0
}
```

## Implementability Blockers

- No concrete code snippets for CSS definitions, only descriptions.
- Interaction logic for components (e.g., button animations, sidebar toggles) is described conceptually but not provided in executable forms (e.g., TypeScript or JavaScript functions).

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `src/components/design-system-components.tsx`, `src/css/design-system.css`, `src/js/design-system-interactions.ts`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | untestable | high | 7/10 | Absence of structured acceptance criteria (GWT blocks) makes objective testing difficult. |
| 2 | missing-contract | medium | 6/10 | The spec describes a design system but lacks concrete, executable code examples (e.g., CSS for tokens, TypeScript for component logic) within the spec itself. |
| 3 | missing-spec | medium | 5/10 | While JSON schema and TS enums are mentioned, the spec does not provide the full definitions inline for all design system elements. |

### Detail + Proposed Corrections

#### 1. [HIGH] Absence of structured acceptance criteria (GWT blocks) makes objective testing difficult.
- **Category:** untestable  |  **Impact:** 7/10
- **Evidence:** ac_count == 0; gwt_block_count == 0
- **Proposed correction:** Convert acceptance criteria into GWT (Given/When/Then) format for objective testability.

#### 2. [MEDIUM] The spec describes a design system but lacks concrete, executable code examples (e.g., CSS for tokens, TypeScript for component logic) within the spec itself.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** code_blocks_by_lang shows descriptive markdown, but no functional CSS/JS code examples that can be directly implemented.
- **Proposed correction:** Embed concrete CSS variable definitions and functional JavaScript/TypeScript snippets for interactive components directly into the relevant spec files.

#### 3. [MEDIUM] While JSON schema and TS enums are mentioned, the spec does not provide the full definitions inline for all design system elements.
- **Category:** missing-spec  |  **Impact:** 5/10
- **Evidence:** has_json_schema=true and has_ts_enums=true, but the overall implementability rating suggests that these are not comprehensively included or easily consumable for direct implementation.
- **Proposed correction:** Inline all relevant JSON schemas and TypeScript enum definitions within the spec where they are referenced, ensuring they are complete and directly usable.
