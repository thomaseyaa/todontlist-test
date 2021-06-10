<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function test_logout_with_success()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(8)
        ];

        $user = User::create($userData);
        $this->actingAs($user);

        $response = $this->postJson('/api/auth/logout');
        $response->assertStatus(204);
    }

    public function test_logout_unauthenticated()
    {
        $response = $this->postJson('/api/auth/logout');
        $response->assertStatus(401);
    }
}
