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

Route::prefix('git-logs/v2')->group(function () {

    // Lane B (CI/CD writers) — middleware: gl.lane-b
    Route::middleware('gl.lane-b')->group(function () {
        Route::post  ('append-log',     [AppendLogController::class,    '__invoke']);
        Route::put   ('fixed-log',      [FixedLogController::class,     '__invoke']);
        Route::post  ('clear-log',      [ClearLogController::class,     '__invoke']);
        Route::post  ('clear-log-all',  [ClearLogAllController::class,  '__invoke']);
    });

    // Lane A (Admin readers) — middleware: gl.lane-a + permission:HistoryView
    Route::middleware(['gl.lane-a', 'gl.permission:HistoryView'])->group(function () {
        Route::get('get-logs',                   [GetLogsController::class,                 '__invoke']);
        Route::get('get-pipeline-logs',          [GetPipelineLogsController::class,         '__invoke']);
        Route::get('get-error-logs',             [GetErrorLogsController::class,            '__invoke']);
        Route::get('get-pipeline-error-logs',    [GetPipelineErrorLogsController::class,    '__invoke']);
    });
});
