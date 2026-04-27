# Acceptance Criteria — App Database Conventions

**Version:** 1.0.0  
**Updated:** 2026-04-27

---

### APP-DB-01: App-specific DDL convention  `[high]`
- **Given** An App migration introduces a convention not covered by the shared database conventions.
- **When** The change is reviewed in PR.
- **Then** A new doc is added here with the convention, rationale, and a forward-only migration template demonstrating it.

### APP-DB-02: Convention promotion  `[medium]`
- **Given** An App-specific convention proves general.
- **When** The change is reviewed in PR.
- **Then** It is moved to `04-database-conventions/` and this subfolder retains a stub pointing to the new canonical location.
