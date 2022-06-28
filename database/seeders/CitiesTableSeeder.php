<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getCities() as $seq => $name)
        City::create([
            'name' => $name,
            'sequence' => $seq,
        ]);
    }

    protected function getCities()
    {
        return [
            1 => "천안시",
            2 => "공주시",
            3 => "보령시",
            4 => "아산시",
            5 => "서산시",
            6 => "논산시",
            8 => "당진시",
            9 => "금산군",
            10 => "부여군",
            11 => "서천군",
            12 => "청양군",
            13 => "홍성군",
            14 => "예산군",
            15 => "태안군",
        ];
    }
}
