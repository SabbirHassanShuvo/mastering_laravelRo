<?php

namespace App\Models;

use App\Models\Conversation;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pickup extends Model
{
    protected $fillable = [
        'conversation_id',
        'product_id',
        'requester_id',
        'receiver_id',
        'pickup_date',
        'pickup_time',
        'location',
        'notes',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ─── Helper ───────────────────────────────────────────────

    public function hasUser(int $userId): bool
    {
        return $this->requester_id === $userId || $this->receiver_id === $userId;
    }

    public function otherUserId(int $myId): int
    {
        return $this->requester_id === $myId ? $this->receiver_id : $this->requester_id;
    }
}