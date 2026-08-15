<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\DB;

class PipelineController extends Controller
{
    public function index(Request $request)
    {
        // Fetch pipelines, joining with RepoVersion and Repo for UI display
        $pipelines = DB::table('Pipeline')
            ->join('RepoVersion', 'Pipeline.RepoVersionId', '=', 'RepoVersion.RepoVersionId')
            ->join('Repo', 'RepoVersion.RepoId', '=', 'Repo.RepoId')
            ->select(
                'Pipeline.PipelineId',
                'Pipeline.Branch',
                'Pipeline.Pipeline as PipelineName',
                'Pipeline.HasError',
                'Pipeline.CreatedAt',
                'Repo.RootRepoName',
                'RepoVersion.VersionSuffix'
            )
            ->orderBy('Pipeline.PipelineId', 'desc')
            ->limit(100)
            ->get();

        return ApiResponse::success($pipelines);
    }
}
