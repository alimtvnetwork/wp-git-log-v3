# Phase 153 — Laravel Binding (slot 40): Confidence & Delivery-Risk Analysis

**Date:** 2026-06-21
**Scope:** `spec/22-git-logs-v2/40-laravel-endpoint-definition.md` v1.0.0 + AC-80
**Author:** Lovable AI (self-audit, no LLM gateway call this turn)
**Overall confidence:** ~88 % (see breakdown below)
**Why this memo exists:** User asked for an explicit, detailed enumeration of every reason the Laravel binding could under-deliver at implementation time. This is the canonical risk register for slot 40; future implementer-facing decisions or AC additions SHOULD cite a row by ID (`LBR-NN`) instead of restating the risk.

---

## 1. Confidence Breakdown by Surface

| Surface | Confidence | Why high / Why not 100 % |
|---|---:|---|
| File-slot mechanics (slot 40 immutability, §00 + §99 inventory, §98 row, h10 stamp) | 99 % | Mechanically verified against `check-tree-health.cjs --strict` + `check-lockstep.cjs` + `check-version-parity.py`. Failure mode here would be a script bug, not a spec bug. |
| Lesson #36 anchor table (8 rows, every contract a link not a copy) | 95 % | Hand-audited. Residual 5 % = an anchor could go stale if the linked file is renumbered (no slot has ever been renumbered post-ship per Core memory, but the failure class exists). |
| AC-80 GWT shape + binding into §97 | 92 % | AC follows AC-79 sibling pattern exactly. 8 % residual = AC-80 is a *parity-AC* (binding-only) and parity-ACs have historically needed mechanical-lock graduation (Lessons L21 / Phase P47–P49) once an implementer surface appears. |
| Routing layout (`/api/git-logs/v2` prefix, middleware aliases `gl.lane-a` / `gl.lane-b` / `gl.permission:*`) | 85 % | Matches §04 verbatim modulo the `/wp-json` → `/api` prefix, which is the only framework-mandated divergence. Residual 15 % below in LBR-01..LBR-04. |
| FormRequest + `GlValidationException` mapping to §15 codes | 80 % | Mapping table is *internal to the exception class* and not enumerated in this spec. Implementer must derive it from §15. See LBR-05. |
| Controller signatures (invokable, constructor-injected, no facades) | 90 % | PSR-12 + spec/02 PHP rules cited; signatures shown are skeletons. Residual = service-layer split (LBR-07). |
| Raw-PDO-on-ingest / Eloquent-on-read split | 75 % | "Why" paragraph cites spec/13 §97 AC-22 locking + latency. Residual 25 % is the largest single risk: no locked decision pins this split (LBR-06). |
| Sanctum as Lane A auth | 70 % | Stated in the middleware table row but never codified as a locked decision in `07-app-entity.md`. Future implementer could pick Passport / WP-cookie-bridge / custom and still be technically conformant. See LBR-08. |
| Migration posture (Laravel `artisan migrate` disabled; framework-agnostic SQL files) | 70 % | One sentence in §6. No worked example, no migrator binding contract, no rollback story. See LBR-09. |
| Test-harness story | 50 % | Not addressed at all in slot 40 (Pest / PHPUnit / Dusk choice; fixture strategy; SQLite `:memory:` vs file). See LBR-10. |

Aggregate: **~88 %** weighted by AC surface area, dominated by the high-confidence structural rows. The *delivery* confidence (i.e. an implementer can build this without coming back to ask) is lower — closer to **~70 %** — because of the rows below.

---

## 2. Delivery-Risk Register

Each row is a concrete reason the Laravel binding might under-deliver, mis-deliver, or require a follow-up phase to clarify. IDs are stable; cite as `LBR-NN`.

### LBR-01 — `/api` prefix divergence is asserted, not contracted
- **Severity:** LOW
- **Where:** §2 route layout
- **Risk:** §04 (WordPress binding) uses `/wp-json/git-logs/v2`. Slot 40 says Laravel uses `/api/git-logs/v2`. The transformation rule ("strip `/wp-json`, prepend `/api`") is asserted in prose but not bound to an AC. A future Symfony / Slim / Lumen binding will have to re-derive the rule.
- **Mitigation:** Lift the prefix-mapping rule into an AC-family table on `04-rest-api-endpoints.md` (one row per framework). Defer until a third binding (slot 41) actually appears — premature abstraction now.

### LBR-02 — Middleware aliases are not in spec/02 PHP naming conventions
- **Severity:** LOW
- **Where:** §5 middleware table (`gl.lane-a`, `gl.lane-b`, `gl.permission:{Perm}`)
- **Risk:** spec/02 PHP guidelines do not currently mandate middleware-alias naming (kebab vs dot vs colon-parameter). Implementer could pick `gl:lane-a` or `gitlogs.laneA` and still be PSR-12 compliant.
- **Mitigation:** Add a one-row table in spec/02 PHP route-binding conventions. Out of scope for slot 40.

### LBR-03 — Permission parameter syntax (`gl.permission:HistoryView`) is Laravel-specific
- **Severity:** LOW
- **Where:** §5 row 2
- **Risk:** The `:Param` colon-parameter syntax is a Laravel middleware idiom. A non-Laravel reader could mis-interpret it as a YAML key or a Pest annotation.
- **Mitigation:** Acceptable — the file is explicitly the *Laravel* binding, so Laravel idioms are expected. No action.

### LBR-04 — `?q=<base64-json>` collapse resolver is one sentence
- **Severity:** MEDIUM
- **Where:** §2 closing paragraph
- **Risk:** §04 defines the 10-logical→8-path `?q=` collapse rule in detail (which 2 endpoints collapse, what JSON keys are required, how to error on malformed base64). Slot 40 says "Laravel binds it via a `?q=<base64-json>` query string resolver inside each Lane A FormRequest" — one sentence. An implementer could put the resolver in a global middleware, a trait, a base FormRequest, or duplicate it per request. All four are conformant.
- **Mitigation:** Add a §3.1 sub-section showing the resolver skeleton (probably a base FormRequest class with a `resolveQParam(): array` method). Defer to the first follow-up phase when a Laravel package author opens an issue.

### LBR-05 — `GlValidationException` ⇄ §15 code mapping is internal, not enumerated
- **Severity:** MEDIUM
- **Where:** §3 step 3
- **Risk:** Slot 40 says "Mapping table is internal to the exception class; this file does not restate codes." This is correct per Lesson #36 (no restatement) but leaves a gap: *how* does the exception know which §15 code to emit for which validation rule failure? Three plausible designs (rule-name → code lookup, FormRequest property `protected array $errorCodes`, annotation-based) all conformant.
- **Mitigation:** Either (a) add an AC requiring a `protected array $errorCodes` property on every FormRequest with one entry per `rules()` key, OR (b) add a `[mapping]` AC in §15 that defines the canonical rule-name → code lookup for every framework. (b) is the better long-term answer; defer until a downstream package surfaces the choice.

### LBR-06 — Raw-PDO-on-ingest split is not a locked decision  ⚠ HIGHEST RISK
- **Severity:** HIGH
- **Where:** §6 "Why raw PDO (not Eloquent) on ingest paths"
- **Risk:** The split ("raw PDO for ingest, Eloquent OK for Lane A reads") is asserted in §6 prose with a latency + locking justification. It is NOT a locked decision in `07-app-entity.md` and NOT bound to an AC. A future implementer who values Eloquent's developer ergonomics over the 3–8× latency cost could legally pick "Eloquent everywhere" and still pass every AC currently in §97 — because no AC says "ingest paths MUST NOT use Eloquent". They would then break spec/13 §97 AC-22's `BEGIN IMMEDIATE` + retry-with-jitter contract *silently* (Eloquent's transaction helper does not expose `BEGIN IMMEDIATE`; you have to drop to `DB::statement` anyway, defeating the ergonomic argument — but the implementer might not discover this until production load).
- **Mitigation (recommended):** Author a locked decision in `07-app-entity.md` pinning "ingest paths MUST use raw PDO; Eloquent permitted on Lane A read paths bounded by pagination". Bind as a new `[critical]` AC in §97 (call it AC-81). This is the **single highest-leverage follow-up** for slot 40.

### LBR-07 — `LogIngestService` interface is named but not signed
- **Severity:** MEDIUM
- **Where:** §4 controller skeleton
- **Risk:** The skeleton injects `LogIngestService`, `ShaRegistryRepository`, `SplitDbWriter`. None have a defined interface. Implementer must derive method signatures from §04 + §39 prose.
- **Mitigation:** Add a §4.1 "Domain Service Contracts" sub-section with `interface` declarations (no bodies). Defer until first downstream package needs it.

### LBR-08 — Sanctum vs Passport vs custom is undecided
- **Severity:** MEDIUM
- **Where:** §5 row 1 ("Sanctum bearer token in Laravel binding")
- **Risk:** Same class of risk as LBR-06: stated in prose, not locked. Sanctum is a reasonable default but Passport's full OAuth2 + token-scope model maps better onto §19's permission matrix. A future Laravel package author who picks Passport would still be conformant.
- **Mitigation:** Author a locked decision in `07-app-entity.md` pinning Sanctum + the rationale (lightweight bearer-token; full OAuth2 not required for App-Password-equivalence). Bundle with LBR-06 in the same follow-up phase.

### LBR-09 — Migration posture lacks a worked example
- **Severity:** MEDIUM
- **Where:** §6 last row
- **Risk:** One sentence says "framework-agnostic SQL files executed by the same migrator as the WP binding". No example of the migrator interface, no rollback contract, no `artisan migrate:status`-equivalent, no story for how a Laravel dev runs migrations in a fresh dev env without Laravel's built-in migrator.
- **Mitigation:** Cross-reference `06-migrations-and-logger.md` more concretely (cite the migrator's CLI surface). Add a one-paragraph "Laravel developer workflow" sub-section. Defer.

### LBR-10 — Test-harness story is absent
- **Severity:** MEDIUM
- **Where:** Nowhere — gap
- **Risk:** No mention of Pest vs PHPUnit choice, no `:memory:` SQLite vs file-DB fixture guidance, no story for testing the `BEGIN IMMEDIATE` retry-with-jitter contract under simulated lock contention, no Dusk-equivalent for the streaming NDJSON ingest path. Implementer will improvise.
- **Mitigation:** Add a §10 "Testing" section. At minimum: (a) Pest preferred per modern Laravel community default, (b) `:memory:` SQLite for unit, file-DB for integration so WAL/locking is real, (c) reference test for AC-22 retry-with-jitter using `pcntl_fork` or a thread-pool helper. Defer to first follow-up phase.

### LBR-11 — Streaming (NDJSON) ingest has no Laravel-specific guidance
- **Severity:** LOW
- **Where:** §1 anchor table row 8 (Phase P2 streaming)
- **Risk:** Anchor row says "Streaming (NDJSON) ingest wire format" lives in §04 §1.1. True, but Laravel's response/request streaming APIs (`StreamedResponse`, `Symfony\HttpFoundation`'s `Request::getContent(stream: true)`) have specific gotchas (memory limits, output buffering, FastCGI flush behaviour). Slot 40 is silent on these.
- **Mitigation:** Add a §11 "Streaming Ingest (Laravel-specific)" sub-section once Phase P2 streaming actually ships in §04. Currently P2 is itself future-spec; defer.

### LBR-12 — No conformance test script anchors AC-80
- **Severity:** MEDIUM (graduation-class)
- **Where:** AC-80 in §97
- **Risk:** Per Lesson L21 / Phase P47–P49 graduation chain, every parity-AC eventually needs a mechanical-lock self-test in `linter-scripts/test/`. AC-80 currently has none. It is verifiable by hand against §04, but a CI gate would prevent silent drift if §04's endpoint inventory changes without AC-80 being re-audited.
- **Mitigation:** Author `test-ac80-laravel-wp-endpoint-parity.sh` that parses §04 + slot 40 route declarations and asserts 1:1 verb+path parity (modulo prefix). Slot it in `linter-scripts/test/`. This is the standard "graduate parity-AC to mechanical lock" follow-up; defer to a P49-style phase.

### LBR-13 — Cache (`.lovable/cache/audit-ai/22-git-logs-v2.json`) does not reflect slot 40 or AC-80
- **Severity:** LOW (cosmetic, gateway-blocked)
- **Where:** Cache `total=82`, score from prior bundle (`files_used: 3` — does not include slot 40)
- **Risk:** Cache is the source of truth for the AI-implementability scorecard in §00. It will read stale until either (a) the LLM gateway re-runs the audit (currently 403-blocked per task-counter LBR rollup), or (b) a fresh `--force` re-score is performed when the gateway unblocks (per Lesson #38).
- **Mitigation:** Run `python3 linter-scripts/audit-ai-implementability.py --only 22-git-logs-v2 --force` on next gateway-up session. Acceptable until then; deterministic gates remain GREEN.

### LBR-14 — `axis: framework-binding` is a new axis tag with no axis_multipliers entry
- **Severity:** LOW
- **Where:** Front-matter line `content_axis: framework-binding`
- **Risk:** `audit-ai-implementability.py`'s `axis_multipliers` table currently has `normative-contract`, `audit-corpus`, etc. A new `framework-binding` axis tag could either default-fall-through to `normative-contract` (probably correct — slot 40 IS normative) or get an unintended multiplier set. Behaviour is untested.
- **Mitigation:** When LBR-13 re-runs the audit, verify the JSON output's `axis` and `axis_multipliers` fields. If a new axis kind needs different weights (e.g. binding files probably should down-weight D2 AC-coverage since AC-80 is a single binding-AC by design), author one in the rubric. Defer.

### LBR-15 — `pairs with` / `sibling of` are new front-matter keys
- **Severity:** LOW
- **Where:** File header
- **Risk:** The `**Pairs with:**` and `**Sibling of (downstream):**` bolded labels are prose, not YAML front-matter. Future linter that wants to walk binding relationships (e.g. "find every sibling of §04") cannot parse them.
- **Mitigation:** If/when a binding-graph linter is needed, lift these into YAML keys. Probably wait until slot 41 (Symfony) ships so the pattern is exercised twice.

---

## 3. Categorisation

| Class | Count | LBR IDs | When to address |
|---|---:|---|---|
| **HIGH** — could silently break upstream contract | 1 | LBR-06 | Open a follow-up phase NOW; recommend bundling with LBR-08 |
| **MEDIUM** — implementer needs to guess; resulting code still passes ACs but quality varies | 7 | LBR-04, LBR-05, LBR-07, LBR-08, LBR-09, LBR-10, LBR-12 | Defer to first downstream Laravel package author opening an issue, OR to a "polish slot 40" phase |
| **LOW** — cosmetic, future-proofing, or framework-idiom | 7 | LBR-01, LBR-02, LBR-03, LBR-11, LBR-13, LBR-14, LBR-15 | Defer indefinitely; address when triggered by another binding or by gateway-unblock |

---

## 4. Recommended Next Phase (if user picks one)

**P153-LBR06+08 — Lock the framework choices that the binding currently asserts only in prose.**

Single phase, two locked decisions in `07-app-entity.md`:
1. **Locked decision 13 (Laravel persistence posture):** "Ingest paths in the Laravel binding (slot 40) MUST use raw PDO. Eloquent is permitted only on Lane A read paths bounded by pagination."
2. **Locked decision 14 (Laravel Lane A auth):** "Lane A authentication in the Laravel binding (slot 40) MUST use Laravel Sanctum bearer tokens. Passport, custom token brokers, and WP-cookie-bridge are forbidden."

Bind each as a `[critical]` AC in §97 (AC-81, AC-82). Lockstep: §97 minor + §00/§98/§99 patch + slot 07 patch (new locked decision rows). Tree-health 168/168 strict + version-parity 74/74 must remain GREEN.

This closes the single HIGH-severity row (LBR-06) plus the highest-confidence MEDIUM (LBR-08), lifting slot 40 delivery confidence from ~70 % to ~85 %.

---

## 5. Cross-References

- AC-79 (WordPress binding parity) — pattern source for AC-80
- AC-80 (Laravel binding parity) — this memo's subject
- Lesson #36 — cross-module link-don't-restate (applied throughout slot 40)
- Lesson #37 — integration-axis modules co-apply Lessons #19 + #36
- Lesson #38 — re-probe LLM gateway before deferring (relevant to LBR-13)
- Lesson L21 + Phase P47–P49 — parity-AC mechanical-lock graduation (relevant to LBR-12)
- `07-app-entity.md` locked-decision register — destination for LBR-06 + LBR-08 mitigation
- spec/13 §97 AC-22 — concurrency contract that LBR-06 protects
- spec/03-error-manage envelope — referenced by LBR-05
- spec/04-database-conventions §2.1 — referenced by §6 boolean storage row
- spec/15 (`GL-*` codes) — referenced by LBR-05

---

## 6. Closing

Slot 40 ships as a structurally clean, lockstep-compliant binding with a high-fidelity anchor table. Its delivery-risk profile is dominated by **two unlocked framework choices** (LBR-06 persistence posture, LBR-08 auth driver) and a cluster of MEDIUM-severity *implementer-ergonomics gaps* (LBR-04/05/07/09/10/12) that will become visible only when a downstream Laravel package author actually attempts the build. None of the LOW rows block delivery. The cache row (LBR-13) is a known gateway-blocked refresh and should be re-run per Lesson #38 the next time `LOVABLE_API_KEY` is confirmed live.
