<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TasksTest extends TestCase
{
    public function test_show_tasks_with_success()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(8)
        ];

        $user = User::create($userData);
        $token = $user->createToken('Mac')->plainTextToken;

        $newTask = [
            'body' => $this->faker->text($maxNbChars = 5),
            'user_id' => $user->id
        ];

        $task = Task::create($newTask);
        $tasks = Auth::user()->tasks()->latest()->get();

        $response = $this->actingAs($user)->postJson('/api/tasks');
        $response->assertStatus(201);
    }

    public function test_show_tasks_unauthorized()
    {
        $response = $this->postJson('/api/tasks');
        $response->assertStatus(401);
    }
}
