-- ============================================================================
-- Git Logs Plugin — V2_0_0 schema + lookup seeds
-- Source spec: spec/22-git-logs-v2/02-database-schema.md, 09-seed-data.md
-- Engine: SQLite 3.35+ (single root file)
-- Conventions: PascalCase tables/columns; PK = {Table}Id INTEGER PK AUTOINCREMENT.
-- All FKs: ON UPDATE CASCADE ON DELETE RESTRICT unless noted.
-- ============================================================================

PRAGMA foreign_keys = ON;
PRAGMA journal_mode = WAL;

BEGIN TRANSACTION;

-- ---------------------------------------------------------------------------
-- Lookup tables (enums)
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS UserStatus (
    UserStatusId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Role (
    RoleId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Permission (
    PermissionId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Provider (
    ProviderId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS OwnerType (
    OwnerTypeId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Acceptance (
    AcceptanceId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS AppStatus (
    AppStatusId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS AppLinkType (
    AppLinkTypeId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS LogSeverity (
    LogSeverityId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE,
    Numeric INTEGER NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS ActionType (
    ActionTypeId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS AuditActionType (
    AuditActionTypeId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS AuditOutcome (
    AuditOutcomeId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL UNIQUE
);

-- ---------------------------------------------------------------------------
-- Identity + access
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS Profile (
    ProfileId        INTEGER PRIMARY KEY AUTOINCREMENT,
    UserName         TEXT NOT NULL UNIQUE,
    Email            TEXT NOT NULL,
    GeneratedKeyApi  TEXT NOT NULL,
    Token            TEXT NOT NULL,
    TempToken        TEXT NOT NULL,
    UserStatusId     INTEGER NOT NULL DEFAULT 1
        REFERENCES UserStatus(UserStatusId),
    CreatedAt        INTEGER NOT NULL,
    UpdatedAt        INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS IxProfileTempToken ON Profile(TempToken);

CREATE TABLE IF NOT EXISTS RoleAssignment (
    RoleAssignmentId INTEGER PRIMARY KEY AUTOINCREMENT,
    ProfileId INTEGER NOT NULL REFERENCES Profile(ProfileId) ON DELETE CASCADE,
    RoleId    INTEGER NOT NULL REFERENCES Role(RoleId),
    UNIQUE (ProfileId, RoleId)
);

CREATE TABLE IF NOT EXISTS RolePermission (
    RolePermissionId INTEGER PRIMARY KEY AUTOINCREMENT,
    RoleId       INTEGER NOT NULL REFERENCES Role(RoleId) ON DELETE CASCADE,
    PermissionId INTEGER NOT NULL REFERENCES Permission(PermissionId),
    UNIQUE (RoleId, PermissionId)
);

-- ---------------------------------------------------------------------------
-- Source-control identity
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS GitProfile (
    GitProfileId        INTEGER PRIMARY KEY AUTOINCREMENT,
    ProfileUrl          TEXT NOT NULL UNIQUE,
    ProviderId          INTEGER NOT NULL REFERENCES Provider(ProviderId),
    OwnerName           TEXT NOT NULL,
    OwnerTypeId         INTEGER NOT NULL REFERENCES OwnerType(OwnerTypeId),
    AcceptanceId        INTEGER NOT NULL REFERENCES Acceptance(AcceptanceId),
    SelectedRepoUrl     TEXT,
    IsRestrictInBranch  INTEGER NOT NULL DEFAULT 0,
    StrictBranch        TEXT,
    CreatedAt           INTEGER NOT NULL,
    UpdatedAt           INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS IxGitProfileLookup ON GitProfile(ProviderId, OwnerName);

CREATE TABLE IF NOT EXISTS Repo (
    RepoId       INTEGER PRIMARY KEY AUTOINCREMENT,
    GitProfileId INTEGER NOT NULL REFERENCES GitProfile(GitProfileId),
    RootRepoName TEXT NOT NULL,
    RepoUrl      TEXT NOT NULL UNIQUE,
    CreatedAt    INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS RepoVersion (
    RepoVersionId INTEGER PRIMARY KEY AUTOINCREMENT,
    RepoId        INTEGER NOT NULL REFERENCES Repo(RepoId) ON DELETE CASCADE,
    VersionSuffix TEXT NOT NULL DEFAULT '',
    RepoUrl       TEXT NOT NULL UNIQUE,
    CreatedAt     INTEGER NOT NULL
);

-- ---------------------------------------------------------------------------
-- App + polymorphic linkage
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS App (
    AppId        INTEGER PRIMARY KEY AUTOINCREMENT,
    AppName      TEXT NOT NULL,
    AppSlug      TEXT NOT NULL UNIQUE,
    Description  TEXT,
    ProfileId    INTEGER NOT NULL REFERENCES Profile(ProfileId),
    AppStatusId  INTEGER NOT NULL DEFAULT 1 REFERENCES AppStatus(AppStatusId),
    CreatedAt    INTEGER NOT NULL,
    UpdatedAt    INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS AppLink (
    AppLinkId       INTEGER PRIMARY KEY AUTOINCREMENT,
    AppId           INTEGER NOT NULL REFERENCES App(AppId) ON DELETE CASCADE,
    AppLinkTypeId   INTEGER NOT NULL REFERENCES AppLinkType(AppLinkTypeId),
    GitProfileId    INTEGER REFERENCES GitProfile(GitProfileId) ON DELETE CASCADE,
    RepoId          INTEGER REFERENCES Repo(RepoId)             ON DELETE CASCADE,
    CreatedAt       INTEGER NOT NULL,
    -- Exactly-one-target check
    CHECK (
        (GitProfileId IS NOT NULL AND RepoId IS NULL)
     OR (GitProfileId IS NULL     AND RepoId IS NOT NULL)
    )
);

CREATE INDEX IF NOT EXISTS IxAppLinkApp ON AppLink(AppId);

-- ---------------------------------------------------------------------------
-- Pipeline + log entries
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS Pipeline (
    PipelineId    INTEGER PRIMARY KEY AUTOINCREMENT,
    RepoVersionId INTEGER NOT NULL REFERENCES RepoVersion(RepoVersionId) ON DELETE CASCADE,
    AppId         INTEGER REFERENCES App(AppId),
    Branch        TEXT NOT NULL,
    Pipeline      TEXT NOT NULL,
    HasError      INTEGER NOT NULL DEFAULT 0,
    CreatedAt     INTEGER NOT NULL,
    UpdatedAt     INTEGER NOT NULL,
    UNIQUE (RepoVersionId, Branch, Pipeline)
);

CREATE TABLE IF NOT EXISTS LogEntry (
    LogEntryId    INTEGER PRIMARY KEY AUTOINCREMENT,
    PipelineId    INTEGER NOT NULL REFERENCES Pipeline(PipelineId) ON DELETE CASCADE,
    LogSeverityId INTEGER NOT NULL REFERENCES LogSeverity(LogSeverityId),
    Message       TEXT NOT NULL,
    OccurredAt    INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS IxLogEntryPipeline ON LogEntry(PipelineId, OccurredAt);

CREATE TABLE IF NOT EXISTS ErrorLogEntry (
    ErrorLogEntryId INTEGER PRIMARY KEY AUTOINCREMENT,
    PipelineId      INTEGER NOT NULL REFERENCES Pipeline(PipelineId) ON DELETE CASCADE,
    LogSeverityId   INTEGER NOT NULL REFERENCES LogSeverity(LogSeverityId),
    Message         TEXT NOT NULL,
    OccurredAt      INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS IxErrorLogEntryPipeline ON ErrorLogEntry(PipelineId, OccurredAt);

-- ---------------------------------------------------------------------------
-- Audit triumvirate
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS History (
    HistoryId     INTEGER PRIMARY KEY AUTOINCREMENT,
    RepoVersionId INTEGER NOT NULL REFERENCES RepoVersion(RepoVersionId) ON DELETE CASCADE,
    PipelineId    INTEGER REFERENCES Pipeline(PipelineId),
    AppId         INTEGER REFERENCES App(AppId),
    ActionTypeId  INTEGER NOT NULL REFERENCES ActionType(ActionTypeId),
    HasError      INTEGER NOT NULL DEFAULT 0,
    Summary       TEXT,
    OccurredAt    INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS IxHistoryRepoVersion ON History(RepoVersionId, OccurredAt);

CREATE TABLE IF NOT EXISTS Action (
    ActionId      INTEGER PRIMARY KEY AUTOINCREMENT,
    HistoryId     INTEGER REFERENCES History(HistoryId) ON DELETE CASCADE,
    ActionTypeId  INTEGER NOT NULL REFERENCES ActionType(ActionTypeId),
    PipelineId    INTEGER REFERENCES Pipeline(PipelineId),
    Detail        TEXT,
    OccurredAt    INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS AuditTrail (
    AuditTrailId       INTEGER PRIMARY KEY AUTOINCREMENT,
    AuditActionTypeId  INTEGER NOT NULL REFERENCES AuditActionType(AuditActionTypeId),
    AuditOutcomeId     INTEGER NOT NULL REFERENCES AuditOutcome(AuditOutcomeId),
    ProfileId          INTEGER REFERENCES Profile(ProfileId),
    AppId              INTEGER REFERENCES App(AppId),
    RouteName          TEXT,
    RequestId          TEXT,
    HttpStatus         INTEGER,
    Detail             TEXT,
    OccurredAt         INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS IxAuditTrailRequest ON AuditTrail(RequestId);

-- ---------------------------------------------------------------------------
-- Operational
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS ConfigKv (
    KeyName   TEXT PRIMARY KEY,
    ValueText TEXT NOT NULL,
    UpdatedAt INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS MigrationState (
    PluginVersion TEXT PRIMARY KEY,
    AppliedAt     INTEGER NOT NULL,
    Checksum      TEXT
);

-- ============================================================================
-- Seeds — see 09-seed-data.md
-- ============================================================================

INSERT OR IGNORE INTO UserStatus (UserStatusId, Name) VALUES
    (1,'Active'),(2,'Suspended'),(3,'Revoked');

INSERT OR IGNORE INTO Role (RoleId, Name) VALUES
    (1,'Admin'),(2,'Editor');

INSERT OR IGNORE INTO Permission (PermissionId, Name) VALUES
    (1,'AppCreate'),(2,'AppView'),(3,'AppModify'),(4,'AppDelete'),
    (5,'ProfileCreate'),(6,'ProfileView'),(7,'ProfileModify'),(8,'ProfileDelete'),
    (9,'GitProfileCreate'),(10,'GitProfileView'),(11,'GitProfileModify'),(12,'GitProfileDelete'),
    (13,'RepoView'),(14,'RepoModify'),(15,'RepoDelete'),
    (16,'HistoryView'),(17,'LogPush');

INSERT OR IGNORE INTO Provider (ProviderId, Name) VALUES
    (1,'GitHub'),(2,'GitLab');

INSERT OR IGNORE INTO OwnerType (OwnerTypeId, Name) VALUES
    (1,'User'),(2,'Organization');

INSERT OR IGNORE INTO Acceptance (AcceptanceId, Name) VALUES
    (1,'AcceptAllRepos'),(2,'AcceptSelectedRepoOnly'),(3,'AcceptSelectedRepoInAllVersions');

INSERT OR IGNORE INTO AppStatus (AppStatusId, Name) VALUES
    (1,'Active'),(2,'Disabled'),(3,'Archived');

INSERT OR IGNORE INTO AppLinkType (AppLinkTypeId, Name) VALUES
    (1,'GitProfile'),(2,'Repo');

INSERT OR IGNORE INTO LogSeverity (LogSeverityId, Name, Numeric) VALUES
    (1,'Trace',10),(2,'Debug',20),(3,'Info',30),
    (4,'Warn',40),(5,'Error',50),(6,'Fatal',60);

INSERT OR IGNORE INTO ActionType (ActionTypeId, Name) VALUES
    (1,'Append'),(2,'Fixed'),(3,'Clear'),(4,'ClearAll');

INSERT OR IGNORE INTO AuditActionType (AuditActionTypeId, Name) VALUES
    (1,'ProfileCreate'),(2,'ProfileUpdate'),(3,'ProfileDelete'),
    (4,'GitProfileCreate'),(5,'GitProfileUpdate'),(6,'GitProfileDelete'),
    (7,'RepoCreate'),(8,'RepoUpdate'),(9,'RepoDelete'),
    (10,'AppCreate'),(11,'AppUpdate'),(12,'AppDelete'),(13,'AppLinkChange'),
    (14,'LogPush'),(15,'LogQuery'),
    (16,'AuthSuccess'),(17,'AuthFail'),(18,'MigrationRun');

INSERT OR IGNORE INTO AuditOutcome (AuditOutcomeId, Name) VALUES
    (1,'Success'),(2,'Rejected'),(3,'Error');

-- Admin role gets every permission
INSERT OR IGNORE INTO RolePermission (RoleId, PermissionId)
SELECT 1, PermissionId FROM Permission;

-- Editor role: view + modify, no create/delete, no LogPush
INSERT OR IGNORE INTO RolePermission (RoleId, PermissionId) VALUES
    (2,2),(2,3),(2,6),(2,10),(2,11),(2,13),(2,14),(2,16);

-- ConfigKv defaults
INSERT OR IGNORE INTO ConfigKv (KeyName, ValueText, UpdatedAt) VALUES
    ('LogLevelMin',          'Info',     strftime('%s','now')),
    ('PluginVersion',        '2.0.0',    strftime('%s','now')),
    ('RatePerMinPerProfile', '60',       strftime('%s','now')),
    ('MaxPushPayloadBytes',  '1048576',  strftime('%s','now')),
    ('MaxLinesPerPush',      '10000',    strftime('%s','now')),
    ('MaxLineBytes',         '65536',    strftime('%s','now'));

-- Migration marker — last
INSERT OR IGNORE INTO MigrationState (PluginVersion, AppliedAt, Checksum) VALUES
    ('2.0.0', strftime('%s','now'), NULL);

COMMIT;
