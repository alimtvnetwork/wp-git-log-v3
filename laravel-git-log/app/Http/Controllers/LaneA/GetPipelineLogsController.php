<?php

namespace App\Http\Controllers\LaneA;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GetPipelineLogsController
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(['ErrorCode' => 'GL-SUCCESS', 'TraceId' => '', 'Message' => 'Stub', 'Details' => []]);
    }
}
