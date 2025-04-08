<?php

namespace App\Http\Controllers;

use App\Models\Buildings;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;


class TasksController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Create and save the new task.
            $task = new Tasks();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->status = $request->status ?? 'open';
            $task->building_id = $request->building ?? 1;
            $task->user_created_id = $request->user_id;
            $task->user_updated_id = $request->user_id;
            $task->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task
            ], 201); // 201 Created
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task creation failed',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    public function index(Request $request)
    {
        // Start the query to retrieve tasks with associated data (userCreated, building, userUpdated).
        $tasks = Tasks::with('userCreated', 'building', 'userUpdated');

        // Apply search filters if provided
        if ($request->searchQuery != '') {
            $tasks = $tasks->where('title', 'like', '%' . $request->searchQuery . '%')
                ->orWhere('description', 'like', '%' . $request->searchQuery . '%');
        }

        if ($request->assignedUser != '') {
           $tasks = $tasks->where('user_updated_id', $request->assignedUser);
        }

        if ($request->building != '') {
            $tasks = $tasks->where('building_id', $request->building);
        }

        if ($request->startDate != '' && $request->endDate != '') {
           $tasks = $tasks->whereBetween('created_at', [$request->startDate, $request->endDate]);
        }

        $tasks = $tasks->latest()->get();

        // Retrieve the filtered tasks, ordered by the latest
        $buildings = Buildings::latest()->get();

        // Load the buildings and users for dropdown options
        $users = User::latest()->get();

        return response()->json([
            'tasks' => $tasks,
            'buildings' => $buildings,
            'users' => $users
        ]);

    }

}
