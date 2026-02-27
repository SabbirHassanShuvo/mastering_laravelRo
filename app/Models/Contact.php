<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'message',
        'status',
    ];

    const STATUS = [
        'UNREAD' => 0,
        'READ' => 1,
    ];
}
