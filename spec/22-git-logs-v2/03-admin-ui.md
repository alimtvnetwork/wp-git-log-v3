# Admin UI (v2)

**Version:** 2.1.0  
**Updated:** 2026-04-26

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
| Profile URL | text input | User or Org URL; trailing slash optional; canonicalized on save (with trailing slash). Canonical form depends on **Is organization** below: `github.com/$org/$repo` when checked, else `github.com/$username/$repo`. |
| Provider | select (read-only `GitHub` in v2) | |
| Is organization | checkbox (default off) | **v3.8.0** — replaces derived `OwnerType`. When checked, the Profile URL is treated as an organization URL and canonicalized as `github.com/$org/$repo`; when unchecked, treated as a user URL (`github.com/$username/$repo`). Persisted as `GitProfile.IsOrganization` (0/1). |
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
