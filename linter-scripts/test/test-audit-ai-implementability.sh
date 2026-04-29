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

echo
echo "Result: $PASS passed, $FAIL failed"
[ "$FAIL" = "0" ]
