<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GitProfile;
use App\Models\Repo;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $stats = [
            'totalProfiles' => GitProfile::count(),
            'totalRepos' => Repo::count(),
            // Mock dynamic pipeline runs since Pipeline table isn't fully scaffolded in our quick models yet
            'totalPipelines' => DB::table('Pipeline')->count() ?? 0,
            'recentErrors' => DB::table('History')->where('HasError', 1)->count() ?? 0,
        ];
        
        return ApiResponse::success($stats);
    }
}
