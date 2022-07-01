<?php

namespace Database\Seeders;

use App\Models\Management;
use App\Models\Farmhouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagementFarmhouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $management = Management::all();
        Farmhouse::all()->each(function ($farmhouse) use ($management) {
            // $nonghyup->management()->attach(
            //     $nonghyup->random(rand(1,3))->pluck('id')->toArray()
            // );
            $farmhouse->management()->sync(
                $management->random(rand(1,3))->pluck('year')->toArray()
            );
        });
    }
}
