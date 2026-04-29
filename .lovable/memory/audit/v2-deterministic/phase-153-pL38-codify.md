# Phase 153 P-L38 — Codify Lesson #38 (slug-validation-before-sweep)

**Date:** 2026-04-29
**Type:** Docs-only codification
**Outcome:** Lesson #38 appended to `mem://process/phase-153-lessons` Section G

## What

Surfaced during the spec/17 Source-header sweep (P-L36-fu): batch-emitting anchor links from AI-inferred slugs produced several near-misses (e.g., `spec/13-cli` vs the actual `spec/13-generic-cli`, `spec/27-toolchain` vs `spec/27-spec-toolchain`). Lockstep + tree-health + version-parity all PASS on dead anchor targets because they only verify banner versions, not link resolution. The drift class is silent until first reader-click.

## Codification

Appended **Lesson #38 — Validate slugs against the live tree before sweeping** under Section G ("Cross-Module Roll-ups") of `mem://process/phase-153-lessons`. Lesson establishes the pre-flight `ls spec/ | grep -i <keyword>` + `grep -oE 'AC-[A-Z]+-[0-9]+'` validation contract for any anchor-emitting sweep, plus an optional post-validator that resolves every emitted link target.

Frontmatter range bumped #11–#37 → #11–#38. Reverse-index table appended row #38 → this memo.

## Mirror

Mirror of Lesson #14 (verify regex matches against real corpus before mass-patching) projected onto the cross-module link-target axis.

## Lockstep

Pure docs work — no spec edits, no script edits, no §97 changes, no banner bumps, no CI gate change.

## Status

Lesson #38 codified. Future anchor-emitting sweeps now have a discoverable pre-flight rule.
