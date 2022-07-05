<?php

namespace Database\Seeders;

use App\Models\Machinery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MachinerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\Machinery::truncate();
        Schema::enableForeignKeyConstraints();

        $MACHINERIES = [
            '트랙터', '이양기', '쟁기', '배토기', '수확기', '살포기', '콤바인',
            '경운기', '관리기', '로타리', '피복기', '두둑기', '예초기', '기타',
        ];

        foreach ($MACHINERIES as $machinery) {
            Machinery::create([
                'type' => $machinery,
                'spec' => '',
            ]);
        }
    }
}
