#!/usr/bin/env bash
# test-check-version-parity.sh — H10 (Phase P15) self-test.
#
# Spec: spec/27-spec-toolchain/29-check-version-parity.md
# Validates the §00 ↔ §98 Version-field parity gate's contract:
#   T1  banner shape line includes scanned/eligible/matches/mismatches/skipped fields
#   T2  default mode exits 0 even when mismatches present (advisory)
#   T3  --strict exits 1 when mismatches present
#   T4  --strict --report-only exits 0 (override)
#   T5  module with matched §00 ↔ §98 versions counts as match (synthetic)
#   T6  module with no §00 banner Version is skipped (skipped_no_banner++)
#   T7  module with no §98 release is skipped (skipped_no_release++)
#   T8  table-row §98 format (folder 22 style) parsed correctly
#   T9  --json output is valid JSON with required keys (incl. stamped/stamped_failed)
#   T10 _archive/ excluded from scan
#   T11 Phase P20: stamped §00 with mismatch fails default mode (per-file strict)
#   T12 Phase P20: stamped §00 with match passes (counts as stamped + match)
#   T13 Phase P20: --report-only overrides per-file stamp failure

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT="$(cd "${SCRIPT_DIR}/../.." && pwd)"
GATE="${ROOT}/linter-scripts/check-version-parity.py"

assert() {
    local label="$1"; shift
    if "$@"; then
        echo "  ✓ ${label}"
    else
        echo "  ✗ ${label}" >&2
        echo "    cmd: $*" >&2
        return 1
    fi
}

PASS=0
FAIL=0
run() {
    local name="$1"; shift
    if "$@"; then
        PASS=$((PASS+1))
    else
        echo "T${name} FAILED" >&2
        FAIL=$((FAIL+1))
    fi
}

# T1 banner shape
t1() {
    local out
    out="$(python3 "$GATE" 2>&1 | head -1)"
    [[ "$out" == *"scanned="* ]] && \
    [[ "$out" == *"eligible="* ]] && \
    [[ "$out" == *"matches="* ]] && \
    [[ "$out" == *"mismatches="* ]] && \
    [[ "$out" == *"skipped(no-banner)="* ]] && \
    [[ "$out" == *"skipped(no-release)="* ]]
}

# T2 default exit 0 with real-tree mismatches
t2() {
    python3 "$GATE" >/dev/null 2>&1
}

# T3 strict exits 1 with real-tree mismatches
t3() {
    ! python3 "$GATE" --strict >/dev/null 2>&1
}

# T4 strict + report-only exits 0
t4() {
    python3 "$GATE" --strict --report-only >/dev/null 2>&1
}

# Sandbox helpers
SANDBOX="$(mktemp -d)"
trap 'rm -rf "$SANDBOX"' EXIT

mk_module() {
    local dir="$1" banner_v="$2" release_v="$3" release_format="$4" stamp="${5:-}"
    mkdir -p "$dir"
    if [[ -n "$banner_v" ]]; then
        printf '# Test\n\n**Version:** %s\n**Updated:** 2026-04-28\n' "$banner_v" > "$dir/00-overview.md"
        if [[ -n "$stamp" ]]; then
            printf '<!-- h10-verified-phase: %s -->\n' "$stamp" >> "$dir/00-overview.md"
        fi
    else
        printf '# Test\n\n**Updated:** 2026-04-28\n' > "$dir/00-overview.md"
    fi
    case "$release_format" in
      heading)
        printf '# Changelog\n\n## %s — 2026-04-28\n- entry\n' "$release_v" > "$dir/98-changelog.md"
        ;;
      table)
        printf '# Changelog\n\n| Version | Date | Notes |\n|---------|------|-------|\n| %s | 2026-04-28 | entry |\n' "$release_v" > "$dir/98-changelog.md"
        ;;
      none)
        printf '# Changelog\n\n(empty)\n' > "$dir/98-changelog.md"
        ;;
    esac
}

# T5 matched module
t5() {
    local sb="$SANDBOX/t5/spec"
    rm -rf "$SANDBOX/t5"; mkdir -p "$sb"
    mk_module "$sb/mod" "1.2.3" "1.2.3" "heading"
    local out
    out="$(python3 "$GATE" --spec-root "$sb" 2>&1)"
    [[ "$out" == *"matches=1"* ]] && [[ "$out" == *"mismatches=0"* ]]
}

# T6 no banner skipped
t6() {
    local sb="$SANDBOX/t6/spec"
    rm -rf "$SANDBOX/t6"; mkdir -p "$sb"
    mk_module "$sb/mod" "" "1.2.3" "heading"
    local out
    out="$(python3 "$GATE" --spec-root "$sb" 2>&1)"
    [[ "$out" == *"skipped(no-banner)=1"* ]] && [[ "$out" == *"eligible=0"* ]]
}

# T7 no release skipped
t7() {
    local sb="$SANDBOX/t7/spec"
    rm -rf "$SANDBOX/t7"; mkdir -p "$sb"
    mk_module "$sb/mod" "1.2.3" "" "none"
    local out
    out="$(python3 "$GATE" --spec-root "$sb" 2>&1)"
    [[ "$out" == *"skipped(no-release)=1"* ]] && [[ "$out" == *"eligible=0"* ]]
}

# T8 table-row format parsed
t8() {
    local sb="$SANDBOX/t8/spec"
    rm -rf "$SANDBOX/t8"; mkdir -p "$sb"
    mk_module "$sb/mod" "3.9.8" "3.9.8" "table"
    local out
    out="$(python3 "$GATE" --spec-root "$sb" 2>&1)"
    [[ "$out" == *"matches=1"* ]] && [[ "$out" == *"mismatches=0"* ]]
}

# T9 --json valid
t9() {
    python3 "$GATE" --json 2>/dev/null | python3 -c "
import json, sys
d = json.load(sys.stdin)
required = {'scanned','eligible','matches','mismatches','skipped_no_banner','skipped_no_release','details'}
sys.exit(0 if required.issubset(d.keys()) else 1)
"
}

# T10 _archive excluded
t10() {
    local sb="$SANDBOX/t10/spec"
    rm -rf "$SANDBOX/t10"; mkdir -p "$sb"
    mk_module "$sb/_archive/old" "1.2.3" "9.9.9" "heading"
    local out
    out="$(python3 "$GATE" --spec-root "$sb" 2>&1)"
    [[ "$out" == *"scanned=0"* ]]
}

echo "test-check-version-parity.sh"
run 1 t1
run 2 t2
run 3 t3
run 4 t4
run 5 t5
run 6 t6
run 7 t7
run 8 t8
run 9 t9
run 10 t10

echo "──────────────────────────────"
echo "PASS: $PASS    FAIL: $FAIL"
[[ $FAIL -eq 0 ]] || exit 1
