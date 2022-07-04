<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Management extends Model
{
    use HasFactory;

    const STATUSES = [
        'success' => 'Success',
        'failed' => 'Failed',
        'processing' => 'Processing',
    ];

    // Define Custom Primary Key
    protected $primaryKey = 'year';
    public $incrementing = false;
    protected $keyType = 'tinyInteger';

    protected $dateFormat = 'Y-m-d';

    // 이거 없으면 안된다. 블레이드쪽에서 editing.date_for_editing값이 NULL 이 됨.
    protected $appends = ['initiate_for_editing', 'deadline_for_editing'];

    protected $casts = [
        // 'initiate' => 'datetime:Y-m-d',
        // 'deadline' => 'datetime:Y-m-d',
        'initiate' => 'date',
        'deadline' => 'date',
        'created_at' => 'date',
    ];

    protected $fillable = [
        'year',
        'initiate',
        'deadline',
    ];

    // 관계설정
    public function nonghyups()
    {
        return $this->belongsToMany(Nonghyup::class, 'management_nonghyups');
    }

    public function farmhouses()
    {
        return $this->belongsToMany(Farmhouse::class, 'management_farmhouse');
    }

    public static function yearList($firstYear = 2020)
    {
        $yearList = [];
        // $firstYear = 2020;
        $currentYear = now()->year;

        while ($firstYear <= $currentYear)
        {
            array_push($yearList, $firstYear);
            $firstYear++;
        }

        return $yearList;
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

    // Mutator 정의
    public function getIdAttribute()
    {
        return $this->year;
    }

    public function getInitiateForHumansAttribute($value)
    {
        return $this->initiate->format('Y-m-d');
    }

    public function getDeadlineForHumansAttribute($value)
    {
        return $this->deadline->format('Y-m-d');
    }

    public function getInitiateForEditingAttribute()
    {
        return $this->initiate->format('Y-m-d');
    }

    public function setInitiateForEditingAttribute($value)
    {
        $this->initiate = Carbon::parse($value);
    }

    public function getDeadlineForEditingAttribute()
    {
        return $this->deadline->format('Y-m-d');
    }

    public function setDeadlineForEditingAttribute($value)
    {
        $this->deadline = Carbon::parse($value);
    }
}
