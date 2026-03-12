<?php

namespace App\Models;

use App\Models\ContactShare;
use App\Models\Message;
use App\Models\Pickup;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'product_id',
        'user_one_id',
        'user_two_id',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function contactShares(): HasMany
    {
        return $this->hasMany(ContactShare::class);
    }

    public function pickups(): HasMany
    {
        return $this->hasMany(Pickup::class);
    }

    // ─── Helper ───────────────────────────────────────────────

    /**
     * Check if a given user belongs to this conversation.
     */
    public function hasUser(int $userId): bool
    {
        return $this->user_one_id === $userId || $this->user_two_id === $userId;
    }

    /**
     * Get the other participant's ID.
     */
    public function otherUserId(int $myId): int
    {
        return $this->user_one_id === $myId ? $this->user_two_id : $this->user_one_id;
    }
}