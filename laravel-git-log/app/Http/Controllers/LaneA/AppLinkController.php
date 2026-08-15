<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppLink;
use App\Support\ApiResponse;

class AppLinkController extends Controller
{
    public function index(Request $request)
    {
        $items = AppLink::orderBy('AppLinkId', 'desc')->get();
        return ApiResponse::success($items->toArray());
    }

    public function show($id)
    {
        $item = AppLink::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-AppLink-404', 'AppLink not found', 'error', now()->toIso8601String()), 404);
        }
        return ApiResponse::success($item->toArray());
    }

    public function destroy($id)
    {
        $item = AppLink::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-AppLink-404', 'AppLink not found', 'error', now()->toIso8601String()), 404);
        }

        $item->delete();
        return ApiResponse::success(['deleted' => true]);
    }
}