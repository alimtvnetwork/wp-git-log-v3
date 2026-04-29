# Phase 153 Task #35-fu — `latest_release()` SemVer-max comparator fix

**Date:** 2026-04-29
**Outcome:** Comparator bug fixed and codified; 5 additional banner-bump wins. Real-tree FAILs went 17→20→15 across the two-step closure. Cumulative: 17→15 stamped FAILs (12 fewer than "ahead-by-N" remaining over Tasks #35 + #35-fu — but the SemVer-max comparator now reveals more honest mismatches than positional-first).

## Comparator change (`linter-scripts/check-version-parity.py`)

Before (positional-first):
```python
def latest_release(text):
    for line in text.split("\n"):
        m = RELEASE_HEADING_RE.match(line)
        if m: return m.group(1)
        m = RELEASE_ROW_RE.match(line)
        if m: return m.group(1)
    return None
```

After (SemVer-max):
```python
def _semver_key(v):
    parts = v.split(".")
    return (int(parts[0]), int(parts[1]), int(parts[2]))

def latest_release(text):
    versions = []
    for line in text.split("\n"):
        m = RELEASE_HEADING_RE.match(line)
        if m: versions.append(m.group(1)); continue
        m = RELEASE_ROW_RE.match(line)
        if m: versions.append(m.group(1))
    return max(versions, key=_semver_key) if versions else None
```

**Why SemVer-max is correct**: §00 banners universally track the SemVer-max
(banner-bump rule is "highest semantic release shipped"). Phase 153 sub-tasks
prepended SemVer-LOWER patch reconciliation rows (e.g. AC #29c/d/e/#31)
ABOVE older SemVer-HIGHER minor releases. The positional-first comparator
returned the prepended (lower) row → false-positive mismatch. SemVer-max
restores invariant.

## AC + self-test changes

- **New AC**: `AC-29-15` — codifies SemVer-max contract + Lesson #28 + worked example. Located in `spec/27-spec-toolchain/29-check-version-parity.md` after AC-29-14.
- **New test T14** in `linter-scripts/test/test-check-version-parity.sh`: builds §98 with row order ≠ SemVer order, asserts `matches=1`. Self-test 13/13 → **14/14**.
- **T2 + T9 sandboxed**: real tree is now 74/74 stamped, so any drift triggers per-file strict promotion → exit 1. T2's "default exits 0 with mismatches" + T9's "JSON parses cleanly" assertions both depended on real-tree exit 0. Switched to sandbox modules (unstamped advisory drift).

## Banner-bump wins this turn (5 cases — §00 was patch-behind §98)

After the comparator fix, 5 NEW §00-behind cases became visible (previously
masked by positional ambiguity). All resolved as pure §00 banner bumps:

| Path | Old §00 | New §00 | Source §98 row |
|------|---------|---------|----------------|
| `spec/12-cicd-pipeline-workflows/01-browser-extension-deploy` | 3.4.1 | 3.4.2 | Task #29e |
| `spec/12-cicd-pipeline-workflows/02-go-binary-deploy` | 3.4.1 | 3.4.2 | Task #29e |
| `spec/03/02/04-error-modal/01-copy-formats` | 3.3.1 | 3.3.2 | Task #29e |
| `spec/03/02/04-error-modal/02-react-components` | 4.1.1 | 4.1.2 | Task #29e |
| `spec/03/02/04-error-modal/04-color-themes` | 3.0.1 | 3.0.2 | Task #29e |

All also got h10 stamp refresh (28/29 → 153).

## Spec lockstep

- Slot 29 inline `## Changelog` v1.2.0 → **v1.3.0** (new AC-29-15)
- §27 §00 v2.77.0 → **v2.77.1**
- §27 §98 v2.77.0 → **v2.77.1**
- §27 §99 v2.74.0 → **v2.74.1**

Patch-only on §27 module banners (child slot got new content; module surface unchanged).

## Validation

- `bash linter-scripts/test/test-check-version-parity.sh` → **14/14 PASS** ✓
- `bash linter-scripts/test/test-overview-inventory-parity.sh` → 6/6 PASS ✓
- `node linter-scripts/check-lockstep.cjs` → 87/87 PASS ✓
- `node linter-scripts/check-tree-health.cjs --strict` → 168/168 PASS ✓
- `python3 linter-scripts/check-version-parity.py` → 15 FAILs remain (all
  §00-ahead-of-§98 — need §98 release rows authored; deferred)

## Remaining work for #35-fu2

The 15 surviving FAILs all fit the same shape: §00 was patch-bumped during
prior Phase 153 sub-tasks but the matching §98 row was never added. Each
needs a single §98 release row documenting what the §00 banner already
claims. Mechanical but per-case (need to identify which prior phase did
the bump). Listed in `phase-153-task-35-version-parity-investigation.md`.

## Files changed

- `linter-scripts/check-version-parity.py` — comparator switched to SemVer-max
- `linter-scripts/test/test-check-version-parity.sh` — T14 added; T2/T9 sandboxed
- `spec/27-spec-toolchain/29-check-version-parity.md` — AC-29-15 + v1.3.0 changelog row
- `spec/27-spec-toolchain/00-overview.md` — banner v2.77.0 → v2.77.1
- `spec/27-spec-toolchain/98-changelog.md` — banner + v2.77.1 release row
- `spec/27-spec-toolchain/99-consistency-report.md` — banner v2.74.0 → v2.74.1
- 5 × `00-overview.md` banner+stamp refreshes (spec/12/01, spec/12/02, three error-modal sub-modules)
