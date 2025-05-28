<?php

namespace App\Services;

use App\Models\Tasks;
use Illuminate\Support\Collection;

class TaskService
{

    public function filterTasks(array $filters): Collection
    {
        $query = Tasks::with('userCreated', 'building', 'userUpdated');

        if (!empty($filters['searchQuery'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['searchQuery'] . '%')
                ->orWhere('description', 'like', '%' . $filters['searchQuery'] . '%');
            });
        }

        if (!empty($filters['assignedUser'])) {
            $query->where('user_updated_id', $filters['assignedUser']);
        }

        if (!empty($filters['building'])) {
            $query->where('building_id', $filters['building']);
        }

        if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
            $query->whereBetween('created_at', [$filters['startDate'], $filters['endDate']]);
        }

        return $query->latest()->get();
    }

    public function createTask(array $data): Tasks
    {
        return Tasks::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'open',
            'building_id' => $data['building'] ?? 1,
            'user_created_id' => $data['user_id'],
            'user_updated_id' => $data['user_id'],
        ]);
    }

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

