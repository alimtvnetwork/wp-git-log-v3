<?php

namespace App\Http\Controllers\LaneB;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Contracts\LogIngestService;

final class ClearLogController
{
    public function __construct(
        private readonly LogIngestService $ingest
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'RepoUrl' => 'required|url',
            'Branch'  => 'required|string',
            'Sha'     => 'required|string'
        ]);

        $result = $this->ingest->clearOne($data);
        return response()->json($result->toEnvelope(), $result->success ? 200 : 400);
    }
}
