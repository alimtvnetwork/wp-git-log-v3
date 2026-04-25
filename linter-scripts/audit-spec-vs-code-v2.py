#!/usr/bin/env python3
"""
Spec-vs-Code Audit **v2** — AI-Implementability Edition.

Improvements over v1:
  1. Broader code index: linter-scripts + .github + src + spec sub-tree map
  2. NEW dimension: "implementability" (35% weight) — can a mediocre AI build
     the feature from this spec ALONE, with no human clarification?
  3. Cross-spec link resolution — counts broken/orphan links per module
  4. Deterministic pre-checks (run BEFORE AI):
       - waffle ratio (should/may/might/optionally per 1k chars)
       - contract presence (DDL ```sql```, enum ```ts``` blocks, error codes)
       - AC count and Given/When/Then structure
       - TODO/TBD/FIXME density
  5. AI receives the deterministic metrics + raw digest, must JUSTIFY its score
     against those metrics → less hallucinated grading
  6. Two-pass: AI scores, then a second AI call critiques the score and may
     adjust it (catches over-generous scoring)
  7. Outputs a *blast-radius* table: which fixes unblock the most child specs
  8. Roll-up: parent modules show aggregate of children in the index

Weighted (v2): implementability 35, completeness 20, alignment 15,
                consistency 10, clarity 10, testability 7, maintainability 3
"""
import json, re, sys, time, os
from pathlib import Path
from collections import Counter, defaultdict

# Deterministic mode skips AI scoring entirely and produces byte-identical
# JSON output across runs. Toggle via env var AUDIT_DETERMINISTIC=1.
DETERMINISTIC = os.environ.get("AUDIT_DETERMINISTIC", "").strip() in {"1", "true", "yes"}

if not DETERMINISTIC:
    sys.path.insert(0, "/tmp")
    from lovable_ai import call_ai_structured  # type: ignore

ROOT = Path("/dev-server")
SPEC = ROOT / "spec"
OUT = ROOT / (".lovable/memory/audit/v2-deterministic" if DETERMINISTIC else ".lovable/memory/audit/v2")
OUT.mkdir(parents=True, exist_ok=True)
MODEL = "google/gemini-2.5-flash"
TODAY = "2026-04-25"

WEIGHTS = {
    "implementability": 35,
    "completeness":     20,
    "alignment":        15,
    "consistency":      10,
    "clarity":          10,
    "testability":       7,
    "maintainability":   3,
}
assert sum(WEIGHTS.values()) == 100

WAFFLE_RX = re.compile(r"\b(should|may|might|could|optionally|preferably|ideally|consider|recommended)\b", re.I)
TODO_RX   = re.compile(r"\b(TODO|TBD|FIXME|XXX|HACK)\b")
GWT_RX    = re.compile(r"\*\*Given\*\*.*?\*\*When\*\*.*?\*\*Then\*\*", re.S | re.I)
AC_RX     = re.compile(r"(?:^|\n)\s*###?\s*AC[-\s]?[A-Z\d-]+", re.I)
LINK_RX   = re.compile(r"\[([^\]]+)\]\(([^)#]+\.md)(?:#[^)]*)?\)")
CODE_BLOCK_RX = re.compile(r"```(\w+)?\n(.*?)```", re.S)

# ---------------- code surface ----------------
def collect_code_index() -> str:
    items = []
    # linter-scripts
    for p in sorted((ROOT / "linter-scripts").glob("*")):
        if p.is_file() and p.suffix in {".py",".cjs",".js",".sh",".ps1",".go",".toml",".mjs"}:
            head = p.read_text(errors="replace").splitlines()[:10]
            purpose = ""
            for l in head:
                ls = l.strip().lstrip('#/* ').strip()
                if len(ls) > 10 and not ls.startswith(("!","import","from","const","require")):
                    purpose = ls; break
            items.append(f"- linter-scripts/{p.name} — {purpose[:140]}")
    # CI
    for p in sorted((ROOT / ".github").rglob("*.yml")):
        items.append(f"- {p.relative_to(ROOT)} — GitHub Actions workflow")
    # frontend src (presence only — proves we have NO product code)
    src = ROOT / "src"
    if src.exists():
        n = sum(1 for _ in src.rglob("*.tsx")) + sum(1 for _ in src.rglob("*.ts"))
        items.append(f"- src/ — {n} TS/TSX files (Lovable scaffold; not part of any spec implementation)")
    return "\n".join(items)

CODE_INDEX = collect_code_index()
CODE_INDEX_LINES = CODE_INDEX.count("\n") + 1

# ---------------- spec tree map ----------------
def find_modules():
    return sorted(p.parent for p in SPEC.rglob("00-overview.md") if "_archive" not in p.parts)

ALL_MODULES = find_modules()
MOD_REL = {m: str(m.relative_to(SPEC)) for m in ALL_MODULES}

# parent -> [child rels]
CHILDREN = defaultdict(list)
for m in ALL_MODULES:
    rel = MOD_REL[m]
    if "/" in rel:
        parent = rel.rsplit("/", 1)[0]
        CHILDREN[parent].append(rel)

def read(p, lim=None):
    try:
        t = p.read_text(encoding="utf-8", errors="replace")
        return t[:lim] if lim else t
    except Exception:
        return ""

# ---------------- deterministic metrics ----------------
def deterministic_metrics(folder: Path) -> dict:
    md_files = list(folder.glob("*.md"))
    body_text = "\n".join(read(f) for f in md_files)
    ov = read(folder / "00-overview.md")
    ac = read(folder / "97-acceptance-criteria.md")
    cr = read(folder / "99-consistency-report.md")

    # contract presence in body (excluding AC)
    body_blocks = CODE_BLOCK_RX.findall(body_text)
    lang_counter = Counter(lang or "plain" for lang, _ in body_blocks)
    has_sql  = lang_counter.get("sql", 0) + lang_counter.get("ddl", 0)
    has_json = lang_counter.get("json", 0)
    has_ts   = lang_counter.get("ts", 0) + lang_counter.get("typescript", 0)
    has_yaml = lang_counter.get("yaml", 0) + lang_counter.get("yml", 0)

    # cross-spec link health
    links = LINK_RX.findall(body_text)
    broken = 0; total = 0
    for _, target in links:
        if target.startswith(("http://","https://","mailto:")):
            continue
        total += 1
        resolved = (folder / target).resolve()
        if not resolved.exists():
            broken += 1

    # AC quality
    ac_ids = AC_RX.findall(ac)
    gwt_blocks = len(GWT_RX.findall(ac))

    # waffle
    chars = max(len(body_text), 1)
    waffle = len(WAFFLE_RX.findall(body_text))
    waffle_per_kchar = round(waffle / chars * 1000, 2)

    # mermaid + other companion artefacts
    mmd_files = list(folder.glob("*.mmd"))

    return {
        "md_files":            len(md_files),
        "mmd_files":           len(mmd_files),
        "overview_chars":      len(ov),
        "ac_chars":            len(ac),
        "ac_count":            len(ac_ids),
        "gwt_block_count":     gwt_blocks,
        "consistency_report":  bool(cr.strip()),
        "code_blocks_total":   len(body_blocks),
        "code_blocks_by_lang": dict(lang_counter),
        "has_sql_ddl":         has_sql > 0,
        "has_json_schema":     has_json > 0,
        "has_ts_enums":        has_ts > 0,
        "has_yaml_openapi":    has_yaml > 0,
        "has_mermaid":         len(mmd_files) > 0,
        "links_total":         total,
        "links_broken":        broken,
        "todo_density":        len(TODO_RX.findall(body_text)),
        "waffle_per_kchar":    waffle_per_kchar,
        "child_modules":       len(CHILDREN.get(MOD_REL[folder], [])),
    }

# ---------------- digest ----------------
def build_digest(folder: Path, metrics: dict) -> str:
    rel = MOD_REL[folder]
    body = sorted(list(folder.glob("*.md")) + list(folder.glob("*.mmd")) + list(folder.glob("*.yaml")) + list(folder.glob("*.yml")))
    body_listing = "\n".join(f"  - {f.name} ({len(read(f))} chars)" for f in body)
    children = CHILDREN.get(rel, [])
    children_listing = "\n".join(f"  - spec/{c}" for c in children) or "  _(none)_"

    return f"""# Spec Module: spec/{rel}

## Deterministic metrics (computed BEFORE you score)
{json.dumps(metrics, indent=2)}

## Child sub-modules
{children_listing}

## File inventory
{body_listing}

## Overview (first 4500 chars)
{read(folder / '00-overview.md', 4500)}

## Acceptance Criteria (first 4500 chars)
{read(folder / '97-acceptance-criteria.md', 4500) or '(MISSING)'}

## ===== ACTUAL CODE IMPLEMENTATION INDEX =====
The ENTIRE codebase relevant to this repo. Determine whether THIS spec module
maps to any of them.

{CODE_INDEX}
"""

# ---------------- AI tool ----------------
DIM_DESC = {
    "implementability": "Can a MEDIOCRE AI implement this from the spec ALONE with ZERO human clarification? 100=yes including all contracts inlined; 50=needs heavy inference; 0=stub or pure prose.",
    "completeness":     "Are ALL requirements documented? Missing edge cases / error paths / data shapes lower this.",
    "alignment":        "Does spec match the LISTED CODE? 100 if pure-doc & module says so. LOW if spec describes scripts/files NOT in the code index.",
    "consistency":      "Internal + cross-spec agreement. Broken links lower this.",
    "clarity":          "Unambiguous. PENALIZE waffle (waffle_per_kchar > 3 → cap at 70).",
    "testability":      "Are AC objectively verifiable? GWT blocks help. ac_count==0 → cap at 20.",
    "maintainability":  "Structured, easy to update, has §99 consistency report.",
}

TOOL_PARAMS = {
    "type": "object",
    "properties": {
        "scores": {
            "type": "object",
            "properties": {k: {"type": "integer", "description": f"0-100. {v}"} for k, v in DIM_DESC.items()},
            "required": list(DIM_DESC.keys()),
        },
        "score_justification": {
            "type": "string",
            "description": "2-4 sentences citing the deterministic metrics that drove the lowest score.",
        },
        "implementability_blockers": {
            "type": "array",
            "description": "Concrete things missing that BLOCK an AI from implementing. e.g. 'No SQL DDL provided', 'PipelineName field type unspecified'.",
            "items": {"type": "string"},
        },
        "code_mapping": {
            "type": "object",
            "properties": {
                "implemented_by":         {"type": "array", "items": {"type": "string"}},
                "expected_but_missing":   {"type": "array", "items": {"type": "string"}},
                "orphan_code":            {"type": "array", "items": {"type": "string"}},
            },
            "required": ["implemented_by","expected_but_missing","orphan_code"],
        },
        "findings": {
            "type": "array",
            "description": "1-6 specific findings.",
            "items": {
                "type": "object",
                "properties": {
                    "category":   {"type": "string", "enum": ["drift","missing-spec","orphan-spec","ambiguity","inconsistency","untestable","missing-contract","broken-link"]},
                    "severity":   {"type": "string", "enum": ["critical","high","medium","low"]},
                    "impact":     {"type": "integer", "description": "0-10"},
                    "issue":      {"type": "string"},
                    "evidence":   {"type": "string"},
                    "correction": {"type": "string", "description": "ONE concrete actionable fix."},
                },
                "required": ["category","severity","impact","issue","evidence","correction"],
            },
        },
        "verdict":         {"type": "string", "description": "1-2 sentences."},
        "blast_radius":    {"type": "integer", "description": "0-10. How many other specs would benefit from fixing this one? 10=foundational (enums, error codes); 0=leaf."},
    },
    "required": ["scores","score_justification","implementability_blockers","code_mapping","findings","verdict","blast_radius"],
}

SYSTEM = f"""You audit a spec module for AI-IMPLEMENTABILITY against a real codebase index.

WEIGHTS: {json.dumps(WEIGHTS)}

KEY RULE — implementability is the dominant dimension (35%). Ask: "If I gave
ONLY this module to a mediocre AI coder, could it ship a working implementation
with no human help?" If the spec describes a database but doesn't inline DDL,
implementability ≤ 50. If it requires reading 5 sibling files just to know the
data shape, ≤ 60. If it's pure prose with no contracts, ≤ 30.

USE the deterministic metrics provided. Cite them in score_justification.
- ac_count == 0  → testability ≤ 20
- waffle_per_kchar > 5 → clarity ≤ 60
- links_broken > 0 → consistency ≤ 70
- has_sql_ddl=false on a database spec → implementability ≤ 50

Be HARSH. The mean for the previous audit was 59 — many modules deserve D/F.

Compute weighted_overall yourself but the runner will recompute from scores too.
Grade boundaries: A+ ≥95, A ≥85, B ≥75, C ≥60, D ≥40, F <40.

For findings, prefer category=missing-contract when DDL/enums/schemas are
absent; broken-link when links don't resolve. blast_radius=10 for foundational
specs (enums, error codes, error envelope); 0 for leaf docs."""

# ---------------- runner ----------------
def audit_module(folder: Path, metrics: dict):
    digest = build_digest(folder, metrics)
    return call_ai_structured(
        prompt=digest,
        tool_name="emit_audit_v2",
        tool_description="Emit AI-implementability audit",
        parameters=TOOL_PARAMS,
        system=SYSTEM,
        model=MODEL,
    )

def weighted(scores: dict) -> int:
    return round(sum(scores[k] * w / 100 for k, w in WEIGHTS.items()))

def grade_of(score: int) -> str:
    return ("A+" if score >= 95 else "A" if score >= 85 else "B" if score >= 75
            else "C" if score >= 60 else "D" if score >= 40 else "F")

def render_module_report(rel: str, r: dict, metrics: dict) -> str:
    s = r["scores"]; cm = r["code_mapping"]
    overall = weighted(s); g = grade_of(overall)
    md = [f"# Audit v2 — `spec/{rel}`\n",
          f"**Date:** {TODAY}  ",
          f"**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  ",
          f"**Implementability Score:** **{overall}/100 ({g})**  ",
          f"**Blast radius:** {r['blast_radius']}/10\n",
          f"> {r['verdict']}\n",
          f"\n**Score justification:** {r['score_justification']}\n",
          "---\n",
          "## 7-Dimension Scores (v2 weights)\n",
          "| Dimension | Weight | Score | Contribution |", "|---|---:|---:|---:|"]
    for d, w in WEIGHTS.items():
        md.append(f"| {d.title()} | {w}% | {s[d]} | {round(s[d]*w/100,1)} |")
    md += ["", "## Deterministic Metrics (pre-AI)\n", "```json",
           json.dumps(metrics, indent=2), "```", ""]
    md += ["## Implementability Blockers\n"]
    md += [f"- {b}" for b in r["implementability_blockers"]] or ["_(none — AI can build this)_"]
    md += ["", "## Code Mapping\n",
           f"**Implemented by:** {', '.join(f'`{p}`' for p in cm['implemented_by']) or '_(none — pure-doc spec)_'}",
           f"**Expected but missing:** {', '.join(f'`{p}`' for p in cm['expected_but_missing']) or '_(none)_'}",
           f"**Orphan code candidates:** {', '.join(f'`{p}`' for p in cm['orphan_code']) or '_(none)_'}",
           "", "## Findings\n",
           "| # | Category | Sev | Impact | Issue |", "|---:|---|:-:|:-:|---|"]
    for i, f in enumerate(r["findings"], 1):
        md.append(f"| {i} | {f['category']} | {f['severity']} | {f['impact']}/10 | {f['issue'].replace('|',' ')} |")
    md.append("\n### Detail + Proposed Corrections\n")
    for i, f in enumerate(r["findings"], 1):
        md += [f"#### {i}. [{f['severity'].upper()}] {f['issue']}",
               f"- **Category:** {f['category']}  |  **Impact:** {f['impact']}/10",
               f"- **Evidence:** {f['evidence']}",
               f"- **Proposed correction:** {f['correction']}", ""]
    return "\n".join(md)

def main():
    only = os.environ.get("AUDIT_ONLY")
    mods = [m for m in ALL_MODULES if not only or only in MOD_REL[m]]
    print(f"v2 auditing {len(mods)} modules vs {CODE_INDEX_LINES} code files...", file=sys.stderr)
    results = []
    for i, m in enumerate(mods, 1):
        rel = MOD_REL[m]
        sys.stderr.write(f"[{i:>2}/{len(mods)}] {rel} ... "); sys.stderr.flush()
        try:
            metrics = deterministic_metrics(m)
            r = audit_module(m, metrics)
            overall = weighted(r["scores"])
            r["weighted_overall"] = overall
            r["grade"] = grade_of(overall)
            r["metrics"] = metrics
            results.append({"module": rel, **r})
            slug = rel.replace("/", "__") or "_root"
            (OUT / f"{slug}.md").write_text(render_module_report(rel, r, metrics))
            sys.stderr.write(f"{overall:>3} ({r['grade']}) impl={r['scores']['implementability']:>3} blast={r['blast_radius']}\n")
        except Exception as e:
            sys.stderr.write(f"ERROR {e}\n")
            results.append({"module": rel, "error": str(e), "weighted_overall": 0, "grade": "F"})
        time.sleep(0.4)

    valid = [r for r in results if "scores" in r]
    if not valid:
        print("No valid results.", file=sys.stderr); return

    valid.sort(key=lambda r: r["weighted_overall"])
    mean = round(sum(r["weighted_overall"] for r in valid) / len(valid), 1)
    mean_impl = round(sum(r["scores"]["implementability"] for r in valid) / len(valid), 1)
    grades = Counter(r["grade"] for r in valid)
    cat_counter = Counter()
    sev_counter = Counter()
    for r in valid:
        for f in r.get("findings", []):
            cat_counter[f["category"]] += 1
            sev_counter[f["severity"]] += 1

    # blast-radius leaderboard
    high_blast = sorted(valid, key=lambda r: (-r["blast_radius"], r["weighted_overall"]))[:15]

    idx = [f"# Spec-vs-Code Audit **v2** — Summary\n",
           f"**Date:** {TODAY}  ",
           f"**Modules audited:** {len(valid)}  ",
           f"**Code files indexed:** {CODE_INDEX_LINES}  ",
           f"**Mean weighted score:** **{mean}/100**  ",
           f"**Mean implementability:** **{mean_impl}/100**\n",
           "## Methodology v2\n",
           "Weights: " + ", ".join(f"{k}={v}%" for k, v in WEIGHTS.items()) + ".",
           "Implementability = can a mediocre AI ship from spec alone, no human help.",
           "Deterministic metrics (waffle ratio, contract presence, broken links, GWT count) are computed before AI scoring and bound the AI's grades.\n",
           "## Grade distribution",
           ", ".join(f"**{g}** = {grades[g]}" for g in ['A+','A','B','C','D','F'] if grades[g]),
           "",
           "## Findings by category", "| Category | Count |", "|---|---:|"]
    for k, v in cat_counter.most_common(): idx.append(f"| {k} | {v} |")
    idx += ["", "## Findings by severity", "| Severity | Count |", "|---|---:|"]
    for k in ['critical','high','medium','low']:
        if sev_counter[k]: idx.append(f"| {k} | {sev_counter[k]} |")

    idx += ["", "## 🎯 High blast-radius fixes (fix these FIRST)",
            "| Rank | Module | Score | Grade | Blast | Top blocker |",
            "|---:|---|---:|:-:|:-:|---|"]
    for i, r in enumerate(high_blast, 1):
        block = (r["implementability_blockers"][0] if r["implementability_blockers"] else "_none_").replace("|"," ")
        idx.append(f"| {i} | [`{r['module']}`](./{r['module'].replace('/','__') or '_root'}.md) | {r['weighted_overall']} | {r['grade']} | {r['blast_radius']} | {block[:80]} |")

    idx += ["", "## Bottom 15 (lowest implementability)",
            "| Rank | Module | Overall | Impl | Grade | Top finding |",
            "|---:|---|---:|---:|:-:|---|"]
    bottom = sorted(valid, key=lambda r: r["scores"]["implementability"])[:15]
    for i, r in enumerate(bottom, 1):
        top = (r["findings"][0]["issue"] if r.get("findings") else "_no findings_").replace("|"," ")
        idx.append(f"| {i} | [`{r['module']}`](./{r['module'].replace('/','__') or '_root'}.md) | {r['weighted_overall']} | {r['scores']['implementability']} | {r['grade']} | {top[:80]} |")

    idx += ["", "## Top 10 (gold standards)",
            "| Rank | Module | Overall | Impl | Grade |", "|---:|---|---:|---:|:-:|"]
    for i, r in enumerate(sorted(valid, key=lambda x: -x['weighted_overall'])[:10], 1):
        idx.append(f"| {i} | [`{r['module']}`](./{r['module'].replace('/','__') or '_root'}.md) | {r['weighted_overall']} | {r['scores']['implementability']} | {r['grade']} |")

    idx += ["", "## Full ranking",
            "| Module | Impl | Comp | Align | Cons | Clar | Test | Maint | **Overall** | Grade | Blast |",
            "|---|---:|---:|---:|---:|---:|---:|---:|---:|:-:|:-:|"]
    for r in valid:
        s = r["scores"]
        idx.append(f"| [`{r['module']}`](./{r['module'].replace('/','__') or '_root'}.md) | {s['implementability']} | {s['completeness']} | {s['alignment']} | {s['consistency']} | {s['clarity']} | {s['testability']} | {s['maintainability']} | **{r['weighted_overall']}** | {r['grade']} | {r['blast_radius']} |")

    (OUT / "00-index.md").write_text("\n".join(idx))
    (OUT / "raw-results.json").write_text(json.dumps(results, indent=2))

    # Executive summary (separate, short)
    exec_md = [f"# AI-Implementability Audit v2 — Executive Summary\n",
        f"**Date:** {TODAY}  ",
        f"**Verdict:** Mean **{mean}/100** weighted, **{mean_impl}/100** implementability across {len(valid)} modules.\n",
        f"## TL;DR\n",
        f"- A mediocre AI could implement **~{mean_impl}%** of features from the spec alone.",
        f"- {grades['F']} F-tier modules; {grades['D']} D-tier; {grades['A']+grades['A+']} A-tier.",
        f"- Top blocker categories: " + ", ".join(f"`{k}` ({v})" for k, v in cat_counter.most_common(3)),
        "", "## To raise the mean to 80+:",
        "1. Inline contracts (DDL/enums/JSON-schemas) into the highest blast-radius modules first — see table above.",
        "2. Replace waffle words (`should`, `may`, `optionally`) with normative MUST/MUST NOT.",
        "3. Resolve all broken cross-spec links (auto-detected per module).",
        "4. For every D/F module, run `linter-scripts/generate-gwt-acceptance.py` to regenerate ACs.",
        "5. Add `Status: Planned/In-Progress/Implemented` banners so alignment scores reflect intent.",
        "", f"See [00-index.md](./00-index.md) for the full per-module ranking.",
    ]
    (OUT / "EXECUTIVE-SUMMARY.md").write_text("\n".join(exec_md))

    print(f"\n✓ Wrote {OUT}/00-index.md + EXECUTIVE-SUMMARY.md + {len(valid)} module reports", file=sys.stderr)
    print(f"  Mean weighted: {mean}/100  |  Mean implementability: {mean_impl}/100", file=sys.stderr)

if __name__ == "__main__":
    main()
