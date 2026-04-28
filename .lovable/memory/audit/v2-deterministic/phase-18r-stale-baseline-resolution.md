---
phase: 18r
date: 2026-04-28
status: closed
trigger: ambiguity-note 01 root-cause investigation
result: stale-baseline artifact, no real AC additions
---

# Phase 18-Resolution — Root Cause: Stale Baseline Snapshot

## Trigger

Ambiguity note `01-trace-map-plus-2-untraced-acs.md` flagged that Phase 18
detected "+2 untraced ACs" but couldn't identify which ones. Task 2 of
No-Questions Mode budget allocated to root-cause investigation.

## Method

Reconstructed historical AC universe at each baseline-bump commit:

```python
# Pseudocode
for rev in [pre_H7_commit, H7_commit, HEAD]:
    md_files = git_ls_tree(rev, '*.md', exclude='_archive')
    ac_ids = AC_HEADING_RX.findall_in_each_file(rev)
    print(rev, len(ac_ids))
```

Then diffed `drift` lists in `trace-map.json` between H7 commit and current:

```python
old_drift = set(json.loads(git_show('H7:trace-map.json'))['drift'])
new_drift = set(json.load('trace-map.json')['drift'])
print(old_drift ^ new_drift)  # → empty set
```

## Findings

| Snapshot | Live AC count | Recorded baseline | Gap |
|----------|---------------|-------------------|-----|
| Pre-H7 commit | 1320 | **1315** | −5 |
| H7 commit | 1322 | **1320** | −2 |
| Phase 18 (today) | 1322 | 1322 (rebaselined) | 0 |

Drift-set diff between H7 commit and current HEAD: **0 added, 0 removed.**

## Root cause

**Stale-baseline snapshot.** Both H5 and H7 wrote `trace-map-baseline.json`
against a `trace-map.json` that was not freshly regenerated immediately
prior. The gap between the two files' regen timestamps allowed live AC
additions to slip into the universe without being captured in the baseline.

The "+2" Phase 18 detected was the H7 baseline catching up to its own
commit-time tree truth, not new content drift.

## Lesson codified (memory Core line 13)

**Rule**: when rebaselining, ALWAYS use
`check-trace-map-regression.py --update-baseline` (atomic regen + write)
instead of manual JSON edits or two-step
`generate-trace-map.py → write-baseline` workflows. The atomic flag is
the only way to guarantee `trace-map.json` and `trace-map-baseline.json`
agree on the same tree-snapshot.

**Belt-and-braces verification rule**: when investigating apparent drift,
diff the `drift` sets in `trace-map.json` between baseline-bump commit
and current. Empty symmetric difference proves no real AC additions
occurred — drift growth is then a baseline-encoding artifact.

## File changes

- updated `.lovable/question-and-ambiguity/01-trace-map-plus-2-untraced-acs.md`
  (Resolution section added; status RESOLVED)
- updated `.lovable/memory/index.md` Core line 13 (added stale-baseline lesson)
- created this memo

**No spec edits, no toolchain edits, no banner bumps.**

## Open question for user

Should we add a CI guard that runs `generate-trace-map.py` immediately
before any `trace-map-baseline.json` write, rejecting writes where the
two snapshots disagree? Currently `--update-baseline` does this correctly,
but a contributor could still bypass it via manual JSON edit. **H10 filter
score**: (1) mechanically detectable ✓ (file mtime + content compare);
(2) active regression surface ✓ (this exact failure mode just bit us
twice); (3) low false-positive risk ✓ (the rule is "snapshots must
agree", which has no legitimate exception). **2 of 3 → graduation
candidate. Logged as ambiguity note 02 for user review.**
