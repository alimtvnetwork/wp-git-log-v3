---
phase: 124
title: AC-20 cite-direction fix — §14 now dual-cites upstream §16 generic blueprint
mode: implementation
predecessor: phase-123-placeholder-token-catalog-shipped.md
successor: TBD (Phase 117 mechanization — all autonomous spec work complete)
date: 2026-04-27
---

# Phase 124 — §14 AC-20 cite-direction fix

## Background

Phase 121 originally proposed adding `**Defers-to:** §14` banners to §16
cross-compilation files. Phase 125 caught the inversion: §16 is `kind: future-spec`
(generic blueprint), §14 is the concrete consumer. Deference flows
**downstream-cites-upstream**: §14 → §16, never §16 → §14.

This phase resolves the reframed task: audit whether §14's GOOS/GOARCH AC-20
explicitly cites §16's `01-cross-compilation.md` as the generic source.

## Audit finding

**§14 AC-20 was incomplete.** Original text cited only the local sibling
`16-cross-compilation.md` (which is `spec/14-update/16-cross-compilation.md`,
a §14-internal restatement) and the local `17-release-pipeline.md`. It did NOT
cite the upstream generic `spec/16-generic-release/01-cross-compilation.md`,
even though §14/00-overview.md correctly cites `../16-generic-release/02-release-pipeline.md`
in the cross-references table.

The 6-target matrix (`linux/amd64`, `linux/arm64`, `darwin/amd64`, `darwin/arm64`,
`windows/amd64`, `windows/arm64`) and `CGO_ENABLED=0` discipline originate in
§16/01 ("Default Targets" table). §14 is the consumer.

## What changed

### Files touched

| File | Change |
|---|---|
| `spec/14-update/97-acceptance-criteria.md` | AC-20 `Given` line now references upstream `../16-generic-release/01-cross-compilation.md`; `Source` line dual-cites both upstream + local; "deviation MUST be justified in §99" clause added; §97 banner 2.0.0 → 2.1.0 |
| `spec/14-update/98-changelog.md` | v1.3.0 row appended; banner 1.2.0 → 1.3.0 |
| `spec/14-update/99-consistency-report.md` | Phase 124 audit row appended |

No content moved files; §16 untouched (it is correctly the upstream — only the
downstream cite was missing).

## AC-20 before/after (excerpt)

**Before:**
> - **Given** a build pipeline per `16-cross-compilation.md` and `17-release-pipeline.md`
> - **Source:** `16-cross-compilation.md`, `17-release-pipeline.md`, AC-08, §13 AC-13.

**After:**
> - **Given** a build pipeline per local `16-cross-compilation.md` and `17-release-pipeline.md`,
>   both of which MUST consume the upstream generic blueprint at
>   [`../16-generic-release/01-cross-compilation.md`](../16-generic-release/01-cross-compilation.md)
> - **Source:** local `16-cross-compilation.md`, local `17-release-pipeline.md`,
>   upstream generic [`../16-generic-release/01-cross-compilation.md`](../16-generic-release/01-cross-compilation.md), AC-08, §13 AC-13.

Plus a new clause in `Then`: any deviation from upstream target list / CGO
exemptions MUST be justified in local `16-cross-compilation.md` and recorded in §99.

## Backlog impact — all autonomous spec work complete

With Phase 124 closed, every autonomous catalog-and-cite task surfaced by Phases
116–127 is shipped:

| Pre-req task | Status |
|---|---|
| Phase 123 placeholder-token catalog (Candidate N) | ✅ Shipped |
| Phase 128 lint-rule catalog (Candidate O) | ✅ Shipped |
| Phase 124 §14 AC-20 cite to §16 | ✅ Shipped (this phase) |

Remaining items in the backlog are all **decisions for the user** or **Phase 117
mechanization** (which is itself a user-decision-gated build-out, not autonomous).

## Recommended next phases

| Phase | Action | Mode |
|---|---|---|
| **117** | Mechanize 8-candidate AC-31-31 backlog. All catalog pre-reqs satisfied; harness can cover full A+H+N+O containment + B+E+K+L parity. | 🚧 Decision |
| **122** | §17 OpenAPI: enumerate `GLCI-*` codes or leave code-free. | 🚧 Decision |
| **108** | Strategy choice for 8 §27 inventory orphans. | 🚧 Decision |
| **B1** | §22 §07 App identity columns. | 🚧 Decision |
| **R1** | Real-AI re-audit. | 🚧 Blocked (needs Lovable Cloud) |

## Completion certification

- ✅ AC-20 amended with bidirectional clarity (consumer → upstream)
- ✅ Lockstep: §97 banner + §98 v1.3.0 row + §99 audit row
- ✅ Lockstep gate passes (87/87 modules)
- ✅ §16 untouched (reframe-correct: cite direction is one-way downstream)
- ✅ Memory mirror updated (this memo)
- ✅ All autonomous spec work from Phases 116–127 backlog now complete
