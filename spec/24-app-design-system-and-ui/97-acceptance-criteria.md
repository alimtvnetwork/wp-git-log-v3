# Acceptance Criteria — 24 App Design System And Ui

**Version:** 2.0.0  
**Updated:** 2026-04-25  
**Scope:** `spec/24-app-design-system-and-ui/`  
**Generated:** AI-extracted Given/When/Then from module body via `linter-scripts/generate-gwt-acceptance.py`

---

## Module Summary

Defines the application-specific UI and design system standards, enforcing theme consistency, layout conventions, and semantic token usage via automated linting and snapshot testing.

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

N/A - This overview module defines meta-standards. 
Required Semantic Token Prefixes: --color-*, --space-*, --font-*
Required Theme Modes: 'light', 'dark'
Required Scripts: 'npm run lint', 'npm run test'
Version: 3.2.0

---

## Acceptance Criteria

### AC-ADS-01: Tokenized Color and Spacing Usage  `[critical]`
- **Given** The application UI codebase and the semantic token definitions in `00-overview.md`
- **When** Running `npm run lint` on the project source code.
- **Then** CSS or Style-in-JS properties must use variables (e.g., --color-primary) instead of hardcoded hex/rgb values.
- **Verifies:** AC-ADS-000: App design-system conformance

### AC-ADS-02: Theme Snapshot Consistency  `[high]`
- **Given** The UI components in light and dark mode configurations.
- **When** Executing `npm run test` to trigger snapshot comparisons.
- **Then** Visual regression tests pass with zero pixel mismatch against current baseline snapshots.
- **Verifies:** AC-ADS-000: App design-system conformance

### AC-ADS-03: Standard Layout Conformance  `[high]`
- **Given** The application layout implementation.
- **When** Inspecting DOM structure for top-level application shell components.
- **Then** The layout must adhere to the 'layout conventions' mentioned in the Purpose section, specifically ensuring standard containers are used.
- **Verifies:** Purpose: layout conventions

### AC-ADS-04: Design System Version Alignment  `[low]`
- **Given** The project versioning and metadata in 00-overview.md.
- **When** Auditing package.json or component library metadata.
- **Then** The implementation must be tagged or documented as conforming to Version 3.2.0.
- **Verifies:** Version: 3.2.0

### AC-ADS-05: Core vs App Design System Hierarchy  `[medium]`
- **Given** A component intended for the application UI.
- **When** The component is rendered in the browser.
- **Then** The component must inherit styles from the Cross-Referenced 'Design System (Core)' while applying 'app-specific' overrides.
- **Verifies:** Cross-References: [Design System (Core)]

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)