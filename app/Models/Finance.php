<?php

namespace App\Models;

use App\Enum\FinanceEnum;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $fillable = [
        'item',
        'amount',
        'type',
        'user_id',
        'remark',
    ];

    protected $casts = [
        'amount' => 'float',
        'type' => FinanceEnum::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
