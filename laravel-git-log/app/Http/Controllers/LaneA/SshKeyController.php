<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SshKey;
use App\Support\ApiResponse;

class SshKeyController extends Controller
{
    public function index(Request $request)
    {
        $items = SshKey::orderBy('SshKeyId', 'desc')->get();
        return ApiResponse::success($items->toArray());
    }

    public function show($id)
    {
        $item = SshKey::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-SshKey-404', 'SshKey not found', 'error', now()->toIso8601String()), 404);
        }
        return ApiResponse::success($item->toArray());
    }

    public function destroy($id)
    {
        $item = SshKey::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-SshKey-404', 'SshKey not found', 'error', now()->toIso8601String()), 404);
        }

        $item->delete();
        return ApiResponse::success(['deleted' => true]);
    }
}