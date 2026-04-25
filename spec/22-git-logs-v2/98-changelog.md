# Changelog (v2)

| Version | Date | Notes |
|---------|------|-------|
| 2.6.0 | 2026-04-25 | Added §27 WP-CLI reference, §28 GitHub Actions workflow example, §29 uninstall policy, §30 STRIDE threat model. Housekeeping pass: appended `Prune` (19) + `Restore` (20) `AuditActionType` seeds in `18-schema.sql`; added 4 new auth codes to `15-error-codes.md` (`GL-AUTH-NOT-LOGGED-IN`, `GL-AUTH-NO-PROFILE-LINK`, `GL-AUTH-PROFILE-SUSPENDED`, `GL-AUTH-WRONG-LANE`); extended `97-acceptance-criteria.md` from AC-25 to AC-41 covering §10–§26. New seeds queued for §29: `PluginUninstall` (21). |
| 2.5.0 | 2026-04-25 | Added §22 retention/pruning (`wp git-logs prune`), §23 backup/restore (SQLite Online Backup API + manifest), §24 multisite behavior, §25 headless auth notes (JWT/OAuth combos), §26 WP.org readme.txt + screenshot inventory. New seed `AuditActionType` rows planned: Prune (19), Restore (20). |
| 2.4.0 | 2026-04-25 | Added §17 OpenAPI 3.1 spec, §18 verbatim SQL DDL, §19 permission matrix, §20 observability (Site Health + Prometheus metrics), §21 i18n plan. |
| 2.3.0 | 2026-04-25 | Added §14 endpoint examples, §15 error code catalog, §16 test plan. §05 cross-linked to CI/CD pipeline workflows folder. Diagrams 07 + 08 rendered to SVG. |
| 2.2.0 | 2026-04-25 | Added §13 v1↔v2 mapping. Extended §97 AC (AC-26..AC-36). §12 cross-linked to PHP coding-standards folder. §03 gained First-run Bootstrap subsection. Folder 26 gained `07-rate-limit-flow.mmd` and `08-encryption-v3-flow.mmd`. |
| 2.1.0 | 2026-04-25 | Added §09 seed data, §10 rate limits/payload caps, §11 encryption deferred plan, §12 WP plugin scaffold. §06 amended with migration class layout. |
| 2.0.0 | 2026-04-25 | Initial parallel rewrite from verbatim brief. SQLite root DB, JWT removed, AppLink polymorphic, three-table audit model, 10 REST endpoints under `git-logs/v2`. |
