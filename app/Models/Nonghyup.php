<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nonghyup extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id', 'name', 'address', 'contact', 'representative', 'sequence'
    ];

    // 관계 설정 시군(cities)과 농협은 1:N관계
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function management()
    {
        return $this->belongsToMany(Management::class, 'management_nonghyup');
    }
}
