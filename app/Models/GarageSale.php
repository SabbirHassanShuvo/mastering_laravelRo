<?php

namespace App\Models;

use App\Models\GarageArchived;
use App\Models\GarageItem;
use App\Models\GarageLove;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarageSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'event_title', 'description', 'date', 'pickup_location',
        'latitude', 'longitude', 'sale_start_date', 'sale_end_date',
        'posting_fee', 'total_fee', 'is_spotlighted', 'status',
        'expires_at', 'stripe_payment_intent_id', 'payment_status',
        'payment_completed_at'
    ];

    protected $casts = [
        'date' => 'date',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
        'expires_at' => 'datetime',
        'payment_completed_at' => 'datetime',
        'is_spotlighted' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(GarageItem::class);
    }

    public function archivedByUsers()
    {
        return $this->hasMany(GarageArchived::class, 'garage_id');
    }

    public function loves()
    {
        return $this->hasMany(GarageLove::class, 'garage_id');
    }

    public function lovedUsers()
    {
        return $this->belongsToMany(User::class, 'garage_loves', 'garage_id', 'user_id');
    }

    // Helper methods for payment status
    public function isPaid()
    {
        return $this->payment_status === 'completed';
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->isPaid();
    }

    /**
     * Scope a query to only include active garage sales.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
