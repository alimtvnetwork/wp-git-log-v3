# Acceptance Criteria

**Version:** 3.3.0  
**Updated:** 2026-04-26 (Phase 15a: Theme & Variables section converted from table-row format to full GWT subsections — AC-001..AC-006 deepened with concrete contracts + cross-refs. AC IDs unchanged. Sections AC-007..AC-034 still in table format pending Phase 15b..15e.)

---

## Overview

Testable criteria for validating design system compliance across all components and pages.

> **Format note (Phase 15a in flight):** Sections in this file are mid-conversion from table-row format to full Given/When/Then format. **Theme & Variables (AC-001..AC-006)** is fully converted to GWT subsections. **Typography, Motion & Transitions, Code Blocks, Navigation, Page Consistency** remain in table format pending Phase 15b..15e. AC IDs are stable across the conversion — `AC-001` means the same criterion in both formats. Tooling that scrapes ACs by ID continues to work; tooling that requires GWT prose can use the converted sections as canonical and treat the table sections as one-line summaries until they are converted.

---

## Theme & Variables

### AC-001: No hardcoded colors anywhere in component code
- **Given** any component file under `src/` (TSX, JSX, CSS module, or styled-component)
- **When** the file is scanned for color literals
- **Then** the file MUST NOT contain ANY of: (a) hex literals matching `#[0-9a-fA-F]{3,8}` (e.g. `#fff`, `#1a1a2e`, `#ff5722cc`), (b) `rgb(...)` / `rgba(...)` / `hsl(...)` / `hsla(...)` function calls with raw numeric arguments (raw `hsl(220, 50%, 60%)` is FORBIDDEN; only `hsl(var(--token))` form is permitted per AC-002), (c) named CSS colors (`red`, `white`, `cornflowerblue`), (d) literal Tailwind color utility classes (`text-white`, `bg-black`, `border-gray-500`, `text-red-600`, `bg-blue-500`) — these bypass the design-token layer and break theming; AND the ONLY exceptions permitted are: (i) the design-token DEFINITIONS in `src/index.css` (`:root` and `.dark` blocks), (ii) `tailwind.config.ts` token registration, (iii) third-party SVG assets where colors are baked into the source asset (in which case the asset MUST be wrapped in a component that exposes a `currentColor` override).
- **Source:** `01-design-principles.md` (token-only rule), `src/index.css` (token definitions), `tailwind.config.ts` (token registration), `mem://index.md` Core rule on semantic-token enforcement.

### AC-002: All colors reference CSS custom properties via `hsl(var(--token))`
- **Given** any color application in component code or CSS
- **When** the color value is inspected
- **Then** it MUST take the form `hsl(var(--<token-name>))` for solid colors OR `hsl(var(--<token-name>) / <alpha>)` for transparency (e.g. `hsl(var(--primary) / 0.5)`); AND the `--<token-name>` MUST be defined in BOTH the `:root` and `.dark` blocks of `src/index.css` (per AC-004); AND the token VALUE in `index.css` MUST be a bare HSL triplet WITHOUT the `hsl()` wrapper (e.g. `--primary: 217 91% 60%;` — NOT `--primary: hsl(217, 91%, 60%);`) so that the `hsl(var(--primary))` consumer expression composes correctly; AND consumer code MUST NOT inline raw HSL values like `hsl(217, 91%, 60%)` even if the numbers happen to match a token — this defeats theming because the dark-mode override would not apply.
- **Source:** `02-theme-variable-architecture.md` (the `hsl(var(--token))` discipline), `src/index.css` (bare-triplet token format), `mem://index.md` Core rule on HSL-only design tokens.

### AC-003: Changing `--primary` in `:root` updates all primary-colored elements
- **Given** the design-token `--primary` defined in `src/index.css :root`
- **When** the value is changed (e.g. from `217 91% 60%` to `340 80% 55%`) and the page is reloaded
- **Then** EVERY surface bound to `primary` MUST update simultaneously: button backgrounds, link underlines, focus rings, hover tints, gradient endpoints involving `--primary`, the icon accent color, and any `border-primary` / `text-primary` / `bg-primary` Tailwind utility — without ANY component requiring a code change; AND derived tokens like `--primary-foreground` (the on-primary text color) MUST be updated MANUALLY in `index.css` to maintain WCAG AA contrast (per AC-006) — the system does NOT auto-derive foreground from background; AND surfaces using `primary-glow` or gradient compositions (per the `--gradient-primary` token) MUST also reflect the new primary because they reference `var(--primary)` internally.
- **Source:** `02-theme-variable-architecture.md` (single-source-of-truth principle), `src/index.css` (token cascade), AC-006 (paired-token contrast invariant).

### AC-004: Both `:root` and `.dark` blocks define all tokens
- **Given** the token catalog in `src/index.css`
- **When** the `:root { ... }` block and the `.dark { ... }` block are diffed
- **Then** the SET of declared `--<token-name>` properties MUST be IDENTICAL between the two blocks — every token defined in `:root` MUST have a corresponding override in `.dark`, and no token may exist in `.dark` that is not also in `:root`; AND the values MUST be appropriate for each mode (e.g. `--background` is light HSL in `:root`, dark HSL in `.dark`); AND tokens that are intentionally mode-invariant (e.g. brand `--accent` if the brand color does not adapt) MUST still appear in both blocks with identical values to make the intent explicit (silence is not consent — a missing override implies "not yet themed for dark mode" which is a bug); AND the `.dark` class MUST be applied to `<html>` or `<body>` by the theme provider — never to a nested element (which would create cascading-context bugs where some descendants escape dark mode).
- **Source:** `02-theme-variable-architecture.md` (dual-block requirement), `src/index.css` (the two blocks under audit), the `ThemeProvider` component (for the `.dark` class application contract).

### AC-005: Dark-mode toggle changes all surfaces, text, and borders correctly
- **Given** the application rendered in light mode
- **When** the user toggles to dark mode (via the theme switcher, which adds `.dark` to `<html>`)
- **Then** within ONE animation frame (≤ 16ms perceptible delay), ALL of the following MUST update simultaneously: page background (`--background`), card surfaces (`--card`, `--popover`), text colors (`--foreground`, `--muted-foreground`), borders (`--border`, `--input`), accent surfaces (`--primary`, `--secondary`, `--accent`, `--destructive`), focus rings, and any gradient compositions referencing the above; AND no element MUST exhibit a "flash" of the wrong-mode color (FOUC) — this requires the theme provider to set the initial class BEFORE first paint via a synchronous script in `index.html` reading from `localStorage`; AND the toggle MUST persist to `localStorage` so the choice survives reload; AND the toggle MUST respect `prefers-color-scheme` for first-visit users (no stored preference yet) per the standard `next-themes` / shadcn theme-provider pattern.
- **Source:** `02-theme-variable-architecture.md` (toggle contract), `06-motion-transitions.md` (no-flash requirement intersects with the reduced-motion rule in AC-014), the theme provider component (persistence + first-paint script).

### AC-006: Text on colored backgrounds meets WCAG AA contrast (4.5:1)
- **Given** any pairing of background token + foreground token used together in the UI (e.g. `bg-primary` + `text-primary-foreground`, `bg-card` + `text-card-foreground`, `bg-destructive` + `text-destructive-foreground`)
- **When** the contrast ratio is measured per WCAG 2.1 §1.4.3
- **Then** for normal-size text (< 18pt or < 14pt bold) the ratio MUST be ≥ 4.5:1; for large text (≥ 18pt or ≥ 14pt bold) the ratio MUST be ≥ 3:1; AND this MUST hold in BOTH light and dark modes (per AC-004 the token pair is defined in both blocks — both pairings MUST pass independently); AND when a new token pair is added to `index.css`, the author MUST verify contrast before merging — the recommended check is `npx @bramus/specificity` or any WCAG checker against the resolved HSL values; AND interactive states (hover, focus, disabled) inherit the same contrast obligation — disabled text at `opacity: 0.5` against the background MUST still pass 3:1 minimum (or use a dedicated `--muted-foreground` token sized for the lower contrast); AND non-text UI elements (icons, focus rings, form-control borders) MUST meet the 3:1 non-text threshold per WCAG 2.1 §1.4.11.
- **Source:** `01-design-principles.md` (accessibility baseline), `02-theme-variable-architecture.md` (paired-token convention `--primary` + `--primary-foreground`), WCAG 2.1 §1.4.3 (contrast — minimum), §1.4.11 (non-text contrast).

## Typography

| # | Criterion | Source |
|---|-----------|--------|
| AC-007 | Headings use Ubuntu font family | `03-typography.md` |
| AC-008 | Body text uses Poppins font family | `03-typography.md` |
| AC-009 | Code blocks use Ubuntu Mono / JetBrains Mono | `03-typography.md` |
| AC-010 | H1 and H2 use gradient text effect | `03-typography.md` |
| AC-011 | No heading level is skipped (H1 → H2 → H3) | `12-page-creation-rules.md` |

## Motion & Transitions

| # | Criterion | Source |
|---|-----------|--------|
| AC-012 | All hover transitions complete within 300ms | `06-motion-transitions.md` |
| AC-013 | No JavaScript animation libraries used for visual effects | `06-motion-transitions.md` |
| AC-014 | `prefers-reduced-motion` media query disables animations | `06-motion-transitions.md` |
| AC-015 | Link underline sweeps right-to-left on hover | `06-motion-transitions.md` |
| AC-016 | CTA buttons use slide text animation, not simple color change | `09-button-system.md` |

## Code Blocks

| # | Criterion | Source |
|---|-----------|--------|
| AC-017 | Code blocks maintain dark background in both themes | `07-code-blocks.md` |
| AC-018 | Language badge shows correct color dot per language | `07-code-blocks.md` |
| AC-019 | Font size controls adjust between 12px and 32px | `07-code-blocks.md` |
| AC-020 | Line click pins/unpins with primary-colored background | `07-code-blocks.md` |
| AC-021 | Shift-click selects line range | `07-code-blocks.md` |
| AC-022 | Fullscreen mode fills viewport with 2rem inset | `07-code-blocks.md` |
| AC-023 | Escape key exits fullscreen | `07-code-blocks.md` |
| AC-024 | Copy button shows "Copied ✓" state for 2 seconds | `07-code-blocks.md` |
| AC-025 | Tree/structure blocks show 📁/📄 prefixes | `07-code-blocks.md` |

## Navigation

| # | Criterion | Source |
|---|-----------|--------|
| AC-026 | Header icon hover shows scale(1.05) effect | `08-header-navigation.md` |
| AC-027 | Menu item hover shows gradient underline sweep | `08-header-navigation.md` |
| AC-028 | Dropdown items show primary-tinted hover background | `08-header-navigation.md` |
| AC-029 | Sidebar collapses to sheet on mobile (< 768px) | `10-sidebar-system.md` |
| AC-030 | Ctrl+B toggles sidebar visibility | `10-sidebar-system.md` |

## Page Consistency

| # | Criterion | Source |
|---|-----------|--------|
| AC-031 | New pages follow section pattern templates | `12-page-creation-rules.md` |
| AC-032 | No page introduces fonts not in the design system | `12-page-creation-rules.md` |
| AC-033 | All interactive elements follow the state language | `12-page-creation-rules.md` |
| AC-034 | Responsive at mobile/tablet/desktop breakpoints | `12-page-creation-rules.md` |

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Full Design System | [00-overview.md](./00-overview.md) |
