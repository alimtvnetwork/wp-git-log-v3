# Release Checklist (v2)

**Version:** 1.0.0
**Updated:** 2026-04-25
**Applies to:** WP.org plugin submission + every tagged release of `git-logs`
**Reads with:** §06 migrations, §18 schema, §26 readme + screenshots, §27 WP-CLI, §29 uninstall, §35 CI

---

## 1. Semver Gates

| Bump | Trigger | Required artifacts |
|------|---------|-------------------|
| **Patch** (X.Y.**Z**) | Bug fix, doc fix, banner, copy-edit. **No DB change. No new GL- code. No endpoint change.** | Changelog row, no migration. |
| **Minor** (X.**Y**.0) | New endpoint, new admin screen, new `GL-*` code, new `AuditActionType`, additive `ConfigKv` default, new optional column with default. **Backward-compatible.** | Changelog row, new `MigrationState` marker, §15 update if codes added, §97 AC update, §99 health re-score. |
| **Major** (**X**.0.0) | Breaking column rename/drop, removed endpoint, removed lane, changed default behavior, removed `ConfigKv` key, schema downgrade-blocking change. | All Minor artifacts **plus**: §13 v1↔vN mapping update, deprecation banner pass on superseded folder, restore-policy review (§23 forbids cross-major downgrade). |

**Hard rule:** never decrement; never re-use a tag; pre-release suffixes use `-rc.N` / `-beta.N` (e.g. `2.8.0-rc.1`).

---

## 2. Version-Tag Hygiene

1. Tag format: `v{Major}.{Minor}.{Patch}` (lowercase `v` prefix). Pre-release: `v2.8.0-rc.1`.
2. Tag commit must contain matching version in **all four** locations:
   - `git-logs.php` plugin header `Version:`
   - `readme.txt` `Stable tag:` (only on full releases, **not** RC/beta)
   - `18-schema.sql` `INSERT OR IGNORE INTO PluginVersion …`
   - `98-changelog.md` top row
3. CI gate (`drift-check` job, §35): tag → grep all four → fail if mismatch → `GL-RELEASE-VERSION-DRIFT` (new code, queued for §15).
4. Annotated tag message = first paragraph of changelog row.
5. Never force-push a published tag. Mistakes get a new patch.

---

## 3. Pre-Release Checklist (run for every tag)

### 3.1 Spec & Doc
- [ ] §98 changelog row added with date + scope summary.
- [ ] §99 consistency report version + health score updated.
- [ ] §97 acceptance criteria updated if new feature (new AC-NN row).
- [ ] §15 error catalog updated if new `GL-*` code.
- [ ] §17 OpenAPI updated if endpoint signature changed.
- [ ] §18 SQL DDL updated if schema changed; `MigrationState` marker added.
- [ ] §26 `readme.txt` `== Changelog ==` section mirrors §98.
- [ ] `mem://specs/git-logs.md` synced (locked decisions, housekeeping).

### 3.2 Code & Schema (when implementation lane active)
- [ ] `composer validate --strict` clean.
- [ ] `phpcs` (WordPress-Extra ruleset) zero errors.
- [ ] `phpstan analyse --level=max` zero errors.
- [ ] PHPUnit suite green on PHP 8.1 / 8.2 / 8.3 (§34).
- [ ] Bats suite green on WP latest + previous, single + multisite (§33, §35).
- [ ] Schema-drift job (§35) green: live SQLite vs §18 byte-equal modulo whitespace.
- [ ] Seed-count job green: `AuditActionType` rows match §16, `ConfigKv` defaults match §16.
- [ ] `GL-*` code-drift job green: `inc/Support/ErrorCodes.php` ↔ §15 ↔ Bats fixtures.
- [ ] `wp git-logs verify` exits 0 on a fresh install + on the upgrade path from previous tag.

### 3.3 Migration & Backup Safety
- [ ] New migration class lands under `inc/Migrations/V{Major}_{Minor}_{Patch}.php` with idempotent up().
- [ ] `MigrationState` row inserted by the migration itself (not by hand).
- [ ] Upgrade test: install previous tag → seed sample data → upgrade → `verify` green → row counts preserved.
- [ ] Backup compatibility: `wp git-logs backup` from previous tag restores cleanly into new tag (§23) for **Patch** and **Minor**. **Major** is allowed to refuse with `GL-RESTORE-MAJOR-DOWNGRADE`.
- [ ] Manifest `SchemaChecksum` regenerated and committed.

### 3.4 WP.org Packaging (§26)
- [ ] `readme.txt` headers: `Tested up to:` matches latest WP, `Requires PHP:` ≥ 8.1, `Stable tag:` matches new version.
- [ ] `readme.txt` validated against [WP.org readme validator](https://wordpress.org/plugins/developers/readme-validator/).
- [ ] All 8 screenshots present (`assets/screenshot-{1..8}.png`, 1280×800 minimum).
- [ ] Banners present: `assets/banner-1544x500.png`, `assets/banner-772x250.png`.
- [ ] Icons present: `assets/icon-256x256.png`, `assets/icon-128x128.png`, `assets/icon.svg`.
- [ ] `wp i18n make-pot` diff = 0 untranslated strings introduced.
- [ ] `wp plugin check` zero blockers.
- [ ] No PHP files outside the plugin root namespace; no `eval`, no `create_function`.

### 3.5 Security
- [ ] §30 threat model reviewed for new attack surface.
- [ ] No new secret stored in plain TEXT outside the v3 deferral list (§30 R3).
- [ ] `dependency-check` job (Composer audit) zero high/critical.
- [ ] All new endpoints have `permission_callback` returning a `RolePermission` join, never role name.

### 3.6 Release Notes
- [ ] User-visible release notes drafted (separate from §98 internal changelog).
- [ ] Breaking changes listed under explicit **⚠ Breaking** heading.
- [ ] Migration steps documented for ops engineers (manual `wp git-logs migrate` if needed).
- [ ] Known issues + workarounds linked.

---

## 4. Release Day Procedure

1. Create release branch `release/v{X.Y.Z}` off `main`.
2. Run §3 checklist top-to-bottom; tick boxes in PR description.
3. Merge release branch via squash merge titled `release: v{X.Y.Z}`.
4. Push annotated tag `v{X.Y.Z}` from the merge commit.
5. CI tag pipeline runs (§35 `release` job): builds zip, attaches to GitHub Release, uploads to WP.org SVN trunk, copies trunk → `tags/{X.Y.Z}`.
6. Post-release smoke: install fresh from WP.org → first-run bootstrap → `wp git-logs verify` → green.
7. Announce in `#releases` (or project equivalent) with link to GitHub Release.

---

## 5. Hotfix Path (Patch only)

1. Branch `hotfix/v{X.Y.Z+1}` off the previous tag (not `main`).
2. Apply minimal fix; no migration; no schema change.
3. Run §3.1 + §3.2 + §3.6 only (skip §3.3/§3.4/§3.5 unless the hotfix touches them).
4. Tag, release, then forward-port fix to `main` with `cherry-pick`.
5. Document hotfix in §98 with `(hotfix)` suffix.

---

## 6. Rollback Procedure

1. **Within 1 hour**: `wp plugin install git-logs.{previous}.zip --force` then `wp git-logs verify`. If migration was destructive, restore from pre-upgrade backup (§23).
2. **After 1 hour**: forward-fix only. Never publish a lower tag.
3. WP.org: revert `Stable tag:` in `readme.txt` to previous version, commit to SVN trunk. Tagged folder for the bad version stays for forensics; do not delete.
4. Open postmortem issue tagged `release-incident`; required outputs: root cause, missing checklist item, new CI gate to prevent recurrence.

---

## 7. New CI Gates Required by This Spec

These gates extend §35 and are required before this checklist is considered enforced:

| Gate | Location | Failure code |
|------|----------|--------------|
| `version-drift-check` | §35 `lint` stage | `GL-RELEASE-VERSION-DRIFT` |
| `readme-validator` | §35 `package` stage | `GL-RELEASE-README-INVALID` |
| `wp-plugin-check` | §35 `package` stage | `GL-RELEASE-WP-CHECK-FAIL` |
| `pot-diff` | §35 `lint` stage | `GL-RELEASE-POT-DIFF` |
| `upgrade-from-previous` | §35 `e2e` stage | `GL-RELEASE-UPGRADE-FAIL` |

All five new `GL-*` codes are **queued for §15** (next minor version that adds them).

---

## 8. Anti-Patterns (forbidden)

- Tagging from a non-merged branch.
- Editing a published tag.
- Re-using a version number for any reason.
- Bumping `Stable tag:` ahead of the SVN-uploaded version.
- Shipping a Minor with a destructive migration.
- Shipping a Major without an updated `13-v1-vs-vN-mapping.md`.
- Skipping `wp git-logs verify` post-upgrade.
- Manual `MigrationState` insert (must come from migration class).
- Deleting screenshots between releases (existing screenshot numbers are URL-stable; replace contents, never renumber).

---

## 9. Cross-References

- §06 — migrations & logger (migration class layout)
- §18 — SQL DDL (`PluginVersion`, `MigrationState`, seeds)
- §23 — backup/restore (cross-major downgrade refusal)
- §26 — readme.txt + screenshots (WP.org assets)
- §27 — WP-CLI reference (verify/migrate/backup commands)
- §29 — uninstall policy (release-time uninstall sanity check)
- §30 — threat model (security gate)
- §35 — reference `ci.yml` (where new gates live)
- §97 — acceptance criteria (per-release AC additions)
- §98 — changelog (mandatory row per release)
