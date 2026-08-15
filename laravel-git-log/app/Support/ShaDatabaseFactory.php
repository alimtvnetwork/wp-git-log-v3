<?php

namespace App\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ShaDatabaseFactory
{
    /**
     * Connects to a per-SHA SQLite database, creating it and migrating if necessary.
     */
    public static function connect(int $repoVersionId, string $gitSha256, string $repoUrl, string $branchName, string $pluginVersion = '2.9.3'): \Illuminate\Database\Connection
    {
        $connectionName = 'sha_' . $gitSha256;

        if (Config::has('database.connections.' . $connectionName)) {
            return DB::connection($connectionName);
        }

        $dir = storage_path('app/git-logs/logs/' . $repoVersionId);
        $path = $dir . '/' . $gitSha256 . '.sqlite';

        $isNew = !File::exists($path);

        if ($isNew) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            File::put($path, '');
        }

        Config::set('database.connections.' . $connectionName, [
            'driver' => 'sqlite',
            'database' => $path,
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        $connection = DB::connection($connectionName);

        if ($isNew) {
            self::runSchema($connection, $repoVersionId, $gitSha256, $repoUrl, $branchName, $pluginVersion);
        }

        return $connection;
    }

    private static function runSchema(\Illuminate\Database\Connection $db, int $repoVersionId, string $gitSha256, string $repoUrl, string $branchName, string $pluginVersion): void
    {
        $db->unprepared('PRAGMA journal_mode = WAL;');
        $db->unprepared('PRAGMA foreign_keys = ON;');
        
        $db->unprepared('
            CREATE TABLE ShaMeta (
                ShaMetaId INTEGER PRIMARY KEY AUTOINCREMENT,
                RepoVersionId INTEGER NOT NULL,
                GitSha256 TEXT NOT NULL,
                RepoUrl TEXT NOT NULL,
                BranchName TEXT NOT NULL,
                CreatedAt INTEGER NOT NULL,
                PluginVersion TEXT NOT NULL
            );
            
            CREATE TABLE LogSeverity (
                LogSeverityId INTEGER PRIMARY KEY,
                Name TEXT NOT NULL UNIQUE,
                Numeric INTEGER NOT NULL
            );
            
            CREATE TABLE LogEntry (
                LogEntryId INTEGER PRIMARY KEY AUTOINCREMENT,
                PipelineId INTEGER NOT NULL,
                BranchName TEXT NOT NULL,
                PipelineName TEXT NOT NULL,
                LineNumber INTEGER NOT NULL,
                LogText TEXT NOT NULL,
                LogSeverityId INTEGER NOT NULL REFERENCES LogSeverity(LogSeverityId),
                FilePath TEXT NULL,
                OccurredAt INTEGER NOT NULL
            );
            CREATE INDEX idx_LogEntry_pipeline ON LogEntry(PipelineId, LineNumber);
            CREATE INDEX idx_LogEntry_severity ON LogEntry(LogSeverityId, OccurredAt);
            
            CREATE TABLE ErrorLogEntry (
                ErrorLogEntryId INTEGER PRIMARY KEY AUTOINCREMENT,
                PipelineId INTEGER NOT NULL,
                BranchName TEXT NOT NULL,
                PipelineName TEXT NOT NULL,
                LineNumber INTEGER NOT NULL,
                LogText TEXT NOT NULL,
                FilePath TEXT NULL,
                OccurredAt INTEGER NOT NULL
            );
            CREATE INDEX idx_ErrorLogEntry_pipeline ON ErrorLogEntry(PipelineId, LineNumber);
            
            CREATE TABLE PipelineRun (
                PipelineRunId INTEGER PRIMARY KEY AUTOINCREMENT,
                PipelineId INTEGER NOT NULL,
                BranchName TEXT NOT NULL,
                PipelineName TEXT NOT NULL,
                StartedAt INTEGER NOT NULL,
                EndedAt INTEGER NULL,
                HasError INTEGER NOT NULL DEFAULT 0,
                ErrorCount INTEGER NOT NULL DEFAULT 0,
                LineCount INTEGER NOT NULL DEFAULT 0,
                LastSeverityId INTEGER NULL REFERENCES LogSeverity(LogSeverityId)
            );
            CREATE UNIQUE INDEX uq_PipelineRun ON PipelineRun(PipelineId, BranchName, PipelineName, StartedAt);
            
            CREATE TABLE StatusSnapshot (
                StatusSnapshotId INTEGER PRIMARY KEY AUTOINCREMENT,
                LastStatus TEXT NOT NULL DEFAULT "Pending",
                FailureCount INTEGER NOT NULL DEFAULT 0,
                LastFailureAt INTEGER NULL,
                LastSuccessAt INTEGER NULL,
                LastEntryAt INTEGER NULL,
                UpdatedAt INTEGER NOT NULL
            );
        ');

        $db->unprepared('
            INSERT INTO LogSeverity (LogSeverityId, Name, Numeric) VALUES
            (1,"Trace",10),(2,"Debug",20),(3,"Info",30),
            (4,"Warn",40),(5,"Error",50),(6,"Fatal",60);
        ');

        $now = now()->timestamp;
        
        $db->table('ShaMeta')->insert([
            'RepoVersionId' => $repoVersionId,
            'GitSha256' => $gitSha256,
            'RepoUrl' => $repoUrl,
            'BranchName' => $branchName,
            'CreatedAt' => $now,
            'PluginVersion' => $pluginVersion
        ]);

        $db->table('StatusSnapshot')->insert([
            'LastStatus' => 'Pending',
            'FailureCount' => 0,
            'UpdatedAt' => $now
        ]);
    }
}
