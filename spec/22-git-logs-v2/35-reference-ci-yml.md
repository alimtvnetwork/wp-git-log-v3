# Reference CI Workflow (v2)

**Version:** 2.7.0  
**Updated:** 2026-04-25  
**Scope:** Reference GitHub Actions workflow that implements §32 stages 1–7 across the full PHP × WP × site-mode matrix. Ships in the plugin repo at `.github/workflows/ci.yml`. Provider-agnostic stage names from §32 are mapped 1:1 to GitHub job keys so GitLab CI, CircleCI, and Jenkins can mirror the structure verbatim.

---

## Goals (recap)

1. Run the full §32 matrix (12 jobs) on `main` push; reduced matrix (3 jobs) on PR.
2. Stages run **in the order defined by §32** (Preflight → Setup → Build → Unit → Integration → Verify → E2E). A failed stage stops downstream stages for that matrix leg.
3. Every stage emits machine-readable artifacts (junit, tap, schema-diff) for CI annotations and post-mortem.
4. No secrets required — every test uses ephemeral keys/fixtures.
5. Cache aggressively: Composer + WP downloads + plugin tarball reused across legs.

---

## File location & ownership

- **Path:** `.github/workflows/ci.yml`
- **Owner:** Spec-defined here. Any drift between this file and §32/§33/§34 is a bug in this file.
- **Triggers:** push to `main`, PR to `main`, manual `workflow_dispatch`.
- **Concurrency:** one in-flight run per ref; older queued runs cancelled.

---

## Matrix policy

```yaml
# Push to main → full matrix (12 jobs)
matrix:
  php: ['8.1', '8.2', '8.3']
  wp:  ['latest', 'previous']
  mode: ['single', 'multisite']

# Pull requests → reduced matrix (3 jobs)
matrix:
  php: ['8.3']
  wp:  ['latest']
  mode: ['single', 'multisite', 'sqlite-only']
```

`sqlite-only` mode skips WP install and runs only Stage 4 (Unit). Provides a sub-30s feedback loop on PHP-only changes.

The matrix is decided in a tiny preceding job `matrix-decider` that emits the JSON via `outputs.matrix`. Downstream jobs consume it with `fromJson`.

---

## Job graph (mirrors §32 stages)

```
matrix-decider
        │
        ▼
preflight  (1 job, runs once)
        │
        ▼
setup      (matrix)
        │
        ▼
build      (matrix; depends-on: setup)
        │
        ├──► unit        (matrix; depends-on: build)
        │
        └──► integration (matrix; depends-on: build)
                  │
                  ▼
              verify     (matrix; depends-on: integration)
                  │
                  ▼
              e2e        (matrix; depends-on: verify)
```

`unit` and `integration` run in parallel (no shared mutable state).  
`verify` and `e2e` need the fixture WP that `integration` produced, so they run sequentially after.

---

## Stage → Job specification

Each stage below names: **inputs (artifacts consumed)**, **outputs (artifacts uploaded)**, **must-fail conditions**, **runtime budget**.

### `matrix-decider` (helper, not a §32 stage)

| Property | Value |
|----------|-------|
| Inputs | `github.event_name` |
| Outputs | `matrix` (JSON string) |
| Must fail if | event is neither `push` / `pull_request` / `workflow_dispatch` |
| Budget | 10 s |

### Stage 1 — `preflight` (1 job, not matrixed)

| Property | Value |
|----------|-------|
| Inputs | runner image (ubuntu-22.04) |
| Outputs | `preflight.txt` (versions of php, composer, wp-cli, bats, jq, ssh-keygen, sqlite3) |
| Must fail if | any required binary missing OR version below floor (php≥8.1, bats≥1.5, sqlite3≥3.40) |
| Budget | 60 s |

### Stage 2 — `setup` (matrix)

| Property | Value |
|----------|-------|
| Inputs | matrix (php, wp, mode), repo checkout |
| Outputs | `wp-fixture-${{matrix-key}}.tar.zst` (compressed `/tmp/wp-test`), `wp-cli.yml`, plugin tarball |
| Must fail if | activator returns non-zero, MigrationState row missing after activation, plugin not active |
| Budget | 4 min |

Setup MUST use `wp core download --version=$WP_VERSION`, `wp core install` (or `wp core multisite-install`), then activate the plugin built from the workspace. Mode `sqlite-only` skips this entire job.

### Stage 3 — `build` (matrix)

| Property | Value |
|----------|-------|
| Inputs | source tree, Composer cache |
| Outputs | `vendor/`, `phpstan.json`, `phpcs.json` |
| Must fail if | `composer install` non-zero, `phpstan --level=max` non-zero, `phpcs` (WP standards subset) errors |
| Budget | 3 min |

Build runs **before** Unit and gates it (per §34 PHPStan rule).

### Stage 4 — `unit` (matrix)

| Property | Value |
|----------|-------|
| Inputs | `vendor/` from Stage 3 |
| Outputs | `junit.xml`, `coverage.xml` |
| Must fail if | any PHPUnit test red, any coverage row in §34 short of target, `error-code-map.json` drifted from §15 |
| Budget | 2 min |

Runs `vendor/bin/phpunit --testsuite=plugin --log-junit junit.xml --coverage-clover coverage.xml`. Then `php tests/phpunit/coverage-check.php` enforces §34 targets.

### Stage 5 — `integration` (matrix; skipped in `sqlite-only` mode)

| Property | Value |
|----------|-------|
| Inputs | fixture WP from Stage 2, `vendor/` from Stage 3 |
| Outputs | `bats.tap`, `wp-error.log` |
| Must fail if | any `.bats` file red, OR `tests/bats/coverage-check.sh` reports < 1 test per §15 GL-* code |
| Budget | 8 min |

Runs `bats --formatter tap tests/bats/ > bats.tap`. Multisite leg auto-loads `99-multisite.bats`; single-site leg skips it.

### Stage 6 — `verify` (matrix; skipped in `sqlite-only` mode)

| Property | Value |
|----------|-------|
| Inputs | fixture DB at `wp-content/uploads/git-logs/db.sqlite` from Stage 5 |
| Outputs | `schema.diff`, `seeds.diff`, `error-code-map.diff` |
| Must fail if | `schema.diff` non-empty (§02 drift), `seeds.diff` non-empty (§16 drift), `error-code-map.diff` non-empty (§15 drift) |
| Budget | 1 min |

Steps:
1. Dump live schema: `sqlite3 db.sqlite '.schema'` → diff against `tests/bats/fixtures/expected-schema.sql`.
2. Dump row counts: `for t in $(cat lookup-tables.txt); do echo -e "$t\t$(sqlite3 db.sqlite "SELECT COUNT(*) FROM $t")"; done` → diff against `tests/bats/fixtures/seed-counts.tsv`.
3. Re-run `php tests/phpunit/regen-error-map.php` → `git diff --exit-code tests/phpunit/fixtures/error-code-map.json`.

### Stage 7 — `e2e` (matrix; skipped in `sqlite-only` mode)

| Property | Value |
|----------|-------|
| Inputs | fixture WP from Stage 5 (still alive) |
| Outputs | `e2e.tap` |
| Must fail if | any step in `tests/bats/e2e-ssh-roundtrip.bats` red, any GL-SSH-* code from §15 not exercised |
| Budget | 3 min |

Runs `bats --formatter tap tests/bats/e2e-ssh-roundtrip.bats > e2e.tap`. Generates a fresh ed25519 key in the runner's tempdir per the §32 Stage 7 contract.

---

## Required action versions

Pin to major versions (renovate-friendly), never SHAs:

| Action | Pin | Purpose |
|--------|-----|---------|
| `actions/checkout@v4` | v4 | repo checkout |
| `shivammathur/setup-php@v2` | v2 | PHP install + Composer |
| `actions/cache@v4` | v4 | Composer + WP downloads |
| `bats-core/bats-action@v2` | v2 | Bats install |
| `mirromutth/mysql-action@v1` | — | NOT used (SQLite only) |

The workflow MUST NOT use any community action that hasn't published a tagged release in the last 12 months. List checked manually before any pin update.

---

## Caching keys

Composer cache:

```yaml
key: composer-${{ matrix.php }}-${{ hashFiles('composer.lock') }}
restore-keys:
  composer-${{ matrix.php }}-
```

WordPress download cache:

```yaml
key: wp-${{ matrix.wp }}-${{ matrix.mode }}
```

Plugin tarball is rebuilt every run (cheap; <5 s) — never cached.

---

## Artifact retention

| Artifact | Retention | Reason |
|----------|-----------|--------|
| `preflight.txt` | 7 days | Audit binary versions per leg |
| `wp-fixture-*.tar.zst` | **0 days** | Discarded immediately after Stage 7 (large, no debug value once tests pass) |
| `junit.xml`, `coverage.xml` | 30 days | CI annotations, coverage trend |
| `bats.tap`, `e2e.tap` | 30 days | Test history |
| `schema.diff`, `seeds.diff`, `error-code-map.diff` | 30 days | Spec-drift forensics |
| `wp-error.log` | 30 days, **failure-only** | Debug red Integration runs |

---

## Annotations & summary

Every job MUST emit a GitHub job summary (`$GITHUB_STEP_SUMMARY`) with:

- Stage name and duration.
- Pass/fail counts.
- For Verify: the diff body inline (≤1000 lines).
- For E2E: the GL-SSH-* coverage table (which codes hit, which missed).

This makes red builds debuggable from the GitHub UI alone (per §32 goal #4).

---

## Exit-code contract

| Outcome | GitHub status | Branch protection |
|---------|---------------|-------------------|
| All matrix legs green | success | required check passes |
| Any leg red | failure | merge blocked |
| `verify` red on a leg whose `unit`/`integration` was green | failure | spec drift; merge blocked |
| `sqlite-only` PR leg green, full matrix not yet run | pending | merge blocked until full matrix completes |

---

## Provider portability checklist

When porting to GitLab CI / CircleCI / Jenkins:

1. Use the same stage names (`preflight`, `setup`, `build`, `unit`, `integration`, `verify`, `e2e`).
2. Preserve the dependency graph (Build gates Unit; Integration gates Verify; Verify gates E2E).
3. Reuse `tests/phpunit/coverage-check.php` and `tests/bats/coverage-check.sh` verbatim — they are CI-agnostic.
4. Keep the matrix shape (PHP × WP × mode). The provider-specific YAML differs; the test inputs do not.
5. Mirror artifact names so cross-provider triage stays unified.

A separate "GitLab CI parity" file is **not** part of v2.7.0 scope; the checklist above is the contract.

---

## Out of scope for §35

- WP.org SVN deployment workflow (separate file, v2.x release pipeline).
- Nightly WP-trunk smoke (deferred until WP 6.x ships).
- Performance benchmarks (D4 in §30).
- Visual regression of admin UI (deferred; Playwright effort).

---

## Cross-references

- §32 — overall test plan with stage definitions.
- §33 — Bats skeleton, layout under `tests/bats/`.
- §34 — PHPUnit skeleton, layout under `tests/phpunit/`.
- §15 — error-code catalog driving Stage 6 verify.
- §16 — seed-data driving `seed-counts.tsv` diff.
- §02 — schema driving `expected-schema.sql` diff.
- §28 — SSH signing workflow that Stage 7 mirrors locally.
- §31 — canonical signing string under fixture lock.
