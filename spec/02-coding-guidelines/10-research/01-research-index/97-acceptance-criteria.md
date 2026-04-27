# Acceptance Criteria — Research Index

**Version:** 1.0.0  
**Updated:** 2026-04-27

---

### RES-IDX-01: Newly approved research  `[high]`
- **Given** An approved research scope is added to this folder.
- **When** The change is reviewed in PR.
- **Then** A new sibling subfolder is created with `00-overview.md`, `97-acceptance-criteria.md`, `98-changelog.md`, `99-consistency-report.md`.

### RES-IDX-02: Withdrawn research  `[medium]`
- **Given** A research item is withdrawn before completion.
- **When** The change is reviewed in PR.
- **Then** Its subfolder is renamed to `_archive-<slug>/` and excluded from audit via the `_archive` path filter.
