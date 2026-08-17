#!/usr/bin/env bash
set -uo pipefail

RESULTS_DIR="${1:-/tmp}"
overall=0
all_failures=""

for dir in "$RESULTS_DIR"/test-results-*; do
  suite=$(basename "$dir" | sed 's/test-results-//')
  file="$dir/test-output.txt"
  [ ! -f "$file" ] && continue

  pass=$(grep -c '^--- PASS:' "$file" || true)
  fail=$(grep -c '^--- FAIL:' "$file" || true)

  if [ "$fail" -gt 0 ]; then
    overall=1
    all_failures="$all_failures\nFailure in suite $suite"
  else
    echo "✅ $suite: $pass passed"
  fi
done

if [ "$overall" -ne 0 ]; then
  echo "========================================="
  echo "  FAILURE REPORT (copy-paste ready)"
  echo "========================================="
  echo -e "$all_failures"
  echo "========================================="
  exit 1
fi

echo "All test suites passed."
