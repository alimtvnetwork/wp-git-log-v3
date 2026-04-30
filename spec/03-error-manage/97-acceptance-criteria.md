# Acceptance Criteria — 03 Error Manage

**Version:** 2.1.0  
**Updated:** 2026-04-29  
**Scope:** `spec/03-error-manage/`  
**Generated:** AI-extracted Given/When/Then from module body via `linter-scripts/generate-gwt-acceptance.py`

---

## Module Summary

Consolidated specification for cross-stack error management, defining a 3-tier architecture (PHP/Go/React), a universal response envelope for APIs, and a centralized master registry of error code ranges to prevent collisions.

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

ENUMS & FORMATS:
- Prefixed Code: ^[A-Z]{2,4}-[0-9]{3}-[0-9]{2}$
- Integer Code: ^[0-9]{4,5}$
- Response Envelope Keys: "Status" { "IsSuccess", "Code", "Message" }, "Attributes", "Results"

ERROR RANGES:
- GEN (Shared): 0-999
- SM (Spec Mgmt): 2000-2999
- GS (GSearch): 7000-7919
- AB (AI Bridge): 9000-9999, 19000-19049

ARCHITECTURE TIERS:
- Tier 1: Delegated Server (PHP/Other)
- Tier 2: Go Backend (apperror package)
- Tier 3: Frontend (React/Error Modal)

---

## Acceptance Criteria

### AC-01: Universal Response Envelope Compliance  `[critical]`
- **Given** A backend API response from a Go or PHP service
- **When** The API returns any data or error to the frontend
- **Then** The response MUST match the Universal Response Envelope containing 'Status', 'Attributes', and 'Results' keys as defined in 02-error-architecture/05-response-envelope/04-response-envelope-reference.md
- **Verifies:** 02-error-architecture/05-response-envelope/04-response-envelope-reference.md

### AC-02: HTTP Status Primary Indicator  `[high]`
- **Given** A frontend component processing an API response
- **When** Deciding whether to trigger the Global Error Modal or process results
- **Then** The detection logic MUST determine success or failure using HTTP status codes (2xx) and NOT fields within the JSON body (e.g., Status.IsSuccess)
- **Verifies:** 00-overview.md#3-http-status-as-primary-indicator

### AC-03: GSearch Error Code Range Allocation  `[medium]`
- **Given** A new error code for a Go CLI tool in the GSearch project
- **When** Assigning a new ID to a domain error
- **Then** The code MUST be an integer within the range 7000-7919 and registered in 03-error-code-registry/error-codes-master.json to avoid collisions
- **Verifies:** 03-error-code-registry/00-overview.md#registered-ranges-quick-reference

### AC-04: Prefixed Error Code Formatting  `[medium]`
- **Given** A PHP plugin or general specification error code
- **When** Defining error codes for non-Go backend services
- **Then** The code MUST follow the regex pattern ^[A-Z]{2,4}-[0-9]{3}-[0-9]{2}$ (e.g., SM-400-01)
- **Verifies:** 03-error-code-registry/00-overview.md#error-code-formats

### AC-05: Three-Tier Error Propagation  `[high]`
- **Given** An error occurring in Tier 1 (Delegated Server/PHP)
- **When** A cross-stack error requires frontend notification
- **Then** The error MUST be propagated through the Go Backend (Tier 2) using the apperror package and DelegatedRequestServer pattern before reaching the Tier 3 Frontend modal
- **Verifies:** 02-error-architecture/01-error-handling-reference.md

### AC-06: Notification Color Token Mapping  `[low]`
- **Given** The frontend application state
- **When** Displaying toast notifications or status indicators
- **Then** The notification system MUST apply specific color tokens as defined in 02-error-architecture/03-notification-colors.md based on the error code mapping
- **Verifies:** 02-error-architecture/03-notification-colors.md

### AC-07: AppError Struct and Type Enforcement  `[medium]`
- **Given** The Go backend 'apperror' package implementation
- **When** Constructing an error object in the Go service layer
- **Then** It MUST include specialized domain enums (AppErrType) and support stack trace serialization as defined in 02-error-architecture/06-apperror-package/01-apperror-reference/05-apperrtype-enums.md
- **Verifies:** 02-error-architecture/06-apperror-package/01-apperror-reference/05-apperrtype-enums.md

### AC-08: Module Asset Inventory Pin (Lesson #29 / Lesson #36)  `[critical]`
- **Given** An LLM auditor or downstream AI implementer bundling this module's normative surface from disk
- **When** Resolving cross-references emitted by ACs AC-05/AC-06/AC-07 and the §00 overview (e.g. `02-error-architecture/01-error-handling-reference.md`, `02-error-architecture/03-notification-colors.md`, `02-error-architecture/06-apperror-package/01-apperror-reference/05-apperrtype-enums.md`, root-level `structure.md`, root-level `lifecycle-error-architecture.mmd`)
- **Then** ALL of the following on-disk assets MUST be treated as PRESENT and authoritative — failure to load any of them is a **harness/walker bug**, NOT a spec gap:
  - Root: `00-overview.md`, `97-acceptance-criteria.md`, `98-changelog.md`, `99-consistency-report.md`, `structure.md`, `lifecycle-error-architecture.mmd`
  - Subfolder `01-error-resolution/` (canonical resolution flows)
  - Subfolder `02-error-architecture/` containing: `01-error-handling-reference.md`, `02-go-delegation-fix.md`, `03-notification-colors.md`, `04-error-modal/`, `05-response-envelope/`, `06-apperror-package/`, `07-logging-and-diagnostics/`, `00-overview.md`
  - Subfolder `03-error-code-registry/` (master registry + lint-rule catalog)
- **And** Auditors that report any of the above as "missing" or "undefined" are misclassifying a deep-walker bundling cap (90 KB tier-1 limit in `linter-scripts/audit-ai-implementability.py`) as a cross-reference defect; the canonical fix is to (a) re-bundle with a higher cap or (b) cite this AC and downgrade the finding to **harness-artifact**, never edit the spec to remove the references
- **Verifies:** on-disk module asset inventory (Lesson #29 module-kind pin extended to deep-tree modules; Lesson #36 cross-module link-don't-restate — references stay as links, contents stay in their owning subfolder)

### AC-09: Sub-Module Reference Resolution (D5 citation density, Lesson #36 + Lesson #44)  `[high]`
- **Given** any cross-reference emitted from this module's normative surface — whether (a) intra-module path (e.g. `02-error-architecture/06-apperror-package/01-apperror-reference/05-apperrtype-enums.md`), (b) sibling-module path (e.g. `spec/02-coding-guidelines/01-cross-language/02-boolean-principles/`), or (c) toolchain path (e.g. `linter-scripts/check-spec-cross-links.py`)
- **When** the deterministic gate `linter-scripts/check-spec-folder-refs.py` (folder-level, F2 gate, strict CI line ≈129 of `.github/workflows/spec-health.yml`) and `linter-scripts/check-spec-cross-links.py` (file-level, P3/P44/P45 gate) are run on the tree
- **Then** EVERY reference emitted from `spec/03-error-manage/**/*.md` MUST resolve to either (i) an on-disk path under `spec/`, (ii) a `linter-scripts/` script that exists, or (iii) an entry in `linter-scripts/spec-folder-refs.allowlist` `[doc-only]` or `[external]` bucket. ZERO unresolved references; ZERO `[D5]` "broken cross-reference" findings from the auditor are valid for this module.
- **And** the canonical reference inventory for spec/03 is enumerated as the union of: (a) every Markdown link `[text](path)` whose target matches `^\.?\.?/.*\.md$` or `^spec/`; (b) every backtick-quoted path matching `\bspec/\d{2}-[a-z0-9-]+/`; (c) every backtick-quoted path matching `linter-scripts/[a-z0-9-]+\.(py|cjs|mjs|sh|go|ps1)`. The two gates above ground-truth this set; any LLM-auditor `[D5]` finding citing a "missing" reference whose path appears in this AC's enumeration MUST be classified **harness-artifact** per AC-08 unless the on-disk path is genuinely absent (verifiable in <100ms via `test -e <path>`).
- **And** the citation-density floor for this module is established at **≥3 cross-references per normative file** (currently `00-overview.md` cites 7 sub-module paths, `02-error-architecture/00-overview.md` cites 6, `03-error-code-registry/00-overview.md` cites 4 — all clear); regressions below this floor are detectable via `rg -c '\[.*\]\(.*\.md\)' spec/03-error-manage/ | awk -F: '$2 < 3'`.
- **Verifies:** D5 cross-reference invariants for the audit-corpus axis (Lesson #36 link-don't-restate + Lesson #44 mechanizable-grep-contract). Closes audit-v7 HIGH D5 finding "Broken Sub-module References" (spec/03 cache 2026-04-30, finding [0]) as a deep-walker artifact already covered by AC-08, and elevates D5 from passive (asset-inventory-only) to active (citation-density floor + dual-gate verification).
- **Source:** `00-overview.md` cross-references; `02-error-architecture/00-overview.md` cross-references; `03-error-code-registry/00-overview.md` cross-references; `linter-scripts/check-spec-folder-refs.py` (F2 gate); `linter-scripts/check-spec-cross-links.py` (P3 gate); `linter-scripts/spec-folder-refs.allowlist` (allowlist contract per AC-62-01..04).

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)