<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    use HasFactory;

    protected $fillable = [
        'input_year',
        'input_start_date',
        'input_end_date',
    ];

    public static function inputYearList()
    {
        $inputYearList = [];
        $startYear = 2020;
        $currentYear = now()->year;

        while ($startYear <= $currentYear)
        {
            array_push($inputYearList, $startYear);
            $startYear++;
        }

        return $inputYearList;
    }

}
