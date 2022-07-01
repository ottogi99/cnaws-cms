<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmhouse extends Model
{
    use HasFactory;

    public $fillable = [
        'name', 'birthday', 'gender', 'address', 'contact',
        'size',
        'rice_field', 'field', 'other_field',
        'area', 'items',
        'nonghyup_id', 'account_id',
    ];

    public function management()
    {
        return $this->belongsToMany(Management::class, 'management_farmhouse');
    }

    public static $FARMSIZES = [
        'S' => '소규모/영세농',
        'L' => '대규모/전업농'
    ];

    public static $GENDERS = [
        'M' => '남',
        'F' => '여'
    ];
}
