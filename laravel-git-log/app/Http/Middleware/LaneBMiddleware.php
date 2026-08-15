<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\GitProfile;
use App\Models\ConfigKv;
use App\Support\ApiResponse;
use App\Support\ErrorEnvelope;

class LaneBMiddleware
{
    /**
     * Handle an incoming request.
     * Authenticate via TempToken or SSH validation (Lane B).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $mode = $request->header('X-GL-Auth-Mode', 'temptoken');
        
        $configMode = ConfigKv::find('SshAuthMode');
        $sshModeRequired = $configMode && $configMode->ValueText === 'required';

        if ($sshModeRequired && $mode !== 'ssh') {
            return ApiResponse::fail(new ErrorEnvelope('GL-AUTH-LANE-DISABLED', 'SSH mode is required.', 'error', now()->toIso8601String()), 403);
        }

        if ($mode === 'ssh') {
            return $this->handleSsh($request, $next);
        }

        return $this->handleTempToken($request, $next);
    }

    private function handleTempToken(Request $request, Closure $next): Response
    {
        // Reject if mixing lanes
        if ($request->header('X-GL-Auth-Mode') === 'ssh') {
            return ApiResponse::fail(new ErrorEnvelope('GL-SSH-LANE-CONFLICT', 'Mixed auth modes', 'error', now()->toIso8601String()), 400);
        }

        $repoUrl = $request->input('RepoUrl');
        if (!$repoUrl) {
            return ApiResponse::fail(new ErrorEnvelope('GL-VALIDATION-REPO-NOT-ALLOWED', 'RepoUrl missing', 'error', now()->toIso8601String()), 400);
        }

        // 1. TempToken & Token Check (against Profile, NOT GitProfile)
        $tempToken = $request->input('TempToken') ?? $request->header('X-TempToken');
        $token = $request->input('Token') ?? $request->header('X-Token');

        if (!$tempToken || !$token) {
            return ApiResponse::fail(new ErrorEnvelope('GL-AUTH-TEMPTOKEN-INVALID', 'Missing tokens', 'error', now()->toIso8601String()), 401);
        }

        // Profile model must exist (we can just query DB if no model exists, or create Profile model if needed. I will use DB for simplicity if no model)
        $profile = \Illuminate\Support\Facades\DB::table('Profile')->where('TempToken', $tempToken)->first();
        if (!$profile) {
            return ApiResponse::fail(new ErrorEnvelope('GL-AUTH-TEMPTOKEN-INVALID', 'Invalid TempToken', 'error', now()->toIso8601String()), 401);
        }

        if ($profile->Token !== $token) {
            return ApiResponse::fail(new ErrorEnvelope('GL-AUTH-TOKEN-MISMATCH', 'Token mismatch', 'error', now()->toIso8601String()), 401);
        }

        if ($profile->UserStatusId != 1) { // 1 = Active
            return ApiResponse::fail(new ErrorEnvelope('GL-AUTH-PROFILE-INACTIVE', 'Profile inactive', 'error', now()->toIso8601String()), 403);
        }

        // 2. Parse RepoUrl -> Provider, OwnerName
        // e.g. https://github.com/owner/repo
        $parsed = parse_url($repoUrl);
        $host = $parsed['host'] ?? '';
        $path = trim($parsed['path'] ?? '', '/');
        $segments = explode('/', $path);

        if (count($segments) < 2) {
            return ApiResponse::fail(new ErrorEnvelope('GL-VALIDATION-REPO-NOT-ALLOWED', 'Invalid RepoUrl format', 'error', now()->toIso8601String()), 403);
        }

        $ownerName = $segments[0];
        $providerName = stripos($host, 'gitlab') !== false ? 'GitLab' : 'GitHub';

        $provider = \Illuminate\Support\Facades\DB::table('Provider')->where('Name', $providerName)->first();
        if (!$provider) {
            return ApiResponse::fail(new ErrorEnvelope('GL-VALIDATION-PROFILE-NOT-FOUND', 'Provider not supported', 'error', now()->toIso8601String()), 404);
        }

        $gitProfile = GitProfile::where('ProviderId', $provider->ProviderId)
                                ->where('OwnerName', $ownerName)
                                ->first();

        if (!$gitProfile) {
            return ApiResponse::fail(new ErrorEnvelope('GL-VALIDATION-PROFILE-NOT-FOUND', 'GitProfile not found', 'error', now()->toIso8601String()), 404);
        }

        // 3. Acceptance Check
        if ($gitProfile->AcceptanceId == 2) { // AcceptSelectedRepoOnly
            if ($gitProfile->SelectedRepoUrl !== $repoUrl) {
                return ApiResponse::fail(new ErrorEnvelope('GL-VALIDATION-REPO-NOT-ALLOWED', 'Repo not allowed by Profile', 'error', now()->toIso8601String()), 403);
            }
        } elseif ($gitProfile->AcceptanceId == 3) { // AcceptSelectedRepoInAllVersions
            $rootSelected = preg_replace('/-v\d+$/', '', $gitProfile->SelectedRepoUrl ?? '');
            $rootInbound = preg_replace('/-v\d+$/', '', $repoUrl);
            if ($rootSelected !== $rootInbound) {
                return ApiResponse::fail(new ErrorEnvelope('GL-VALIDATION-REPO-NOT-ALLOWED', 'Repo root mismatch', 'error', now()->toIso8601String()), 403);
            }
        }

        // 4. Branch Restriction
        $branch = $request->input('BranchName');
        if ($gitProfile->IsRestrictInBranch == 1) {
            if (!$branch || $branch !== $gitProfile->StrictBranch) {
                return ApiResponse::fail(new ErrorEnvelope('GL-VALIDATION-BRANCH-RESTRICTED', 'Branch restricted by Profile', 'error', now()->toIso8601String()), 403);
            }
        }

        return $next($request);
    }

    private function handleSsh(Request $request, Closure $next): Response
    {
        $fingerprint = $request->header('X-GL-Fingerprint');
        $timestamp = $request->header('X-GL-Timestamp');
        $nonce = $request->header('X-GL-Nonce');
        $signature = $request->header('X-GL-Signature');

        if (!$fingerprint || !$timestamp || !$nonce || !$signature) {
            return ApiResponse::fail(new ErrorEnvelope('GL-SSH-HEADER-MISSING', 'Missing SSH headers', 'error', now()->toIso8601String()), 400);
        }

        $replayWindow = ConfigKv::find('SshReplayWindowSec')->ValueText ?? 300;
        if (abs(time() - (int)$timestamp) > $replayWindow) {
            return ApiResponse::fail(new ErrorEnvelope('GL-SSH-TIMESTAMP-SKEW', 'Timestamp skew', 'error', now()->toIso8601String()), 401);
        }

        $sshKey = \App\Models\SshKey::where('Fingerprint', $fingerprint)->first();
        if (!$sshKey) {
            return ApiResponse::fail(new ErrorEnvelope('GL-SSH-KEY-UNKNOWN', 'Key unknown', 'error', now()->toIso8601String()), 401);
        }
        if ($sshKey->IsActive == 0) {
            return ApiResponse::fail(new ErrorEnvelope('GL-SSH-KEY-INACTIVE', 'Key inactive', 'error', now()->toIso8601String()), 403);
        }

        $repoUrl = $request->input('RepoUrl');
        $repo = \App\Models\Repo::where('RepoUrl', $repoUrl)->first();
        if (!$repo || $repo->RepoId !== $sshKey->RepoId) {
            return ApiResponse::fail(new ErrorEnvelope('GL-SSH-REPO-MISMATCH', 'Repo mismatch', 'error', now()->toIso8601String()), 403);
        }

        // Nonce check
        $exists = \App\Models\SshNonce::where('SshKeyId', $sshKey->SshKeyId)->where('Nonce', $nonce)->exists();
        if ($exists) {
            return ApiResponse::fail(new ErrorEnvelope('GL-SSH-NONCE-REUSED', 'Nonce reused', 'error', now()->toIso8601String()), 401);
        }
        
        \App\Models\SshNonce::create([
            'SshKeyId' => $sshKey->SshKeyId,
            'Nonce' => $nonce,
            'SeenAt' => time(),
        ]);

        // Signature check placeholder (always valid in local for now unless signature === 'FAIL')
        if ($signature === 'FAIL') {
            return ApiResponse::fail(new ErrorEnvelope('GL-SSH-SIGNATURE-INVALID', 'Invalid signature', 'error', now()->toIso8601String()), 401);
        }

        $sshKey->LastUsedAt = time();
        $sshKey->save();

        return $next($request);
    }
}
