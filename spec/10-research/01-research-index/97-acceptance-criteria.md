# Acceptance Criteria — Top-Level Research Index

**Version:** 1.0.0  
**Updated:** 2026-04-27

---

### TOP-RES-01: New top-level study  `[high]`
- **Given** A research scope spans multiple domains and does not fit under any single guideline.
- **When** The change is reviewed in PR.
- **Then** A new child subfolder is created here with the standard 4-file layout.

### TOP-RES-02: Research → spec promotion  `[medium]`
- **Given** A research finding is adopted as a normative spec.
- **When** The change is reviewed in PR.
- **Then** The original research subfolder is preserved with a `promoted_to:` frontmatter pointer to the new spec module.
