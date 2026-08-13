<?php

namespace App\Http\Controllers\LaneB;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Contracts\LogIngestService;

final class FixedLogController
{
    public function __construct(
        private readonly LogIngestService $ingest
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        // Minimal implementation for now, assuming standard array input
        $data = $request->validate([
            'RepoUrl' => 'required|url',
            'Branch'  => 'required|string',
            'Sha'     => 'required|string'
        ]);

        $result = $this->ingest->markFixed($data);
        return response()->json($result->toEnvelope(), $result->success ? 200 : 400);
    }
}
