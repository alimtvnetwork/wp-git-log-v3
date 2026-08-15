<?php

namespace App\Http\Controllers\LaneA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GitProfile;
use App\Support\ApiResponse;
use Illuminate\Support\Str;

class GitProfileController extends Controller
{
    public function index(Request $request)
    {
        $profiles = GitProfile::orderBy('GitProfileId', 'desc')->get();
        return ApiResponse::success($profiles->toArray());
    }

    public function show($id)
    {
        $profile = GitProfile::find($id);
        if (!$profile) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-PROFILE-404', 'GitProfile not found', 'error', now()->toIso8601String()), 404);
        }
        return ApiResponse::success($profile->toArray());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ProviderId' => 'required|integer',
            'OwnerName' => 'required|string',
            'IsOrganization' => 'required|boolean',
            'AcceptanceId' => 'required|integer',
            'SelectedRepoUrl' => 'nullable|string',
            'IsRestrictInBranch' => 'required|boolean',
            'StrictBranch' => 'nullable|string',
        ]);

        $profile = new GitProfile();
        $profile->ProviderId = $validated['ProviderId'];
        $profile->OwnerName = $validated['OwnerName'];
        $profile->IsOrganization = $validated['IsOrganization'] ? 1 : 0;
        $profile->AcceptanceId = $validated['AcceptanceId'];
        $profile->SelectedRepoUrl = $validated['SelectedRepoUrl'] ?? null;
        $profile->IsRestrictInBranch = $validated['IsRestrictInBranch'] ? 1 : 0;
        $profile->StrictBranch = $validated['StrictBranch'] ?? null;
        
        // Auto-generate standard fields
        $profile->ProfileUrl = "https://example.com/" . $validated['OwnerName']; // Simplification
        $profile->CreatedAt = time();
        $profile->UpdatedAt = time();
        $profile->save();

        return ApiResponse::success($profile->toArray());
    }

    public function update(Request $request, $id)
    {
        $profile = GitProfile::find($id);
        if (!$profile) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-PROFILE-404', 'GitProfile not found', 'error', now()->toIso8601String()), 404);
        }

        $validated = $request->validate([
            'ProviderId' => 'required|integer',
            'OwnerName' => 'required|string',
            'IsOrganization' => 'required|boolean',
            'AcceptanceId' => 'required|integer',
            'SelectedRepoUrl' => 'nullable|string',
            'IsRestrictInBranch' => 'required|boolean',
            'StrictBranch' => 'nullable|string',
        ]);

        $profile->ProviderId = $validated['ProviderId'];
        $profile->OwnerName = $validated['OwnerName'];
        $profile->IsOrganization = $validated['IsOrganization'] ? 1 : 0;
        $profile->AcceptanceId = $validated['AcceptanceId'];
        $profile->SelectedRepoUrl = $validated['SelectedRepoUrl'] ?? null;
        $profile->IsRestrictInBranch = $validated['IsRestrictInBranch'] ? 1 : 0;
        $profile->StrictBranch = $validated['StrictBranch'] ?? null;
        $profile->UpdatedAt = time();
        $profile->save();

        return ApiResponse::success($profile->toArray());
    }

    public function destroy($id)
    {
        $profile = GitProfile::find($id);
        if (!$profile) {
            return ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-PROFILE-404', 'GitProfile not found', 'error', now()->toIso8601String()), 404);
        }

        $profile->delete();
        return ApiResponse::success(['deleted' => true]);
    }
}
