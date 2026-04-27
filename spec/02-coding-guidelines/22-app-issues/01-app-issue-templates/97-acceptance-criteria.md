# Acceptance Criteria — App Issue Templates

**Version:** 1.0.0  
**Updated:** 2026-04-27

---

### APP-ISS-01: Template creation  `[high]`
- **Given** A new App issue category is needed.
- **When** The change is reviewed in PR.
- **Then** A new template file is added to this subfolder with required fields: severity, repro steps, expected vs actual, environment.

### APP-ISS-02: Template deprecation  `[medium]`
- **Given** An issue template is replaced.
- **When** The change is reviewed in PR.
- **Then** The old template's frontmatter gains `superseded_by:` pointing to the replacement; the file remains for historical issue references.
