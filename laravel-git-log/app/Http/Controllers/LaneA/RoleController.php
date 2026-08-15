<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Support\ApiResponse;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $items = Role::orderBy('RoleId', 'desc')->get();
        return ApiResponse::success($items->toArray());
    }

    public function show($id)
    {
        $item = Role::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-Role-404', 'Role not found', 'error', now()->toIso8601String()), 404);
        }
        return ApiResponse::success($item->toArray());
    }

    public function destroy($id)
    {
        $item = Role::find($id);
        if (!$item) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-Role-404', 'Role not found', 'error', now()->toIso8601String()), 404);
        }

        $item->delete();
        return ApiResponse::success(['deleted' => true]);
    }
}