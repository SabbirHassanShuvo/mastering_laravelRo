<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'subject',
    'message',
    'status'
    ];

    const STATUS = [
        'UNREAD' => 0,
        'READ' => 1,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
