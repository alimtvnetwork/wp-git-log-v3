# Acceptance Criteria

**Version:** 3.4.0  
**Updated:** 2026-04-26 (Phase 15b: Typography section AC-007..AC-011 converted from table format to full GWT subsections — Ubuntu/Poppins/mono font-loading contracts, gradient text effect cross-browser rules, heading-discipline WCAG semantics. AC IDs unchanged at AC-001..AC-034. 11 of 34 ACs now GWT; 23 await Phase 15c..15e.)

---

## Overview

Testable criteria for validating design system compliance across all components and pages.

> **Format note (Phase 15b in flight):** Sections in this file are mid-conversion from table-row format to full Given/When/Then format. **Theme & Variables (AC-001..AC-006) and Typography (AC-007..AC-011)** are fully converted to GWT subsections (11 of 34 ACs total). **Motion & Transitions, Code Blocks, Navigation, Page Consistency** remain in table format pending Phase 15c..15e. AC IDs are stable across the conversion — `AC-007` means the same criterion in both formats. Tooling that scrapes ACs by ID continues to work; tooling that requires GWT prose can use the converted sections as canonical and treat the table sections as one-line summaries until they are converted.

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

### AC-007: Headings use the Ubuntu font family
- **Given** any heading element rendered in the application (`<h1>` through `<h6>`, plus any custom component that visually presents as a heading — e.g. `<CardTitle>`, `<DialogTitle>`, hero display text)
- **When** the computed style is inspected in browser devtools
- **Then** `font-family` MUST resolve to `"Ubuntu"` as the FIRST family in the stack, with a system-sans fallback chain (`"Ubuntu", system-ui, -apple-system, "Segoe UI", sans-serif`); AND the Ubuntu font MUST be loaded via Google Fonts in `index.html` `<head>` with `display=swap` (e.g. `<link rel="preconnect" href="https://fonts.googleapis.com">` + `<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">`) — `display=swap` is REQUIRED so headings remain readable during font load (no FOIT); AND the family MUST be registered in `tailwind.config.ts` under `theme.extend.fontFamily.heading` (or `display`) so utility classes like `font-heading` resolve correctly; AND headings MUST NOT use Poppins (the body family per AC-008) — mixing the two within a single heading is a contract violation; AND when a heading appears INSIDE a card or dialog, the font family MUST still be Ubuntu — child-component overrides are FORBIDDEN unless the design system explicitly defines a non-heading display font.
- **Source:** `03-typography.md` (font-stack contract), `index.html` (font-loading), `tailwind.config.ts` (`fontFamily.heading` registration), AC-008 (body family separation), AC-010 (gradient effect on H1/H2 — applied AFTER the family rule).

### AC-008: Body text uses the Poppins font family
- **Given** any non-heading text element (`<p>`, `<span>`, `<li>`, `<button>` label, form input text, table cell, footer copyright)
- **When** the computed style is inspected
- **Then** `font-family` MUST resolve to `"Poppins"` as the first family with a system-sans fallback (`"Poppins", system-ui, -apple-system, "Segoe UI", sans-serif`); AND Poppins MUST be loaded via Google Fonts in `index.html` `<head>` with `display=swap` and weights `300;400;500;600;700` at minimum (the design system uses `font-medium` and `font-semibold` for emphasis); AND the family MUST be registered in `tailwind.config.ts` under `theme.extend.fontFamily.sans` so it becomes the project DEFAULT (Tailwind's `font-sans` utility resolves to Poppins, not the built-in system stack); AND body text MUST NOT use Ubuntu — Ubuntu is reserved for headings per AC-007 to maintain visual hierarchy; AND inline `<code>` (within paragraphs) MUST use the monospace family per AC-009, NOT Poppins, even though it's body context.
- **Source:** `03-typography.md` (body-stack contract), `index.html` (font-loading), `tailwind.config.ts` (`fontFamily.sans` default override), AC-007 (heading family separation), AC-009 (code/mono escape hatch).

### AC-009: Code blocks use Ubuntu Mono / JetBrains Mono
- **Given** any `<code>`, `<pre>`, or `<CodeBlock>` element (incl. inline `<code>` inside paragraphs)
- **When** the computed style is inspected
- **Then** `font-family` MUST resolve to a monospace stack with `"Ubuntu Mono"` as the first family and `"JetBrains Mono"` as a secondary fallback before generic `monospace` (full stack: `"Ubuntu Mono", "JetBrains Mono", "Fira Code", Consolas, "Courier New", monospace`); AND BOTH `Ubuntu Mono` AND `JetBrains Mono` MUST be loaded via Google Fonts in `index.html` with `display=swap` (Ubuntu Mono weights `400;700`; JetBrains Mono weights `400;500;700` — the heavier weights support syntax-highlight bold tokens); AND the family MUST be registered in `tailwind.config.ts` under `theme.extend.fontFamily.mono` so utilities like `font-mono` resolve to this stack (overriding Tailwind's built-in `font-mono` which uses ui-monospace + Menlo); AND code blocks MUST preserve `font-feature-settings: "liga" 0;` to disable programming ligatures by default — ligatures are user-opt-in via a `format:ligatures` directive per `07-code-blocks.md` because they obscure the actual character sequence which matters in code review; AND inline `<code>` MUST use the same family as block `<pre>` so a snippet quoted in prose visually matches the same snippet in a code block.
- **Source:** `03-typography.md` (monospace stack), `07-code-blocks.md` (ligature default + inline-vs-block parity), `index.html` (dual font-loading), `tailwind.config.ts` (`fontFamily.mono` override).

### AC-010: H1 and H2 use the gradient text effect
- **Given** any `<h1>` or `<h2>` rendered in the application (raw HTML headings AND component wrappers like `<PageTitle>` / `<SectionTitle>` that semantically render as H1/H2)
- **When** the computed style is inspected
- **Then** the heading MUST have a gradient text fill via `background-image: var(--gradient-primary)` (or an equivalent `linear-gradient(...)` referencing design tokens) PLUS `background-clip: text` PLUS `-webkit-background-clip: text` PLUS `color: transparent` (or `-webkit-text-fill-color: transparent` for Safari compatibility) — ALL FOUR properties are required for cross-browser gradient text; AND the gradient MUST reference design tokens — hardcoded color stops are FORBIDDEN per AC-001 (typical token form: `linear-gradient(135deg, hsl(var(--primary)), hsl(var(--primary-glow)))`); AND H3..H6 MUST NOT use the gradient effect — they remain solid `hsl(var(--foreground))` to preserve the visual hierarchy where only the top two levels carry the brand emphasis; AND when the heading wraps to multiple lines, the gradient MUST flow continuously across all lines (the gradient is applied to the full text box, not per-line); AND for accessibility, the gradient MUST NOT be the SOLE conveyor of meaning — semantic `<h1>`/`<h2>` tagging carries the structural meaning for screen readers, the gradient is purely visual decoration.
- **Source:** `03-typography.md` (gradient effect), `02-theme-variable-architecture.md` (`--gradient-primary` token), AC-001 (no hardcoded colors in gradient stops), `12-page-creation-rules.md` (heading-hierarchy semantics — see AC-011).

### AC-011: No heading level is skipped (H1 → H2 → H3)
- **Given** any page in the application
- **When** the heading outline is extracted (e.g. via browser accessibility tree or `document.querySelectorAll('h1,h2,h3,h4,h5,h6')` traversal)
- **Then** the heading levels MUST form a monotonically descending or sibling-only sequence — a heading at level N MUST be preceded by a heading at level N-1 OR a heading at level N (sibling) OR be the first heading on the page (which MUST be H1); AND every page MUST have EXACTLY ONE `<h1>` (the page title) — pages with zero H1s violate WCAG and SEO; pages with multiple H1s break the document outline; AND skipping levels (H1 → H3, or H2 → H4) is FORBIDDEN even if it visually looks acceptable, because screen readers use the level structure to build a navigable outline and gaps make the structure unreliable; AND when a component wraps a section with its own heading, the wrapper MUST accept a `level` prop (or use a polymorphic `as` prop) so the consumer can choose the correct level for the surrounding context — hardcoded `<h2>` inside a reusable Card component is a bug because it forces every consumer to nest at the same depth; AND visual styling MUST be decoupled from semantic level via Tailwind utilities — a semantic `<h3>` rendered with `text-4xl font-bold` is permitted (and often correct) when the visual hierarchy doesn't match the semantic outline.
- **Source:** `12-page-creation-rules.md` (heading-discipline rule), `03-typography.md` (visual styling decoupling), WCAG 2.1 §1.3.1 (info & relationships) + §2.4.6 (headings & labels), `tailwind.config.ts` (utility classes for visual sizing).


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
