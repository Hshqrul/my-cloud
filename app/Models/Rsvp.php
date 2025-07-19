<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = [
        'name',
        'attendence',
        'no_of_pax',
        'notes',
    ];

    protected $casts = [
        'attendence' => 'boolean',
    ];
}
