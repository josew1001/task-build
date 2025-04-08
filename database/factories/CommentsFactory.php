<?php

namespace Database\Factories;

use App\Models\Comments;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentsFactory extends Factory
{
    protected $model = Comments::class;

    public function definition()
    {
        return [
            'content' => $this->faker->paragraph,
            'task_id' => Tasks::factory(),
            'user_created_id' => User::factory(),
        ];
    }
}