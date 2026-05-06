#!/usr/bin/env bash
# Self-test for AC-34-18: bounded tier-1B promotion (nested contract files).
#
# Asserts:
#   T1: spec/05 (small nested T1B set, FITS path) → all 4 root T1 + all 8
#       nested T1B files appear at the head of the bundle (positions 1-12).
#   T2: spec/02 (giant nested T1B set, OVERFLOW-fallback path) → all 4 root T1
#       still appear at the head; nested T1B files NOT mass-promoted (≤ 3 of
#       116 nested T1B files in the first 12 bundle entries — they fall back
#       to natural alphabetical order).
#   T3: spec/22 (zero nested T1B) → behavior unchanged (root T1 first, no
#       new entries, no error).
#
# Codifies the bounded-promotion contract so future walker edits cannot
# silently regress the FITS path (clean lift) or the OVERFLOW path
# (graceful no-mass-promotion fallback).

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

PASS=0
FAIL=0

assert() {
  local label="$1"; local expected="$2"; local actual="$3"
  if [[ "$actual" == "$expected" ]]; then
    PASS=$((PASS+1))
    echo "  PASS  $label  (got: $actual)"
  else
    FAIL=$((FAIL+1))
    echo "  FAIL  $label  expected=$expected  got=$actual"
  fi
}

probe() {
  local slug="$1"
  python3 - "$ROOT" "$slug" <<'PY'
import sys, importlib.util, re
from pathlib import Path
root = Path(sys.argv[1])
slug = sys.argv[2]
spec = importlib.util.spec_from_file_location("aai", str(root/"linter-scripts/audit-ai-implementability.py"))
m = importlib.util.module_from_spec(spec); spec.loader.exec_module(m)
text, _t, _u, _tf = m.load_module_bundle(root/"spec"/slug)
headers = [h.strip() for h in re.findall(r"===== FILE: ([^=]+) =====", text)]
T1 = {"00-overview.md","97-acceptance-criteria.md","98-changelog.md","99-consistency-report.md"}
root_t1 = [h for h in headers if h.split("/")[-1] in T1 and h.count("/") == 2]
nested_t1b_first12 = [h for h in headers[:12] if h.split("/")[-1] in T1 and h.count("/") >= 3]
nested_t1b_total = [h for h in headers if h.split("/")[-1] in T1 and h.count("/") >= 3]
print(f"root_t1={len(root_t1)} nT1B_first12={len(nested_t1b_first12)} nT1B_total={len(nested_t1b_total)}")
PY
}

echo "AC-34-18: bounded tier-1B promotion self-test"
echo "---"

echo "T1: spec/05 (FITS path — full clean lift expected)"
out=$(probe "05-split-db-architecture")
echo "  probe → $out"
rt1=$(echo "$out" | sed -n 's/.*root_t1=\([0-9]*\).*/\1/p')
nt1b_top=$(echo "$out" | sed -n 's/.*nT1B_first12=\([0-9]*\).*/\1/p')
nt1b_all=$(echo "$out" | sed -n 's/.*nT1B_total=\([0-9]*\).*/\1/p')
assert "spec/05 root_t1 == 4"          "4" "$rt1"
assert "spec/05 nT1B in first12 == 8"  "8" "$nt1b_top"
assert "spec/05 nT1B total == 8"       "8" "$nt1b_all"

echo "T2: spec/02 (OVERFLOW-fallback — no mass promotion)"
out=$(probe "02-coding-guidelines")
echo "  probe → $out"
rt1=$(echo "$out" | sed -n 's/.*root_t1=\([0-9]*\).*/\1/p')
nt1b_top=$(echo "$out" | sed -n 's/.*nT1B_first12=\([0-9]*\).*/\1/p')
assert "spec/02 root_t1 == 4"            "4" "$rt1"
# Fallback contract: at most 3 nested T1B files may appear in first 12
# (they bubble up only via natural alphabetical order, not by promotion).
if [[ "$nt1b_top" -le 3 ]]; then
  PASS=$((PASS+1)); echo "  PASS  spec/02 nT1B_first12 ≤ 3 (got: $nt1b_top — fallback held)"
else
  FAIL=$((FAIL+1)); echo "  FAIL  spec/02 nT1B_first12 ≤ 3  expected≤3 got=$nt1b_top (mass-promotion regression!)"
fi

echo "T3: spec/22 (no nested T1B — no behavior change)"
out=$(probe "22-git-logs-v2")
echo "  probe → $out"
nt1b_all=$(echo "$out" | sed -n 's/.*nT1B_total=\([0-9]*\).*/\1/p')
assert "spec/22 nT1B total == 0"  "0" "$nt1b_all"

echo "---"
echo "AC-34-18 self-test: $PASS pass, $FAIL fail"
[[ "$FAIL" -eq 0 ]] || exit 1
