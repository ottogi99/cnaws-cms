<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
    use HasFactory;

    public $fillable = [
        'type',
        'nonghyup_id',
        'item',
        'target',
        'details',
        'amount',
        'payment_at',
        'staff_id',
    ];

    // $expenditureTypes = ['OPER', 'LABOR', 'EDUPR'];
    public static $EXPENDITURE_TYPES = [
        'OPER' => '운영비',
        'LABOR' => '인건비',
        'EDUPR' => '교육 및 홍보비',
    ];

    public static $LABOR_TYPE = 'LABOR';
}
