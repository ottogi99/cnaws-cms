<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $banks = ['카카오뱅크', '토스뱅크', 'K뱅크', '하나은행', '우리은행', '기업은행', '산업은행'];
        $staffIds = DB::table('staff')->pluck('id');

        $faker = $this->faker;
        return [
            'name' => $this->faker->randomElement($banks),
            'number' => $this->faker->numerify('##############'),
            'accountable_type' => \App\Models\Staff::class,
            'accountable_id' => function () use ($faker, $staffIds) {
                return $faker->randomElement($staffIds);
            },
        ];
    }
}
