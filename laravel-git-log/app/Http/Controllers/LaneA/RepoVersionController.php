<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RepoVersion;
use App\Support\ApiResponse;

class RepoVersionController extends Controller
{
    public function index(Request $request)
    {
        $items = RepoVersion::orderBy('RepoVersionId', 'desc')->get();
        return ApiResponse::success($items->toArray());
    }

    public function show($id)
    {
        $item = RepoVersion::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-RepoVersion-404', 'RepoVersion not found', 'error', now()->toIso8601String()), 404);
        }
        return ApiResponse::success($item->toArray());
    }

    public function destroy($id)
    {
        $item = RepoVersion::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-RepoVersion-404', 'RepoVersion not found', 'error', now()->toIso8601String()), 404);
        }

        $item->delete();
        return ApiResponse::success(['deleted' => true]);
    }
}