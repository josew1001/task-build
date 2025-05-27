<?php

namespace App\Services;

use App\Models\Tasks;

class TaskService
{
    public function findByIdWithRelations($id)
    {
        return Tasks::with(['userCreated', 'building', 'userUpdated'])->find($id);
    }

    public function updateTaskStatus($taskId, $status, $userId)
    {
        $task = Tasks::findOrFail($taskId);
        $task->status = $status;
        $task->user_updated_id = $userId;
        $task->save();
    }
}
