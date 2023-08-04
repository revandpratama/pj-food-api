<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Rendang',
            'price' => 23000,
            'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora neque accusamus quis corrupti dolorem animi voluptatibus reprehenderit incidunt numquam accusantium!"
        ];
    }
}
