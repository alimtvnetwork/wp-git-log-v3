<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use App\Models\ShaRegistry;
use App\Models\RepoVersion;
use App\Support\ApiResponse;
use App\Support\ErrorEnvelope;
use App\Support\ShaDatabaseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class GetErrorLogsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'RepoUrl' => 'required|string',
            'GitSha256' => 'required|string',
            'Limit' => 'integer|min:1|max:1000',
            'Offset' => 'integer|min:0'
        ]);

        $limit = $validated['Limit'] ?? 100;
        $offset = $validated['Offset'] ?? 0;

        $repoVersion = RepoVersion::where('RepoUrl', $validated['RepoUrl'])->first();
        if (!$repoVersion) {
            return ApiResponse::fail(new ErrorEnvelope('GL-REPO-404', 'RepoVersion not found', 'warn', now()->toIso8601String()), 404);
        }

        $shaReg = ShaRegistry::where('Sha', $validated['GitSha256'])->first();
        if (!$shaReg) {
            return ApiResponse::fail(new ErrorEnvelope('GL-SHA-DB-NOT-FOUND', 'Logs not found for this SHA', 'warn', now()->toIso8601String()), 404);
        }

        $db = ShaDatabaseFactory::connect($repoVersion->RepoVersionId, $shaReg->Sha, $repoVersion->RepoUrl, 'main');

        $logs = $db->table('ErrorLogEntry')
            ->orderBy('LineNumber', 'asc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return ApiResponse::success([
            'ErrorLogs' => $logs
        ]);
    }
}
