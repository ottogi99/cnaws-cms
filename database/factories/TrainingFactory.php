<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startDate = $this->faker->date('Y-m-d');
        $endDate = Carbon::parse($startDate)->add(rand(0, 3), 'day');

        return [
            'subject' => $this->faker->sentence(),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
