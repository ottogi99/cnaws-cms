<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Staff extends Model
{
    use HasFactory;

    // protected $appends = ['birthday_for_editing'];

    public $fillable = [
        'nonghyup_id', 'name', 'birthday', 'account_id',
    ];

    public function nonghyup()
    {
        return $this->belongsTo(Nonghyup::class, 'nonghyup_id');
    }

    public function account()
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    // public function getBirthdayForHumansAttribute($value)
    // {
    //     return $this->birthday->format('Y-m-d');
    // }

    // public function getBirthdayForEditingAttribute()
    // {
    //     return $this->birthday;
    // }

    // public function setBirthdayForEditingAttribute($value)
    // {
    //     $this->birthday = Carbon::parse($value);
    // }
}
