<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sequence'];

    public function nonghyups()
    {
        return $this->hasMany(Nonghyup::class);
    }

    public static $NAMES = [
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
