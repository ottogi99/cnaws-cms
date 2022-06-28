<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // App\Management::truncate();
        // $this->call(ManagementTableSeeder::class);

        \App\Models\City::trunctate();
        $this->call(CitiesTableSeeder::class);

        \App\Models\Nonghyup::truncate();
        $this->call(NonghyupsTableSeeder::class);

        \App\Models\Expense::truncate();
        $this->call(ExpensesTableSeeder::class);
    }
}
