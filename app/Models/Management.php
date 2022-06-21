<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    use HasFactory;

    protected $casts = [
        'input_start_date' => 'date',
        'input_end_date' => 'date',
    ];

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

    // 따라하기 코딩
    public function getStatusColorAttribute()
    {
        return [
            // 'processing' => 'cool-gray',
            'success' => 'green',
            'failed' => 'red',
        ][$this->status] ?? 'cool-gray';
    }

    public function getDateForHumansAttribute()
    {
        return $this->input_start_date->format('M, d Y');
    }

}
