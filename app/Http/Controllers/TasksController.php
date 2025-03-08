<?php

namespace App\Http\Controllers;

use App\Models\buildings;
use App\Models\tasks;
use App\Models\User;
use Illuminate\Http\Request;


class TasksController extends Controller
{
    public function store(Request $request)
    {
        // Create and save the new task.
        $task = new tasks();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status ?? 'open'; // Default status is 'open'
        $task->building_id = $request->building ?? 1; // Default building_id is 1
        $task->user_created_id = $request->user_id;
        $task->user_updated_id = $request->user_id;

        $task->save();  // Return a success message with the created task
    }

    public function index(Request $request)
    {
        // Start the query to retrieve tasks with associated data (userCreated, building, userUpdated).
        $tasks = tasks::with('userCreated', 'building', 'userUpdated');

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
        $buildings = buildings::latest()->get();

        // Load the buildings and users for dropdown options
        $users = User::latest()->get();

        return response()->json([
            'tasks' => $tasks,
            'buildings' => $buildings,
            'users' => $users
        ]);

    }

}
