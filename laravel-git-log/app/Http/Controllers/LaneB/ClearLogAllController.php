<?php

namespace App\Http\Controllers\LaneB;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Contracts\LogIngestService;

final class ClearLogAllController
{
    public function __construct(
        private readonly LogIngestService $ingest
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'RepoUrl' => 'required|url',
            'Branch'  => 'required|string',
        ]);

        $result = $this->ingest->clearAll($data);
        return response()->json($result->toEnvelope(), $result->success ? 200 : 400);
    }
}
