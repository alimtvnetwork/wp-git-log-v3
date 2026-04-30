#!/usr/bin/env bash
# test-audit-ai-implementability.sh — self-test for slot 34 (Phase 153 Task A4).
# Exercises CLI surface only (no network calls); uses --no-network mode.
set -euo pipefail

cd "$(dirname "$0")/../.."
SCRIPT="linter-scripts/audit-ai-implementability.py"
PASS=0
FAIL=0

assert() {
  local desc="$1"; shift
  if "$@" >/dev/null 2>&1; then
    echo "  PASS — $desc"
    PASS=$((PASS+1))
  else
    echo "  FAIL — $desc"
    FAIL=$((FAIL+1))
  fi
}

assert_contains() {
  local desc="$1" needle="$2"; shift 2
  if "$@" 2>&1 | grep -qF -- "$needle"; then
    echo "  PASS — $desc"
    PASS=$((PASS+1))
  else
    echo "  FAIL — $desc (expected to contain: $needle)"
    FAIL=$((FAIL+1))
  fi
}

echo "Self-test: audit-ai-implementability.py (--no-network mode)"

# 1. Help surface present.
assert_contains "AC-34-01: --help advertises five mode flags" "--no-network" \
  python3 "$SCRIPT" --help

# 2. --no-network exits 0 and emits stats lines.
assert "AC-34-02: --no-network exits 0" \
  python3 "$SCRIPT" --no-network

# 3. --module filter restricts scope to one module.
out=$(python3 "$SCRIPT" --no-network --module=04-database-conventions 2>&1)
if echo "$out" | grep -q "04-database-conventions" && ! echo "$out" | grep -q "06-seedable-config-architecture"; then
  echo "  PASS — AC-34-03: --module filter narrows scope"
  PASS=$((PASS+1))
else
  echo "  FAIL — AC-34-03: --module filter did not narrow scope"
  FAIL=$((FAIL+1))
fi

# 4. Unknown module slug exits 2.
set +e
python3 "$SCRIPT" --no-network --module=99-does-not-exist >/dev/null 2>&1
rc=$?
set -e
if [ "$rc" = "2" ]; then
  echo "  PASS — AC-34-04: unknown --module exits 2"
  PASS=$((PASS+1))
else
  echo "  FAIL — AC-34-04: expected exit 2, got $rc"
  FAIL=$((FAIL+1))
fi

# 5. --no-network --json emits parseable JSON array.
if python3 "$SCRIPT" --no-network --json --module=04-database-conventions 2>/dev/null | python3 -c "import sys,json; d=json.load(sys.stdin); assert isinstance(d,list) and len(d)==1 and d[0]['module']=='04-database-conventions' and d[0].get('no_network') is True and 'bundle_sha' in d[0]"; then
  echo "  PASS — AC-34-05: --json emits parseable list"
  PASS=$((PASS+1))
else
  echo "  FAIL — AC-34-05: --json output not parseable"
  FAIL=$((FAIL+1))
fi

# 6. Walk includes non-md artefacts (spec/11 has schemas/templates → ≥18 files).
out=$(python3 "$SCRIPT" --no-network --module=11-powershell-integration 2>&1)
total=$(echo "$out" | grep -oE "\([0-9]+/[0-9]+ files" | head -1 | grep -oE "/[0-9]+" | tr -d '/')
if [ -n "$total" ] && [ "$total" -ge "18" ]; then
  echo "  PASS — AC-34-06: non-md walker includes schemas/templates (got $total files)"
  PASS=$((PASS+1))
else
  echo "  FAIL — AC-34-06: walker only saw '${total:-none}' files (expected ≥18 incl. schemas/templates)"
  FAIL=$((FAIL+1))
fi

# 7. AC-34-10: axis multipliers normalise to sum 5.0 (apply_rubric_v7 invariant).
if python3 -c "
import importlib.util, pathlib
spec = importlib.util.spec_from_file_location('aai', 'linter-scripts/audit-ai-implementability.py')
m = importlib.util.module_from_spec(spec); spec.loader.exec_module(m)
for axis in m.AXIS_VALUES:
    s = sum(m.axis_multipliers(axis).values())
    assert abs(s - 5.0) < 1e-6, f'{axis} sum={s}'
# spot-check audit-corpus weighting penalises D2/D3 vs uniform
r = m.apply_rubric_v7({'d1':20,'d2':20,'d3':20,'d4':20,'d5':20}, 'audit-corpus')
assert r['total_v7'] == 95, f'audit-corpus 100s capped to 95, got {r[\"total_v7\"]}'
" 2>/dev/null; then
  echo "  PASS — AC-34-10: axis multipliers normalise to 5.0; audit-corpus capped at 95"
  PASS=$((PASS+1))
else
  echo "  FAIL — AC-34-10: multiplier renorm or cap broken"
  FAIL=$((FAIL+1))
fi

# 8. AC-34-11: 60-floor preserved across all axes (BLOCKING band threshold unchanged).
if python3 -c "
import importlib.util
spec = importlib.util.spec_from_file_location('aai', 'linter-scripts/audit-ai-implementability.py')
m = importlib.util.module_from_spec(spec); spec.loader.exec_module(m)
# A module scoring all 12s (raw 60) under any axis MUST land in NEEDS_WORK or above (≥60).
# A module scoring all 11s (raw 55) MUST land in BLOCKING for axes whose multipliers don't lift it above 60.
for axis in m.AXIS_VALUES:
    r60 = m.apply_rubric_v7({k: 12 for k in ['d1','d2','d3','d4','d5']}, axis)
    assert r60['total_v7'] >= 60, f'{axis} 12s should be ≥60, got {r60[\"total_v7\"]}'
    # Caps never lift below their declared minimum (i.e. cap is a max, not a min).
    assert r60['axis_cap'] >= 95, f'{axis} cap below 95: {r60[\"axis_cap\"]}'
" 2>/dev/null; then
  echo "  PASS — AC-34-11: 60-floor preserved across all 5 axes; caps ≥95"
  PASS=$((PASS+1))
else
  echo "  FAIL — AC-34-11: floor or cap invariant broken"
  FAIL=$((FAIL+1))
fi

# 9. AC-34-12: missing/invalid content_axis MUST exit 2 (fail-fast).
TMPMOD="spec/00-aai-axis-test-fixture"
mkdir -p "$TMPMOD"
# fixture A: no front-matter at all → exit 2
cat > "$TMPMOD/00-overview.md" <<'EOF'
# Test fixture (no front-matter)
This module has no YAML front-matter and MUST trigger AC-34-12 fail-fast.
EOF
set +e
python3 "$SCRIPT" --no-network --module=00-aai-axis-test-fixture >/dev/null 2>&1
rc_missing=$?
set -e
# fixture B: invalid axis value → exit 2
cat > "$TMPMOD/00-overview.md" <<'EOF'
---
content_axis: not-a-real-axis
---
# Test fixture (invalid axis)
EOF
set +e
python3 "$SCRIPT" --no-network --module=00-aai-axis-test-fixture >/dev/null 2>&1
rc_invalid=$?
set -e
rm -rf "$TMPMOD"
if [ "$rc_missing" = "2" ] && [ "$rc_invalid" = "2" ]; then
  echo "  PASS — AC-34-12: missing axis → exit 2; invalid axis → exit 2"
  PASS=$((PASS+1))
else
  echo "  FAIL — AC-34-12: expected exit 2/2, got missing=$rc_missing invalid=$rc_invalid"
  FAIL=$((FAIL+1))
fi

echo
echo "Result: $PASS passed, $FAIL failed"
[ "$FAIL" = "0" ]
