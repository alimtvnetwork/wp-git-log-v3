# Admin UI (v2)

**Version:** 2.0.0  
**Updated:** 2026-04-25

---

## Top-Level Menu (in order)

1. **Profile** — list, create, edit plugin Profiles. Fields: `UserName`, `Email`, `GeneratedKeyApi` (read-only after generation, regenerate button), `Token` (regenerate), `TempToken` (regenerate; shown). No password field.
2. **Roles** — list, create, edit Roles. Default seeded: `Admin`, `Editor`.
3. **AccessToRoles** — assign Permissions to Roles via `RolePermission`. UI = matrix of Roles × Permissions checkboxes. Admin row is locked-on for all permissions.
4. **GitProfile** — list, add, edit GitProfiles.
5. **Repo** — list of master Repos. Click → opens History for that Repo.
6. **RepoVersion** — list of RepoVersion variants per Repo, with link to History filtered by version.
7. **History** — per-RepoVersion timeline (App, Branch, Pipeline, Action, HasError, OccurredAt).
8. **Action** — enum-typed action log.

> Items marked `format:hide` in the mind-map (notes under GitProfile, etc.) are not rendered in UI.

---

## GitProfile — Add/Edit Form

| Field | Control | Notes |
|-------|---------|-------|
| Profile URL | text input | User or Org URL; trailing slash optional; canonicalized on save (with trailing slash). |
| Provider | select (read-only `GitHub` in v2) | |
| OwnerType | derived (User \| Organization) from URL parse | |
| Acceptance | select | `AcceptAllRepos`, `AcceptSelectedRepoOnly`, `AcceptSelectedRepoInAllVersions` |
| Selected Repo URL | text input | Visible iff Acceptance ≠ `AcceptAllRepos` |
| IsRestrictInBranch | checkbox (default off) | |
| StrictBranch | text input | Visible iff `IsRestrictInBranch` is on |

Validation: server normalizes URL, parses owner/repo, derives `RootRepoName` for selected variants.

---

## Profile — Add/Edit Form

| Field | Control |
|-------|---------|
| UserName | text |
| Email | email |
| GeneratedKeyApi | read-only + Regenerate button |
| Token | read-only + Regenerate button |
| TempToken | read-only + Regenerate button |
| UserStatus | select (Active/Suspended/Revoked) |
| Roles | multi-select (RoleAssignment) |

---

## Roles + AccessToRoles

- **Roles screen**: CRUD on Role table.
- **AccessToRoles screen**: matrix `Role × Permission` writing to `RolePermission`.
- Authorization layer always queries `RolePermission` (never the role name).

---

## Repo / RepoVersion / History / Action

- **Repo list** → click row → **History** view filtered to that Repo's RepoVersions.
- **RepoVersion list** → click row → **History** filtered to one variant.
- **History** columns: OccurredAt, App, Branch, Pipeline, ActionType, HasError, Summary.
- **Action** view: raw enum log with filters (ActionType, RepoVersion, Profile, date range).

---

## First-run Bootstrap (one-time)

Triggered when `Profile` table is empty AND the visiting WP user has the `manage_options` capability. Shown as a full-page form (not a menu item) on first admin entry to any Git Logs screen.

| Field | Control | Notes |
|-------|---------|-------|
| UserName | text (required) | Defaults to current WP user's `display_name`; editable. |
| Email | email (required) | Defaults to current WP user's email; editable. |
| Confirm | checkbox + button "Create First Profile" | Required to submit. |

On submit:
1. Create `Profile` row with auto-generated `GeneratedKeyApi`, `Token`, `TempToken` (cryptographically random).
2. Insert `RoleAssignment(ProfileId, RoleId=1)` (Admin).
3. Write `AuditTrail` row: `AuditActionType=ProfileCreate`, `AuditOutcome=Success`, `Detail=BootstrapFirstProfile`.
4. Redirect to **Profile** screen showing the freshly minted credentials with a one-time "Copy and store securely" banner.

Subsequent visits never show the bootstrap form again because the `Profile` table is no longer empty. If the only Profile is later deleted, the bootstrap form re-appears (intentional — prevents lockout).
