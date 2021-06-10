<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{

    public function test_empty_input()
    {
        $response = $this->postJson('/api/auth/login');
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
    }

    public function test_invalid_input()
    {
        $userData = [
            'email' => $this->faker->name,
            'password' => $this->faker->password,
            'device_name' => 'Mac'
        ];

        $response = $this->postJson('/api/auth/login', $userData);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
    }

    public function test_invalid_credentials()
    {
        $userData = [
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'device_name' => 'Mac'
        ];

        $response = $this->postJson('/api/auth/login', $userData);
        $response->assertStatus(401)
            ->assertJsonStructure(['error']);
    }

    public function test_login_with_success()
    {
        $password = $this->faker->password(8);

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($password),
        ];

        $user = User::create($userData);

        $formData = [
            'email' => $user->email,
            'password' => $password,
            'device_name' => 'Mac'
        ];

        $response = $this->postJson('/api/auth/login', $formData);
        $this->assertDatabaseHas('users', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'name', 'email'])
            ->assertJson(['email' => $user->email]);
    }
}
