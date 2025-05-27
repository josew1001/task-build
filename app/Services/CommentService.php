<?php

namespace App\Services;

use App\Models\Comments;

class CommentService
{
    public function getCommentsForTask($taskId)
    {
        return Comments::with('userCreated')
            ->where('task_id', $taskId)
            ->get();
    }

    public function createComment(array $data)
    {
        return Comments::create([
            'content' => $data['content'],
            'task_id' => $data['task_id'],
            'user_created_id' => $data['user_id'],
        ]);
    }
}
