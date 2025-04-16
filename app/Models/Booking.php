<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'username',
        'number',
        'department',
        'service',
        'type',
        'date',
        'time'
    ];
}
