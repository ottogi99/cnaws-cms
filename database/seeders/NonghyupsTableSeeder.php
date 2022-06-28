<?php

namespace Database\Seeders;

use App\Models\Nonghyup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Container\Container;

class NonghyupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $faker;

    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    public function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    public function run()
    {
        foreach ($this->getNonghyups() as $cityId => $nonghyups) {
            foreach ($nonghyups as $nonghyup) {
                Nonghyup::create([
                    'name' => $nonghyup,
                    'address' => $this->faker->metropolitanCity().' '.$this->faker->borough(),
                    'contact' => \Illuminate\Support\Str::replace('-', '', $this->faker->localAreaPhoneNumber()),
                    'representative' => $this->faker->company(),
                    'sequence' => $this->faker->numberBetween(1, 10),
                    'cities_id' => $cityId,
                ]);
            }
        }

    }

    public function getNonghyups()
    {
        return [
            1 => ['천안농협','동천안농협','성거농협','성환농협','입장농협','직산농협','아우내농협','천안배농협',],
            2 => ['계룡농협','탄천농협','유구농협','정안농협','의당농협','신풍농협','반포농협','사곡농협','우성농협','이인농협','공주농협','세종공주원예농협',],
            3 => ['대천농협','남포농협','오천농협','청소농협','천북농협','웅천농협','주산농협',],
            4 => ['온양농협','송악농협','배방농협','탕정농협','음봉농협','둔포농협','영인농협','인주농협','선도농협','염치농협','아산원예농협',],
            5 => ['서산농협','부석농협','지곡농협','대산농협','성연농협','음암농협','운산농협','해미농협','고북농협','충서원예농협',],
            6 => ['논산농협','부적농협','광석농협','노성농협','상월농협','논산계룡농협','양촌농협','강경농협','성동농협','연무농협',],
            7 => ['당진농협','고대농협','석문농협','대호지농협','정미농협','면천농협','순성농협','합덕농협','우강농협','신평농협','송악농협','송산농협',],
            8 => ['금산농협','부리농협','만인산농협','진산농협',],
            9 => ['세도농협','장암농협','규암농협','구룡농협','서부여농협','부여농협','동부여농협',],
            10 => ['서천농협','판교농협','서서천농협','한산농협','동서천농협','장항농협',],
            11 => ['청양농협','정산농협','화성농협',],
            12 => ['홍성농협','홍북농협','금마농협','홍동농협','구항농협','갈산농협','광천농협','장곡농협','결성농협','서부농협',],
            13 => ['신양농협','예산중앙농협','광시농협','삽교농협','덕산농협','고덕농협','예산농협','예산능금농협',],
            14 => ['안면도농협','남면농협','태안농협','근흥농협','소원농협','원북농협',],
        ];
    }
}
