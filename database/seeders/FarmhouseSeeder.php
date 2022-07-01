<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Farmhouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class FarmhouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\Farmhouse::truncate();
        Schema::enableForeignKeyConstraints();

        $count = 100;
        for($i=0; $i < $count; $i++) {
            $farmhouse = Farmhouse::factory()->create();
            $account = null;

            if ($farmhouse->size === 'L') {
                $account = Account::factory()->create([
                    'accountable_type' => Farmhouse::class,
                    'accountable_id' => $farmhouse->id,
                ]);
                $farmhouse->account_id = $account->id;
            }

            $farmhouse->save();
        }
    }
}
