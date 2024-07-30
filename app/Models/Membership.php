<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';  // Ensure this matches your actual database table name

    protected $fillable = [
        'name',       // Assuming your membership has a 'name'
        'address',    // Assuming your membership has an 'address'
    ];
}
