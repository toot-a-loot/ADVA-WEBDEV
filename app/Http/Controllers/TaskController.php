<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    private $supabaseUrl;
    private $supabaseKey;

    // Show the form for editing the specified task
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    // Update the specified task in storage
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    // Remove the specified task from storage
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted!');
    }

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseKey = env('SUPABASE_KEY');
    }

    public function save(Request $request)
    {
        try {
            if (!Auth::check()) {
                throw new \Exception('User not authenticated');
            }

            // Generate a UUID for the user if not exists
            $userId = Str::uuid();
            $tasks = $request->input('tasks');

            foreach ($tasks as $task) {
                $taskData = [
                    'id' => Str::uuid(), // Generate UUID for each task
                    'user_id' => $userId,
                    'title' => $task['title'],
                    'status' => $task['status'],
                    'content' => json_encode($task['items']),
                    'position' => json_encode([
                        'x' => str_replace('px', '', $task['position']['left']),
                        'y' => str_replace('px', '', $task['position']['top']),
                        'width' => str_replace('px', '', $task['position']['width']),
                        'height' => str_replace('px', '', $task['position']['height'])
                    ])
                ];

                $response = Http::withHeaders([
                    'apikey' => $this->supabaseKey,
                    'Authorization' => 'Bearer ' . $this->supabaseKey,
                    'Content-Type' => 'application/json',
                    'Prefer' => 'return=minimal'
                ])->post($this->supabaseUrl . '/rest/v1/tasks', $taskData);

                if (!$response->successful()) {
                    \Log::error('Supabase error:', ['response' => $response->body()]);
                    throw new \Exception('Failed to save to database: ' . $response->body());
                }
            }

            return response()->json(['message' => 'Tasks saved successfully']);
        } catch (\Exception $e) {
            \Log::error('Error saving tasks:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserTasks()
    {
        try {
            if (!auth()->check()) {
                throw new \Exception('User not authenticated');
            }

            $userId = auth()->id();

            $response = Http::withHeaders([
                'apikey' => $this->supabaseKey,
                'Authorization' => 'Bearer ' . $this->supabaseKey
            ])->get($this->supabaseUrl . '/rest/v1/tasks', [
                'select' => '*',
                'user_id' => 'eq.' . $userId
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch tasks');
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
