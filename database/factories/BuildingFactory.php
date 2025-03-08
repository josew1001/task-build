<?php

namespace Database\Factories;

use App\Models\buildings;
use Illuminate\Database\Eloquent\Factories\Factory;
// use App\Models\buildings;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\buildings>
 */
class BuildingFactory extends Factory
{
    protected $model = buildings::class;
    // protected $model = buildings::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company, // Gera um nome aleatório
        ];
    }
}
