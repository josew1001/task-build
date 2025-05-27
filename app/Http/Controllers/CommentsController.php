<?php

namespace App\Http\Controllers;


use App\Services\TaskService;
use App\Services\CommentService;
use App\Services\UserService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
// use Carbon\Carbon;  // format data

class CommentsController extends Controller
{
    
    public function __construct(
        protected TaskService $taskService,
        protected CommentService $commentService,
        protected UserService $userService,
        protected ResponseService $responseService
    ) {}

    public function index(Request $request)
    {
        try {

            $task = $this->taskService->findByIdWithRelations($request->talkId);

            if (!$task) {
                return $this->responseService->respondNotFound('Task not found');
            }

            $comments = $this->commentService->getCommentsForTask($request->talkId);
            $users = $this->userService->getAllUsers();

            return response()->json([
                'success' => true,
                'task' => $task,
                'comments' => $comments,
                'users' => $users,
                'message' => 'Task loaded successfully.'
            ], 200);

        } catch (\Exception $e) {

            return $this->responseService->respondWithError('Error fetching task data', $e);
            
        }
    }

    public function store(Request $request)
    {
        try {
            $this->taskService->updateTaskStatus($request->task_id, $request->task_status, $request->user_id);
            $this->commentService->createComment($request->only(['task_id', 'content', 'user_id']));

            return response()->json([
                'success' => true,
                'message' => 'Comment added and task updated.'
            ], 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->responseService->respondNotFound('Task not found.');

        } catch (\Exception $e) {
            return $this->responseService->respondWithError('Unexpected error.'. $e);
        }
    }

}
