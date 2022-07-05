<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupportTeam>
 */
class SupportTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $nonghyupIds = DB::table('nonghyups')->pluck('id');
        $genders = ['M', 'F'];
        $sizes = ['S', 'L'];

        // 농가규모 구분
        $size = $this->faker->randomElement($sizes);
        $nonghyupId = $this->faker->randomElement($nonghyupIds);

        return [
            'nonghyup_id' => $nonghyupId,
            'name' => $this->faker->name(),
            'birthday' => $this->faker->date('Y-m-d'),
            'gender' => $this->faker->randomElement($genders),
            'address' => $this->faker->metropolitanCity().' '.$this->faker->borough(),
            'contact' => \Illuminate\Support\Str::replace('-', '', $this->faker->localAreaPhoneNumber()),
            'size' => $size,
        ];
    }
}
