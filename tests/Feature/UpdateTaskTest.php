<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateTaskTest extends TestCase
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

    public function test_edit_task_with_success()
    {
        $user = $this->createUser();
        $token = $user->createToken('Mac')->plainTextToken;

        $taskForm = [
            'id' => 1,
            'body' => $this->faker->text,
            'user_id'=> $user->id
        ];

        $response = $this->actingAs($user)->postJson('/api/tasks/create', $taskForm);
        $editForm = [
            'title' => $this->faker->title,
            'body' => $this->faker->text,
        ];
        $response = $this->actingAs($user)->postJson('/api/tasks/update/1', $editForm);

        $response->assertStatus(200);
    }

    public function test_edit_task_unauthorized()
    {
        $editForm = [
            'body' => $this->faker->text,
        ];
        $response = $this->postJson('/api/tasks/update/1', $editForm);

        $response->assertStatus(401);
    }

    public function test_edit_task_forbidden()
    {
        $user = $this->createUser();
        $token = $user->createToken('Mac')->plainTextToken;

        $taskForm = [
            'id' => 1245,
            'body' => $this->faker->text,
            'user_id'=> 100
        ];

        $response = $this->actingAs($user)->postJson('/api/tasks/create', $taskForm);
        $editForm = [
            'title' => $this->faker->title,
            'body' => $this->faker->text,
        ];

        $response = $this->actingAs($user)->postJson('/api/tasks/update/1245', $editForm);
        $response->assertStatus(403);
    }

    public function test_edit_task_not_found()
    {
        $user = $this->createUser();
        $token = $user->createToken('Mac')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/api/tasks/update/100');
        $response->assertStatus(404);
    }
}
