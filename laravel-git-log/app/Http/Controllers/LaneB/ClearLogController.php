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

final class ClearLogController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'RepoUrl' => 'required|string',
            'GitSha256' => 'required|string',
        ]);

        $repoVersion = RepoVersion::where('RepoUrl', $validated['RepoUrl'])->first();
        if (!$repoVersion) {
            return ApiResponse::fail(new ErrorEnvelope('GL-REPO-404', 'RepoVersion not found', 'warn', now()->toIso8601String()), 404);
        }

        $shaReg = ShaRegistry::where('Sha', $validated['GitSha256'])->first();
        if (!$shaReg) {
            return ApiResponse::fail(new ErrorEnvelope('GL-SHA-DB-NOT-FOUND', 'Logs not found for this SHA', 'warn', now()->toIso8601String()), 404);
        }

        // Delete the split database file
        $dbPath = storage_path('app/git-logs/' . $shaReg->DbFilePath);
        if (File::exists($dbPath)) {
            File::delete($dbPath);
        }

        // Delete registry entry
        $shaReg->delete();

        return ApiResponse::success([
            'Cleared' => true,
            'Sha' => $validated['GitSha256']
        ]);
    }
}
