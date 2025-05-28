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

            $task = $this->taskService->createTask($request->all());

            return $this->responseService->respondCreated('Task created successfully.', $task);

        } catch (\Exception $e) {
            return $this->responseService->respondWithError('Unexpected error while creating task.', $e);
        }
    }


    public function index(Request $request)
    {
        try{
            
            $tasks = $this->taskService->filterTasks($request->all());
    
            $buildings = Buildings::latest()->get();
    
            $users = User::latest()->get();
            
            return $this->responseService->respondWithData( $tasks, $buildings, $users );
        } catch (\Exception $e) {
            return $this->responseService->respondWithError('Unexpected error.'. $e);
        }
    }

}
