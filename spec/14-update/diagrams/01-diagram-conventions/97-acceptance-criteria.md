# Acceptance Criteria — Update Diagram Conventions

**Version:** 1.0.0  
**Updated:** 2026-04-27

---

### UPD-DIA-01: Diagram authoring  `[high]`
- **Given** A new update workflow diagram is added.
- **When** The change is reviewed in PR.
- **Then** It MUST use `flowchart TD`, name nodes `[Verb Noun]`, and label edges with the trigger condition. Linter `linter-scripts/check-mermaid-style.py` (when present) MUST exit 0.

### UPD-DIA-02: Diagram refresh  `[medium]`
- **Given** An existing diagram is updated to reflect a workflow change.
- **When** The change is reviewed in PR.
- **Then** The corresponding `98-changelog.md` entry MUST link to the PR that motivated the diagram change.
