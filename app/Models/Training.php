<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    public $fillable = [
        'subject',
        'start_date',
        'end_date',
    ];

    // Dynamic property
    public static function getTrainings($id)
    {
        return static::where('support_team_id', '=', $id)->orderby('type', 'asc')->get();
    }
}
