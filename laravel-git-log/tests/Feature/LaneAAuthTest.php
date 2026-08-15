<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Profile;

class LaneAAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_lane_a_rejects_unauthenticated_requests(): void
    {
        $response = $this->getJson('/api/git-logs/v2/git-profiles');

        $response->assertStatus(401)
                 ->assertJsonPath('Status.IsSuccess', false)
                 ->assertJsonPath('Results.0.code', 'GL-AUTH-INVALID-TOKEN');
    }

    public function test_lane_a_accepts_sanctum_authenticated_requests(): void
    {
        $profile = Profile::create([
            'UserName' => 'admin_user',
            'Email' => 'admin@example.com',
            'GeneratedKeyApi' => 'test-key',
            'Token' => 'test-token',
            'TempToken' => 'temp-token',
            'UserStatusId' => 1,
            'CreatedAt' => now()->timestamp,
            'UpdatedAt' => now()->timestamp,
        ]);

        $token = $profile->createToken('test-token')->plainTextToken;

        // Since the GitProfileController index method may not be fully implemented,
        // we just assert it doesn't return 401. It should return 200.
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/git-logs/v2/git-profiles');

        $response->assertStatus(200);
    }
}
