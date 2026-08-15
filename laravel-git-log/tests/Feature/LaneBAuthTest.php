<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\GitProfile;
use App\Models\Provider;
use App\Models\Acceptance;

class LaneBAuthTest extends TestCase
{
    use RefreshDatabase; // Automatically resets the :memory: database

    protected function setUp(): void
    {
        parent::setUp();
        
        // Data seeding (schema is now built via Laravel migrations in testing)
        \DB::table('Profile')->updateOrInsert(
            ['UserName' => 'ci_user'],
            [
                'Email' => 'ci@example.com',
                'GeneratedKeyApi' => 'key',
                'Token' => 'valid-token',
                'TempToken' => 'valid-temp',
                'UserStatusId' => 1,
                'CreatedAt' => time(),
                'UpdatedAt' => time()
            ]
        );
        \DB::table('Provider')->updateOrInsert(['ProviderId' => 1], ['Name' => 'GitHub']);
        \DB::table('Acceptance')->updateOrInsert(['AcceptanceId' => 2], ['Name' => 'AcceptSelectedRepoOnly']);
    }

    public function test_rejects_missing_tokens()
    {
        $response = $this->postJson('/api/git-logs/v2/append-log', [
            'RepoUrl' => 'https://github.com/test/repo',
        ]);

        $response->assertStatus(401)
                 ->assertJsonPath('Results.0.code', 'GL-AUTH-TEMPTOKEN-INVALID');
    }

    public function test_rejects_mismatched_tokens()
    {
        // Seed a GitProfile
        \DB::table('GitProfile')->insert([
            'ProviderId' => 1,
            'OwnerName' => 'test',
            'IsOrganization' => 0,
            'AcceptanceId' => 2,
            'CreatedAt' => time(),
            'UpdatedAt' => time(),
            'ProfileUrl' => 'test-url'
        ]);

        $response = $this->postJson('/api/git-logs/v2/append-log', [
            'RepoUrl' => 'https://github.com/test/repo',
            'TempToken' => 'valid-temp',
            'Token' => 'wrong-token',
        ]);

        $response->assertStatus(401)
                 ->assertJsonPath('Results.0.code', 'GL-AUTH-TOKEN-MISMATCH');
    }

    public function test_enforces_branch_restriction()
    {
        \DB::table('GitProfile')->insert([
            'ProviderId' => 1,
            'OwnerName' => 'test',
            'IsOrganization' => 0,
            'AcceptanceId' => 2,
            'IsRestrictInBranch' => 1,
            'StrictBranch' => 'main',
            'SelectedRepoUrl' => 'https://github.com/test/repo',
            'CreatedAt' => time(),
            'UpdatedAt' => time(),
            'ProfileUrl' => 'test-url2'
        ]);

        $response = $this->postJson('/api/git-logs/v2/append-log', [
            'RepoUrl' => 'https://github.com/test/repo',
            'TempToken' => 'valid-temp',
            'Token' => 'valid-token',
            'BranchName' => 'develop', // Incorrect branch
        ]);

        $response->assertStatus(403)
                 ->assertJsonPath('Results.0.code', 'GL-VALIDATION-BRANCH-RESTRICTED');
    }

    public function test_ssh_rejects_missing_headers()
    {
        $response = $this->postJson('/api/git-logs/v2/append-log', [], [
            'X-GL-Auth-Mode' => 'ssh'
        ]);

        $response->assertStatus(400)
                 ->assertJsonPath('Results.0.code', 'GL-SSH-HEADER-MISSING');
    }

    public function test_ssh_rejects_expired_timestamp()
    {
        $response = $this->postJson('/api/git-logs/v2/append-log', [], [
            'X-GL-Auth-Mode' => 'ssh',
            'X-GL-Fingerprint' => 'SHA256:dummy',
            'X-GL-Timestamp' => (string)(time() - 400), // older than 300s window
            'X-GL-Nonce' => 'nonce123',
            'X-GL-Signature' => 'sig'
        ]);

        $response->assertStatus(401)
                 ->assertJsonPath('Results.0.code', 'GL-SSH-TIMESTAMP-SKEW');
    }

    public function test_ssh_accepts_valid_signature_and_rejects_reused_nonce()
    {
        // Ensure GitProfile exists for Acceptance check
        $gitProfileId = \DB::table('GitProfile')->insertGetId([
            'ProviderId' => 1,
            'OwnerName' => 'test',
            'IsOrganization' => 0,
            'AcceptanceId' => 1, // AcceptAllRepos
            'CreatedAt' => time(),
            'UpdatedAt' => time(),
            'ProfileUrl' => 'test-url3'
        ]);

        // 1. Setup Repo and SshKey
        $repoId = \DB::table('Repo')->insertGetId([
            'GitProfileId' => $gitProfileId,
            'RootRepoName' => 'test-repo',
            'RepoUrl' => 'https://github.com/test/repo',
            'CreatedAt' => time()
        ]);

        $sshKeyId = \DB::table('SshKey')->insertGetId([
            'Fingerprint' => 'SHA256:valid-fingerprint',
            'RepoId' => $repoId,
            'KeyType' => 'ssh-ed25519',
            'PublicKey' => 'ssh-ed25519 AAA...',
            'OwnedByProfileId' => 1,
            'IsActive' => 1,
            'CreatedAt' => time()
        ]);

        $headers = [
            'X-GL-Auth-Mode' => 'ssh',
            'X-GL-Fingerprint' => 'SHA256:valid-fingerprint',
            'X-GL-Timestamp' => (string)time(),
            'X-GL-Nonce' => 'unique-nonce-123',
            'X-GL-Signature' => 'valid-sig'
        ];
        
        $payload = [
            'RepoUrl' => 'https://github.com/test/repo',
            'GitSha256' => 'dummy',
            'BranchName' => 'main',
            'PipelineName' => 'ci',
            'Logs' => []
        ];

        // First attempt (Valid)
        // Note: append-log might fail with 404 RepoVersion not found, but it should pass the LaneBMiddleware!
        $response1 = $this->postJson('/api/git-logs/v2/append-log', $payload, $headers);
        $response1->assertJsonMissing(['code' => 'GL-SSH-NONCE-REUSED']);
        
        // Ensure we actually passed the SSH middleware (i.e. not getting 401/403)
        // If it fails at AppendLogController (e.g. GL-REPO-404), it means middleware passed!
        $this->assertNotEquals(401, $response1->status());
        $this->assertNotEquals(403, $response1->status());

        // Second attempt (Reused Nonce)
        $response2 = $this->postJson('/api/git-logs/v2/append-log', $payload, $headers);
        $response2->assertStatus(401)
                  ->assertJsonPath('Results.0.code', 'GL-SSH-NONCE-REUSED');
    }
}
