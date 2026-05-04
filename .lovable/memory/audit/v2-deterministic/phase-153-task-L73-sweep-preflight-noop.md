---
phase: 153
task: L73-sweep-preflight
date: 2026-05-04
status: CLOSED no-op (Lesson #30 verify-before-open)
gates: docs-only · no spec edits · no lockstep ripple
---

# L73-sweep pre-flight — no-op closure

## Trigger

Prior `next` queued an L73-sweep across `spec/{13,14,15,16}/97` to pair OS-binary citations with stdlib fallbacks per the newly-codified Lesson #73.

## Pre-flight findings

Grep across 4 candidate §97s (regex: `\b(xmllint|jq|dot|mmdc|git|curl|openssl|tar|unzip|gpg|sha256sum|chmod|chown|awk|sed|grep)\b`):

| Module | Cache score | D5 findings | Binary cites | Classification |
|---|---:|---:|---:|---|
| spec/13-generic-cli | 93 EXCELLENT | 0 | ~6 | deliverable-runtime (jq for output validation, curl for install) |
| spec/14-update | 91 EXCELLENT | 0 (1H is D2) | ~12 | deliverable-runtime (signtool/codesign/spctl/gpg/sha256sum/curl — all install-script plumbing) |
| spec/15-distribution-and-runner | 93 EXCELLENT | 0 | ~5 | deliverable-runtime |
| spec/16-generic-release | 98 EXCELLENT | 0 | ~5 | deliverable-runtime |

## Verify-before-open analysis (Lesson #30)

All 4 modules are at EXCELLENT band with **zero D5 "External Linter Dependencies" findings** in `.lovable/cache/audit-ai/*.json`. The auditor correctly does NOT flag deliverable-runtime binaries as D5 dependencies — D5 targets *spec-authoring tools* (linters, validators run during spec maintenance), not *runtime commands the user invokes after installing the deliverable*.

## Lesson #73 scope clarification

Re-reading Lesson #73 with this evidence: the precedent (AC-24 in `spec/26-gitlogs-diagrams`) cites `xmllint` as part of an **audit harness** that validates `.mmd` files during spec authoring. That IS a spec-authoring-tool dependency — without a stdlib fallback, the auditor cannot run the validation step on a stripped-down CI image, hence the D5 hit.

By contrast:
- `signtool` in spec/14 AC-09 is a **deliverable runtime command** the user's release pipeline runs to ship the binary. The spec doesn't authoritatively run signtool itself; it requires the implementer to.
- `jq` in spec/13 AC validates that the deliverable's `--format=json` output is parseable. Again, this is a contract about the deliverable, not a spec-authoring tool.

**Lesson #73 narrowed:** applies to ACs whose *spec-authoring or spec-CI* harness invokes an external binary, NOT ACs that contract on a *deliverable's runtime use* of a binary.

## Action

No spec edits. No new ACs. Close as no-op.

## Forward-looking guard

Append a "When NOT to apply (stronger form)" note to Lesson #73 covering this distinction. Deferred to a future docs-only follow-up to keep this memo single-purpose.

## Cross-references

- Lesson #30 — verify-before-open (correctly applied here; saved authoring 30+ ACs against zero findings)
- Lesson #75 — zero-finding tree means no actionable backlog
- Lesson #72 — saturation pre-flight (would have triggered on spec/14 §97 at 49 KB)
- Lesson #73 — precedent unchanged (AC-24 spec/26 xmllint remains the canonical case)

## Lockstep

None.
