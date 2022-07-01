<?php

namespace Database\Seeders;

use App\Models\Management;
use App\Models\Nonghyup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagementNonghyupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $management = Management::all();
        Nonghyup::all()->each(function ($nonghyup) use ($management) {
            // $nonghyup->management()->attach(
            //     $nonghyup->random(rand(1,3))->pluck('id')->toArray()
            // );
            $nonghyup->management()->sync(
                $management->random(rand(1,3))->pluck('year')->toArray()
            );
        });
    }
}
