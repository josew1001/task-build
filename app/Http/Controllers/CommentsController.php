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


        // Find the task based on the provided task ID (talkId).
        $task = Tasks::with('userCreated', 'building', 'userUpdated')->find($request->talkId);

        // If task is not found, return an empty response or error (based on preference).
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Fetch all users.
        $users = User::latest()->get();


        // Get comments for the task.
        $comments = Comments::query()
            ->with('userCreated')
            ->where('task_id', $request->talkId)
            ->get();

        // If no comments found, return an empty array.
        if ($comments->isEmpty()) {
            $comments = []; // Define $comments como um array vazio
        }

        return response()->json([
            'task' => $task,
            'comments' => $comments,
            'users' => $users
        ]);

    }

    public function store(Request $request)
    {

        // Update task with the provided user_id and task status.
        $task = Tasks::find($request->task_id);
        $task->user_updated_id = $request->user_id;
        $task->status = $request->task_status;
        $task->save();

        // Create and save the comment.
        $comments = new Comments();
        $comments->content = $request->content;
        $comments->task_id = $request->task_id;
        $comments->user_created_id = $request->user_id;

        $comments->save();

    }
}
