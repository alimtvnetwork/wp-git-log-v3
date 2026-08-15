<?php

use Illuminate\Support\Facades\Route;

// Domain controllers
use App\Http\Controllers\LaneB\AppendLogController;
use App\Http\Controllers\LaneB\FixedLogController;
use App\Http\Controllers\LaneB\ClearLogController;
use App\Http\Controllers\LaneB\ClearLogAllController;

use App\Http\Controllers\LaneA\GetLogsController;
use App\Http\Controllers\LaneA\GetPipelineLogsController;
use App\Http\Controllers\LaneA\GetErrorLogsController;
use App\Http\Controllers\LaneA\GetPipelineErrorLogsController;

use App\Http\Controllers\LaneA\GitProfileController;
use App\Http\Controllers\LaneA\RepoController;
use App\Http\Controllers\LaneA\RepoVersionController;
use App\Http\Controllers\LaneA\AppController;
use App\Http\Controllers\LaneA\AppLinkController;
use App\Http\Controllers\LaneA\RoleController;
use App\Http\Controllers\LaneA\PermissionController;
use App\Http\Controllers\LaneA\SshKeyController;
use App\Http\Controllers\LaneA\AuditTrailController;
use App\Http\Controllers\LaneA\DashboardController;

Route::prefix('git-logs/v2')->group(function () {

    // Lane B (CI/CD writers) — middleware: gl.lane-b
    Route::middleware('gl.lane-b')->group(function () {
        Route::post  ('append-log',     [AppendLogController::class,    '__invoke']);
        Route::put   ('fixed-log',      [FixedLogController::class,     '__invoke']);
        Route::post  ('clear-log',      [ClearLogController::class,     '__invoke']);
        Route::post  ('clear-log-all',  [ClearLogAllController::class,  '__invoke']);
    });

    // Lane A (Admin readers) — middleware: gl.lane-a + permission:HistoryView
    Route::middleware(['gl.lane-a'])->group(function () {
        Route::apiResource('git-profiles', GitProfileController::class);
        Route::apiResource('repos', RepoController::class);
        Route::apiResource('repo-versions', RepoVersionController::class);
        Route::apiResource('apps', AppController::class);
        Route::apiResource('app-links', AppLinkController::class);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);
        Route::apiResource('ssh-keys', SshKeyController::class);
        Route::apiResource('audit-trails', AuditTrailController::class)->only(['index', 'show']);
        
        Route::get('dashboard-stats', [DashboardController::class, 'stats']);
        Route::get('get-logs',                   [GetLogsController::class,                 '__invoke']);
        Route::get('get-pipeline-logs',          [GetPipelineLogsController::class,         '__invoke']);
        Route::get('get-error-logs',             [GetErrorLogsController::class,            '__invoke']);
        Route::get('get-pipeline-error-logs',    [GetPipelineErrorLogsController::class,    '__invoke']);
    });
});
