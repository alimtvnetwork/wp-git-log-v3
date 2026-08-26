# CI Provider Bindings

**Version:** 1.0.0  
**Updated:** 2026-04-25

The CLI auto-fills `RepoUrl`, `Branch`, `GitSha256`, and a default `PipelineName` prefix from the host CI's environment so users never have to thread these through pipeline YAML.

Detection is by env-var presence (in priority order). First match wins.

---

## Provider Detection Order

| Order | Provider | Trigger env var |
|-------|----------|-----------------|
| 1 | `github` | `GITHUB_ACTIONS=true` |
| 2 | `gitlab` | `GITLAB_CI=true` |
| 3 | `azure` | `TF_BUILD=True` |
| 4 | `bitbucket` | `BITBUCKET_BUILD_NUMBER` set |
| 5 | `shell` | _(fallback)_ — uses `git` CLI shellout |

`[ci_provider].override` in `rlogger.toml` short-circuits detection.

---

## Field Harvest Map

| CLI field | github | gitlab | azure | bitbucket | shell |
|-----------|--------|--------|-------|-----------|-------|
| `RepoUrl` | `${GITHUB_SERVER_URL}/${GITHUB_REPOSITORY}` | `${CI_PROJECT_URL}` | `${BUILD_REPOSITORY_URI}` | `https://bitbucket.org/${BITBUCKET_REPO_FULL_NAME}` | `git config --get remote.origin.url` (HTTPS-normalized) |
| `Branch` | `${GITHUB_HEAD_REF}` (PR) else `${GITHUB_REF_NAME}` | `${CI_COMMIT_REF_NAME}` | `${BUILD_SOURCEBRANCHNAME}` | `${BITBUCKET_BRANCH}` | `git rev-parse --abbrev-ref HEAD` |
| `GitSha256` | `${GITHUB_SHA}` | `${CI_COMMIT_SHA}` | `${BUILD_SOURCEVERSION}` | `${BITBUCKET_COMMIT}` | `git rev-parse HEAD` |
| `PipelineName` prefix _(optional)_ | `${GITHUB_JOB}` | `${CI_JOB_NAME}` | `${SYSTEM_JOBNAME}` | `${BITBUCKET_STEP_TRIGGERER_UUID}` _(opaque, ignored)_ | _(empty)_ |
| `RootRepo` | _(derived: strip `-vN` from `RepoUrl`)_ | _(same)_ | _(same)_ | _(same)_ | _(same)_ |

If a field cannot be harvested AND no override is given, that field is left unset and validation in `config.Resolve()` will reject the run with `RLOGGER-CONFIG-MISSING-*` or `RLOGGER-PUSH-NO-SHA`.

---

## URL Normalization

`RepoUrl` is normalized to HTTPS form even when the harvested value is SSH:

| Input | Output |
|-------|--------|
| `git@github.com:org/repo.git` | `https://github.com/org/repo` |
| `https://github.com/org/repo.git` | `https://github.com/org/repo` |
| `https://github.com/org/repo/` | `https://github.com/org/repo` |
| `ssh://git@gitlab.com/group/project.git` | `https://gitlab.com/group/project` |

This matches the v2 server's parser in `spec/22-git-logs-v2/05-auth-and-validation.md` step 1.

---

## PR vs Push Distinction

The CLI does NOT branch its behavior on PR-vs-push (the server's `History`/`Action` model already records `Branch` separately). However, it does prefer PR head refs when available so that logs surface against the PR branch rather than `merge/<sha>`:

- GitHub: `GITHUB_HEAD_REF` (PR) > `GITHUB_REF_NAME`
- GitLab: `CI_MERGE_REQUEST_SOURCE_BRANCH_NAME` > `CI_COMMIT_REF_NAME`
- Azure: `SYSTEM_PULLREQUEST_SOURCEBRANCH` > `BUILD_SOURCEBRANCHNAME`
- Bitbucket: `BITBUCKET_PR_DESTINATION_BRANCH` IS NOT used (it's the target); fall back to `BITBUCKET_BRANCH`

---

## Drop-in YAML Snippets

### GitHub Actions

```yaml
- name: Run rlogger
  uses: alimtvnetwork/rlogger-action@v1
  env:
    RLOGGER_SERVER_URL: ${{ secrets.RLOGGER_SERVER_URL }}
    RLOGGER_TEMP_TOKEN: ${{ secrets.RLOGGER_TEMP_TOKEN }}
    RLOGGER_TOKEN:      ${{ secrets.RLOGGER_TOKEN }}
```

### GitLab CI

```yaml
rlogger:
  image: ghcr.io/alimtvnetwork/rlogger:1
  variables:
    RLOGGER_SERVER_URL: $RLOGGER_SERVER_URL
    RLOGGER_TEMP_TOKEN: $RLOGGER_TEMP_TOKEN
    RLOGGER_TOKEN:      $RLOGGER_TOKEN
  script:
    - rlogger run
```

### Azure Pipelines

```yaml
- script: |
    curl -sSL https://github.com/alimtvnetwork/rlogger/releases/latest/download/rlogger-linux-amd64 -o /usr/local/bin/rlogger
    chmod +x /usr/local/bin/rlogger
    rlogger run
  env:
    RLOGGER_SERVER_URL: $(RLOGGER_SERVER_URL)
    RLOGGER_TEMP_TOKEN: $(RLOGGER_TEMP_TOKEN)
    RLOGGER_TOKEN:      $(RLOGGER_TOKEN)
```

### Bitbucket Pipelines

```yaml
- step:
    name: rlogger
    script:
      - curl -sSL https://github.com/alimtvnetwork/rlogger/releases/latest/download/rlogger-linux-amd64 -o /usr/local/bin/rlogger
      - chmod +x /usr/local/bin/rlogger
      - rlogger run
```
