<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Machinery;
use App\Models\SupportTeam;
use App\Models\Training;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SupportTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\Training::truncate();
        DB::table('support_team_machinery')->truncate();
        Schema::enableForeignKeyConstraints();

        $count = 100;

        for($i=0; $i < $count; $i++) {
            $supportTeam = SupportTeam::factory()->create();

            if ($supportTeam->size === 'S') {
                $machineries = Machinery::all();
                $supportTeam->machineries()->sync(
                    $machineries->random(rand(1,3))->pluck('id')->toArray()
                );
            } else {
                $supportTeam->insurance = rand(0, 1);

                $traing_count = rand(0, 2);
                for ($j=0; $j < $traing_count; $j++) {
                    $training = Training::factory()->create([
                        'support_team_id' => $supportTeam->id,
                    ]);
                    $training->save();
                }
            }

            $account = Account::factory()->create([
                'accountable_type' => SupportTeam::class,
                'accountable_id' => $supportTeam->id,
            ]);

            $supportTeam->account_id = $account->id;

            $supportTeam->save();
        }
    }
}
