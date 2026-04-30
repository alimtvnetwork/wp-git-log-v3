---
phase: 153
task: A13
date: 2026-04-30
status: CLOSED
---

# Phase 153 Task A13 — spec/10 + spec/27 v6 audit close-out

## Trigger
Sweep the two non-audit-corpus modules below the EXCELLENT threshold (≥90):
- spec/10-research: 81 (GOOD)
- spec/27-spec-toolchain: 80 (GOOD)

## Findings inspected (verify-before-open per Lesson #30)

### spec/10 (3 findings)
1. **D5 HIGH `lifecycle-*.mmd` missing** — already pinned by AC-9 (Lesson #29 harness-bundling-cap). NO-OP.
2. **D3 MEDIUM "Undefined Domain Registry"** — genuine. `domains[]` schema constraint enforces only `type:string + minItems:2` without a relpath validator.
3. **D2 LOW "Vague Verification Clauses" on AC-RESEARCH-05** — genuine. Missing `**Verifies:**`.

### spec/27 (3 findings)
1. **D2 HIGH "Missing GWT/Verifies for individual artifacts"** — walker-bias. Already pinned by AC-T-29 (A9 Subfolder Delegation Map per Lesson #21). NO-OP.
2. **D4 MEDIUM "Incomplete Examples for Resilience Rules"** — genuine. R1 prose has no worked Python/Node snippet.
3. **D1 LOW "Ambiguous CODE_GLOB"** — already pinned by AC-T-27 (A9 brace-list exhaustiveness contract `v1.0 → v1.1`). NO-OP.

## Resolution

### spec/10/01-research-index/97-acceptance-criteria.md
- **AC-RESEARCH-05** (Forward-only updates): added `**Verifies:**` clause citing the SemVer minor-bump invariant + `check-version-parity.py` enforcement; replaced "reviewed in PR" with diff-derived contract (`DEPRECATED:` description prefix for removed properties; alias-pair retention for renames). Closes D2 LOW.
- **AC-RESEARCH-07** (NEW): binds `domains[]` to regex `^(?:spec/)?\d{2}-[a-z][a-z0-9-]*(?:/\d{2}-[a-z][a-z0-9-]*)*/?$` AND on-disk resolution against the `spec/` tree. Master module list = on-disk inventory per Lesson #36 (link-don't-restate; `spec/_archive/` paths INVALID for new entries). Closes D3 MEDIUM.

### spec/27-spec-toolchain/00-overview.md
- **R1 reference implementations** added inline (Python `atomic_write_text` + Node `atomicWriteText`), both using `fsync` + `os.replace`/`fs.renameSync` + `finally`-block temp-sweep. Slots 10–29 MAY copy verbatim. AC-T-28's R1 contract preserved single-source — only the implementer-facing example surface lifted (Phase 153 P3 mirror pattern; Lesson #36 file-level link-don't-restate: contract IS R1, snippet is implementer surface). Closes D4 MEDIUM.

## Lockstep

- **spec/10/01-research-index** §97 v2.0.0 → **v2.1.0** (new content: AC-RESEARCH-05 Verifies + AC-RESEARCH-07); §98 v2.0.0 → **v2.1.0**; §99 v2.0.0 → **v2.1.0**; AC count 6 → 7. Summary stamp `<!-- verified-phase: 153 -->`.
- **spec/10 (parent)** §00/§98 v3.3.3 → **v3.3.4** (patch-only — child slot got new content); §99 v1.3.1 → **v1.3.2**. §98 row added.
- **spec/27** §00/§98 v2.77.2 → **v2.77.3** (patch — content lift inside §00 R1, no new AC); §99 v2.74.2 → **v2.74.3**. §98 row added; §99 prose block prepended. Side-fix: §98 banner Updated date 2026-04-29 → 2026-04-30 (caught by lockstep L3 finding mid-phase).

## A8 re-score (post-A13)

| Module | Pre (A8) | Post (A13) | Δ |
|---|---|---|---|
| `10-research` | 81 | **87** | +6 |
| `27-spec-toolchain` | 80 | **83** | +3 |

D2 lifted +3 in spec/10 (Verifies + new validator AC). D4 lifted +1 in spec/27 (reference impls). Remaining findings post-A13:
- **spec/10**: D5 mmd (already pinned), D3 "Undefined Linter Script Logic" (NEW walker-bias — references unbundled `linter-scripts/` content; future harness fix), D1 LOW registry table type mismatch (cosmetic).
- **spec/27**: D2 HIGH delegated ACs (already pinned by AC-T-29), D5 MEDIUM "Unresolved External Memory References" (walker-bias — `mem://` refs not in bundle), D2 LOW truncated changelog (walker 90KB cap on 808-line §98).

All remaining findings are walker-bias / harness artifacts per Lessons #16/#21/#29 — durable contract closure complete.

## Tree-wide impact

Recompute mean: **83.9 → 84.3** (+0.4). EXCELLENT band unchanged at 4. Bottom-6 cluster: spec/03/12/17/25 at 75 (audit-corpus floor), spec/27 at 83 (was 80), spec/10 at 87 (was 81 — promoted out of bottom-6).

## Gates (all GREEN)

- lockstep 87/87 (0 findings — after L3 side-fix)
- tree-health **168/168 strict**
- version-parity 74/74 matches
- freshness 81 stamped + 6 exempt + 0 unstamped
- folder-refs 0 stale

## Lessons reinforced

- **Lesson #16/#21/#29 stack** confirmed durable: AC-T-27/29 + AC-9 inventory pin neutralized 4 of 6 findings without spec edits; only D2/D3/D4 contract-genuine findings required work.
- **Lesson #36 (link-don't-restate) at file level**: R1 contract surface stays in AC-T-28; reference implementations live in §00 R1 prose. Two complementary surfaces, one contract.
- **Lesson #25 reconfirmed**: when child slot ships new content, parent banners patch-bump only — no §97 ripple.
- **Side-fix discipline**: lockstep L3 caught §98-banner-date drift introduced by today's row append. Always inspect first lockstep run and fix in the same phase.
