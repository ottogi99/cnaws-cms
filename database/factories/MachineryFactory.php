<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Machinery>
 */
class MachineryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $machinerys = [
            '트랙터', '이양기', '쟁기', '배토기', '수확기', '살포기', '콤바인',
            '경운기', '관리기', '로타리', '피복기', '두둑기', '예초기', '기타',
        ];

        return [
            'name' => $this->faker->randomElement($machinerys),
            'spec' => $this->faker->word(),
        ];
    }
}
