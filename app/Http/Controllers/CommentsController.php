<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;
// use Carbon\Carbon;  // format data

class CommentsController extends Controller
{

    public function index(Request $request)
    {
        try {
            $task = $this->getTask($request->talkId);
    
            if (!$task) {
                return $this->respondNotFound('Task not found');
            }
    
            $comments = $this->getTaskComments($request->talkId);
            $users = $this->getUsers();
    
            return response()->json([
                'success' => true,
                'task' => $task,
                'comments' => $comments,
                'users' => $users,
                'message' => 'Task loaded successfully.'
            ], 200);
    
        } catch (\Exception $e) {
            return $this->respondWithError('Error fetching task data', $e);
        }
    }
   

    public function store(Request $request)
    {
        try {
            $task = Tasks::find($request->task_id);
            $task->user_updated_id = $request->user_id;
            $task->status = $request->task_status;
            $task->save();

            $comments = new Comments();
            $comments->content = $request->content;
            $comments->task_id = $request->task_id;
            $comments->user_created_id = $request->user_id;
            $comments->save();

            return response()->json([
                'success' => true,
                'message' => 'Comment added and task updated.'
                // 'comment' => $comment
            ], 201);
    
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound('Task not found.');
        } catch (\Exception $e) {
            return $this->respondWithError('Unexpected error.', $e);
        }
    }

    private function getTask($id)
    {
        return Tasks::with(['userCreated', 'building', 'userUpdated'])->find($id);
    }
    
    private function getUsers()
    {
        return User::latest()->get();
    }
    
    private function getTaskComments($taskId)
    {
        return Comments::with('userCreated')
            ->where('task_id', $taskId)
            ->get();
    }
    
    private function respondNotFound($message)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], 404);
    }
    
    private function respondWithError($message, $exception)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => $exception->getMessage()
        ], 500);
    }
}
