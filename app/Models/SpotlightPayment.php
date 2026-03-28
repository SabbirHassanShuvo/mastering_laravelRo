<?php

namespace App\Models;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SpotlightPayment extends Model
{
     protected $fillable = [
        'user_id', 'product_id',
        'stripe_payment_intent_id', 'stripe_payment_method_id',
        'amount', 'posting_fee', 'total_fee', 'currency', 'boost_plan', 'boost_hours',
        'status', 'spotlight_start_at', 'spotlight_end_at',
    ];

    protected $casts = [
        'spotlight_start_at' => 'datetime',
        'spotlight_end_at'   => 'datetime',
    ];

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where($this->getTable() . '.status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where($this->getTable() . '.status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where($this->getTable() . '.status', 'paid')
                     ->where($this->getTable() . '.spotlight_start_at', '<=', now())
                     ->where($this->getTable() . '.spotlight_end_at', '>=', now());
    }

    public function product() { return $this->belongsTo(Product::class); }
    public function user()    { return $this->belongsTo(User::class); }
}
