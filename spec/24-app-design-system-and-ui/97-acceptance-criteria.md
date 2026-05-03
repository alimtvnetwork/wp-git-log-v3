# Acceptance Criteria — 24 App Design System & UI

**Version:** 3.1.0
**Updated:** 2026-04-29
**Scope:** `spec/24-app-design-system-and-ui/`
**Generated:** Hand-authored alongside the v4.0.0 overview (Phase 39a). Supersedes the auto-extracted v2.0.0 set.

---

## Module Summary

Verifies that the app overlay (§24) is strictly additive over the core design system (§07): app-only tokens exist with proper light/dark parity, are derived from §07 primitives (no raw HSL except the documented status-color exception), and that all app components consume tokens via Tailwind utilities — never raw color literals. Also verifies the AppShell layout invariant.

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

### Token namespace

- App-only token names MUST start with `--app-`.
- App tokens MUST NOT shadow §07 token names (`--background`, `--foreground`, `--primary`, `--accent`, `--card`, `--popover`, `--muted`, `--border`, `--input`, `--ring`, `--space-*`, `--font-*`).

### Allowed raw HSL exception

- Only `--app-status-success`, `--app-status-warning`, `--app-status-danger` may declare raw HSL components in `:root` / `.dark`. Every other `--app-*` token MUST be expressed as `var(--<§07 token>)`.

### Forbidden literals (in app components under `src/components/app/**`)

Regex of forbidden substrings in `*.tsx` / `*.ts` / `*.css` under `src/components/app/`:

```
#[0-9a-fA-F]{3,8}\b           # hex literals
\brgb\(|\brgba\(              # rgb()/rgba()
\bhsl\([^v][^)]*\)            # hsl(literal,…) — but NOT hsl(var(--…))
```

### AppShell invariants

- `<header>` MUST be `position: fixed; top: 0; height: var(--app-toolbar-height)`.
- `<aside>` MUST be `position: fixed; left: 0; top: var(--app-toolbar-height); width: var(--app-sidebar-width)`.
- `<main>` MUST have `padding-top: var(--app-toolbar-height)` AND `padding-left: var(--app-sidebar-width)` so content never collides with the fixed header/sidebar.

### Verification scripts

- `npm run lint` — ESLint config enforces the forbidden-literal regexes above.
- `npm run test` — Vitest snapshot suite renders AppShell in light + dark.

---

## Acceptance Criteria

### AC-ADS-01: App tokens never redefine §07 primitives  `[critical]`
- **Given** The combined `:root` and `.dark` blocks of `src/index.css`.
- **When** All custom property declarations are extracted.
- **Then** No declaration whose name does NOT start with `--app-` may appear inside the §24-managed block; equivalently, no `--app-<x>` may share a name with any §07 token.
- **Verifies:** `00-overview.md` § "Token namespace"

### AC-ADS-02: App tokens derive from §07 primitives  `[critical]`
- **Given** Every `--app-*` declaration in `src/index.css`.
- **When** The right-hand side is parsed.
- **Then** It MUST be either (a) `var(--<§07 token>)`, OR (b) one of the three documented status tokens (`--app-status-success|warning|danger`) declared with raw HSL components.
- **Verifies:** `00-overview.md` § "App-only semantic tokens"

### AC-ADS-03: No raw color literals in app components  `[critical]`
- **Given** All `*.tsx`, `*.ts`, and `*.css` files under `src/components/app/**`.
- **When** ESLint runs the forbidden-literal regex set inlined above.
- **Then** Zero matches MUST be reported. Any match fails the build.
- **Verifies:** `00-overview.md` § "Inlined Contracts" + Verification command

### AC-ADS-04: Light/dark parity for every app token  `[critical]`
- **Given** The set of `--app-*` tokens declared in `:root`.
- **When** The same tokens are looked up in `.dark`.
- **Then** Every token MUST resolve to a real value in `.dark` — either by direct declaration or by inheriting from a §07 token that itself has both `:root` and `.dark` values.
- **Verifies:** `00-overview.md` § "Theme parity rule"

### AC-ADS-05: AppShell fixed-region geometry  `[high]`
- **Given** A rendered `<AppShell>` component in jsdom (Vitest).
- **When** Computed styles are inspected for `header`, `aside`, and `main`.
- **Then**
  - `header.position === "fixed"` AND `header.height === var(--app-toolbar-height)`.
  - `aside.position === "fixed"` AND `aside.top === var(--app-toolbar-height)` AND `aside.width === var(--app-sidebar-width)`.
  - `main.paddingTop === var(--app-toolbar-height)` AND `main.paddingLeft === var(--app-sidebar-width)`.
- **Verifies:** `00-overview.md` § "Layout container — the App Shell" + "AppShell invariants"

### AC-ADS-06: Marketing routes do not import AppShell  `[medium]`
- **Given** All routes under `src/pages/(marketing)/**` (or equivalent public-route folder).
- **When** Imports are scanned.
- **Then** None of these files MUST import from `src/components/app/AppShell`.
- **Verifies:** `00-overview.md` § "Layout container — the App Shell" — App Shell is for authenticated routes only.

### AC-ADS-07: Sidebar collapses below `md` breakpoint  `[medium]`
- **Given** A rendered `<AppShell>` at viewport width 767px (just below `md`).
- **When** The aside's computed width is read.
- **Then** It MUST equal `var(--app-sidebar-width-collapsed)` (4rem), and `main.paddingLeft` MUST equal the same value.
- **Verifies:** `00-overview.md` § "Responsive breakpoints (binding)"

### AC-ADS-08: Lint + test pipeline passes  `[critical]`
- **Given** A clean working tree on the spec branch.
- **When** Running `npm run lint && npm run test`.
- **Then** Exit code MUST be `0`. Any non-zero exit blocks merge.
- **Verifies:** `00-overview.md` § Verification

### AC-ADS-09: Ownership matrix has no overlap with §07  `[high]`
- **Given** The component inventories of `src/components/ui/**` (§07 primitives) and `src/components/app/**` (§24 composites).
- **When** Component names are compared.
- **Then** No name MUST appear in both folders. App composites MUST be built from §07 primitives, not parallel re-implementations.
- **Verifies:** `00-overview.md` § "Relationship to §07 (Core Design System)"

### AC-ADS-10: Status tokens are app-scoped only  `[low]`
- **Given** All references to `--app-status-*` tokens.
- **When** Their usage is scanned in `src/`.
- **Then** They MUST appear only inside `src/components/app/**` and `src/index.css`. They MUST NOT appear in `src/components/ui/**` (which is §07 territory).
- **Verifies:** `00-overview.md` § "App-only semantic tokens" warning block

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)
- [§07 Design System (canonical primitives)](../07-design-system/00-overview.md)
