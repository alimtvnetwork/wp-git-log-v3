# Audit — `spec/02-coding-guidelines/11-security`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **72/100 (C)**

> The spec is well-written but describes a sub-module (01-axios-version-control) that isn't in the inventory, and it ignores existing security tools in the codebase like the forbidden-strings scanner.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 75 | 18.8 |
| Consistency | 25% | 70 | 17.5 |
| Alignment | 20% | 60 | 12.0 |
| Clarity | 15% | 90 | 13.5 |
| Maintainability | 10% | 85 | 8.5 |
| Testability | 5% | 80 | 4.0 |

## Code Mapping

**Implemented by:** `linter-scripts/check-axios-version.sh`
**Expected but missing:** `spec/02-coding-guidelines/11-security/01-axios-version-control/00-overview.md`, `spec/02-coding-guidelines/11-security/01-axios-version-control/01-implementation-rules.md`, `spec/02-coding-guidelines/11-security/01-axios-version-control/02-security-notes.md`
**Orphan code candidates:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `linter-scripts/forbidden-strings.toml`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | high | 8/10 | The sub-module for Axios version control is missing from the provided file inventory despite being central to the ACs. |
| 2 | untestable | medium | 5/10 | ACs reference package.json which is not present in the codebase index. |
| 3 | missing-spec | medium | 7/10 | The spec ignores existing security infrastructure for forbidden string detection. |
| 4 | drift | low | 2/10 | Internal versioning inconsistency within the spec module. |

### Detail + Proposed Corrections

#### 1. [HIGH] The sub-module for Axios version control is missing from the provided file inventory despite being central to the ACs.
- **Category:** drift  |  **Impact:** 8/10
- **Evidence:** ACs verify 01-axios-version-control/00-overview.md which is missing from the file inventory.
- **Proposed correction:** Ensure subfolder naming matches the spec (the spec mentions 01-axios-version-control but the index doesn't show it).

#### 2. [MEDIUM] ACs reference package.json which is not present in the codebase index.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** AC-01: 'Given A project package.json file' - the codebase index contains no package.json.
- **Proposed correction:** Specify which files/manifests the script 'check-axios-version.sh' actually scans.

#### 3. [MEDIUM] The spec ignores existing security infrastructure for forbidden string detection.
- **Category:** missing-spec  |  **Impact:** 7/10
- **Evidence:** linter-scripts/forbidden-strings.toml and linter-scripts/check-forbidden-strings.py exist but are not mentioned in the spec.
- **Proposed correction:** Add requirements to the security spec regarding forbidden strings/patterns using the existing forbidden-strings.toml.

#### 4. [LOW] Internal versioning inconsistency within the spec module.
- **Category:** drift  |  **Impact:** 2/10
- **Evidence:** Overview Version: 3.2.0 vs Acceptance Criteria Version: 2.0.0.
- **Proposed correction:** Update Version to be consistent across the module (Overview says 3.2.0, AC says 2.0.0).
