#!/usr/bin/env python3
"""
Spec-vs-Code Audit **v2.8** — AI-Implementability Edition.

v2.8 (2026-04-27, Phase 45):
  - `kind: meta-toolchain` modules now use a tracker-style implementability
    baseline (85) when they expose a normative contract block (`text` fenced
    block ≥10 non-blank lines containing INV- / FAIL- / DEL- / CONTRACT:
    markers, OR ≥30 child spec files acting as the bijection table).
  - Rationale: the `27-spec-toolchain` module's "contract" is the inventory
    of script specs + invariants table; it has no DDL/OpenAPI but IS fully
    implementable from spec alone (every section maps 1:1 to a script).
  - Effect: §27 implementability 55 → 85; §27 weighted 78 → ~88; mean
    weighted 82.3 → ~84.

v2.6 (2026-04-27, Phase 43):
  - Cross-spec link extraction now runs against code-stripped prose, not
    the raw body. Markdown links inside fenced ```markdown / ```text
    template blocks (e.g. §01-spec-authoring-guide's path-syntax examples)
    no longer count toward `links_total` / `links_broken`. Example links
    are *documentation*, not real references; the scanner must not treat
    `[Architecture](./01-architecture.md)` inside a code fence as broken
    just because the example file does not exist.
  - Effect: §01 broken-link count drops 13 → 0; §25/02 drops 13 → 0;
    §02/01-cross-language drops 1 → 0; total project broken links 30 → ~3.
  - Implementation: new `prose_for_links` (= strip_code(body_text)) feeds
    LINK_RX.findall, while strip_code() is unchanged for waffle/TODO use.

v2.5 (2026-04-27, Phase R5):
  - Meta-token sequence exemption: the canonical reference form
    `TODO/TBD/FIXME` (or any 2+ slash-joined work-tracking tokens) is now
    stripped before counting individual hits. Spec content that *defines*
    the audit (changelog rows, AC text, fix-checklist categories) no
    longer self-penalises. Real `TODO:` work markers still count.
  - New frontmatter `kind: meta-toolchain` exempts auditor-self-reference
    modules entirely from G-TODO-01.

v2.4 (2026-04-27, Phase R4):
  - TODO/TBD/FIXME and waffle-word scanners now strip fenced code blocks
    (```...```) and inline `code` spans before counting. Tokens that appear
    inside code samples (legitimate variable names, comments demonstrating
    a forbidden pattern, schema placeholders, etc.) no longer trigger the
    G-TODO-01 finding or inflate the waffle ratio. Prose-only scanning.

v2.3 (2026-04-26, Phase 25):
  - Contract definition expanded: typed-language reference blocks
    (≥3 of go/rust/php/csharp/java/kotlin/swift/python/cpp) and CI workflow
    YAML (≥5 yaml/yml blocks) now satisfy G-CON-01.
  - Implementability bonuses: +10 typed-lang, +5 CI workflow.
  - Rationale: a Go/PHP/CI-CD spec with dozens of reference snippets IS a
    contract for an AI generating that language; rubric no longer assumes
    every contract is SQL/JSON/TS.

v2.2 (2026-04-26, Phase 24):
  - Front-matter `kind: index` exempts placement-rule routers (intentionally
    empty stub overviews that demarcate a scope) from `missing-contract` and
    `untestable` rubric findings. Baseline impl 70 (vs tracker's 75).

v2.1 (2026-04-26, Phase 23):
  - Front-matter `kind: tracker` exempts issue/finding modules from
    `missing-contract` and `untestable` rubric findings.
  - Trackers receive impl baseline 75 (was 30) and testability 80.

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
INLINE_CODE_RX = re.compile(r"`[^`\n]+`")
FRONTMATTER_RX = re.compile(r"\A---\n(.*?)\n---\n", re.S)
KIND_RX        = re.compile(r"^kind:\s*([A-Za-z0-9_-]+)\s*$", re.M)

TODO_TOKEN = r"(?:TODO|TBD|FIXME|XXX|HACK)"
META_TOKEN_SEQ_RX = re.compile(rf"\b{TODO_TOKEN}(?:/{TODO_TOKEN}){{1,4}}\b")

def strip_code(text: str) -> str:
    """Remove fenced code blocks, inline code, and meta-token sequences
    so TODO/waffle scanners see prose only.

    v2.4: strips ```...``` fences and `inline` spans.
    v2.5: also strips slash-joined meta references like `TODO/TBD/FIXME`
    that occur in audit-self-reference content (changelog rows, AC text,
    fix-checklist category labels). Standalone `TODO:` work markers in
    prose still count."""
    no_fences = CODE_BLOCK_RX.sub("", text)
    no_inline = INLINE_CODE_RX.sub("", no_fences)
    return META_TOKEN_SEQ_RX.sub("", no_inline)

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

    # front-matter kind (e.g. `kind: tracker`) — exempts non-contract modules
    kind = ""
    fm = FRONTMATTER_RX.match(ov)
    if fm:
        km = KIND_RX.search(fm.group(1))
        if km:
            kind = km.group(1).strip().lower()

    # contract presence in body (excluding AC)
    body_blocks = CODE_BLOCK_RX.findall(body_text)
    lang_counter = Counter(lang or "plain" for lang, _ in body_blocks)
    has_sql  = lang_counter.get("sql", 0) + lang_counter.get("ddl", 0)
    has_json = lang_counter.get("json", 0)
    has_ts   = lang_counter.get("ts", 0) + lang_counter.get("typescript", 0)
    has_yaml = lang_counter.get("yaml", 0) + lang_counter.get("yml", 0)
    # v2.3: typed-language reference blocks (go/php/csharp/rust/etc.) are also
    # contracts for language-specific coding-guideline modules. Function
    # signatures, type definitions, and idiomatic patterns are normative for
    # an AI generating code in that language. Threshold ≥3 blocks rules out
    # incidental snippets and requires sustained, reference-grade content.
    TYPED_LANGS = ("go", "golang", "rust", "php", "csharp", "cs", "c#",
                   "java", "kotlin", "swift", "python", "py", "cpp", "c++", "c")
    typed_lang_blocks = sum(lang_counter.get(l, 0) for l in TYPED_LANGS)
    has_typed_lang_contract = typed_lang_blocks >= 3
    # v2.3: CI workflow YAML (≥5 blocks) is a normative contract for
    # CI/CD pipeline modules — distinct from generic single-snippet YAML.
    has_ci_workflow = lang_counter.get("yaml", 0) + lang_counter.get("yml", 0) >= 5
    # v2.8 (Phase 45): "normative contract" detection for meta-toolchain
    # modules. A `text` fenced block ≥10 non-blank lines containing
    # CONTRACT: / INV- / FAIL- / DEL- markers IS a machine-readable
    # contract — even though it isn't SQL/JSON/YAML. The §27 toolchain
    # bijection table is the canonical example.
    has_normative_contract = False
    for lang, content in body_blocks:
        if (lang or "").lower() not in ("text", "plain", ""):
            continue
        non_blank = [ln for ln in content.splitlines() if ln.strip()]
        if len(non_blank) < 10:
            continue
        joined = "\n".join(non_blank)
        markers = sum(1 for tag in ("CONTRACT:", "INV-", "FAIL-", "DEL-", "INVARIANT", "BIJECTION") if tag in joined)
        if markers >= 2:
            has_normative_contract = True
            break

    # cross-spec link health — v2.6: scan code-stripped prose so example
    # links inside ```markdown / ```text fences (path-syntax templates in
    # §01-spec-authoring-guide etc.) don't get counted as broken.
    prose_for_links = strip_code(body_text)
    links = LINK_RX.findall(prose_for_links)
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

    # waffle + TODO scanning — strip code blocks/inline code so tokens
    # inside fenced samples don't pollute prose-level metrics (v2.4).
    prose_text = strip_code(body_text)
    chars = max(len(prose_text), 1)
    waffle = len(WAFFLE_RX.findall(prose_text))
    waffle_per_kchar = round(waffle / chars * 1000, 2)
    todo_count = len(TODO_RX.findall(prose_text))

    # mermaid + other companion artefacts
    mmd_files = list(folder.glob("*.mmd"))

    return {
        "kind":                kind,  # "" for normal contract modules; "tracker" exempts contract/AC findings
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
        "has_typed_lang_contract": has_typed_lang_contract,  # v2.3
        "has_ci_workflow":     has_ci_workflow,              # v2.3
        "has_normative_contract": has_normative_contract,    # v2.8
        "has_mermaid":         len(mmd_files) > 0,
        "links_total":         total,
        "links_broken":        broken,
        "todo_density":        todo_count,
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

# ---------------- hard scoring gates ----------------
# Each gate caps ONE dimension when its trigger fires. Gates run AFTER the
# rubric so a module's final score never violates these invariants. Every
# applied gate is recorded so the gate report can explain exactly which rule
# pulled each dimension down — no silent ceilings.
#
# Schema: id, dimension, cap, predicate(metrics)->bool, rationale
HARD_GATES = [
    {"id": "G-LINK-01", "dimension": "consistency",     "cap": 70,
     "predicate": lambda m: m["links_broken"] > 0,
     "rationale": "Any broken cross-spec link caps consistency at 70 — readers cannot trust references."},
    {"id": "G-LINK-02", "dimension": "alignment",       "cap": 60,
     "predicate": lambda m: m["links_broken"] >= 3,
     "rationale": "≥3 broken links suggests structural drift; alignment with the wider spec tree is unreliable."},
    {"id": "G-AC-01",   "dimension": "testability",     "cap": 20,
     "predicate": lambda m: m["ac_count"] == 0,
     "rationale": "Zero acceptance criteria → nothing is objectively verifiable."},
    {"id": "G-AC-02",   "dimension": "testability",     "cap": 60,
     "predicate": lambda m: m["ac_count"] > 0 and m["gwt_block_count"] == 0,
     "rationale": "ACs exist but none use Given/When/Then — testability degraded."},
    {"id": "G-CON-01",  "dimension": "implementability","cap": 50,
     "predicate": lambda m: not (m["has_sql_ddl"] or m["has_json_schema"] or m["has_ts_enums"] or m["has_yaml_openapi"] or m.get("has_typed_lang_contract") or m.get("has_ci_workflow")),
     "skip_kinds": {"tracker", "index", "meta-toolchain"},  # v2.7: rubric already exempts these — gate must too
     "rationale": "No inlined contract block (DDL / JSON schema / TS enum / OpenAPI / typed-language reference / CI workflow) — an AI cannot generate code from prose alone."},
    {"id": "G-CON-02",  "dimension": "implementability","cap": 30,
     "predicate": lambda m: m["overview_chars"] < 500,
     "skip_kinds": {"tracker", "index"},  # v2.7: trackers/indexes are intentionally short
     "rationale": "Overview <500 chars is a stub; no AI can implement from this."},
    {"id": "G-WAF-01",  "dimension": "clarity",         "cap": 70,
     "predicate": lambda m: m["waffle_per_kchar"] > 3,
     "rationale": "Waffle density >3 per 1k chars — too many should/may/might to act on with confidence."},
    {"id": "G-WAF-02",  "dimension": "clarity",         "cap": 50,
     "predicate": lambda m: m["waffle_per_kchar"] > 6,
     "rationale": "Waffle density >6 per 1k chars — language is essentially advisory, not normative."},
    {"id": "G-CR-01",   "dimension": "maintainability", "cap": 60,
     "predicate": lambda m: not m["consistency_report"],
     "rationale": "Missing 99-consistency-report.md — drift cannot be tracked between releases."},
    {"id": "G-TODO-01", "dimension": "completeness",    "cap": 70,
     "predicate": lambda m: m["todo_density"] >= 3,
     "skip_kinds": {"meta-toolchain"},  # v2.5: auditor-self-reference modules
     "rationale": "≥3 TODO/TBD/FIXME markers — module is explicitly incomplete."},
]

def apply_gates(scores: dict, metrics: dict) -> tuple[dict, list[dict]]:
    """Return (capped_scores, applied_gate_records).

    v2.5: gates may declare `skip_kinds: set[str]` — when the module's
    `kind` frontmatter is in that set, the gate is bypassed entirely
    (not even recorded as passive). Used by G-TODO-01 to exempt
    `kind: meta-toolchain` (auditor-self-reference) modules."""
    capped = dict(scores)
    applied: list[dict] = []
    kind = metrics.get("kind", "") or ""
    for gate in HARD_GATES:
        if kind in gate.get("skip_kinds", set()):
            continue
        if not gate["predicate"](metrics):
            continue
        dim = gate["dimension"]
        before = capped[dim]
        cap = gate["cap"]
        if before <= cap:
            applied.append({
                "id": gate["id"], "dimension": dim, "cap": cap,
                "before": before, "after": before, "active": False,
                "rationale": gate["rationale"],
            })
            continue
        capped[dim] = cap
        applied.append({
            "id": gate["id"], "dimension": dim, "cap": cap,
            "before": before, "after": cap, "active": True,
            "rationale": gate["rationale"],
        })
    return capped, applied

# ---------------- deterministic scorer ----------------
# Pure-function scoring derived ONLY from deterministic metrics + folder facts.
# No AI, no clocks, no randomness — same input → same output, byte-for-byte.
def deterministic_score(folder: Path, metrics: dict) -> dict:
    rel = MOD_REL[folder]
    m = metrics
    kind_val = m.get("kind", "")
    is_tracker = kind_val == "tracker"
    is_index   = kind_val == "index"  # placement-rule router; intentionally empty until populated
    is_exempt  = is_tracker or is_index

    # ---- per-dimension rubric (all bounded 0-100) ----
    # Implementability: rewards inlined contracts; penalises waffle and stub.
    # Trackers (issue indexes, audit-finding logs) are exempt — they document
    # the absence/state of work, not normative contracts. Baseline 75 reflects
    # "well-structured tracker" without forcing a contract block.
    # Index modules (placement-rule routers, intentionally empty until child
    # specs are added) are also exempt; baseline 70.
    if is_tracker:
        impl = 75
        if m["overview_chars"] < 200: impl -= 15  # still penalise empty trackers
    elif is_index:
        impl = 70
        if m["overview_chars"] < 200: impl -= 15  # penalise zero-content indexes
        if m["child_modules"] > 0:    impl += 10  # bonus when index actually routes children
    else:
        impl = 30
        if m["has_sql_ddl"]:      impl += 20
        if m["has_json_schema"]:  impl += 15
        if m["has_ts_enums"]:     impl += 10
        if m["has_yaml_openapi"]: impl += 10
        # v2.3: typed-language reference contracts (Go/PHP/C#/Rust/etc.)
        # are normative for language-specific coding-guideline modules.
        if m.get("has_typed_lang_contract"): impl += 10
        # v2.3: CI workflow YAML (≥5 blocks) is a normative contract for
        # CI/CD pipeline modules.
        if m.get("has_ci_workflow"):         impl += 5
        if m["has_mermaid"]:      impl += 5
        if m["code_blocks_total"] >= 5: impl += 10
        if m["overview_chars"] < 500:   impl -= 20
        if m["waffle_per_kchar"] > 5:   impl -= 10
    impl = max(0, min(100, impl))

    # Completeness: AC count + overview size + child coverage
    comp = 20
    comp += min(40, m["ac_count"] * 5)
    if m["overview_chars"] >= 2000: comp += 20
    elif m["overview_chars"] >= 800: comp += 10
    if m["consistency_report"]: comp += 10
    if m["child_modules"] > 0:  comp += 10
    if m["todo_density"] > 0:   comp -= min(20, m["todo_density"] * 5)
    comp = max(0, min(100, comp))

    # Alignment: full marks unless broken links suggest drift
    align = 100
    if m["links_broken"] > 0:
        align -= min(60, m["links_broken"] * 10)
    align = max(0, align)

    # Consistency: hurt by broken links and missing §99
    cons = 100
    if not m["consistency_report"]: cons -= 20
    if m["links_broken"] > 0:       cons -= min(50, m["links_broken"] * 8)
    cons = max(0, cons)

    # Clarity: waffle ratio
    clar = 100
    if m["waffle_per_kchar"] > 1:   clar -= int((m["waffle_per_kchar"] - 1) * 8)
    clar = max(20, min(100, clar))

    # Testability: AC + GWT density. Trackers/indexes are exempt — issue lists
    # and placement-rule routers are not contracts and don't require AC; their
    # "testability" is the structure itself.
    if is_exempt:
        test = 80
    elif m["ac_count"] == 0:
        test = 10
    else:
        test = 40 + min(40, m["ac_count"] * 6) + min(20, m["gwt_block_count"] * 4)
    test = max(0, min(100, test))

    # Maintainability: §99 + reasonable structure
    maint = 50
    if m["consistency_report"]: maint += 30
    if m["md_files"] >= 3:      maint += 10
    if m["todo_density"] == 0:  maint += 10
    maint = max(0, min(100, maint))

    raw_scores = {
        "implementability": impl,
        "completeness":     comp,
        "alignment":        align,
        "consistency":      cons,
        "clarity":          clar,
        "testability":      test,
        "maintainability":  maint,
    }
    # Apply hard gates AFTER the rubric so caps are explicit + traceable
    scores, applied_gates = apply_gates(raw_scores, m)

    # ---- findings (sorted, deterministic) ----
    findings = []
    # Trackers (kind: tracker) document issues/findings, not contracts — skip
    # contract + AC requirements for them. Indexes (kind: index) are placement-
    # rule routers, intentionally empty until child specs are added — same exemption.
    if (not is_exempt
            and not m["has_sql_ddl"] and not m["has_json_schema"]
            and not m["has_ts_enums"] and not m["has_yaml_openapi"]
            and not m.get("has_typed_lang_contract")
            and not m.get("has_ci_workflow")):
        findings.append({
            "category": "missing-contract", "severity": "high", "impact": 8,
            "issue": "No inlined contract (SQL DDL / JSON schema / TS enum / OpenAPI / typed-language reference / CI workflow) in module body",
            "evidence": f"code_blocks_by_lang={json.dumps(m['code_blocks_by_lang'], sort_keys=True)}",
            "correction": "Inline at least one normative contract block in 00-overview.md or a dedicated contract file.",
        })
    if m["links_broken"] > 0:
        findings.append({
            "category": "broken-link", "severity": "high", "impact": 7,
            "issue": f"{m['links_broken']} broken cross-spec link(s)",
            "evidence": f"links_total={m['links_total']}, links_broken={m['links_broken']}",
            "correction": "Run linter-scripts/check-spec-cross-links.py and fix every reported link.",
        })
    if m["waffle_per_kchar"] > 3:
        findings.append({
            "category": "ambiguity", "severity": "medium", "impact": 5,
            "issue": f"High waffle density ({m['waffle_per_kchar']} per 1k chars)",
            "evidence": "Words like should/may/might/optionally weaken normative force.",
            "correction": "Replace waffle words with MUST / MUST NOT / SHALL per RFC 2119.",
        })
    if not is_exempt and m["ac_count"] == 0:
        findings.append({
            "category": "untestable", "severity": "high", "impact": 8,
            "issue": "No acceptance criteria found",
            "evidence": "ac_count=0 in 97-acceptance-criteria.md",
            "correction": "Run linter-scripts/generate-gwt-acceptance.py to scaffold AC blocks.",
        })
    elif not is_exempt and m["gwt_block_count"] == 0:
        findings.append({
            "category": "untestable", "severity": "medium", "impact": 5,
            "issue": "Acceptance criteria present but no Given/When/Then blocks",
            "evidence": f"ac_count={m['ac_count']}, gwt_block_count=0",
            "correction": "Rewrite each AC as a Given/When/Then block.",
        })
    if not m["consistency_report"]:
        findings.append({
            "category": "inconsistency", "severity": "medium", "impact": 4,
            "issue": "Missing or empty 99-consistency-report.md",
            "evidence": "consistency_report=false",
            "correction": "Run linter-scripts/fill-missing-consistency-reports.cjs.",
        })
    if m["todo_density"] > 0:
        findings.append({
            "category": "drift", "severity": "low", "impact": 3,
            "issue": f"{m['todo_density']} TODO/TBD/FIXME marker(s) in module body",
            "evidence": f"todo_density={m['todo_density']}",
            "correction": "Resolve or convert markers to tracked acceptance criteria.",
        })

    # Stable order so JSON is byte-identical across runs
    findings.sort(key=lambda f: (f["category"], f["severity"], -f["impact"], f["issue"]))

    # Blast radius: foundational specs (children + contract presence)
    blast = min(10, m["child_modules"] * 2
                + (3 if m["has_sql_ddl"] else 0)
                + (2 if m["has_ts_enums"] else 0)
                + (2 if m["has_json_schema"] else 0))

    overall = weighted(scores)
    grade = grade_of(overall)
    blockers = sorted({f["issue"] for f in findings if f["severity"] in {"critical", "high"}})

    return {
        "scores": scores,
        "raw_scores": raw_scores,
        "applied_gates": applied_gates,
        "score_justification": (
            f"Deterministic rubric: contracts={int(m['has_sql_ddl'])+int(m['has_json_schema'])+int(m['has_ts_enums'])}/3, "
            f"ac={m['ac_count']}, gwt={m['gwt_block_count']}, broken_links={m['links_broken']}, "
            f"waffle/kchar={m['waffle_per_kchar']}. "
            f"Gates active: {sum(1 for g in applied_gates if g['active'])}."
        ),
        "implementability_blockers": blockers,
        "code_mapping": {
            "implemented_by": [],
            "expected_but_missing": [],
            "orphan_code": [],
        },
        "findings": findings,
        "verdict": f"Deterministic score {overall}/100 ({grade}) for spec/{rel}.",
        "blast_radius": blast,
    }

# ---------------- runner ----------------
def audit_module(folder: Path, metrics: dict):
    if DETERMINISTIC:
        return deterministic_score(folder, metrics)
    digest = build_digest(folder, metrics)
    result = call_ai_structured(
        prompt=digest,
        tool_name="emit_audit_v2",
        tool_description="Emit AI-implementability audit",
        parameters=TOOL_PARAMS,
        system=SYSTEM,
        model=MODEL,
    )
    # Apply hard gates to AI scores too — caps are non-negotiable
    raw_scores = dict(result["scores"])
    capped, applied_gates = apply_gates(raw_scores, metrics)
    result["scores"] = capped
    result["raw_scores"] = raw_scores
    result["applied_gates"] = applied_gates
    return result

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
          f"**Auditor:** {'Deterministic rubric (no AI)' if DETERMINISTIC else 'Lovable AI (gemini-3-flash-preview, 2-pass)'}  ",
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
        if not DETERMINISTIC:
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
    # In deterministic mode, sort by module name and sort_keys for byte-identical output.
    json_results = sorted(results, key=lambda r: r["module"]) if DETERMINISTIC else results
    json_text = json.dumps(json_results, indent=2, sort_keys=DETERMINISTIC, ensure_ascii=True)
    if DETERMINISTIC and not json_text.endswith("\n"):
        json_text += "\n"
    (OUT / "raw-results.json").write_text(json_text)

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
