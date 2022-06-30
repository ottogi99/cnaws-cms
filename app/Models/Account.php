<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    // protected $appends = ['nonghyup_id_for_account'];

    public $fillable = [
        'name',
        'number',
        'accountable_type',
    ];

    public static $ACCOUNT_TYPES = [
        Staff::class => '직원',
        'Farmes::class' => '농가',
    ];

    public function accountable()
    {
        return $this->morphTo();
    }

    // public function getNonghyupIdForAccountAttribute()
    // {
    //     return $this->accountable->nonghyup;
    // }

    // public function getTypeForEditingAttribute()
    // {
    //     return $this->accountable_type;// . '::class';
    // }

    // public function setTypeForEditingAttribute($value)
    // {
    //     return $this->accountable_type = $value;
    // }
}
