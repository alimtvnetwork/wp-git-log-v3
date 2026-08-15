<?php

namespace App\Http\Controllers\LaneB;

use App\Http\Controllers\Controller;
use App\Models\ShaRegistry;
use App\Models\RepoVersion;
use App\Models\Pipeline;
use App\Support\ApiResponse;
use App\Support\ErrorEnvelope;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class FixedLogController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'RepoUrl' => 'required|string',
            'BranchName' => 'required|string',
            'PipelineName' => 'required|string',
        ]);

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

        // Update pipeline state
        $pipeline->update([
            'PreviousHasError' => $pipeline->HasError,
            'HasError' => 0,
            'UpdatedAt' => now()->timestamp,
        ]);

        return ApiResponse::success([
            'Fixed' => true,
        ]);
    }
}
