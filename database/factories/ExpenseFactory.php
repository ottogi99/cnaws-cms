<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Expense::class;

    public function definition()
    {
        $nonghyupIds = DB::table('nonghyups')->pluck('id');
        $managementYears = DB::table('management')->pluck('year');

        $randomTotal = $this->faker->randomNumber(5, true);
        $amount = [
            'total' => $randomTotal,
            'do' => $randomTotal * 0.4,
            'sigun' => $randomTotal * 0.3,
            'center' => $randomTotal * 0.2,
            'unit' => $randomTotal * 0.1,
        ];

        return [
            'nonghyup_id' => $this->faker->randomElement($nonghyupIds),
            'year' => $this->faker->randomElement($managementYears),
            'total' => $amount['total'],
            'do' => $amount['do'],
            'sigun' => $amount['sigun'],
            'center' => $amount['center'],
            'unit' => $amount['unit'],
        ];
    }
}
