<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditTrail;
use App\Support\ApiResponse;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $items = AuditTrail::orderBy('AuditTrailId', 'desc')->get();

        return ApiResponse::success($items->toArray());
    }

    public function show($id)
    {
        $item = AuditTrail::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-AuditTrail-404', 'AuditTrail not found', 'error', now()->toIso8601String()), 404);
        }
        return ApiResponse::success($item->toArray());
    }

    public function destroy($id)
    {
        $item = AuditTrail::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-AuditTrail-404', 'AuditTrail not found', 'error', now()->toIso8601String()), 404);
        }

        $item->delete();

        return ApiResponse::success(['deleted' => true]);
    }
}