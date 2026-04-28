#!/usr/bin/env bash
# test-check-99-summary-freshness.sh — self-test for §26 gate (Phase H1).
#
# Per Phase F3 addendum: new self-tests SHOULD be `.sh` with `set -euo pipefail`
# + `assert` contract + `test-readme-inventory.sh` parity.

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
GATE="$ROOT/linter-scripts/check-99-summary-freshness.py"
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT

PASS=0
FAIL=0

assert() {
  local desc="$1"; local actual="$2"; local expected="$3"
  if [[ "$actual" == "$expected" ]]; then
    echo "  ✓ $desc"
    PASS=$((PASS+1))
  else
    echo "  ✗ $desc — expected '$expected', got '$actual'"
    FAIL=$((FAIL+1))
  fi
}

echo "== test-check-99-summary-freshness.sh =="

# --- T1: real-tree run (default mode) — should exit 0 (Phase H2: gate now
# scans all §99 files except _archive/ and accepts stamps under Summary OR
# inventory-rubric headings; assert structural shape only, not exact counts
# which churn as adoption progresses).
PYTHONIOENCODING=utf-8 python3 "$REPO/linter-scripts/check-99-summary-freshness.py" >/tmp/h1-out 2>&1
RC=$?
assert "T1 real-tree default mode exits 0" "$RC" "0"
grep -qE "§99 files scanned: [0-9]+; stamped: [0-9]+; unstamped: [0-9]+" /tmp/h1-out && echo "  ✓ T1 reports scan/stamped/unstamped counts" || { echo "  ✗ T1 counts line missing"; FAIL=$((FAIL+1)); }

# --- T2: real-tree --report-only also exits 0
set +e
python3 "$GATE" --report-only >/tmp/h1-out 2>&1
RC=$?
set -e
assert "T2 --report-only exits 0" "$RC" "0"

# --- T3: --max-age default visible in header
python3 "$GATE" 2>&1 | grep -q "max stale delta: 20" \
  && { echo "  ✓ T3 default --max-age=20 in header"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T3 default --max-age missing from header"; FAIL=$((FAIL+1)); }

# --- T4: structural error path (no Phase token visible) — synthetic
SANDBOX="$TMP/sandbox"
mkdir -p "$SANDBOX/spec/test-folder" "$SANDBOX/.lovable/memory" "$SANDBOX/spec/27-spec-toolchain"
cp "$GATE" "$SANDBOX/check.py"
# rewrite REPO to point at sandbox
sed -i "s|REPO = Path(__file__).resolve().parent.parent|REPO = Path('$SANDBOX').resolve()|" "$SANDBOX/check.py"
echo "(no phase tokens here)" > "$SANDBOX/.lovable/memory/index.md"
echo "(no phase tokens here either)" > "$SANDBOX/spec/27-spec-toolchain/98-changelog.md"
echo "## Summary" > "$SANDBOX/spec/test-folder/99-consistency-report.md"
set +e
python3 "$SANDBOX/check.py" >/tmp/h1-out 2>&1
RC=$?
set -e
assert "T4 missing-phase-token exits 2" "$RC" "2"
grep -q "cannot determine current phase" /tmp/h1-out \
  && { echo "  ✓ T4 emits cannot-determine error"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T4 error message missing"; FAIL=$((FAIL+1)); }

# --- T5: stamped + stale exits 1 (synthetic)
echo "Phase 200" >> "$SANDBOX/.lovable/memory/index.md"
cat > "$SANDBOX/spec/test-folder/99-consistency-report.md" <<'MD'
# Test §99
## Summary
<!-- verified-phase: 100 -->
Some claim.
MD
set +e
python3 "$SANDBOX/check.py" >/tmp/h1-out 2>&1
RC=$?
set -e
assert "T5 stamped+stale (delta 100, max 20) exits 1" "$RC" "1"
grep -q "stamp: Phase 100, delta: 100" /tmp/h1-out \
  && { echo "  ✓ T5 reports stamp + delta"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T5 stamp/delta line missing"; FAIL=$((FAIL+1)); }

# --- T6: stamped + stale + --report-only exits 0
set +e
python3 "$SANDBOX/check.py" --report-only >/tmp/h1-out 2>&1
RC=$?
set -e
assert "T6 stamped+stale + --report-only exits 0" "$RC" "0"
grep -q "not failing" /tmp/h1-out \
  && { echo "  ✓ T6 emits 'not failing' footer"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T6 footer missing"; FAIL=$((FAIL+1)); }

# --- T7: stamped + fresh exits 0
cat > "$SANDBOX/spec/test-folder/99-consistency-report.md" <<'MD'
# Test §99
## Summary
<!-- verified-phase: 195 -->
Some claim.
MD
set +e
python3 "$SANDBOX/check.py" >/tmp/h1-out 2>&1
RC=$?
set -e
assert "T7 stamped+fresh (delta 5, max 20) exits 0" "$RC" "0"
grep -q "stamped: 1" /tmp/h1-out \
  && { echo "  ✓ T7 counts 1 stamped"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T7 stamped count missing"; FAIL=$((FAIL+1)); }

echo
echo "Results: $PASS passed, $FAIL failed"
if [[ "$FAIL" -gt 0 ]]; then exit 1; fi
echo "✅ §26 freshness gate self-test green."
