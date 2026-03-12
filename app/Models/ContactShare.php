<?php

namespace App\Models;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactShare extends Model
{
    protected $fillable = [
        'conversation_id',
        'requester_id',
        'receiver_id',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}