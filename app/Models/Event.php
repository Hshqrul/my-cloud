<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'event_name',
        'slug',
        'description',
        'event_date',
        'allowed_pax',
        'user_id',
        'marquee_duration',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
