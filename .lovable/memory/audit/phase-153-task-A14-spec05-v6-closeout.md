---
phase: 153
task: A14
date: 2026-04-30
status: CLOSED
---

# Phase 153 Task A14 — spec/05 v6 audit close-out

## Findings (verify-before-open per Lesson #30)
1. **D5 HIGH** AC-CL-* cross-ref — walker-bias (Lesson #36 link-don't-restate); needs AC-AI-style harness pin.
2. **D3 MEDIUM** AC-SD-22 polyglot pseudo-code — genuine.
3. **D1 LOW** ProjectSlug binding — genuine.

## Resolution
- **AC-SD-22** extended in-place with language-agnostic retry pseudo-code + per-language driver mappings (PHP/Rust/C#/TS/Python).
- **AC-SD-24 (NEW `[critical]`)** — cross-module link-don't-restate harness pin per Lesson #36; mirror of 13-module pattern.
- **AC-SD-25 (NEW `[high]`)** — `{ProjectSlug}` ↔ Root DB `Project.Slug` byte-equal binding + slug derivation + immutability + UNIQUE NOT NULL + defense-in-depth regex.

## Lockstep
§97 v4.2.0 → **v4.3.0**; §00/§98 v4.2.0 → **v4.3.0** (matched §97 minor per Lesson #25 — version-parity gate fired mid-phase, fixed in-flight); §99 v4.0.2 → **v4.0.3** + Generated date 2026-04-29 → 2026-04-30 (lockstep L1 fired mid-phase, fixed in-flight).

## A8 re-score
**spec/05: 89 → 89** (D2 +1, D3 +2, D4 −3, D5 unchanged). Net **0**.

D5 finding still flagged despite AC-SD-24 harness pin — confirms LLM rubric does not honor module-kind/cross-ref pins for D5 dim, mirror of audit-corpus 75-floor (Lesson #29). D4 −3 is honest-baseline correction (Lesson #18) — auditor now sees more bundled content with prior issues unmasked. **Contract closure complete; score ceiling structural pending v7 rubric.**

## Lessons reinforced
- **Lesson #29 generalised**: Cross-module-ref harness pins (AC-SD-24 class) also do NOT move LLM scores, just like audit-corpus pins (AC-AI-09/10/11). Both are contract-correct and audit-rubric-invisible. Future contributors MUST NOT chase score lift via additional cross-ref pins — the contract is the ceiling.
- **Lesson #25**: §97 minor bump triggered version-parity FAIL because §00/§98 stayed at patch (4.2.1) — initial decision wrong; Lesson #25's "child slot got new content → patch parent" applies to CHILD slot edits, NOT same-module §97 minors. Same-module §97 minor → §00/§98 minor MUST match.
- **Side-fix discipline**: 2 lockstep findings (L1 §99 stale + version-parity §00 behind) caught + fixed in-phase per "always inspect first lockstep run".

## Gates (all GREEN)
lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · freshness 81+6+0 · folder-refs 0
