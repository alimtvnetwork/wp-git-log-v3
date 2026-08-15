<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use App\Models\ShaRegistry;
use App\Models\RepoVersion;
use App\Models\Pipeline;
use App\Support\ApiResponse;
use App\Support\ErrorEnvelope;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class GetPipelineLogsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'RepoUrl' => 'required|string',
            'BranchName' => 'required|string',
            'PipelineName' => 'required|string',
            'Limit' => 'integer|min:1|max:1000'
        ]);

        $limit = $validated['Limit'] ?? 100;

        $repoVersion = RepoVersion::where('RepoUrl', $validated['RepoUrl'])->first();
        if (!$repoVersion) {
            return ApiResponse::fail(new ErrorEnvelope('GL-REPO-404', 'RepoVersion not found', 'warn', now()->toIso8601String()), 404);
        }

        $pipeline = Pipeline::where('RepoVersionId', $repoVersion->RepoVersionId)
            ->where('Branch', $validated['BranchName'])
            ->where('Pipeline', $validated['PipelineName'])
            ->first();

        if (!$pipeline) {
            return ApiResponse::fail(new ErrorEnvelope('GL-PIPE-404', 'Pipeline not found', 'warn', now()->toIso8601String()), 404);
        }

        $shaRegs = ShaRegistry::where('PipelineId', $pipeline->PipelineId)
            ->orderBy('LastSeenAt', 'desc')
            ->limit($limit)
            ->get();

        return ApiResponse::success([
            'PipelineLogs' => $shaRegs
        ]);
    }
}
