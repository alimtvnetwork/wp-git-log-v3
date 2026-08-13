<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Requests\LaneA\GetLogsRequest;
use App\Services\Contracts\ShaRegistryRepository;
use App\Services\Contracts\SplitDbWriter;
use Illuminate\Http\JsonResponse;

final class GetLogsController
{
    public function __construct(
        private readonly ShaRegistryRepository $shaRegistry,
        private readonly SplitDbWriter $splitDb
    ) {}

    public function __invoke(GetLogsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $limit = $validated['Limit'] ?? 50;

        $shaReg = $this->shaRegistry->findByCompositeKey(
            $validated['RepoUrl'],
            $validated['Branch'],
            $validated['Sha'] ?? 'unknown'
        );

        if (!$shaReg) {
            return response()->json([
                'ErrorCode' => 'GL-NOT-FOUND',
                'TraceId'   => (string) \Illuminate\Support\Str::uuid(),
                'Message'   => 'Logs not found for the given parameters',
                'Details'   => []
            ], 404);
        }

        $pdo = $this->splitDb->acquire($shaReg->ShaId);
        $stmt = $pdo->prepare('SELECT Severity, Message, CreatedAt FROM LogLine ORDER BY LineId ASC LIMIT ?');
        $stmt->execute([$limit]);
        $logs = $stmt->fetchAll();

        return response()->json([
            'ErrorCode' => 'GL-SUCCESS',
            'TraceId'   => (string) \Illuminate\Support\Str::uuid(),
            'Message'   => 'Logs retrieved',
            'Details'   => ['Logs' => $logs]
        ], 200);
    }
}
