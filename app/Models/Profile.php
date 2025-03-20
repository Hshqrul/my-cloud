<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tel_no',
        'address',
        'city',
        'postcode',
        'state',
        'country',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
