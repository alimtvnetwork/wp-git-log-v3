# Uninstall Policy (v2)

**Version:** 2.9.0  
**Updated:** 2026-04-26 (Phase 3: Wipe mode now also deletes the per-SHA `<ShaLogsRoot>/` tree — see §39)

What happens to the SQLite database, uploads, and DB rows when an operator removes the plugin. Defines the contract for `register_uninstall_hook` and the `uninstall.php` file at the plugin root.

---

## Three modes

WordPress distinguishes three lifecycle events:

| Event | Trigger | Default plugin behavior |
|-------|---------|--------------------------|
| **Deactivate** | Plugins screen → Deactivate; or `wp plugin deactivate` | **Preserve everything.** No deletes, no file moves. Routes/hooks unregister. |
| **Delete** (from WP UI) | Plugins screen → Delete after deactivation | Runs `uninstall.php`. **Default: preserve DB**, archive marker added. |
| **Network uninstall** (multisite) | Network Admin → Plugins → Delete | Runs `uninstall.php` once per site (WP core handles the loop). Same per-site behavior as above. |

The default is **preserve, never delete** — log history is the kind of thing operators regret losing.

---

## What `uninstall.php` does

```php
<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }

require_once __DIR__ . '/inc/Bootstrap/Uninstaller.php';
\GitLogs\Bootstrap\Uninstaller::run();
```

`Uninstaller::run()` consults `ConfigKv.UninstallMode` (default `Preserve`) and dispatches:

| `UninstallMode` | DB file | Audit row | Reinstall behavior |
|-----------------|---------|-----------|---------------------|
| `Preserve` (default) | Untouched at `wp-content/uploads/git-logs/git-logs.sqlite`. A sidecar `.uninstalled` marker file written with timestamp + plugin version. | `AuditTrail.AuditActionType=PluginUninstall` (new seed ID 21) with `Detail.Mode='Preserve'`. | Reinstalling the plugin auto-detects the file, removes the marker, runs migrator, resumes. |
| `Archive` | Renamed to `git-logs.sqlite.archive-<unix>` plus `.archive-<unix>.json` manifest (same shape as `wp git-logs backup`). | `Detail.Mode='Archive'`, `ArchiveFile=<name>`. | Reinstall starts with an empty DB; operator can `wp git-logs restore --from=...archive-...` later. |
| `Wipe` | DB file + WAL + SHM + the entire `uploads/git-logs/` folder deleted. | `Detail.Mode='Wipe'` written to PHP error log only (no DB to write to). | Reinstall starts fresh. |

`UninstallMode` is set via `wp git-logs config set UninstallMode <mode>` and surfaced in the admin Settings screen with a yellow warning on `Wipe`.

---

## What is NEVER touched on uninstall

- WP `users` table.
- WP `usermeta` (no plugin keys live there in v2).
- `wp_options` rows for unrelated plugins.
- Other plugins' uploads.
- WP cron schedules registered by other plugins (we only unregister our own `gitlogs_metric_flush`).

---

## What IS touched on every uninstall regardless of mode

These are housekeeping deletes, not data:

- WP cron event `gitlogs_metric_flush` unscheduled.
- WP transients prefixed `gitlogs_metric_*` deleted.
- WP options prefixed `git_logs_*` deleted (only used as feature flags / first-run marker).
- `MaintenanceMode` flag cleared (so a stale maintenance state doesn't survive a reinstall).

The SQLite file is unaffected by any of the above.

---

## Multisite uninstall

WP core runs `uninstall.php` per site when network-deleted. Each site's DB follows the per-site `UninstallMode`. There is no "wipe network in one shot" mode — operators must set `Wipe` per-site or run:

```bash
wp site list --field=url \
  | xargs -I{} wp --url={} git-logs config set UninstallMode Wipe
wp plugin uninstall git-logs --network --deactivate
```

This is intentional. A single network-wide wipe button is too easy to misclick.

---

## Recovery from accidental uninstall

If `UninstallMode=Preserve` (the default) was active:

1. Reinstall the plugin from the WP.org directory or upload the same version.
2. Activate. The `Bootstrap` module detects `git-logs.sqlite` + `.uninstalled` marker, removes the marker, runs migrator (idempotent), restores cron schedules.
3. All data, audit history, and credentials are intact. No bootstrap form re-shown (Profile table non-empty).

If `UninstallMode=Archive`:

1. Reinstall + activate as above (starts empty).
2. `wp git-logs restore --from=wp-content/uploads/git-logs/git-logs.sqlite.archive-<unix>`.
3. Plugin runs through normal post-restore migrator + verify.

If `UninstallMode=Wipe`: data is gone. Restore from operator-managed backups taken via `wp git-logs backup` (§23).

---

## Seed update

Add to `09-seed-data.md` queue (when that file is created) and to `18-schema.sql` `AuditActionType` seed:

```sql
(21,'PluginUninstall')
```

This is a queued housekeeping delta, tracked in §99.

---

## Why no `Wipe`-by-default?

Because:

1. CI history is forensic data — silent deletion violates trust.
2. WP's "Delete" button is one click away from "Deactivate"; operators routinely click it for upgrades.
3. SQLite files are small and easy to re-attach.

If an operator truly wants ephemeral installs, they explicitly choose `Wipe` and take the warning.
