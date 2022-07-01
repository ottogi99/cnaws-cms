<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expenditure>
 */
class ExpenditureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $nonghyupsIds = DB::table('nonghyups')->pluck('id');
        $nonghyupId = $this->faker->randomElement($nonghyupsIds);
        $expenditureTypes = ['OPER', 'LABOR', 'EDUPR'];
        // $expenditureTypes = ['OPER', 'EDUPR'];

        // $accountId = null;
        // $expenditureType = 'OPER';
        $staffId = null;

        $expenditureType = $this->faker->randomElement($expenditureTypes);

        if ($expenditureType == 'LABOR') {
            $staffIds = DB::table('staff')->where('nonghyup_id', '=', $nonghyupId)->pluck('id');
            if (Count($staffIds) > 0)
                $staffId = $this->faker->randomElement($staffIds);
        }

        // if(Count($staffIds) > 0) {
        //     if (DB::table('accounts')
        //         ->where('accountable_type', '=', 'App\\Models\\Staff')
        //         // ->where('accountable_type', '=', 'Staff')
        //         // ->orWhereIn('accountable_id', $staffIds)->first()->pluck('id');
        //         ->whereIn('accountable_id', $staffIds)->exists()) {

        //         $accountId = DB::table('accounts')
        //             ->where('accountable_type', '=', 'App\\Models\\Staff')
        //             ->whereIn('accountable_id', $staffIds)->first()->id;

        //         $expenditureType = 'LABOR';
        //     }
        // }
        // else {
        //     $expenditureType = $this->faker->randomElement($expenditureTypes);
        // }

        return [
            'type' => $expenditureType,
            'nonghyup_id' => $nonghyupId,
            'item' => $this->faker->word(),
            'target' => $expenditureType != 'LABOR' ? $this->faker->word() : null,
            'details' => $this->faker->sentence(),
            'amount' => $this->faker->randomNumber(4, true),
            'payment_at' => $this->faker->date(),
            'staff_id' => $expenditureType == 'LABOR' ? $staffId : null,
            // 'account_id' => $expenditureType === 'LABOR'
            //     ? $accountId
            //     : null,
        ];
    }
}
