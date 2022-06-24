<?php

namespace Database\Factories;

use App\Models\Management;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Management>
 */
class ManagementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Management::class;

    public function definition()
    {
        $fakerStartDate = $this->faker->dateTimeThisYear($max= 'now', $timezone = 'Asia/Seoul');
        $fakerEndDate = Carbon::parse($fakerStartDate)->add(1, 'month');

        return [
            'input_year' => $this->faker->unique()->numberBetween($min=2020, $max=now()->year),
            'input_start_date' => $fakerStartDate,
            'input_end_date' => $fakerEndDate,
        ];
    }
}
