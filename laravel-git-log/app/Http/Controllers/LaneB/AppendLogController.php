<?php

namespace App\Http\Controllers\LaneB;

use App\Http\Requests\LaneB\AppendLogRequest;
use App\Services\Contracts\LogIngestService;
use App\Services\Contracts\ShaRegistryRepository;
use App\Services\Contracts\SplitDbWriter;
use Illuminate\Http\JsonResponse;

final class AppendLogController
{
    public function __construct(
        private readonly LogIngestService $ingest,
        private readonly ShaRegistryRepository $shaRegistry,
        private readonly SplitDbWriter $splitDb,
    ) {}

    public function __invoke(AppendLogRequest $request): JsonResponse
    {
        $result = $this->ingest->append($request->validated());
        return response()->json($result->toEnvelope(), $result->success ? 200 : 400);
    }
}
