# Retention & Pruning (v2)

**Version:** 2.5.0  
**Updated:** 2026-04-25

The plugin keeps every log entry forever by default. Operators control disk growth via an **opt-in** `wp git-logs prune` WP-CLI command. There is no automatic background prune in v2.

---

## Eligibility rules

A `LogEntry` or `ErrorLogEntry` row is eligible for deletion iff **all** of:

1. `OccurredAt < (now - --older-than)`
2. The owning `Pipeline.HasError = 0`, **OR** the caller passed `--include-errors`.
3. The owning `Pipeline` is not referenced by an unresolved `Action` row dated within the retention window (avoid orphaning audit context).

A `Pipeline` row itself is eligible for deletion iff it has zero remaining `LogEntry` AND `ErrorLogEntry` rows after the line-level prune. Pipelines are **never** deleted in the same transaction as their lines — two-phase, so a crash leaves the DB consistent.

`History`, `Action`, `AuditTrail` rows are **never** pruned by this command. They are the audit floor.

---

## Command surface

```
wp git-logs prune \
  --older-than=<duration>     # required, e.g. 30d, 12w, 6mo, 1y
  [--include-errors]          # also prune lines from HasError pipelines
  [--include-pipelines]       # phase-2 sweep: drop now-empty Pipeline rows
  [--app=<AppSlug>]           # scope to one App
  [--repo=<RepoUrl>]          # scope to one Repo
  [--dry-run]                 # report counts only; no writes
  [--batch=<n>]               # default 1000; rows per transaction
  [--quiet | --verbose]
```

### Exit codes

| Code | Meaning |
|------|---------|
| 0    | Success (including dry-run) |
| 1    | Validation error (bad `--older-than`, unknown `--app`) |
| 2    | DB locked / SQLite busy after retries |
| 3    | Aborted by operator (Ctrl-C between batches) |

### Duration grammar

`<int><unit>` where unit is `d`, `w`, `mo`, `y`. No fractions, no compound (`1mo15d` invalid). Lower bound `7d` — refused below to prevent foot-guns.

---

## Audit trail

Every prune run emits an `AuditTrail` row:

| Field | Value |
|-------|-------|
| `AuditActionTypeId` | seed for `Prune` (add to `09-seed-data.md` + `18-schema.sql`) |
| `AuditOutcomeId`    | `Success` / `Error` |
| `ProfileId`         | NULL (CLI runs as system) |
| `Detail`            | JSON: `{ olderThan, includeErrors, scope, deletedLogLines, deletedErrorLines, deletedPipelines, dryRun, durationMs }` |

Add `Prune` to `AuditActionType` seed (next free ID = 19).

---

## Transaction shape

```
BEGIN IMMEDIATE;
  DELETE FROM LogEntry      WHERE LogEntryId      IN (SELECT … LIMIT :batch);
  DELETE FROM ErrorLogEntry WHERE ErrorLogEntryId IN (SELECT … LIMIT :batch);
COMMIT;
```

Loop until both `DELETE`s return zero rows. Then phase-2 sweep (only if `--include-pipelines`):

```
BEGIN IMMEDIATE;
  DELETE FROM Pipeline
   WHERE PipelineId NOT IN (SELECT DISTINCT PipelineId FROM LogEntry      UNION
                            SELECT DISTINCT PipelineId FROM ErrorLogEntry)
     AND PipelineId NOT IN (SELECT PipelineId FROM History  WHERE PipelineId IS NOT NULL UNION
                            SELECT PipelineId FROM Action   WHERE PipelineId IS NOT NULL)
     AND UpdatedAt < :cutoff;
COMMIT;
```

Run `PRAGMA wal_checkpoint(TRUNCATE);` once at the end to reclaim file size.

---

## Output (human format)

```
Git Logs prune
  Cutoff: 2026-03-26 00:00:00 UTC (older than 30d)
  Scope:  all apps / all repos
  Mode:   dry-run

Eligible:
  LogEntry rows       :  1,402,901
  ErrorLogEntry rows  :        842 (skipped — no --include-errors)
  Pipeline rows       :        117 (would be dropped — no --include-pipelines)

Estimated reclaim    : ~412 MiB
```

Same shape with `Deleted:` instead of `Eligible:` for a real run.

---

## What NOT to do

- Do **not** add a `wp_cron` auto-prune in v2. Operators choose retention windows; surprise deletes erode trust.
- Do **not** allow prune to run while a migration is pending (`MigrationState.PluginVersion != ConfigKv.PluginVersion`). Refuse with exit 1.
- Do **not** prune below `7d` — minimum retention floor for incident triage.
