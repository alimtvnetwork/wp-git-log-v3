#!/usr/bin/env python3
"""
audit-ai-implementability.py — Phase 153 Task A4 deep-walk LLM audit.

Productionises the prototype harness used in Phase 153 Tasks A1/A2.
Scores every top-level `spec/<NN>-*` module on a 5-dimension rubric
(D1 Contract Clarity, D2 Acceptance-Test Coverage, D3 Edge/Error Handling,
D4 Examples & Worked Cases, D5 Cross-Ref / Dependency Closure) — each 0-20,
total 0-100 — using `google/gemini-3-flash-preview` via the Lovable AI Gateway.

Improvements over `/tmp/run_ai_audit_v2.py` (Phase 153 Task A1):
  - Walks `*.md` PLUS `*.json|*.yaml|*.yml|*.tmpl|*.toml|*.schema.json`
    (closes the spec/11 schemas/templates blind spot from Task A2).
  - Per-module on-disk cache (`.lovable/cache/audit-ai/<module>.json`).
  - `--module=<slug>` filter for targeted re-runs.
  - `--no-network` mode prints per-module file-bundle stats only.
  - `--json` machine-readable output mirroring `check-ai-confidence.py` shape.
  - `--report-only` never fails (advisory-by-default per H1/P20/P48-1-fu1).
  - Cloudflare 1010 fix baked in (explicit `User-Agent`).
  - Tolerant JSON response parser (strips fences + stray backslashes).

Slot 34 in spec/27-spec-toolchain (auditor band 30-39).
"""
from __future__ import annotations
import argparse
import hashlib
import json
import os
import re
import sys
import time
import urllib.error
import urllib.request
from pathlib import Path
from typing import Any

ROOT = Path(__file__).resolve().parent.parent
SPEC = ROOT / "spec"
CACHE_DIR = ROOT / ".lovable" / "cache" / "audit-ai"
DEFAULT_REPORT = ROOT / ".lovable" / "memory" / "audit" / "v2-deterministic" / "audit-ai-implementability-latest.md"

ENDPOINT = "https://ai.gateway.lovable.dev/v1/chat/completions"
MODEL = "google/gemini-3-flash-preview"
USER_AGENT = "lovable-spec-audit/1.0 (audit-ai-implementability.py)"
MAX_BYTES = 120_000  # Cloudflare-safe ceiling (~30k tokens). Raised from 90_000 in Phase 153 Task A12 (codified as AC-34-13) after tree-wide saturation probe (every audited module hit the 90 KB cap; most fit only 3-10 of 17-251 files). 120 KB live-probe at gateway returned HTTP 200; remaining headroom for `User-Agent`-tagged POSTs above this point produces Cloudflare 1010.

WALK_GLOBS = ("*.md", "*.json", "*.yaml", "*.yml", "*.tmpl", "*.toml")

# ─── Rubric v7 (Phase 153 Task A17 contract; A19 wiring) ─────────────────────
# Per-axis dimension multipliers; raw rows that sum to >5.0 are renormalised
# to 5.0 so the weighted total stays bounded at 100. See slot 34 §97 AC-34-10.
AXIS_VALUES = {
    "normative-contract",
    "process-guidance",
    "integration-spec",
    "audit-corpus",
    "tooling-spec",
}
AXIS_MULTIPLIERS_RAW: dict[str, dict[str, float]] = {
    "normative-contract": {"d1": 1.0, "d2": 1.5, "d3": 1.2, "d4": 0.8, "d5": 0.5},
    "process-guidance":   {"d1": 1.5, "d2": 0.7, "d3": 0.8, "d4": 1.0, "d5": 1.0},
    "integration-spec":   {"d1": 1.0, "d2": 0.9, "d3": 0.9, "d4": 1.4, "d5": 1.2},  # raw sum 5.4
    "audit-corpus":       {"d1": 1.0, "d2": 0.5, "d3": 0.5, "d4": 1.5, "d5": 1.5},
    "tooling-spec":       {"d1": 1.0, "d2": 1.3, "d3": 1.0, "d4": 1.3, "d5": 0.9},  # raw sum 5.5
}
# Per-axis soft cap on band assignment (AC-34-11). Strict CI gate threshold
# stays 60 (BLOCKING) tree-wide regardless of axis.
AXIS_CAPS: dict[str, int] = {
    "normative-contract": 100,
    "process-guidance":   95,
    "integration-spec":   95,
    "audit-corpus":       95,
    "tooling-spec":       100,
}


def axis_multipliers(axis: str) -> dict[str, float]:
    """Return AC-34-10 normalised multipliers for an axis (sum exactly 5.0)."""
    raw = AXIS_MULTIPLIERS_RAW[axis]
    s = sum(raw.values())
    if abs(s - 5.0) < 1e-9:
        return dict(raw)
    factor = s / 5.0
    return {k: v / factor for k, v in raw.items()}


FRONT_MATTER_RE = re.compile(r"^---\s*\n(.*?)\n---\s*\n", re.DOTALL)
AXIS_LINE_RE = re.compile(r"^\s*content_axis:\s*([A-Za-z0-9_\-]+)\s*$", re.MULTILINE)


def read_content_axis(mod_dir: Path) -> tuple[str | None, str | None]:
    """Parse `content_axis:` from the module's `00-overview.md` front-matter.

    Returns (axis, error). On success: (axis, None). On any error (no §00,
    no front-matter, no axis key, invalid axis value): (None, error_msg).
    Per AC-34-12, missing or invalid axis MUST cause the auditor to exit 2.
    """
    overview = mod_dir / "00-overview.md"
    if not overview.exists():
        return None, f"{mod_dir.name}: no 00-overview.md"
    txt = overview.read_text(encoding="utf-8", errors="replace")
    m = FRONT_MATTER_RE.match(txt)
    if not m:
        return None, f"{mod_dir.name}: 00-overview.md has no YAML front-matter block"
    fm = m.group(1)
    am = AXIS_LINE_RE.search(fm)
    if not am:
        return None, f"{mod_dir.name}: front-matter missing `content_axis:` key"
    axis = am.group(1).strip()
    if axis not in AXIS_VALUES:
        return None, f"{mod_dir.name}: invalid content_axis '{axis}' (allowed: {sorted(AXIS_VALUES)})"
    return axis, None


def apply_rubric_v7(scores: dict[str, int], axis: str) -> dict[str, Any]:
    """Apply AC-34-10 multipliers + AC-34-11 soft cap. Returns dict with
    weighted_total (pre-cap) and total_v7 (post-cap, the score the band uses).
    """
    mults = axis_multipliers(axis)
    weighted = sum(scores[k] * mults[k] for k in ("d1", "d2", "d3", "d4", "d5"))
    weighted_total = round(weighted, 1)
    cap = AXIS_CAPS[axis]
    total_v7 = min(int(round(weighted)), cap)
    return {
        "axis": axis,
        "axis_multipliers": {k: round(v, 4) for k, v in mults.items()},
        "axis_cap": cap,
        "weighted_total": weighted_total,
        "total_v7": total_v7,
    }


RUBRIC = """You are an exacting spec auditor. Score this spec module for whether a MEDIOCRE AI coder
(no clarifying questions, no web access) can implement it with 100% confidence on first try.

Score 5 dimensions, each 0-20 (integers only):
- D1 Contract Clarity: types pinned, units explicit, error codes enumerated
- D2 Acceptance-Test Coverage: every behaviour has a GWT acceptance criterion + Verifies clause
- D3 Edge / Error Handling: nulls, concurrency, large inputs, timeouts, partial failures addressed
- D4 Examples & Worked Cases: sample I/O, code snippets, file paths, fixtures
- D5 Cross-Ref / Dependency Closure: every external symbol/file referenced is resolved IN THE PROVIDED CONTEXT

Then list the TOP 3 failing issues with severity (CRITICAL/HIGH/MEDIUM/LOW), why-it-fails,
and a one-line fix.

Reply ONLY with strict JSON of shape:
{"d1":N,"d2":N,"d3":N,"d4":N,"d5":N,"issues":[{"severity":"CRITICAL|HIGH|MEDIUM|LOW","dim":"D1..D5","title":"...","why":"...","fix":"..."}, ...]}
"""


def discover_modules() -> list[Path]:
    return sorted(
        p for p in SPEC.iterdir()
        if p.is_dir()
        and not p.name.startswith("_")
        and len(p.name) > 2
        and p.name[:2].isdigit()
        and (p / "00-overview.md").exists()
    )


def load_module_bundle(mod_dir: Path) -> tuple[str, int, int, int]:
    """Concatenate all walk-globbed files up to MAX_BYTES.

    File ordering (Phase 153 Task A6 fix — codified as AC-34-09):
      Tier 1 (always-first, contract-bearing):  00-overview.md, 97-acceptance-criteria.md, 98-changelog.md, 99-consistency-report.md
      Tier 2 (alphabetical):                    everything else under WALK_GLOBS

    Pre-A6 the walker sorted purely alphabetically, which silently dropped
    every module's `97-acceptance-criteria.md` (alphabetically last) out
    of the 90 KB context window for any module whose `02-*`/`03-*` siblings
    were chunky. The auditor then scored on examples without seeing the
    binding contract — Task A6's first re-score loop produced no movement
    because the §97 additions were never bundled. Tier-1 priority guarantees
    the contract surface is always sampled.

    Phase 153 Task A12 (AC-34-13) raised MAX_BYTES from 90 KB → 120 KB after a
    tree-wide saturation probe found every audited module exhausted the 90 KB
    cap (most modules fit only 3-10 files of 17-251). The 120 KB limit was
    confirmed via a live gateway probe (HTTP 200); above ~125 KB Cloudflare
    1010 fires for `User-Agent`-tagged POSTs.

    Returns (bundle_text, bytes_used, files_used, files_total).
    """
    files: list[Path] = []
    for pattern in WALK_GLOBS:
        files.extend(mod_dir.rglob(pattern))
    files = sorted(set(files))

    # Tier 1: contract-bearing files at the module root, in canonical order.
    tier1_names = ["00-overview.md", "97-acceptance-criteria.md", "98-changelog.md", "99-consistency-report.md"]
    tier1: list[Path] = []
    for name in tier1_names:
        candidate = mod_dir / name
        if candidate in files:
            tier1.append(candidate)
            files.remove(candidate)
    files = tier1 + files

    parts: list[str] = []
    total = 0
    used = 0
    for f in files:
        try:
            txt = f.read_text(encoding="utf-8", errors="replace")
        except Exception:
            continue
        header = f"\n\n===== FILE: {f.relative_to(ROOT)} =====\n\n"
        chunk = header + txt
        if total + len(chunk) > MAX_BYTES:
            remaining = MAX_BYTES - total
            if remaining > 500:
                parts.append(chunk[:remaining] + f"\n\n[...TRUNCATED at {MAX_BYTES//1024}KB context cap...]")
                total += remaining
                used += 1
            break
        parts.append(chunk)
        total += len(chunk)
        used += 1
    return "".join(parts), total, used, len(files)


def call_gateway(content: str, api_key: str) -> dict[str, Any]:
    body = json.dumps({
        "model": MODEL,
        "messages": [
            {"role": "system", "content": RUBRIC},
            {"role": "user", "content": content},
        ],
        "temperature": 0,
    }).encode()
    req = urllib.request.Request(
        ENDPOINT,
        data=body,
        headers={
            "Authorization": f"Bearer {api_key}",
            "Content-Type": "application/json",
            "User-Agent": USER_AGENT,
        },
    )
    last: Exception | None = None
    for attempt in range(3):
        try:
            with urllib.request.urlopen(req, timeout=120) as r:
                return json.loads(r.read())
        except urllib.error.HTTPError as e:
            last = e
            if e.code in (429, 502, 503, 504) and attempt < 2:
                time.sleep(5 * (attempt + 1))
                continue
            raise
        except Exception as e:  # noqa: BLE001
            last = e
            if attempt < 2:
                time.sleep(3 * (attempt + 1))
                continue
            raise
    raise RuntimeError(f"retries exhausted: {last}")


def parse_score(raw: str) -> dict[str, Any]:
    s = raw.strip()
    if s.startswith("```"):
        s = s.split("```", 2)[1]
        if s.startswith("json"):
            s = s[4:]
        s = s.rsplit("```", 1)[0]
    s = s.strip()
    # Tolerate stray backslashes the model occasionally emits inside JSON strings.
    try:
        return json.loads(s)
    except json.JSONDecodeError:
        s2 = re.sub(r'\\(?!["\\/bfnrtu])', "", s)
        return json.loads(s2)


def band(total: int) -> str:
    if total >= 90:
        return "EXCELLENT"
    if total >= 75:
        return "GOOD"
    if total >= 60:
        return "NEEDS_WORK"
    return "BLOCKING"


def audit_module(mod: Path, api_key: str | None, no_network: bool, force: bool, axis: str) -> dict[str, Any]:
    cache_file = CACHE_DIR / f"{mod.name}.json"
    bundle, used_bytes, used_files, total_files = load_module_bundle(mod)
    # Fold axis into the cache key so v6 caches (no axis) re-score under v7
    # and any future axis re-classification invalidates the prior score.
    bundle_sha = hashlib.sha256(f"axis={axis}\n{bundle}".encode()).hexdigest()[:16]

    if cache_file.exists() and not force:
        cached = json.loads(cache_file.read_text())
        if cached.get("bundle_sha") == bundle_sha and cached.get("rubric") == "v7":
            cached["from_cache"] = True
            return cached

    if no_network:
        return {
            "module": mod.name,
            "axis": axis,
            "no_network": True,
            "files_used": used_files,
            "files_total": total_files,
            "bytes_used": used_bytes,
            "bundle_sha": bundle_sha,
        }

    if api_key is None:
        raise RuntimeError("LOVABLE_API_KEY not set; pass --no-network for stats-only mode")

    prompt = f"# Module: spec/{mod.name}\n\nFiles: {used_files}/{total_files}, ~{used_bytes//1024} KB\n\n{bundle}"
    resp = call_gateway(prompt, api_key)
    raw = resp["choices"][0]["message"]["content"]
    parsed = parse_score(raw)
    parsed["module"] = mod.name
    parsed["files_used"] = used_files
    parsed["files_total"] = total_files
    parsed["bytes_used"] = used_bytes
    parsed["bundle_sha"] = bundle_sha
    parsed["total_v6"] = sum(int(parsed[k]) for k in ("d1", "d2", "d3", "d4", "d5"))
    v7 = apply_rubric_v7({k: int(parsed[k]) for k in ("d1", "d2", "d3", "d4", "d5")}, axis)
    parsed.update(v7)
    parsed["total"] = v7["total_v7"]  # band + report use v7 score
    parsed["band"] = band(parsed["total"])
    parsed["rubric"] = "v7"
    CACHE_DIR.mkdir(parents=True, exist_ok=True)
    cache_file.write_text(json.dumps(parsed, indent=2))
    return parsed


def write_report(results: list[dict[str, Any]], out: Path) -> None:
    scored = [r for r in results if "total" in r]
    if not scored:
        out.write_text("# Audit AI Implementability — no scored results\n")
        return
    avg = lambda k: round(sum(r[k] for r in scored) / len(scored), 1)
    overall = round(sum(r["total"] for r in scored) / len(scored), 1)
    sev = {"CRITICAL": 0, "HIGH": 0, "MEDIUM": 0, "LOW": 0}
    for r in scored:
        for i in r.get("issues", []):
            s = str(i.get("severity", "")).upper()
            if s in sev:
                sev[s] += 1
    lines = [
        "# Spec AI-Implementability Audit (production)",
        "",
        f"**Generator:** `linter-scripts/audit-ai-implementability.py`  ",
        f"**Modules scored:** {len(scored)}  ",
        f"**Overall:** **{overall} / 100** ({band(int(overall))})  ",
        f"**Severity tally:** CRITICAL {sev['CRITICAL']} · HIGH {sev['HIGH']} · MEDIUM {sev['MEDIUM']} · LOW {sev['LOW']}",
        "",
        "| Dimension | Avg |",
        "|---|---:|",
        f"| D1 Contract Clarity | {avg('d1')}/20 |",
        f"| D2 AC Coverage | {avg('d2')}/20 |",
        f"| D3 Edge/Error | {avg('d3')}/20 |",
        f"| D4 Examples | {avg('d4')}/20 |",
        f"| D5 Cross-Ref Closure | {avg('d5')}/20 |",
        "",
        "## Per-module ranking (low → high)",
        "",
        "| Rank | Module | Axis | Total (v7) | Raw (v6) | D1 | D2 | D3 | D4 | D5 | Files | KB | Band |",
        "|---:|---|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---|",
    ]
    for i, r in enumerate(sorted(scored, key=lambda x: x["total"]), 1):
        cap_marker = " 🔒" if r.get("total_v7") == r.get("axis_cap") else ""
        lines.append(
            f"| {i} | `spec/{r['module']}` | {r.get('axis','?')} | **{r['total']}**{cap_marker} | {r.get('total_v6','?')} | "
            f"{r['d1']} | {r['d2']} | {r['d3']} | {r['d4']} | {r['d5']} | "
            f"{r['files_used']}/{r['files_total']} | {r['bytes_used']//1024} | {r.get('band','')} |"
        )
    out.parent.mkdir(parents=True, exist_ok=True)
    out.write_text("\n".join(lines) + "\n")


def main(argv: list[str] | None = None) -> int:
    ap = argparse.ArgumentParser(description="Deep-walk AI implementability audit (Phase 153 Task A4)")
    ap.add_argument("--module", help="Only audit this module slug (e.g. 04-database-conventions)")
    ap.add_argument("--no-network", action="store_true", help="Print bundle stats only; never call gateway")
    ap.add_argument("--force", action="store_true", help="Ignore cache and re-score")
    ap.add_argument("--json", action="store_true", help="Emit machine-readable JSON to stdout")
    ap.add_argument("--report-only", action="store_true", help="Always exit 0 (advisory mode)")
    ap.add_argument("--strict", action="store_true", help="Exit 1 if any module scores BLOCKING (<60)")
    ap.add_argument("--report", type=Path, default=DEFAULT_REPORT, help="Markdown report output path")
    args = ap.parse_args(argv)

    api_key = os.environ.get("LOVABLE_API_KEY")
    modules = discover_modules()
    if args.module:
        modules = [m for m in modules if m.name == args.module]
        if not modules:
            print(f"audit-ai-implementability: no module matches --module={args.module}", file=sys.stderr)
            return 2

    # AC-34-12 fail-fast: every module MUST declare a valid `content_axis` in
    # its 00-overview.md front-matter BEFORE any gateway call. Silent v6
    # uniform-weighting fallback is FORBIDDEN.
    axes: dict[str, str] = {}
    axis_errors: list[str] = []
    for mod in modules:
        axis, err = read_content_axis(mod)
        if err:
            axis_errors.append(err)
        else:
            axes[mod.name] = axis  # type: ignore[assignment]
    if axis_errors:
        print("audit-ai-implementability: invalid or missing content_axis (AC-34-12):", file=sys.stderr)
        for e in axis_errors:
            print(f"  - {e}", file=sys.stderr)
        print(f"  Allowed values: {sorted(AXIS_VALUES)}", file=sys.stderr)
        return 2

    results: list[dict[str, Any]] = []
    for mod in modules:
        try:
            r = audit_module(mod, api_key, args.no_network, args.force, axes[mod.name])
            results.append(r)
            if not args.json:
                if "total" in r:
                    cap_note = f" (cap {r['axis_cap']})" if r.get("total_v7") == r.get("axis_cap") else ""
                    print(f"  {mod.name:40s} {r['total']:3d}/100  {r.get('band','')}  "
                          f"axis={r.get('axis','?'):20s}{cap_note}  "
                          f"({r['files_used']}/{r['files_total']} files, {r['bytes_used']//1024} KB)"
                          f"{'  [cache]' if r.get('from_cache') else ''}")
                elif r.get("no_network"):
                    print(f"  {mod.name:40s} stats-only axis={r.get('axis','?'):20s} ({r['files_used']}/{r['files_total']} files, {r['bytes_used']//1024} KB, sha={r['bundle_sha']})")
        except Exception as e:  # noqa: BLE001
            print(f"  {mod.name:40s} ERROR: {e}", file=sys.stderr)
            results.append({"module": mod.name, "error": str(e)})
        time.sleep(0.5)

    if args.json:
        print(json.dumps(results, indent=2))
    else:
        write_report([r for r in results if "total" in r], args.report)
        print(f"\nReport: {args.report}")

    if args.report_only:
        return 0
    if args.strict:
        blocking = [r for r in results if r.get("band") == "BLOCKING"]
        if blocking:
            print(f"\nFAIL: {len(blocking)} module(s) in BLOCKING band (<60).", file=sys.stderr)
            return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
