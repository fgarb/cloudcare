<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use \App\Enum\TokenAbilityEnum;

class BeerTest extends TestCase
{
    use RefreshDatabase;

    public function test_beers_output_is_valid_json(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [TokenAbilityEnum::ACCESS_API->value]);
        $response = $this->getJson('api/v1/proxy/beers');

        $response->assertStatus(200);
        $response->assertJsonIsObject();
        $response->assertJsonIsArray('data');
    }

    public function test_beers_returns_valid_json_error_when_type_mismatch(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [TokenAbilityEnum::ACCESS_API->value]);
        $response = $this->getJson('api/v1/proxy/beers?page=1x');

        $response->assertStatus(400);
        $response->assertJsonIsObject();
        $response->assertJsonIsArray('data');
    }


    public function test_beers_returns_valid_json_error_when_parameter_has_wrong_value(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [TokenAbilityEnum::ACCESS_API->value]);
        $response = $this->getJson('api/v1/proxy/beers?per_page=1000');

        $response->assertStatus(400);
        $response->assertJsonIsObject();
        $response->assertJsonIsArray('data');
    }

    public function test_beers_returns_correct_beers_number(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [TokenAbilityEnum::ACCESS_API->value]);
        $response = $this->getJson('api/v1/proxy/beers?page=1&page_limit=10&per_page=50');

        $response->assertStatus(200);
        $response->assertJsonCount(50, 'data');
    }

}
