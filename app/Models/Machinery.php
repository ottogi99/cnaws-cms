<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machinery extends Model
{
    use HasFactory;

    public $fillable = [
        'name', 'spec',
    ];

    public static $MACHINERIES = [
        '트랙터',
        '이양기',
        '쟁기',
        '배토기',
        '수확기',
        '살포기',
        '콤바인',
        '경운기',
        '관리기',
        '로타리',
        '피복기',
        '두둑기',
        '예초기',
        '기타',
    ];

    public function supportTeam()
    {
        return $this->belongsToMany(SupportTeam::class, 'support_team_machinery');
    }

    // Dynamic property
    public static function getMachineries()
    {
        return static::orderby('type', 'asc')->get();
    }
}
