<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

}
