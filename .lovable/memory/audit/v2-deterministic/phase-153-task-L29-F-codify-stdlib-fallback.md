---
phase: 153
task: L29-F-codify-stdlib-fallback
date: 2026-05-04
status: CLOSED — Lesson #73 graduated to indexed process rule (Lesson #36 sub-pattern)
gates: docs-only · no spec edits · no lockstep ripple
---

# L29-F-codify — Stdlib-fallback for OS-binary citations

## Trigger

Single precedent on the books, but high-impact + structurally clean enough to graduate as a forward-looking guard:

- **Phase 153 Task S26-D3** (`spec/26-gitlogs-diagrams` D3 finding) — `xmllint` cited in normative AC; auditor flagged D5 "External Linter Dependencies" MEDIUM. Resolved by pairing with `xml.etree.ElementTree` Python-stdlib fallback in the SAME AC body (AC-24). D5 finding cleared on next re-score.

User backlog item from prior `next` cycles labeled this as "L29-F sub-pattern" (Section F of `mem://process/phase-153-lessons` is the audit-corpus module-kind block; this is structurally a Lesson #36 sub-pattern, codified inline at Section G).

## Rule (codified inline in `mem://process/phase-153-lessons` Section G as Lesson #73)

When a normative AC cites an external OS binary, it MUST also cite a stdlib fallback in the SAME AC body:

1. Grep §97 for binary names (`xmllint`, `jq`, `dot`, `mmdc`, `git`, `curl`, `openssl`, `awk`, `sed`, `grep`).
2. Pair each with a stdlib alternative (Python `xml.etree`/`json`/`hashlib`, Go `encoding/xml`/`encoding/json`, Node `fs`/`crypto`).
3. Specify dispatch contract: `which <name>` POSIX / `where.exe` Windows; on absence, fall back to stdlib path with byte-identical output for GWT examples.
4. Cross-reference Lesson #36: stdlib fallback IS the canonical anchor; OS binary is the perf optimization.

## Why "sub-pattern" of Lesson #36

Lesson #36 (link-don't-restate) governs the *cross-module documentation axis* — module B links to module A's contract instead of restating it. Lesson #73 governs the *runtime-dependency axis* — the AC's stdlib fallback IS the canonical execution path; the OS binary is an optional performance hint. Both lessons share the structural pattern: **the canonical surface MUST be unambiguous and singular**, with downstream references (cross-module link in #36, OS-binary call in #73) treated as derived/optional.

## Exclusion

ACs whose entire purpose IS the binary (e.g., "verify `git` binary is installed") do NOT need this pattern — there is no stdlib `git`. Declare the binary as a hard prerequisite in §00 and let the auditor score it as expected-external.

## Files changed

- `mem://process/phase-153-lessons` — Section G (Cross-Module Roll-ups) extended with Lesson #73 between #38 and the Cross-references block; reverse-index row #73 appended.

## Cross-references

- Lesson #36 — parent pattern (link-don't-restate, cross-module documentation axis).
- Lesson #38 — sibling sub-pattern (slug validation before sweep, link-target axis).
- AC-24 (`spec/26-gitlogs-diagrams/97-acceptance-criteria.md`) — sole precedent.

## Lockstep

None — pure docs work in `mem://`. No `spec/` files touched.

## Forward sweep candidates

A future tree-wide sweep (deferred — no current `next` priority) could grep all 23 modules' §97s for unpaired OS-binary citations. Likely candidates:
- `spec/13-generic-cli` — `git`, `curl` likely cited in install/update flows.
- `spec/14-update` — `tar`, `unzip` likely cited.
- `spec/15-distribution-and-runner` — `chmod`, `chown` likely cited.
- `spec/16-generic-release` — `sha256sum`, `gpg` likely cited.

Do NOT open a sweep phase without first grep-confirming candidates AND verifying each cited binary doesn't already have a §00 prerequisite declaration (Lesson #30: verify before opening).
