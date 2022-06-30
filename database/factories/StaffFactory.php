<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $nonghyupIds = DB::table('nonghyups')->pluck('id');

        return [
            'nonghyup_id' => $this->faker->randomElement($nonghyupIds),
            'name' => $this->faker->name(),
            'birthday' => $this->faker->date('Y-m-d'),
        ];
    }
}
