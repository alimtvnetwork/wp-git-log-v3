<?php

namespace App\Http\Controllers\LaneB;

use App\Http\Controllers\Controller;
use App\Models\ShaRegistry;
use App\Models\RepoVersion;
use App\Models\Pipeline;
use App\Support\ApiResponse;
use App\Support\ErrorEnvelope;
use App\Support\ShaDatabaseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class AppendLogController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'RepoUrl' => 'required|string',
            'GitSha256' => 'required|string',
            'BranchName' => 'required|string',
            'PipelineName' => 'required|string',
            'Logs' => 'required|array',
            'Logs.*.LineNumber' => 'required|integer',
            'Logs.*.LogText' => 'required|string',
            'Logs.*.Severity' => 'required|integer',
            'Logs.*.OccurredAt' => 'integer',
            'HasError' => 'boolean'
        ]);

        $repoVersion = RepoVersion::where('RepoUrl', $validated['RepoUrl'])->first();
        if (!$repoVersion) {
            return ApiResponse::fail(new ErrorEnvelope('GL-REPO-404', 'RepoVersion not found', 'warn', now()->toIso8601String()), 404);
        }

        // Ensure pipeline exists
        $pipeline = Pipeline::firstOrCreate([
            'RepoVersionId' => $repoVersion->RepoVersionId,
            'Branch' => $validated['BranchName'],
            'Pipeline' => $validated['PipelineName']
        ], [
            'HasError' => $validated['HasError'] ?? false ? 1 : 0,
            'PreviousHasError' => 0,
            'CreatedAt' => now()->timestamp,
            'UpdatedAt' => now()->timestamp,
        ]);

        if (isset($validated['HasError'])) {
            $newHasError = $validated['HasError'] ? 1 : 0;
            if ($pipeline->HasError !== $newHasError) {
                $pipeline->PreviousHasError = $pipeline->HasError;
                $pipeline->HasError = $newHasError;
                $pipeline->UpdatedAt = now()->timestamp;
                $pipeline->save();
            }
        }

        $shaReg = ShaRegistry::firstOrCreate([
            'PipelineId' => $pipeline->PipelineId,
            'Sha' => $validated['GitSha256']
        ], [
            'DbFilePath' => 'logs/' . $repoVersion->RepoVersionId . '/' . $validated['GitSha256'] . '.sqlite',
            'RowCount' => 0,
            'FirstSeenAt' => now()->timestamp,
            'LastSeenAt' => now()->timestamp,
            'FileSizeBytes' => 0
        ]);

        $db = ShaDatabaseFactory::connect($repoVersion->RepoVersionId, $shaReg->Sha, $repoVersion->RepoUrl, $validated['BranchName']);

        foreach ($validated['Logs'] as $log) {
            $db->table('LogEntry')->insert([
                'PipelineId' => $pipeline->PipelineId,
                'BranchName' => $validated['BranchName'],
                'PipelineName' => $validated['PipelineName'],
                'LineNumber' => $log['LineNumber'],
                'LogText' => $log['LogText'],
                'LogSeverityId' => $log['Severity'],
                'OccurredAt' => $log['OccurredAt'] ?? now()->timestamp
            ]);
            
            if (($log['Severity'] >= 4) || ($validated['HasError'] ?? false)) {
                $db->table('ErrorLogEntry')->insert([
                    'PipelineId' => $pipeline->PipelineId,
                    'BranchName' => $validated['BranchName'],
                    'PipelineName' => $validated['PipelineName'],
                    'LineNumber' => $log['LineNumber'],
                    'LogText' => $log['LogText'],
                    'OccurredAt' => $log['OccurredAt'] ?? now()->timestamp
                ]);
            }
        }

        return ApiResponse::success([
            'Appended' => true
        ]);
    }
}
