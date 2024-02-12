<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Enum\TokenAbilityEnum;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_response_validation_error_status(): void
    {
        $response = $this->postJson('api/v1/auth/login');
        $response->assertStatus(422);
    }

    public function test_refresh_token_response_unauthenticated_status(): void
    {
        $response = $this->getJson('api/v1/auth/refresh-token');
        $response->assertStatus(401);
    }

    public function test_logout_response_unauthenticated_status(): void
    {
        $response = $this->getJson('api/v1/auth/logout');
        $response->assertStatus(401);
    }

    public function test_users_can_authenticate_at_login(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('api/v1/auth/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user);
    }

    public function test_users_cannot_authenticate_with_wrong_credentials_to_the_login(): void
    {

        $user = User::factory()->create();

        $response = $this->postJson('api/v1/auth/login', [
            'username' => $user->username,
            'password' => 'test123',
        ]);

        $response->assertStatus(401);

    }

    public function test_login_password_validation_errors(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('api/v1/auth/login', [
            'username' => $user->username,
            'password' => 'pass',
        ]);

        $response->assertJsonValidationErrorFor('password');
    }

    public function test_login_username_validation_errors(): void
    {

        $response = $this->postJson('api/v1/auth/login', [
            'password' => 'pass',
        ]);

        $response->assertJsonValidationErrorFor('username');
    }

    public function test_users_get_user_data_after_login(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('api/v1/auth/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment($user->toArray());
        $response->assertJsonPath('user.username', $user->username);
    }

    public function test_users_response_tokens_structure_after_login(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('api/v1/auth/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertJsonStructure([
            'user' => [
                'username',
            ],
            'token',
            'refresh_token'
        ]);

    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, [TokenAbilityEnum::ACCESS_API->value]);
        $response = $this->getJson('api/v1/auth/logout');

        $response->assertStatus(200);
    }

    public function test_users_can_get_refresh_token(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, [TokenAbilityEnum::ISSUE_ACCESS_TOKEN->value]);
        $response = $this->getJson('api/v1/auth/refresh-token');

        $response->assertStatus(200);
    }

    public function test_users_with_access_api_token_cannot_get_refresh_token(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, [TokenAbilityEnum::ACCESS_API->value]);
        $response = $this->getJson('api/v1/auth/refresh-token');

        $response->assertStatus(401);
    }

    public function test_unauthenticated_users_cannot_get_beers(): void
    {
        $response = $this->getJson('api/v1/proxy/beers');
        $response->assertStatus(401);
    }

    public function test_users_can_get_beers(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, [TokenAbilityEnum::ACCESS_API->value]);
        $response = $this->getJson('api/v1/beers');

        $response->assertStatus(200);
    }

}
