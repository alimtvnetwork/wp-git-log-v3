# Recent session — 2026-10-29

This note records what was created and shipped on 2026-10-29. Paths below are relative git paths.

## go-email-reader

- Specifications for warm-up sequences 08 through 13 are in `02-spec/21-app/08-ui-and-warm-up-algorithm-improvements/` through `02-spec/21-app/13-ui-and-warm-up-algorithm-improvements/`.
- Plans are in `.ai-memory/plans/pending/08-ui-and-warm-up-algorithm-improvements.md` through `.ai-memory/plans/pending/13-ui-and-warm-up-algorithm-improvements.md`.
- Sequence 08 is the connector and gate map. Sequence 09 is brakes. Sequence 10 is the send lease and cool-down. Sequence 11 is the shadow compare. Sequence 12 is the pace envelope. Sequence 13 is the shared quiet window.
- The live tick now consults `internal/warmup/gatefeed.go`. A peer can tighten the remaining-send cap and cannot raise it. An absent or stale peer does not stop local warm-up. Local quiet hours hold. A quiet skip does not send. A dry run does not spend the cap. The gate order is health, cadence, connector, theme.
- The reader UI gate map reads `GET /api/warmup/gates`.
- Commit `f3864ee` is on `main` and is pushed. The remote uses SSH.
- Sequences 08 through 13 stay pending as catalogs. Only the gate engine is implemented.
- `user.email` is `local@gitmap`. The name is `gitmap automation`.

## gitmap-v28

- Cursor-like IDE setup landed in `cli/cmdide/ide_setup.go`, `cli/cmdide/setup/`, and `cli/cmdui/ui_server_ide_setup.go`.
- The spec is `02-spec/21-app/352-cursor-like-ide-setup-from-gitmap/`.
- Pushed commits include `3229df9b4`, `864012968`, and `04690b952`.
- `cli/cov` is a coverage profile and was not committed.

## repo-cache

- Commit `f474298` stores heatmap databases, including `.heatmap/06-cursor/go-email-reader/heatmap.db`.
- SQLite `-shm` and `-wal` sidecars were not committed.

## Push denied

These local commits are still unpushed because GitHub denied `alimtvnetwork`:

- `alim.karim.profile` (19 commits)
- `kubernetes-training-v1` (19 commits)
- `common-linux-installer` (1 commit)

## Rules learned

- Do not put a personal access token in a git remote URL. Use SSH.
- Do not commit coverage profiles or SQLite sidecar files.

## Last 30 commits in this repo

```text
b3ffa53a chore(version) - bump to v4.2.57
79e50dbc feat(sync) - sync canonical v6 execute suite, gitmap heatmap telemetry, skills, and prompts (29-Oct-26 06:33 pm)
9c081423 chore(version) - bump to v4.2.56
7df7123c feat(sync) - sync canonical v6 execute suite, gitmap heatmap telemetry, skills, and prompts (29-Oct-26 04:25 pm)
22fc727d chore(version) - bump to v4.2.55
814631db feat(sync) - sync canonical v6 execute suite, gitmap heatmap telemetry, skills, and prompts (29-Oct-26 03:19 pm)
78378ca1 chore(version) - bump to v4.2.54
e41b4e91 feat(sync) - sync canonical v6 execute suite, gitmap heatmap telemetry, skills, and prompts (29-Oct-26 02:56 pm)
9d41623c chore(version) - bump to v4.2.53
73d8ca61 feat(sync) - sync canonical v6 execute suite, gitmap heatmap telemetry, skills, and prompts (28-Oct-26 10:20 am)
9236664f chore(version) - bump to v4.2.52
8a2a2ce4 feat(sync) - sync canonical v6 execute suite, gitmap heatmap telemetry, skills, and prompts
6797ae68 chore(version) - bump to v4.2.51
ae993373 feat(sync) - sync canonical v6 execute suite, gitmap heatmap telemetry, skills, and prompts
a3f7fbfd chore(version): bump to v4.2.50
ca938456 feat(sync): sync canonical prompts, skills, gitmap agent standards, and purge deprecated 46 task manager
a63dc88d chore(version): bump to v4.2.49
b4ac662b feat(sync): sync canonical prompts, skills, gitmap agent standards, and purge deprecated 46 task manager
94ba7a4b chore(version): bump to v4.2.48
fac0c10e feat(sync): sync canonical prompts, skills, gitmap agent standards, and purge deprecated 46 task manager
f9cdcfb5 chore(version): bump to v4.2.47
20af16d9 feat(sync): sync canonical prompts, skills, gitmap agent standards, and purge deprecated 46 task manager
6bf90df0 chore(version): bump to v4.2.46
7d6cdd69 feat(sync): sync canonical prompts, skills, gitmap agent standards, and purge deprecated 46 task manager
94c62278 chore(version) - bump to v4.2.45
d2cd720b feat(sync) - sync canonical v6 execute suite, gitmap heatmap telemetry, skills, and prompts
3ffb81d4 chore(version): bump to v4.2.44
8352bc43 feat(sync): sync canonical prompts, skills, gitmap agent standards, and purge deprecated 46 task manager
282cf932 chore(version) - bump to v4.2.43
eaa0f9d2 feat(sync) - sync canonical v6 execute suite, gitmap heatmap telemetry, skills, and prompts
```
