<?php

namespace App\Http\Controllers;

use App\Models\Buildings;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;

use App\Services\TaskService;
use App\Services\CommentService;
use App\Services\UserService;
use App\Services\ResponseService;


class TasksController extends Controller
{

    public function __construct(
        protected TaskService $taskService,
        protected CommentService $commentService,
        protected UserService $userService,
        protected ResponseService $responseService
    ) {}

    public function store(Request $request)
    {
        try {
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
            return $this->responseService->respondWithError('Unexpected error.'. $e);
        }
    }

    public function index(Request $request)
    {
        try{
            $tasks = Tasks::with('userCreated', 'building', 'userUpdated');
    
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
    
            $buildings = Buildings::latest()->get();
    
            $users = User::latest()->get();
    
            return response()->json([
                'success' => true,
                'tasks' => $tasks,
                'buildings' => $buildings,
                'users' => $users
            ], 200); // 200 OK
        } catch (\Exception $e) {
            return $this->responseService->respondWithError('Unexpected error.'. $e);
        }
    }

}
