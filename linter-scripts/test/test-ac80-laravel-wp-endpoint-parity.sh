#!/usr/bin/env bash
# linter-scripts/test/test-ac80-laravel-wp-endpoint-parity.sh
#
# Phase 153 (LBR-12 closure) — mechanical-lock self-test for spec/22 AC-80
# + AC-83. Asserts 1:1 verb+path parity between the WordPress binding
# (`spec/22-git-logs-v2/04-rest-api-endpoints.md` Endpoint Map) and the
# Laravel binding (`spec/22-git-logs-v2/40-laravel-endpoint-definition.md`
# Route file Layout, §2).
#
# Why this gate exists (Lesson L21 + Phase P47–P49 parity-AC graduation
# chain): AC-80 says every per-framework binding MUST mirror §04 verbatim
# modulo the framework HTTP prefix. Without a CI gate, a contributor could
# add an endpoint to §04 (or to slot 40) without updating the sibling and
# the drift would survive into the next downstream Laravel package build.
#
# Scope: §04 lists 10 LOGICAL endpoints that collapse to 8 distinct HTTP
# paths (rows #5/#6 share `/get-logs`, rows #7/#8 share `/get-pipeline-logs`
# — the `?q=…` variant is a query parameter, not a separate route). Laravel
# binding therefore declares 8 Route:: statements. Test asserts that exact
# 8-vs-8 set parity.
#
# Snapshot-restore: no fixtures touched, no state mutated. Pure read-only
# parse of two spec files.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"
WP_FILE="$REPO_ROOT/spec/22-git-logs-v2/04-rest-api-endpoints.md"
LARAVEL_FILE="$REPO_ROOT/spec/22-git-logs-v2/40-laravel-endpoint-definition.md"

PASS=0
FAIL=0
assert() {
  local label="$1"; shift
  if "$@"; then echo "✅ $label"; PASS=$((PASS+1))
  else          echo "❌ $label"; FAIL=$((FAIL+1)); fi
}

[ -f "$WP_FILE" ]      || { echo "❌ §04 not found: $WP_FILE"; exit 2; }
[ -f "$LARAVEL_FILE" ] || { echo "❌ §40 not found: $LARAVEL_FILE"; exit 2; }

# ── Extract §04 endpoints ────────────────────────────────────────
# Rows look like: | 1 | POST | `/append-log` | ...
# Normalize: strip trailing `?q=…` query param to collapse 10→8 distinct paths.
wp_pairs=$(
  awk '
    /^\| *[0-9]+ *\| *(POST|PUT|GET|DELETE) *\| *`\// {
      # split on backticks: $2 is the path
      n = split($0, parts, "`")
      if (n >= 2) {
        path = parts[2]
        sub(/\?.*$/, "", path)           # strip query string
        # extract verb from field between first two pipes after the row number
        match($0, /\| *(POST|PUT|GET|DELETE) *\|/)
        verb = substr($0, RSTART, RLENGTH)
        gsub(/[ |]/, "", verb)
        print verb " " path
      }
    }
  ' "$WP_FILE" | sort -u
)

# ── Extract §40 Laravel routes ──────────────────────────────────
# Route file lives inside a ```php fenced block; Route::prefix('git-logs/v2')
# is the parent group. Lines look like:
#   Route::post  ('append-log',     [LaneB\AppendLogController::class, ...
#   Route::get('get-logs',          [LaneA\GetLogsController::class, ...
laravel_pairs=$(
  awk '
    /Route::(get|post|put|delete) *\(/ {
      # Extract verb
      match($0, /Route::(get|post|put|delete)/)
      verb = toupper(substr($0, RSTART+7, RLENGTH-7))
      # Extract path between the first pair of single quotes
      n = split($0, parts, "\x27")
      if (n >= 2) {
        path = parts[2]
        # Prepend slash to match §04 shape
        if (substr(path, 1, 1) != "/") path = "/" path
        printf "%-6s %s\n", verb, path
      }
    }
  ' "$LARAVEL_FILE" | awk '{print $1" "$2}' | sort -u
)

wp_count=$(printf '%s\n' "$wp_pairs"      | grep -c .)
la_count=$(printf '%s\n' "$laravel_pairs" | grep -c .)

# ── Assertions ──────────────────────────────────────────────────
assert "§04 yields exactly 8 distinct (verb,path) pairs" test "$wp_count" = "8"
assert "§40 yields exactly 8 distinct (verb,path) pairs" test "$la_count" = "8"

# Pairwise parity: every §04 pair appears in §40 and vice versa.
missing_in_laravel=$(comm -23 <(printf '%s\n' "$wp_pairs") <(printf '%s\n' "$laravel_pairs") || true)
missing_in_wp=$(comm -13 <(printf '%s\n' "$wp_pairs") <(printf '%s\n' "$laravel_pairs") || true)

assert "every §04 endpoint has a §40 Route:: declaration" test -z "$missing_in_laravel"
assert "every §40 Route:: declaration has a §04 endpoint" test -z "$missing_in_wp"

# Lane B writers (POST + PUT) are bound under gl.lane-b middleware.
lane_b_in_40=$(grep -c "Route::middleware('gl.lane-b')" "$LARAVEL_FILE" || true)
assert "§40 declares the gl.lane-b middleware group" test "$lane_b_in_40" -ge "1"

# Lane A readers (GET) are bound under gl.lane-a + gl.permission:HistoryView.
lane_a_in_40=$(grep -c "gl.lane-a" "$LARAVEL_FILE" || true)
assert "§40 declares the gl.lane-a middleware group" test "$lane_a_in_40" -ge "1"

# AC-81 contract: raw-PDO posture pinned, not Eloquent on writes.
assert "§40 cites AC-81 raw-PDO posture (DB::connection(...)->getPdo())" \
  grep -q "getPdo()" "$LARAVEL_FILE"
assert "§40 forbids Eloquent writes (Model::create / Model::save listed FORBIDDEN)" \
  grep -q "Model::create()" "$LARAVEL_FILE"

# AC-82 contract: Sanctum pinned, alternatives forbidden.
assert "§40 cites Sanctum as Lane A auth driver" \
  grep -qi "sanctum" "$LARAVEL_FILE"
assert "§40 enumerates forbidden auth drivers (Passport)" \
  grep -qi "passport" "$LARAVEL_FILE"

# Debug surface on failure.
if [ "$FAIL" -ne 0 ]; then
  echo ""
  echo "── §04 (verb,path) pairs ────────────────────────"
  printf '%s\n' "$wp_pairs"
  echo "── §40 (verb,path) pairs ────────────────────────"
  printf '%s\n' "$laravel_pairs"
  echo "── Missing in §40 ───────────────────────────────"
  printf '%s\n' "$missing_in_laravel"
  echo "── Missing in §04 ───────────────────────────────"
  printf '%s\n' "$missing_in_wp"
fi

echo ""
echo "Summary: $PASS passed, $FAIL failed (AC-80 + AC-81 + AC-82 mechanical lock)."
[ "$FAIL" -eq 0 ] && exit 0 || exit 1
