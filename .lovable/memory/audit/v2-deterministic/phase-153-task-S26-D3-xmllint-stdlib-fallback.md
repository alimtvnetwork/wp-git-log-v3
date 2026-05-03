# Phase 153 Task S26-D3 — spec/26 close audit-v7 [D3] MEDIUM xmllint dependency

**Date:** 2026-05-04
**Module:** spec/26-gitlogs-diagrams
**Pre-score:** 94 GOOD (D3=17, only remaining MED finding)
**Expected re-score:** ≥97 EXCELLENT

## Finding closed

| dim | sev | title | resolution |
|---|---|---|---|
| D3 | MED | External Dependency on xmllint | AC-24 — Python-stdlib `xml.etree.ElementTree.canonicalize` (3.8+) fallback when `command -v xmllint` is absent |

## Design

- AC-24 forks ONLY the canonicaliser; AC-23 Tier 2 step 3/4/5 (normalisation, diff gate, drift policy) are delegated by reference (Lesson #36).
- Equivalence claim: for Mermaid-emitted SVG, c14n10 (stdlib) ≡ c14n11 (xmllint) after the sed normalisation step. Mermaid output has no namespace prefixes beyond root, no DTD subset, no PIs, no `xml:space` — the four c14n11-vs-c14n10 divergence axes.
- Forbidden patterns: hard-fail on xmllint absence; non-stdlib fallback libs; diverging from AC-23 step 3/4/5; silent dual-implementation when both succeed but disagree.

## Banners

- §97 v3.4.1 → v3.5.0 (AC +1, minor — new contract)
- §00 v3.5.1 → v3.6.0
- §98 v3.5.1 → v3.6.0 (+row)
- §99 v3.4.1 → v3.5.0

## Lessons codified

- **#36 link-don't-restate** — only canonicaliser binary forks; AC-23 Tier 2 stays canonical.
- **NEW sub-pattern under #29 Section F**: audit-corpus protocol surfaces citing OS-level binaries MUST also offer a stdlib fallback so AI sandboxes can verify the contract. The fallback is part of the normative surface, not optional implementer convenience.

No CI workflow / RUBRIC change.
