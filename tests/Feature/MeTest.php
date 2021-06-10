<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MeTest extends TestCase
{
    public function test_show_user_with_success()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(8)
        ];

        $user = User::create($userData);
        $token = $user->createToken('Mac')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/api/auth/me');
        $response->assertStatus(200)
            ->assertJson(['email' => $userData['email'], 'name' => $userData['name']]);
    }

    public function test_show_user_unauthenticated()
    {
        $response = $this->postJson('/api/auth/me');
        $response->assertStatus(401);
    }
}
