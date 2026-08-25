<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\App;
use App\Support\ApiResponse;

class AppController extends Controller
{
    public function index(Request $request)
    {
        $items = App::orderBy('AppId', 'desc')->get();

        return ApiResponse::success($items->toArray());
    }

    public function show($id)
    {
        $item = App::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-App-404', 'App not found', 'error', now()->toIso8601String()), 404);
        }
        return ApiResponse::success($item->toArray());
    }

    public function destroy($id)
    {
        $item = App::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-App-404', 'App not found', 'error', now()->toIso8601String()), 404);
        }

        $item->delete();

        return ApiResponse::success(['deleted' => true]);
    }
}