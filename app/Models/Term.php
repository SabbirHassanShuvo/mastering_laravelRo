<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $fillable = [
    'title',
    'description',
    'priority',
    'status'
    ];

    const STATUS = [
        'ACTIVE' => 1,
        'INACTIVE' => 0,
    ];
}
