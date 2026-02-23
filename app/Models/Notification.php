<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Notification extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'notifications';
    protected $primaryKey = 'id';
    public $incrementing = false; // UUID primary key
    protected $keyType = 'string';

    // Mass assignment protection
    protected $guarded = [];

    // Cast data column to array
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Polymorphic relation (notifiable)
    public function notifiable()
    {
        return $this->morphTo();
    }
}