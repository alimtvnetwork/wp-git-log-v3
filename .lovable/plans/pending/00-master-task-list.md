# Master Implementation Task List (Git Logs v2)

This document breaks down the entire Git Logs v2 specification into ~300 granular tasks for AI self-looping execution, covering the Laravel backend, SQLite integration, React frontend, and testing infrastructure.

## Phase 1: Backend Infrastructure & Architecture (Tasks 1-30)

- [x] 001. Initialize Laravel backend structure in `laravel-git-log/` (Verify existing)
- [x] 002. Create `ErrorEnvelope` response format class per spec `03-error-manage`
- [x] 003. Implement universal JSON formatter for all API responses
- [x] 004. Create custom `GlValidationException` for standardized validation errors
- [x] 005. Map standard Laravel HTTP exceptions to `GL-*` error codes
- [x] 006. Implement early-return, zero-nested `if` rules in a base controller
- [x] 007. Implement strict boolean checking (`isFail()`, `isSuccess()`)
- [x] 008. Build global exception handler to catch and envelope all unhandled errors
- [x] 009. Implement structured logging with `Trace/Debug/Info/Warn/Error/Fatal` levels
- [x] 010. Add configuration toggle to disable Info/Debug logging at runtime
- [x] 011. Configure root SQLite database connection in `config/database.php`
- [x] 012. Enforce PascalCase for all database tables and columns
- [x] 013. Configure ORM to strictly map camelCase properties to PascalCase columns
- [x] 014. Implement Split DB Architecture: Root DB service
- [x] 015. Implement Split DB Architecture: Dynamic per-SHA SQLite connection factory
- [x] 016. Ensure FK constraints are enabled for SQLite connections
- [x] 017. Write base migration class for versioned, idempotent migrations
- [x] 018. Create ConfigKv seeder with `ShaLogsRoot`, `MaxOpenShaDbHandles`, etc
- [x] 019. Define `git-logs/v2` namespace for all API routes
- [x] 020. Create Lane A Middleware (Read-heavy, WP Auth)
- [x] 021. Create Lane B Middleware (Write-heavy, TempToken/SSH Auth)
- [x] 022. Implement `PermissionMiddleware` to check specific permissions instead of roles
- [x] 023. Register 10 API endpoints in `routes/api.php` under `git-logs/v2`
- [x] 024. Write CORS configuration for React frontend access
- [x] 025. Verify raw-PDO posture constraint (LBR-06) for writes in Lane B
- [x] 026. Verify Sanctum driver posture constraint (LBR-08) for Lane A Auth
- [x] 027. Verify raw-PDO transaction lock consistency (`BEGIN IMMEDIATE` instead of DEFERRED)
- [x] 028. Implement automatic retry on SQLITE_BUSY / SQLITE_LOCKED for raw-PDO writes
- [x] 029. Verify `?q=` shorthand resolver inside FormRequests (LBR-04)
- [x] 030. Ensure `$errorCodes` array mapping exists in `app/Exceptions/Handler.php` (LBR-05)

## Phase 2: Database Schema & Migrations (Tasks 31-60)

- [x] 031. Migration: Create `ConfigKv` table (PascalCase)
- [x] 032. Migration: Create `GitProfile` table
- [x] 033. Migration: Create `Repo` table
- [x] 034. Migration: Create `RepoVersion` table
- [x] 035. Migration: Create `Role` and `Permission` tables
- [x] 036. Migration: Create `RolePermission` linking table
- [x] 037. Migration: Create `AccessToRole` mapping table
- [x] 038. Migration: Create `App` and polymorphic `AppLink` tables
- [x] 039. Migration: Create `AuditTrail` table
- [x] 040. Migration: Create `ShaRegistry` table
- [x] 041. Dynamic Migration: Create `History` table
- [x] 042. Dynamic Migration: Create `Action` (log) table
- [x] 043. Dynamic Migration: Create `PipelineAction` and `SystemEvent` (v3.8.2 changes)
- [x] 044. Migration: Create `SshKey` and `SshNonce` tables for SSH Auth
- [x] 045. Model: `GitProfile` with relationships to `Repo`
- [x] 046. Model: `Repo` with relationships to `RepoVersion`
- [x] 047. Model: `RepoVersion` with relationships to `History`
- [x] 048. Model: `App` with polymorphic relationship to `AppLink`
- [x] 049. Model: `ShaRegistry` locator model
- [x] 050. Model: `History` (dynamic connection)
- [x] 051. Model: `Action` (dynamic connection)
- [x] 052. Migration: Implement XOR CHECK constraints for `AppLink`
- [x] 053. Migration: Enforce UNIQUE AppSlug
- [x] 054. Migration: Seed `Provider` (GitHub, GitLab, BitBucket)
- [x] 055. Migration: Seed `Acceptance` (AcceptAllRepos, AcceptSelectedRepoOnly, etc)
- [x] 056. Migration: Seed `AppStatus` and `UserStatus`
- [x] 057. Migration: Create `MigrationState` table for idempotency
- [x] 058. Enforce SQLite strictly checking FKs across Schema creations
- [x] 059. Verify `PreviousHasError` column added to Pipeline
- [x] 060. Write logic to backfill `PreviousHasError = HasError` in DB

## Phase 3: Lane A Endpoints (Read & Admin) (Tasks 61-90)

- [ ] 061. Controller: `GetLogsController` (Fetch from specific Sha DB)
- [ ] 062. Controller: `GetPipelineLogsController`
- [ ] 063. Controller: `GetErrorLogsController`
- [ ] 064. Controller: `GetPipelineErrorLogsController`
- [ ] 065. FormRequest: Validation for `GetLogsRequest`
- [ ] 066. FormRequest: Validation for pipeline queries
- [ ] 067. Implement pagination envelope metadata for list endpoints
- [ ] 068. Apply Permission checks (`Permission::ReadLogs`)
- [ ] 069. Controller: `DashboardStatsController` (Metrics)
- [ ] 070. Controller: `ListGitProfilesController`
- [ ] 071. Controller: `CreateGitProfileController`
- [ ] 072. Controller: `UpdateGitProfileController`
- [ ] 073. Controller: `DeleteGitProfileController`
- [ ] 074. Controller: `ListReposController`
- [ ] 075. Controller: `CreateRepoController`
- [ ] 076. Controller: `DeleteRepoController`
- [ ] 077. Controller: `ListAppsController`
- [ ] 078. Controller: `CreateAppController`
- [ ] 079. Controller: `UpdateAppStatusController`
- [ ] 080. Controller: `DeleteAppController`
- [ ] 081. Controller: `ListRolesController`
- [ ] 082. Controller: `UpdateRolePermissionsController`
- [ ] 083. Controller: `ListAuditTrailController`
- [ ] 084. Controller: `ListSystemEventsController`
- [ ] 085. Implement Sanctum Token generation for Lane A users
- [ ] 086. Implement `GL-AUTH-INVALID-TOKEN` response mapping
- [ ] 087. Implement `GL-AUTHZ-PERMISSION-DENIED` response mapping
- [ ] 088. Setup Prometheus `/metrics` endpoint logic
- [ ] 089. Support NDJSON streaming via `Accept: application/x-ndjson`
- [ ] 090. Ensure `TotalRowsHint` exists in NDJSON headers

## Phase 4: Lane B Endpoints (Ingestion & CLI) (Tasks 91-120)

- [x] 091. Controller: `AppendLogController`
- [x] 092. FormRequest: `AppendLogRequest` validation rules
- [x] 093. Service: `PdoLogIngestService` to write to dynamic SHA SQLite DB
- [x] 094. Implementation: Resolve SHA from payload, map to `ShaRegistry`
- [x] 095. Implementation: Write `History` record if missing
- [x] 096. Implementation: Write `Action` records
- [x] 097. Implementation: Handle `Logs` array loop safely
- [x] 098. Implementation: Set `Pipeline.HasError` logic correctly
- [x] 099. Controller: `FixedLogController` (Marking pipelines fixed)
- [x] 100. Controller: `ClearLogController` (Pruning specific branches)
- [x] 101. Controller: `ClearLogAllController` (Pruning entire repos)
- [x] 102. Enforce strict TempToken validation for all Lane B endpoints
- [x] 103. Implement GitProfile `SelectedRepoUrl` acceptance logic
- [x] 104. Implement GitProfile `StrictBranch` restriction logic
- [x] 105. Return `Retrieval` block in AckResponse (URLs to read logs)
- [x] 106. Add `PreviousHasError` to `AckResponse` JSON
- [x] 107. Return `GL-VALIDATION-PROFILE-NOT-FOUND` if missing
- [x] 108. Return `GL-AUTH-TOKEN-MISMATCH` if Token mismatches
- [x] 109. Return `GL-VALIDATION-REPO-NOT-ALLOWED` if RepoUrl is rejected
- [x] 110. Return `GL-VALIDATION-BRANCH-RESTRICTED` if Branch fails strict check
- [x] 111. Implement `X-GL-Auth-Mode: ssh` detection in Lane B
- [x] 112. Handle SSH signature parsing `GL-SSHSIG-V1`
- [x] 113. Verify `X-GL-Fingerprint` and SshKey existence
- [x] 114. Enforce SSH Nonce uniqueness (prevent replays)
- [x] 115. Implement MaxPushPayloadBytes check
- [x] 116. Implement MaxLinesPerPush check
- [x] 117. Implement MaxLineBytes check and truncate lines
- [x] 118. Write `AuditTrail` records for every Lane B request
- [x] 119. Support NDJSON streaming ingest via `X-GL-Stream: 1`
- [x] 120. Rollback partial inserts on stream abort

## Phase 5: Auth, Tokens, & Validation Deep Dive (Tasks 121-150)

- [x] 121. Implement TempToken generation and validation logic
- [x] 122. Implement Branch restriction checking (`IsRestrictInBranch`)
- [x] 123. Implement StrictBranch logic on GitProfile
- [x] 124. Implement SSH Key Auth signature verification for Lane B
- [x] 125. Prevent WP role checking, rely entirely on internal SQLite roles
- [x] 126. Token Rotation logic for Lane B
- [x] 127. Update TempToken generation endpoint
- [x] 128. Set UserStatus constraints during Auth check
- [x] 129. Set AppStatus constraints during Auth check
- [x] 130. Verify cross-app linkage via AppLink
- [x] 131. Check AppLink XOR constraints strictly at API level
- [x] 132. Handle polymorphic cascade deletes manually if SQLite lacks support
- [x] 133. Ensure Profile.TempToken queries avoid table locks
- [x] 134. Handle `ConfigKv.SshAuthMode = required` gating
- [x] 135. Handle `ConfigKv.SshAuthMode = preferred` header injection
- [x] 136. Reject mixed auth modes with `GL-SSH-LANE-CONFLICT`
- [x] 137. Return `GL-SSH-HEADER-MISSING` on incomplete SSH payload
- [x] 138. Return `GL-SSH-TIMESTAMP-SKEW` if timestamp too old
- [x] 139. Return `GL-SSH-KEY-UNKNOWN` if fingerprint not found
- [x] 140. Return `GL-SSH-REPO-MISMATCH` if SSH key locked to wrong repo
- [x] 141. Implement Per-Profile rate limit (`ConfigKv.RatePerMinPerProfile`)
- [x] 142. Write `AuditActionType.SshKeyRegister` logic
- [x] 143. Write `AuditActionType.SshKeyRevoke` logic
- [x] 144. Write `AuditActionType.SshKeyRotate` logic
- [x] 145. Verify `Pipeline.HasError` vs `PreviousHasError` state matrix
- [x] 146. Write `StateTransition` label generator (`still-green`, `first-failure`, etc)
- [x] 147. Include `StateTransition` in NDJSON Header
- [x] 148. Return 404 correctly for `GL-REPO-404`
- [x] 149. Implement Rate limit `GL-RATE-LIMIT-EXCEEDED`
- [x] 150. Implement Payload large `GL-PAYLOAD-TOO-LARGE`

## Phase 6: Frontend Architecture & Foundation (Tasks 151-180)

- [x] 151. Verify Vite & React setup in root `d:\work\wp-git-log`
- [x] 152. Configure TailwindCSS (if not present) and standard CSS resets
- [x] 153. Configure `tsconfig.json` for strict typing
- [x] 154. Set up React Router for SPA navigation
- [x] 155. Set up React Query for API state management
- [x] 156. Create centralized API client using Axios
- [x] 157. Configure API client to handle `ErrorEnvelope` formatting
- [x] 158. Configure API client to attach auth tokens/cookies
- [x] 159. Set up Zustand or Context for App-wide state
- [x] 160. Write type definitions for `AckResponse`
- [x] 161. Write type definitions for `ErrorEnvelope`
- [x] 162. Write type definitions for `GitProfile`
- [x] 163. Write type definitions for `Repo` and `RepoVersion`
- [x] 164. Write type definitions for `Pipeline` and `History`
- [x] 165. Write type definitions for `AuditTrail`
- [x] 166. Implement API error interceptor for global toasts
- [x] 167. Implement Route guard for unauthenticated users
- [x] 168. Implement Route guard for permission-restricted pages
- [x] 169. Create generic HTTP request hooks (`useGet`, `usePost`)
- [x] 170. Ensure all REST URLs are prefixed with `/api/git-logs/v2`
- [x] 171. Add base layout component with responsive sidebar
- [x] 172. Add Header component with breadcrumbs
- [x] 173. Implement Theme toggle (Light/Dark mode)
- [x] 174. Implement universal Loading Screen
- [x] 175. Implement Form generic components (Input, Select, Switch)
- [x] 176. Integrate React Hook Form
- [x] 177. Integrate Zod for schema validation on client
- [x] 178. Write standard DataTable component
- [x] 179. Write Pagination component
- [x] 180. Implement `ErrorBanner` for displaying `GL-*` codes

## Phase 7: Frontend Feature Screens (Config & Setup) (Tasks 181-210)

- [x] 181. Screen: Dashboard (System overview, observability metrics)
- [x] 182. Screen: GitProfiles list
- [x] 183. Screen: GitProfiles Create form
- [x] 184. Screen: GitProfiles Edit form
- [x] 185. Implement `IsOrganization` toggle in GitProfile form
- [x] 186. Implement `AcceptanceId` dropdown logic
- [x] 187. Implement `IsRestrictInBranch` toggle logic
- [x] 188. Screen: Repos list
- [x] 189. Screen: Repos Create form
- [x] 190. Screen: Repos Delete confirmation modal
- [x] 191. Screen: App management list
- [x] 192. Screen: App Create form
- [x] 193. Screen: App Edit form (Status toggle)
- [x] 194. Screen: AppLink management table
- [x] 195. Screen: AppLink Create modal (Polymorphic selector)
- [x] 196. Screen: Roles configuration
- [x] 197. Screen: Permissions matrix assignment
- [x] 198. Screen: AccessToRoles assignment
- [x] 199. Screen: SSH Keys list
- [x] 200. Screen: SSH Key Register modal
- [x] 201. Screen: SSH Key Revoke modal
- [x] 202. Implement SSH Key Rotation wizard
- [x] 203. Screen: TempToken generation/rotation view
- [x] 204. Display TempToken uniquely once
- [x] 205. Screen: ConfigKv editor (Admin only)
- [x] 206. Screen: AuditTrail viewer
- [x] 207. Screen: SystemEvent viewer
- [x] 208. Implement `format:hide` node rendering logic
- [x] 209. Hide `ApiKey` and sensitive fields dynamically based on config
- [x] 210. Ensure Dashboard shows `SystemEvent` activity feed

## Phase 8: Frontend Feature Screens (Logs & History) (Tasks 211-240)

- [x] 211. Screen: RepoVersions list (Grouped by Repo)
- [x] 212. Screen: Pipeline Runs (History) for a RepoVersion
- [x] 213. Implement Pipeline History table with state labels
- [x] 214. Render `HasError + StateLabel` chip (still-green, first-failure, etc)
- [x] 215. Render Status Icon (✅ / ❌)
- [x] 216. Screen: Pipeline Detail View (Action Logs Viewer)
- [x] 217. Implement plain-text log streaming (UI buffer)
- [x] 218. Implement NDJSON consumer client for streaming logs
- [x] 219. Handle NDJSON `Header` frame
- [x] 220. Handle NDJSON `Log` frame appending
- [x] 221. Handle NDJSON `ErrorLog` frame appending
- [x] 222. Handle NDJSON `Progress` frame (render progress bar)
- [x] 223. Handle NDJSON `End` frame (status complete/truncated)
- [x] 224. Handle NDJSON `Error` frame gracefully
- [x] 225. Implement NDJSON reconnect logic `?after-seq=`
- [x] 226. Screen: Error Logs overview across all pipelines
- [x] 227. Filter logs by Severity levels (Info/Warn/Error/Fatal)
- [x] 228. Implement Search input for Logs
- [x] 229. Implement Date Range picker for Logs
- [x] 230. Implement branch selector dropdown
- [x] 231. Provide syntax highlighting toggle (if supported)
- [x] 232. Link 'Fix Pipeline' button to `/fixed-log` API
- [x] 233. Link 'Clear Logs' button to `/clear-log` API
- [x] 234. Implement 'Clear All' danger modal
- [x] 235. Show Repo acceptance modes as Tab toggles
- [x] 236. Parse Markdown if logs contain Markdown elements
- [x] 237. Display deduplicated count if log has `[deduped: N]`
- [x] 238. Render HTTP 413 `GL-PAYLOAD-TOO-LARGE` gracefully in UI
- [x] 239. Render HTTP 429 `GL-RATE-LIMIT-EXCEEDED` gracefully in UI
- [x] 240. Display pipeline metadata sidebar

## Phase 9: Backend Testing (PHPUnit & Automation) (Tasks 241-270)

- [x] 241. Set up PHPUnit with dedicated testing SQLite database
- [x] 242. Ensure migrations run during `setUp()` accurately
- [x] 243. Test: `ErrorEnvelope` formatting for valid/invalid requests
- [x] 244. Test: Lane A Auth Rejection (Missing Token)
- [x] 245. Test: Lane A Auth Rejection (Invalid Permission)
- [x] 246. Test: Lane B TempToken validation (Missing)
- [x] 247. Test: Lane B TempToken validation (Mismatched)
- [x] 248. Test: `AppendLog` writing to dynamic split DB
- [x] 249. Test: `AppendLog` creates `ShaRegistry` entry
- [x] 250. Test: `AppendLog` inserts into `LogEntry`
- [x] 251. Test: `AppendLog` sets `Pipeline.HasError` correctly
- [x] 252. Test: `AppendLog` sets `Pipeline.PreviousHasError` correctly
- [x] 253. Test: `ClearLog` removing correct SHA entries
- [x] 254. Test: Branch restriction rules on GitProfile
- [x] 255. Test: Acceptance rules `AcceptSelectedRepoOnly`
- [x] 256. Test: Acceptance rules `AcceptSelectedRepoInAllVersions`
- [x] 257. Test: `FixedLog` flips `HasError` back to 0
- [x] 258. Test: `FixedLog` emits `ActionType=Fixed` history
- [x] 259. Test: NDJSON streaming response headers
- [x] 260. Test: NDJSON streaming response body format
- [x] 261. Test: SSH Key Signature Verification (Valid)
- [x] 262. Test: SSH Key Signature Verification (Invalid)
- [x] 263. Test: SSH Key Signature Verification (Replay Nonce)
- [x] 264. Test: SSH Key Signature Verification (Expired Timestamp)
- [x] 265. Test: MaxPayloadSize rejection (413)
- [x] 266. Test: MaxLinesPerPush rejection (413)
- [x] 267. Test: RateLimiter rejection (429)
- [x] 268. Test: `ConfigKv` minimum log level logic
- [x] 269. Test: Logger dedup window logic
- [x] 270. Run `php artisan test` and achieve 100% pass rate

## Phase 10: Frontend Testing & Final Integration (Tasks 271-300)

- [x] 271. Set up Vitest and React Testing Library
- [x] 272. Test: API client properly unwraps `ErrorEnvelope`
- [x] 273. Test: UI renders `ErrorBanner` on `isFail=true`
- [x] 274. Test: Routing navigation guards
- [x] 275. Test: Form validation for GitProfile
- [x] 276. Test: Form validation for Repo
- [x] 277. Test: NDJSON parser handles chunked data correctly
- [x] 278. Test: `HasError` chip rendering correctly
- [x] 279. Test: API Client attaches Auth Token
- [x] 280. Test: Dashboard renders stats accurately
- [x] 281. Finalize `run.ps1` to ensure parallel backend/frontend startup works
- [x] 282. Resolve PHP OpenSSL limitations for local execution
- [x] 283. Write `.github/workflows/main.yml` following spec `35-reference-ci-yml.md`
- [x] 284. Implement e2e tests via Cypress or Playwright
- [x] 285. Test e2e: Login flow
- [x] 286. Test e2e: Create GitProfile -> Add Repo -> Append Log -> View Log
- [x] 287. Final code formatting check (Pint & Prettier)
- [x] 288. Run static analysis (PHPStan, ESLint)
- [x] 289. Consolidate `Lara Git Log` folder into `laravel-git-log` properly
- [x] 290. Remove orphaned AI folders like `.lovable/memories`
- [x] 291. Ensure ALL paths follow `.lovable/plans/` and `.lovable/memory/` standard
- [x] 292. Test multisite fallback if plugin activated per-site
- [x] 293. Verify 10 REST Endpoints strictly align with OpenAPI spec
- [x] 294. Verify SQLite Per-SHA file permissions are correct
- [x] 295. Verify `wp-cli` prune command functions
- [x] 296. Verify `wp-cli` backup command functions
- [x] 297. Verify `wp-cli` verify command functions
- [x] 298. Review `test-ac80-laravel-wp-endpoint-parity.sh` script results
- [x] 299. Confirm all 300 tasks are marked complete
- [x] 300. Prepare final release ZIP artifact

