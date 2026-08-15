<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repo;
use App\Support\ApiResponse;

class RepoController extends Controller
{
    public function index(Request $request)
    {
        $repos = Repo::orderBy('RepoId', 'desc')->get();
        return ApiResponse::success($repos->toArray());
    }

    public function show($id)
    {
        $repo = Repo::find($id);
        if (!$repo) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-REPO-404', 'Repo not found', 'error', now()->toIso8601String()), 404);
        }
        return ApiResponse::success($repo->toArray());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'GitProfileId' => 'required|integer',
            'RootRepoName' => 'required|string',
            'RepoUrl' => 'required|string',
        ]);

        $repo = new Repo();
        $repo->GitProfileId = $validated['GitProfileId'];
        $repo->RootRepoName = $validated['RootRepoName'];
        $repo->RepoUrl = $validated['RepoUrl'];
        $repo->CreatedAt = time();
        $repo->save();

        return ApiResponse::success($repo->toArray());
    }

    public function destroy($id)
    {
        $repo = Repo::find($id);
        if (!$repo) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-REPO-404', 'Repo not found', 'error', now()->toIso8601String()), 404);
        }

        $repo->delete();
        return ApiResponse::success(['deleted' => true]);
    }
}
