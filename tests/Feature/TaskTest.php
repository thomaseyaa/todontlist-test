<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function createUser()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password(8)),
        ];

        return $user = User::create($userData);
    }

    public function test_show_task_success()
    {
        $userData = [
            'id' => 1,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password(8)),
        ];

        $user = User::create($userData);
        $token = $user->createToken('Mac')->plainTextToken;

        $taskData = [
            'id' => 1,
            'body' => $this->faker->text,
            'user_id' => 1,
        ];

        $response = $this->actingAs($user)->getJson('/api/tasks/1');
        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'id', 'body', 'user_id'])
            ->assertJson(['body' => $taskData['body']]);
    }

    public function test_show_task_unauthorized()
    {
        $response = $this->getJson('/api/tasks/1');
        $response->assertStatus(401);
    }

    public function test_show_task_not_found()
    {
        $user = $this->createUser();
        $token = $user->createToken('Mac')->plainTextToken;

        $response = $this->actingAs($user)->getJson('/api/tasks/100');
        $response->assertStatus(404);
    }

    public function test_show_task_forbidden()
    {
        $user = User::create();
        $token = $user->createToken('Mac')->plainTextToken;

        $response = $this->actingAs($user)->getJson('/api/tasks/1');
        $response->assertStatus(403);
    }
}
