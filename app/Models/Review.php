<?php

namespace App\Models;

use App\Models\Pickup;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'pickup_id',
        'product_id',
        'reviewer_id',
        'reviewee_id',
        'rating',
        'comment',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function pickup(): BelongsTo
    {
        return $this->belongsTo(Pickup::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }
}