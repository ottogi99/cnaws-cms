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
        7 => "당진시",
        9 => "금산군",
        9 => "부여군",
        10 => "서천군",
        11 => "청양군",
        12 => "홍성군",
        13 => "예산군",
        14 => "태안군",
    ];
}
