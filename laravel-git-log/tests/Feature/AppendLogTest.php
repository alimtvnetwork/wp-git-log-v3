<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\GitProfile;
use App\Models\Repo;
use App\Models\RepoVersion;
use Illuminate\Support\Facades\File;

class AppendLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Data seeding (schema is now built via Laravel migrations in testing)
        DB::table('Profile')->updateOrInsert(
            ['UserName' => 'ci_user'],
            [
                'Email' => 'ci@example.com',
                'GeneratedKeyApi' => 'key',
                'Token' => 'token-123',
                'TempToken' => 'temp-123',
                'UserStatusId' => 1,
                'CreatedAt' => time(),
                'UpdatedAt' => time()
            ]
        );
        DB::table('Provider')->updateOrInsert(['ProviderId' => 1], ['Name' => 'GitHub']);
        DB::table('Acceptance')->updateOrInsert(['AcceptanceId' => 1], ['Name' => 'AcceptAllRepos']);
    }

    public function test_append_log_creates_split_db()
    {
        $profile = \App\Models\GitProfile::forceCreate([
            'ProviderId' => 1,
            'OwnerName' => 'test-owner',
            'IsOrganization' => 0,
            'AcceptanceId' => 1, // Accept All
            'IsRestrictInBranch' => 0,
            'CreatedAt' => time(),
            'UpdatedAt' => time(),
            'ProfileUrl' => 'test-url'
        ]);

        $repo = \App\Models\Repo::forceCreate([
            'GitProfileId' => $profile->GitProfileId,
            'RootRepoName' => 'test-repo',
            'RepoUrl' => 'https://github.com/test-owner/repo',
            'CreatedAt' => time(),
        ]);

        $repoVersion = \App\Models\RepoVersion::forceCreate([
            'RepoId' => $repo->RepoId,
            'VersionSuffix' => '',
            'RepoUrl' => 'https://github.com/test-owner/repo',
            'CreatedAt' => time(),
        ]);

        $payload = [
            'RepoUrl' => 'https://github.com/test-owner/repo',
            'TempToken' => 'temp-123',
            'Token' => 'token-123',
            'GitSha256' => '1234567890abcdef1234567890abcdef12345678',
            'BranchName' => 'main',
            'PipelineName' => 'build-and-test',
            'HasError' => true,
            'Logs' => [
                ['LineNumber' => 1, 'LogText' => 'Starting build...', 'Severity' => 1, 'OccurredAt' => time()],
                ['LineNumber' => 2, 'LogText' => 'Error: Build failed!', 'Severity' => 4, 'OccurredAt' => time()]
            ]
        ];

        $response = $this->postJson('/api/git-logs/v2/append-log', $payload);

        if ($response->status() !== 200) {
            dd($response->json());
        }

        $response->assertStatus(200);

        // Verify Pipeline was created
        $this->assertDatabaseHas('Pipeline', [
            'RepoVersionId' => $repoVersion->RepoVersionId,
            'Branch' => 'main',
            'Pipeline' => 'build-and-test',
            'HasError' => 1 // Because severity 4 was included
        ]);

        // Verify ShaRegistry was created
        $this->assertDatabaseHas('ShaRegistry', [
            'Sha' => '1234567890abcdef1234567890abcdef12345678',
        ]);
        
        $registry = DB::table('ShaRegistry')->where('Sha', '1234567890abcdef1234567890abcdef12345678')->first();
        $this->assertNotNull($registry);
        
        // Clean up the split DB file created during test
        $storageRoot = env('WP_LOGS_DIR', storage_path('app/git-logs'));
        $dbPath = $storageRoot . '/' . $registry->DbFilePath;
        
        $this->assertTrue(File::exists($dbPath), "Split DB file should exist at: " . $dbPath);
        
        if (File::exists($dbPath)) {
            File::delete($dbPath);
        }
    }
}
