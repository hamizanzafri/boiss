<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = ['event', 'venue', 'date','photo','ticket_price','ticket_stock','latitude', 'longitude'];

    protected $casts = [
        'date' => 'date',  // This ensures Laravel treats the 'date' field as a Date object
    ];
}
