<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Support\ApiResponse;
use App\Support\ErrorEnvelope;
use App\Support\ShaDatabaseFactory;
use Illuminate\Support\Facades\DB;

class LogViewerController extends Controller
{
    public function logs($pipelineId)
    {
        return $this->fetchLogsFromSplitDb($pipelineId, 'LogEntry');
    }

    public function errors($pipelineId)
    {
        return $this->fetchLogsFromSplitDb($pipelineId, 'ErrorLogEntry');
    }

    private function fetchLogsFromSplitDb($pipelineId, $table)
    {
        $registry = DB::table('ShaRegistry')
            ->join('Pipeline', 'ShaRegistry.PipelineId', '=', 'Pipeline.PipelineId')
            ->join('RepoVersion', 'Pipeline.RepoVersionId', '=', 'RepoVersion.RepoVersionId')
            ->where('ShaRegistry.PipelineId', $pipelineId)
            ->select('ShaRegistry.Sha', 'RepoVersion.RepoVersionId')
            ->first();

        if (!$registry) {
            return ApiResponse::fail(new ErrorEnvelope('GL-PIPELINE-NOT-FOUND', 'Pipeline or SHA registry not found', 'error', now()->toIso8601String()), 404);
        }

        try {
            // Establish connection to the specific SHA sqlite file
            $connectionName = ShaDatabaseFactory::connectToShaDb($registry->RepoVersionId, $registry->Sha);
            
            // Read the logs
            $logs = DB::connection($connectionName)
                ->table($table)
                ->where('PipelineId', $pipelineId)
                ->orderBy('LineNumber', 'asc')
                ->limit(5000) // Prevent overwhelming UI
                ->get();
                
            return ApiResponse::success([
                'PipelineId' => $pipelineId,
                'Sha' => $registry->Sha,
                'Logs' => $logs
            ]);
            
        } catch (\Exception $e) {
            return ApiResponse::fail(new ErrorEnvelope('GL-SPLIT-DB-ERROR', 'Failed to read from Split DB: ' . $e->getMessage(), 'error', now()->toIso8601String()), 500);
        }
    }
}
