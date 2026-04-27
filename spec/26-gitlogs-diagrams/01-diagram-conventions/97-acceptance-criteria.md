# Acceptance Criteria — Git Logs Diagram Conventions

**Version:** 1.0.0  
**Updated:** 2026-04-27

---

### GLD-01: Diagram + SVG pairing  `[high]`
- **Given** A new diagram is added to the parent folder.
- **When** The change is reviewed in PR.
- **Then** Both `<name>.mmd` (source) and `<name>.svg` (rendered) MUST be committed in the same PR. CI MUST diff-check that the SVG matches a fresh render of the MMD.

### GLD-02: Diagram retirement  `[medium]`
- **Given** A diagram is no longer accurate.
- **When** The change is reviewed in PR.
- **Then** Both the `.mmd` and the `.svg` MUST be removed in the same commit; an entry in the parent `98-changelog.md` MUST justify the removal.
