<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    public $fillable = [
        'year',
        'total',
        'do',
        'sigun',
        'center',
        'unit',
        'nonghyup_id',
    ];
}
