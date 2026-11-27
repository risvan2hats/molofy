<?php

namespace App\Modules\Boat\Models;

use Illuminate\Database\Eloquent\Model;

class Boat extends Model
{
    protected $fillable = [
        'name',
        'description',
        'capacity',
        'status',
    ];
}