# Acceptance Criteria

**Version:** 3.5.0  
**Updated:** 2026-04-26 (Phase 15c: Motion & Transitions section AC-012..AC-016 converted from table format to full GWT subsections — 300ms timing budget, JS-animation-library prohibition list, `prefers-reduced-motion` global override + per-component opt-in, link sweep direction + pseudo-element implementation, CTA slide animation contract. AC IDs unchanged at AC-001..AC-034. 16 of 34 ACs now GWT; 18 await Phase 15d..15e.)

---

## Overview

Testable criteria for validating design system compliance across all components and pages.

> **Format note (Phase 15c in flight):** Sections in this file are mid-conversion from table-row format to full Given/When/Then format. **Theme & Variables (AC-001..AC-006), Typography (AC-007..AC-011), and Motion & Transitions (AC-012..AC-016)** are fully converted to GWT subsections (16 of 34 ACs total). **Code Blocks, Navigation, Page Consistency** remain in table format pending Phase 15d..15e. AC IDs are stable across the conversion — `AC-012` means the same criterion in both formats. Tooling that scrapes ACs by ID continues to work; tooling that requires GWT prose can use the converted sections as canonical and treat the table sections as one-line summaries until they are converted.

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

### AC-012: All hover transitions complete within 300ms
- **Given** any interactive element with a hover state (buttons, links, cards, navigation items, dropdowns, form controls, icon buttons)
- **When** the user moves their pointer onto the element and the `:hover` styles take effect
- **Then** the `transition-duration` MUST be ≤ 300ms for every animatable property (color, background-color, border-color, transform, opacity, box-shadow); AND the canonical durations are: 150ms for color/background swaps (fast feedback), 200ms for transforms (scale, translate), 300ms for compound effects (gradient sweeps, shadow expansions) — values OUTSIDE the 150/200/300ms set are FORBIDDEN unless justified inline with a comment, because the design system relies on a small fixed timing vocabulary so transitions feel coherent across surfaces; AND the `transition-timing-function` MUST be `cubic-bezier(0.4, 0, 0.2, 1)` (Tailwind's `ease-in-out` default) — `linear` is FORBIDDEN for hover effects (mechanical feel), `ease-in` is FORBIDDEN (slow start feels laggy on quick mouse-overs); AND every hover transition MUST also define the REVERSE transition (mouse-out) with the same duration — asymmetric durations (e.g. 150ms in, 300ms out) cause visual "stickiness" and are FORBIDDEN; AND when `prefers-reduced-motion: reduce` is active per AC-014, the transition MUST collapse to ≤ 10ms (effectively instant) — the hover effect itself MAY still apply (e.g. color change), only the animation between states is suppressed.
- **Source:** `06-motion-transitions.md` (timing vocabulary + easing function), `tailwind.config.ts` (`transitionDuration` extension if non-default values are needed), AC-014 (reduced-motion override), AC-016 (CTA-specific composition built on this baseline).

### AC-013: No JavaScript animation libraries used for visual effects
- **Given** the project's dependency tree (`package.json` + lock file)
- **When** the dependency list is audited
- **Then** the project MUST NOT depend on ANY of: `framer-motion`, `react-spring`, `react-motion`, `gsap`, `anime.js`, `lottie-web`, `lottie-react`, `mo.js`, `popmotion`, `react-transition-group`, `velocity-animate`, or any other runtime JavaScript animation library — these add 30-200KB of bundle weight, conflict with the design system's CSS-first motion vocabulary per AC-012, and bypass the `prefers-reduced-motion` automatic respect that pure CSS gives for free; AND visual transitions MUST be implemented with CSS (`transition`, `@keyframes`, `animation` property) OR with the Tailwind `animate-*` utilities backed by `tailwind.config.ts` `keyframes` + `animation` registration; AND the ONLY exceptions are: (a) `tailwindcss-animate` (the official shadcn animation plugin, ~2KB, registers utility classes but emits pure CSS), (b) `embla-carousel-react` and similar UI libraries where the animation is intrinsic to the widget's behavior (carousel scroll, drawer slide), (c) third-party charting libraries (`recharts`, `d3`) where the animation is part of data visualization, NOT decoration; AND animating React state via `requestAnimationFrame` loops in user-land code is FORBIDDEN — if a CSS-only solution is impossible, the implementation MUST be approved as a documented exception in `06-motion-transitions.md` before merge.
- **Source:** `06-motion-transitions.md` (CSS-first principle), `package.json` (dependency audit surface), `tailwind.config.ts` (`keyframes`/`animation` extensions), AC-014 (reduced-motion compatibility — CSS gets it free, JS libs MUST be wrapped manually).

### AC-014: `prefers-reduced-motion` media query disables animations
- **Given** the user has set `prefers-reduced-motion: reduce` in their OS accessibility preferences (macOS: System Settings → Accessibility → Display → Reduce motion; Windows: Settings → Ease of Access → Display → Show animations; iOS: Settings → Accessibility → Motion → Reduce Motion)
- **When** the application loads
- **Then** `src/index.css` MUST contain a global rule that suppresses transitions and animations: `@media (prefers-reduced-motion: reduce) { *, *::before, *::after { transition-duration: 0.01ms !important; animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; } }` — the `0.01ms` (not `0`) is intentional so JavaScript that listens for `transitionend` still fires; AND component-level `@keyframes` animations MUST individually respect the media query if they are essential to the UI (e.g. loading spinners — wrap in `@media (prefers-reduced-motion: no-preference) { ... }` so the spin only runs when motion is allowed, and provide a static fallback indicator); AND `scroll-behavior: smooth` MUST be overridden to `auto` so users with reduced-motion preference don't experience smooth-scroll which can trigger vestibular issues; AND the rule MUST live in `index.css` GLOBAL scope, NOT in a component-scoped CSS module — global enforcement is the only way to catch every transition without per-component opt-in; AND parallax effects, auto-playing video, and infinite scroll auto-advance MUST be DISABLED entirely (not just slowed) when reduced-motion is active.
- **Source:** `06-motion-transitions.md` (reduced-motion contract), `src/index.css` (global override location), WCAG 2.1 §2.3.3 (animation from interactions), MDN `prefers-reduced-motion` reference, AC-012 (transitions collapse to ≤10ms), AC-013 (CSS-first means free reduced-motion compliance).

### AC-015: Link underline sweeps right-to-left on hover
- **Given** any inline text link (`<a>` element with text content, NOT navigation menu items which use the gradient-underline sweep per AC-027)
- **When** the user hovers over the link
- **Then** an underline MUST animate from the RIGHT edge of the link to the LEFT edge over 300ms (per AC-012), revealing the underline progressively; AND the underline MUST be implemented via a `::after` pseudo-element with `position: absolute`, `bottom: 0`, `right: 0`, `width: 0`, `height: 1px` (or `2px` for emphasis links), `background-color: hsl(var(--primary))` (or the link's current color via `currentColor`), and `transition: width 300ms cubic-bezier(0.4, 0, 0.2, 1)` — on hover, `width: 100%` triggers the sweep; AND the parent link MUST be `position: relative` so the absolute pseudo-element anchors correctly; AND on mouse-out the underline MUST sweep BACK from full-width to zero in the SAME direction (right-to-left out, then right-to-left in on next hover) — using `right: 0` for the anchor achieves this naturally because `width` shrinks toward `right`; AND the link MUST NOT use `text-decoration: underline` simultaneously with the pseudo-element underline — pick one, the pseudo-element is the design-system standard because `text-decoration` cannot animate width on most browsers; AND focus-visible state MUST show the underline at full width without animation (instant) so keyboard users get immediate feedback; AND when `prefers-reduced-motion: reduce` is active per AC-014, the underline MUST appear instantly at full width on hover/focus (no sweep animation).
- **Source:** `06-motion-transitions.md` (sweep direction + pseudo-element implementation), AC-012 (300ms baseline), AC-014 (reduced-motion override), AC-027 (navigation menu items use a DIFFERENT sweep — gradient + left-to-right — so the patterns are intentionally distinct).

### AC-016: CTA buttons use slide text animation, not simple color change
- **Given** any primary call-to-action button (the `Button` component with `variant="default"` or `variant="premium"` per `09-button-system.md`)
- **When** the user hovers over the button
- **Then** the button's text MUST animate via a slide effect — typically the visible text translates UP and a duplicate text element below translates UP into view (giving the illusion of the text being replaced by an identical copy with a subtle "press-up" feel); the implementation MUST use `transform: translateY(...)` on TWO stacked text spans inside an `overflow: hidden` button body, with the original at `translateY(0)` → `translateY(-100%)` and the duplicate at `translateY(100%)` → `translateY(0)`, BOTH with `transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1)`; AND a SIMPLE `background-color` change alone is FORBIDDEN for primary CTAs — that's the secondary/ghost variant pattern; AND the slide MUST be REVERSIBLE on mouse-out (text slides back down to original position); AND the button background MAY ALSO change (e.g. gradient angle shift, glow intensification) BUT the slide animation is the REQUIRED differentiator that signals "this is the primary action"; AND when `prefers-reduced-motion: reduce` is active per AC-014, the slide MUST be replaced with an instant background tint change (the visual emphasis of "this is hovered" remains, only the motion is suppressed); AND the slide direction MUST be vertical (up) — horizontal slides are reserved for navigation transitions per the design system's directional vocabulary; AND the duplicate text MUST be `aria-hidden="true"` so screen readers announce the button label only once.
- **Source:** `09-button-system.md` (CTA variant contract + slide implementation), `06-motion-transitions.md` (vertical-direction reservation), AC-012 (300ms + cubic-bezier baseline), AC-014 (reduced-motion fallback), AC-006 (the new background color after hover MUST still meet WCAG AA against the foreground text).


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
