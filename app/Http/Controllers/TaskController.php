<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class TaskController extends Controller
{
    private $supabaseUrl;
    private $supabaseKey;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseKey = env('SUPABASE_KEY');
    }

    /**
     * Get tasks for the authenticated user
     */
    public function getUserTasks()
    {
        // Force error display
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        try {
            // Debug: Write to system log
            error_log('getUserTasks called - ' . date('Y-m-d H:i:s'));

            // Debug: Check authentication
            if (!Auth::check()) {
                error_log('User not authenticated');
                Log::error('User not authenticated in getUserTasks');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Debug: Check user ID
            $userId = Auth::id();
            Log::info('User ID:', ['id' => $userId]);

            // Debug: Check Supabase credentials
            if (empty($this->supabaseUrl) || empty($this->supabaseKey)) {
                Log::error('Supabase credentials missing');
                return response()->json(['error' => 'Configuration error'], 500);
            }

            Log::info('Making Supabase request', [
                'url' => $this->supabaseUrl,
                'userId' => $userId
            ]);

            $response = Http::withHeaders([
                'apikey' => $this->supabaseKey,
                'Authorization' => 'Bearer ' . $this->supabaseKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get($this->supabaseUrl . '/rest/v1/tasks', [
                'select' => '*',
                'user_id' => 'eq.' . $userId
            ]);

            Log::info('Supabase response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                Log::error('Failed to fetch tasks from Supabase', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'user_id' => $userId
                ]);
                return response()->json(['error' => 'Failed to fetch tasks: ' . $response->body()], 500);
            }

            $tasks = $response->json();

            // If tasks is null or empty array, return empty array instead of error
            if (empty($tasks)) {
                return response()->json([]);
            }

            return response()->json($tasks);

        } catch (\Exception $e) {
            Log::error('Exception in getUserTasks', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Save a new task
     */
    public function save(Request $request)
    {
        try {
            $userId = Auth::id();
            $tasks = $request->input('tasks');
            $savedTasks = [];

            foreach ($tasks as $task) {
                $taskData = [
                    'id' => $task['id'] ?? Str::uuid(),
                    'user_id' => $userId,
                    'title' => $task['title'],
                    'status' => $task['status'],
                    'content' => json_encode($task['items']),
                    'due_date' => $task['dueDate'] ?? null,
                    'position' => json_encode($task['position'])
                ];

                $response = Http::withHeaders([
                    'apikey' => $this->supabaseKey,
                    'Authorization' => 'Bearer ' . $this->supabaseKey,
                    'Content-Type' => 'application/json',
                    'Prefer' => 'return=minimal'
                ])->post($this->supabaseUrl . '/rest/v1/tasks', $taskData);

                if (!$response->successful()) {
                    Log::error('Failed to save task to Supabase', [
                        'task' => $taskData,
                        'response' => $response->body()
                    ]);
                    throw new \Exception('Failed to save task');
                }

                $savedTasks[] = $taskData;
            }

            return response()->json([
                'message' => 'Tasks saved successfully',
                'tasks' => $savedTasks
            ]);

        } catch (\Exception $e) {
            Log::error('Error in save', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return response()->json(['error' => 'Failed to save tasks'], 500);
        }
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, $id)
    {
        try {
            // First verify the task belongs to the user
            $response = Http::withHeaders([
                'apikey' => $this->supabaseKey,
                'Authorization' => 'Bearer ' . $this->supabaseKey
            ])->get($this->supabaseUrl . '/rest/v1/tasks', [
                'select' => 'user_id',
                'id' => 'eq.' . $id
            ]);

            if (!$response->successful()) {
                throw new \Exception('Task not found');
            }

            $task = $response->json()[0] ?? null;
            if (!$task || $task['user_id'] !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Update the task
            $updateData = $request->only(['title', 'status', 'content', 'position', 'due_date']);
            if (isset($updateData['content'])) {
                $updateData['content'] = json_encode($updateData['content']);
            }
            if (isset($updateData['position'])) {
                $updateData['position'] = json_encode($updateData['position']);
            }

            $response = Http::withHeaders([
                'apikey' => $this->supabaseKey,
                'Authorization' => 'Bearer ' . $this->supabaseKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=minimal'
            ])->patch($this->supabaseUrl . '/rest/v1/tasks?id=eq.' . $id, $updateData);

            if (!$response->successful()) {
                throw new \Exception('Failed to update task');
            }

            return response()->json(['message' => 'Task updated successfully']);

        } catch (\Exception $e) {
            Log::error('Error in update', [
                'error' => $e->getMessage(),
                'task_id' => $id,
                'user_id' => Auth::id()
            ]);
            return response()->json(['error' => 'Failed to update task'], 500);
        }
    }

    /**
     * Delete the specified task
     */
    public function destroy($id)
{
    try {
        $response = Http::withHeaders([
            'apikey' => $this->supabaseKey,
            'Authorization' => 'Bearer ' . $this->supabaseKey
        ])->delete($this->supabaseUrl . '/rest/v1/tasks', [
            'id' => 'eq.' . $id,
            'user_id' => 'eq.' . Auth::id() // Only delete if task belongs to user
        ]);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Failed to delete task');
        }

        return redirect()->back()->with('success', 'Task deleted successfully');
    } catch (\Exception $e) {
        Log::error('Error deleting task:', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Error deleting task');
    }
}

}
