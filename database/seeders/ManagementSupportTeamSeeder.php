<?php

namespace Database\Seeders;

use App\Models\Management;
use App\Models\SupportTeam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagementSupportTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $management = Management::all();
        SupportTeam::all()->each(function ($supportTeam) use ($management) {
            $supportTeam->management()->sync(
                $management->random(rand(1,3))->pluck('year')->toArray()
            );
        });
    }
}
