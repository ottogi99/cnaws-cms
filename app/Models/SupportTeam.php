<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTeam extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'birthday',
        'gender',
        'address',
        'contact',
        'size',
        'insurance',
    ];

    public function machineries()
    {
        return $this->belongsToMany(Machinery::class, 'support_team_machinery');
    }

    public function management()
    {
        return $this->belongsToMany(Management::class, 'management_support_team');
    }

}
