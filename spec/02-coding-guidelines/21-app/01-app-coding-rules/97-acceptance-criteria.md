# Acceptance Criteria — App-Specific Coding Rules

**Version:** 1.0.0  
**Updated:** 2026-04-27

---

### APP-COD-01: App-only override  `[high]`
- **Given** A coding rule diverges between App and CLI layers.
- **When** The change is reviewed in PR.
- **Then** The override is documented in this subfolder with rationale, scope, and a reference back to the master guideline being overridden.

### APP-COD-02: Override removal  `[medium]`
- **Given** An App-only override is reconciled with the master guideline.
- **When** The change is reviewed in PR.
- **Then** The subfolder entry is marked `superseded_by:` in frontmatter and the master guideline is updated.
