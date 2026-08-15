<?php

namespace App\Http\Controllers\LaneB;

use App\Http\Controllers\Controller;
use App\Models\ShaRegistry;
use App\Models\RepoVersion;
use App\Support\ApiResponse;
use App\Support\ErrorEnvelope;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

final class ClearLogAllController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'RepoUrl' => 'required|string',
        ]);

        $repoVersion = RepoVersion::where('RepoUrl', $validated['RepoUrl'])->first();
        if (!$repoVersion) {
            return ApiResponse::fail(new ErrorEnvelope('GL-REPO-404', 'RepoVersion not found', 'warn', now()->toIso8601String()), 404);
        }

        // Delete the entire directory for this RepoVersion
        $dirPath = storage_path('app/git-logs/logs/' . $repoVersion->RepoVersionId);
        if (File::exists($dirPath)) {
            File::deleteDirectory($dirPath);
        }

        // Delete all registries for pipelines in this repo version
        $pipelineIds = $repoVersion->pipelines()->pluck('PipelineId');
        ShaRegistry::whereIn('PipelineId', $pipelineIds)->delete();

        return ApiResponse::success([
            'ClearedAll' => true,
        ]);
    }
}
