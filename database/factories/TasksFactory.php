<?php

namespace Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Buildings;
use App\Models\Tasks;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tasks>
 */
class TasksFactory extends Factory
{

    protected $model = Tasks::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'open',
            'building_id' => 1,
            'user_created_id' => User::factory(),
            'user_updated_id' => User::factory()
        ];
    }
}
