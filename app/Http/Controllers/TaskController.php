<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $task = Task::create([
            'body' => $request->body,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($task, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => "Not Found"], 404);
        }
        if ($task->user_id != $request->user()->id) {
            return response()->json(["message" => "Forbidden"], 403);
        }

        $task->update([
            'body' => $request->body,
        ]);

        $updatedTask = Task::find($id);

        return response()->json($updatedTask, 200);
    }

    public function getTasks(Request $request)
    {
        $tasks = Task::where('user_id', $request->user()->id)->orderBy('id', 'DESC')->get();

        return response()->json($tasks, 200);
    }

    public function getTask(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => "Not Found"], 404);
        }
        if ($task->user_id != $request->user()->id) {
            return response()->json(["message" => "Forbidden"], 403);
        }

        return response()->json($task, 200);
    }

    public function delete(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => "Not Found"], 404);
        }
        if ($task->user_id != $request->user()->id) {
            return response()->json(["message" => "Forbidden"], 403);
        }

        $task->delete();

        return response()->json($task, 200);
    }
}
