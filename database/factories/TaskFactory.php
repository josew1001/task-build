<?php

namespace Database\Factories;

use App\Models\buildings;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\tasks;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\tasks>
 */
class TaskFactory extends Factory
{

    protected $model = tasks::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'open',
            'building_id' => buildings::factory(),            
            'user_created_id' => User::factory(),
            'user_updated_id' => User::factory(),
        ];
    }
}
