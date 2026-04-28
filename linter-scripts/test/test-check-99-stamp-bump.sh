#!/usr/bin/env bash
# test-check-99-stamp-bump.sh — self-test for §27 gate (Phase H4).
#
# Per Phase F3 addendum: new self-tests SHOULD be `.sh` with `set -euo pipefail`
# + `assert` contract + `test-readme-inventory.sh` parity.
#
# Strategy: run the gate against a synthetic git repo per test case, so we can
# control both the diff and the on-disk content precisely without polluting
# the project's git state.

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
GATE="$ROOT/linter-scripts/check-99-stamp-bump.py"
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

# Build a synthetic repo with mem://index.md and §27 changelog so the gate
# can detect the current phase. Each test seeds the state, commits, mutates,
# and runs the gate against the prior commit.
make_sandbox() {
  local sb="$1"
  rm -rf "$sb"
  mkdir -p "$sb/.lovable/memory" "$sb/spec/27-spec-toolchain" "$sb/spec/test-folder" "$sb/linter-scripts"
  cp "$GATE" "$sb/linter-scripts/check-99-stamp-bump.py"
  echo "Phase 147 — synthetic" > "$sb/.lovable/memory/index.md"
  echo "Phase 147 — synthetic changelog" > "$sb/spec/27-spec-toolchain/98-changelog.md"
  (
    cd "$sb"
    git init -q -b main
    git config user.email "test@test"
    git config user.name "test"
    git add -A
    git commit -qm "init"
  )
}

echo "== test-check-99-stamp-bump.sh =="

# --- T1: no §99 changes → exit 0
SB="$TMP/t1"; make_sandbox "$SB"
echo "no change" > "$SB/spec/test-folder/00-overview.md"
(cd "$SB" && git add -A && git commit -qm "non-99 edit")
set +e
(cd "$SB" && python3 linter-scripts/check-99-stamp-bump.py --base-ref HEAD~1 >/tmp/h4-out 2>&1)
RC=$?
set -e
assert "T1 no §99 change exits 0" "$RC" "0"
grep -q "No §99 files changed" /tmp/h4-out \
  && { echo "  ✓ T1 emits 'No §99 files changed'"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T1 missing 'No §99 files changed' message"; FAIL=$((FAIL+1)); }

# --- T2: unstamped §99 changed → SKIP, exit 0
SB="$TMP/t2"; make_sandbox "$SB"
cat > "$SB/spec/test-folder/99-consistency-report.md" <<'MD'
# Test §99
## Summary
Original content, no stamp.
MD
(cd "$SB" && git add -A && git commit -qm "add unstamped 99")
echo "edited content" >> "$SB/spec/test-folder/99-consistency-report.md"
(cd "$SB" && git add -A && git commit -qm "edit unstamped 99")
set +e
(cd "$SB" && python3 linter-scripts/check-99-stamp-bump.py --base-ref HEAD~1 >/tmp/h4-out 2>&1)
RC=$?
set -e
assert "T2 unstamped §99 edit exits 0 (skip)" "$RC" "0"
grep -q "unstamped (skip):     1" /tmp/h4-out \
  && { echo "  ✓ T2 counts 1 unstamped skip"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T2 unstamped skip count missing"; FAIL=$((FAIL+1)); }

# --- T3: stamped §99 with stamp-only diff (pure bump) → SKIP, exit 0
SB="$TMP/t3"; make_sandbox "$SB"
cat > "$SB/spec/test-folder/99-consistency-report.md" <<'MD'
# Test §99
## Summary
<!-- verified-phase: 100 -->
Body unchanged.
MD
(cd "$SB" && git add -A && git commit -qm "add stamped 99 phase 100")
sed -i 's/verified-phase: 100/verified-phase: 147/' "$SB/spec/test-folder/99-consistency-report.md"
(cd "$SB" && git add -A && git commit -qm "bump stamp only")
set +e
(cd "$SB" && python3 linter-scripts/check-99-stamp-bump.py --base-ref HEAD~1 >/tmp/h4-out 2>&1)
RC=$?
set -e
assert "T3 stamp-only diff exits 0 (skip)" "$RC" "0"
grep -q "stamp-only diff:      1" /tmp/h4-out \
  && { echo "  ✓ T3 counts 1 stamp-only skip"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T3 stamp-only skip count missing"; FAIL=$((FAIL+1)); }

# --- T4: stamped §99 materially edited WITHOUT bumping → FAIL, exit 1
SB="$TMP/t4"; make_sandbox "$SB"
cat > "$SB/spec/test-folder/99-consistency-report.md" <<'MD'
# Test §99
## Summary
<!-- verified-phase: 100 -->
Original body claim.
MD
(cd "$SB" && git add -A && git commit -qm "add stamped 99 phase 100")
sed -i 's/Original body claim/Edited body claim WITHOUT bumping/' "$SB/spec/test-folder/99-consistency-report.md"
(cd "$SB" && git add -A && git commit -qm "edit body, forget bump")
set +e
(cd "$SB" && python3 linter-scripts/check-99-stamp-bump.py --base-ref HEAD~1 >/tmp/h4-out 2>&1)
RC=$?
set -e
assert "T4 unbumped material edit exits 1" "$RC" "1"
grep -q "unbumped (issue):     1" /tmp/h4-out \
  && { echo "  ✓ T4 counts 1 unbumped issue"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T4 unbumped count missing"; FAIL=$((FAIL+1)); }
grep -q "stamp: Phase 100, current: Phase 147" /tmp/h4-out \
  && { echo "  ✓ T4 reports stamp/current phases"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T4 stamp/current report missing"; FAIL=$((FAIL+1)); }

# --- T5: same as T4 but --report-only → exit 0
set +e
(cd "$SB" && python3 linter-scripts/check-99-stamp-bump.py --base-ref HEAD~1 --report-only >/tmp/h4-out 2>&1)
RC=$?
set -e
assert "T5 unbumped + --report-only exits 0" "$RC" "0"
grep -q "not failing" /tmp/h4-out \
  && { echo "  ✓ T5 emits 'not failing' footer"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T5 footer missing"; FAIL=$((FAIL+1)); }

# --- T6: stamped §99 materially edited AND stamp bumped to current → exit 0
SB="$TMP/t6"; make_sandbox "$SB"
cat > "$SB/spec/test-folder/99-consistency-report.md" <<'MD'
# Test §99
## Summary
<!-- verified-phase: 100 -->
Original body claim.
MD
(cd "$SB" && git add -A && git commit -qm "add stamped 99 phase 100")
cat > "$SB/spec/test-folder/99-consistency-report.md" <<'MD'
# Test §99
## Summary
<!-- verified-phase: 147 -->
Edited body claim AND bumped.
MD
(cd "$SB" && git add -A && git commit -qm "edit + bump")
set +e
(cd "$SB" && python3 linter-scripts/check-99-stamp-bump.py --base-ref HEAD~1 >/tmp/h4-out 2>&1)
RC=$?
set -e
assert "T6 edit + bump exits 0" "$RC" "0"
grep -q "bumped to current:    1" /tmp/h4-out \
  && { echo "  ✓ T6 counts 1 bumped"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T6 bumped count missing"; FAIL=$((FAIL+1)); }

# --- T7: _archive/ §99 edited without bump → SKIP (excluded), exit 0
SB="$TMP/t7"; make_sandbox "$SB"
mkdir -p "$SB/spec/_archive/old-folder"
cat > "$SB/spec/_archive/old-folder/99-consistency-report.md" <<'MD'
# Archived §99
## Summary
<!-- verified-phase: 100 -->
Original archived claim.
MD
(cd "$SB" && git add -A && git commit -qm "add archived 99")
sed -i 's/Original archived claim/Edited archived claim no bump/' "$SB/spec/_archive/old-folder/99-consistency-report.md"
(cd "$SB" && git add -A && git commit -qm "edit archived")
set +e
(cd "$SB" && python3 linter-scripts/check-99-stamp-bump.py --base-ref HEAD~1 >/tmp/h4-out 2>&1)
RC=$?
set -e
assert "T7 _archive edit excluded (exits 0)" "$RC" "0"
grep -q "No §99 files changed" /tmp/h4-out \
  && { echo "  ✓ T7 _archive excluded from diff list"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T7 _archive leaked into diff"; FAIL=$((FAIL+1)); }

# --- T8: bad base-ref → exit 2
SB="$TMP/t8"; make_sandbox "$SB"
set +e
(cd "$SB" && python3 linter-scripts/check-99-stamp-bump.py --base-ref nonexistent-ref >/tmp/h4-out 2>&1)
RC=$?
set -e
assert "T8 bad base-ref exits 2" "$RC" "2"
grep -q "git diff failed" /tmp/h4-out \
  && { echo "  ✓ T8 emits git diff error"; PASS=$((PASS+1)); } \
  || { echo "  ✗ T8 git diff error missing"; FAIL=$((FAIL+1)); }

echo
echo "Results: $PASS passed, $FAIL failed"
if [[ "$FAIL" -gt 0 ]]; then exit 1; fi
echo "✅ §27 stamp-bump gate self-test green."
