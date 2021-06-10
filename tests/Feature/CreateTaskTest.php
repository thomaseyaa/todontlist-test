<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateTaskTest extends TestCase
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

    public function test_create_task_empty_input()
    {
        $user = $this->createUser();
        $token = $user->createToken('Mac')->plainTextToken;

        $response = $this->postJson('/api/tasks');
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
    }

    public function test_create_task_invalid_input()
    {
        $user = $this->createUser();
        $token = $user->createToken('Mac')->plainTextToken;

        $taskForm = [
            'body' => ''
        ];

        $response = $this->postJson('/api/tasks', $taskForm);
        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);;
    }

    public function test_create_task_with_success()
    {
        $user = $this->createUser();
        $token = $user->createToken('Mac')->plainTextToken;

        $taskForm = [
            'body' => $this->faker->text,
            'user_id'=> $user->id
        ];

        $response = $this->actingAs($user)->postJson('/api/tasks/create', $taskForm);
        $response->assertStatus(201)
            ->assertJsonStructure(['token', 'body', 'user_id'])
            ->assertJson(['body' => $user->body]);;
    }
}
