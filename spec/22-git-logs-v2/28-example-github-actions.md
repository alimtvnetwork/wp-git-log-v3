# Example GitHub Actions Workflow (v2)

**Version:** 2.5.0  
**Updated:** 2026-04-25

Copy-paste workflow showing the canonical Lane B integration: push log + error lines on every CI run, mark fixed on green re-runs. Ships in `examples/github-actions/git-logs.yml` of the WP plugin release ZIP and is referenced from the WP.org listing per §26.

---

## Required GitHub repository secrets

| Secret | Where it comes from | Notes |
|--------|--------------------|-------|
| `GITLOGS_BASE_URL` | Your WP site, e.g. `https://wp.example.com/wp-json/git-logs/v2` | No trailing slash. |
| `GITLOGS_TEMP_TOKEN` | Profile screen → "Reveal TempToken" (one-time on rotate) | Treat as sensitive. |
| `GITLOGS_TOKEN` | Profile screen → "Reveal Token" (one-time on rotate) | Treat as sensitive. |

These are repo (or org) secrets; never inline them in YAML. Rotate by regenerating the Profile in WP admin and updating both secrets.

---

## Drop-in workflow: `examples/github-actions/git-logs.yml`

```yaml
name: CI with Git Logs

on:
  push:
    branches: [main, develop]
  pull_request:

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Run build + capture output
        id: build
        # Capture stdout AND stderr separately, but keep the job exit status
        # so subsequent steps can branch on success/failure.
        run: |
          set +e
          npm ci
          npm run build > /tmp/stdout.log 2> /tmp/stderr.log
          echo "exit=$?" >> "$GITHUB_OUTPUT"

      - name: Push logs to Git Logs (always)
        if: always()
        env:
          GITLOGS_BASE_URL:   ${{ secrets.GITLOGS_BASE_URL }}
          GITLOGS_TEMP_TOKEN: ${{ secrets.GITLOGS_TEMP_TOKEN }}
          GITLOGS_TOKEN:      ${{ secrets.GITLOGS_TOKEN }}
          BUILD_EXIT:         ${{ steps.build.outputs.exit }}
        run: |
          set -euo pipefail

          # Convert raw text to LogLine[] (Severity Info for stdout, Error for stderr)
          jq -Rn --rawfile out /tmp/stdout.log --rawfile err /tmp/stderr.log '
            def lines(t; sev):
              [t | split("\n") | .[] | select(length > 0)
                 | { Severity: sev, Message: ., OccurredAt: now | floor }];
            {
              TempToken:  env.GITLOGS_TEMP_TOKEN,
              Token:      env.GITLOGS_TOKEN,
              RepoUrl:    "https://github.com/\(env.GITHUB_REPOSITORY)",
              Branch:     (env.GITHUB_REF_NAME // "unknown"),
              AppSlug:    "ci",
              Pipeline:   env.GITHUB_JOB,
              HasError:   ((env.BUILD_EXIT // "0") | tonumber) != 0,
              Logs:       lines($out; "Info"),
              ErrorLogs:  lines($err; "Error")
            }
          ' > /tmp/payload.json

          curl --fail-with-body -sS \
            -X POST "$GITLOGS_BASE_URL/append-log" \
            -H "Content-Type: application/json" \
            --data-binary @/tmp/payload.json \
          | tee /tmp/ack.json

          echo "Pipeline ID: $(jq -r .PipelineId /tmp/ack.json)"

      - name: Mark Pipeline fixed (only on success)
        if: success() && steps.build.outputs.exit == '0'
        env:
          GITLOGS_BASE_URL:   ${{ secrets.GITLOGS_BASE_URL }}
          GITLOGS_TEMP_TOKEN: ${{ secrets.GITLOGS_TEMP_TOKEN }}
          GITLOGS_TOKEN:      ${{ secrets.GITLOGS_TOKEN }}
        run: |
          jq -n '
            {
              TempToken:  env.GITLOGS_TEMP_TOKEN,
              Token:      env.GITLOGS_TOKEN,
              RepoUrl:    "https://github.com/\(env.GITHUB_REPOSITORY)",
              Branch:     env.GITHUB_REF_NAME,
              AppSlug:    "ci",
              Pipeline:   env.GITHUB_JOB
            }
          ' \
          | curl --fail-with-body -sS \
              -X POST "$GITLOGS_BASE_URL/fixed-log" \
              -H "Content-Type: application/json" \
              --data-binary @-

      - name: Fail job if build failed
        if: steps.build.outputs.exit != '0'
        run: |
          echo "Build failed (exit ${{ steps.build.outputs.exit }})." >&2
          exit 1
```

---

## Why this shape

- **`if: always()`** on the push step — log lines must arrive even when the build fails. Otherwise History is misleading.
- **`HasError` derived from the captured exit code**, not from `${{ failure() }}`, because we want a single push payload that owns the pipeline state for this run.
- **`/fixed-log` only on success** — flips `Pipeline.HasError=0` so the History timeline shows recovery.
- **`set +e`** around the build itself so we can capture the exit code. Without it, `bash -e` aborts the step before logs are pushed.
- **`jq -Rn --rawfile`** keeps Unicode + newlines safe; never interpolate raw log text into JSON manually.
- **`curl --fail-with-body`** so the runner step fails on 4xx/5xx but still surfaces the GL- error envelope in logs.

---

## Common gotchas

| Symptom | Cause | Fix |
|---------|-------|-----|
| `GL-AUTH-TEMPTOKEN-INVALID` | Secret rotated in WP admin but not in GitHub. | Update both secrets after every rotate. |
| `GL-VALIDATION-REPO-NOT-ALLOWED` | GitProfile Acceptance is `AcceptSelectedRepoOnly` and `RepoUrl` doesn't match. | Switch to `AcceptAllRepos` or set `SelectedRepoUrl`. |
| `GL-VALIDATION-BRANCH-RESTRICTED` | `IsRestrictInBranch=1` and the workflow ran on a different branch. | Push the workflow from the allowed branch or relax the restriction. |
| `GL-LINES-TOO-MANY` | Verbose builds emit > 10000 lines. | Filter logs (`grep -v ^DEBUG`) before constructing the payload, or split into multiple `/append-log` calls. |
| `GL-PAYLOAD-TOO-LARGE` | Single payload > 1 MiB. | Split by line range; the same `Pipeline` accepts multiple appends. |
| `GL-RATE-LIMIT-EXCEEDED` | Too many concurrent jobs from one Profile. | Use a per-environment Profile or raise `RatePerMinPerProfile` via `wp git-logs config set`. |

---

## GitLab CI variant

Same shape using `before_script` + `after_script`. Sample lives at `examples/gitlab-ci/git-logs.yml` (not included here for brevity; mirrors the GitHub flow).

---

## Reading logs from CI artifacts

If the build needs to fetch its own historical logs (e.g. comparing test counts across runs):

```bash
curl -u "ci-bot:$WP_APP_PASSWORD" \
  "$GITLOGS_BASE_URL/get-logs?q=https://github.com/${GITHUB_REPOSITORY}@${GITHUB_REF_NAME}&Limit=200"
```

Note the **WP App Password** (Lane A, basic auth), not the TempToken — reads use a different lane per §25.
