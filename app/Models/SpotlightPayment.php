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
        'amount', 'currency', 'boost_plan', 'boost_hours',
        'status', 'spotlight_start_at', 'spotlight_end_at',
    ];

    protected $casts = [
        'spotlight_start_at' => 'datetime',
        'spotlight_end_at'   => 'datetime',
    ];

    public function product() { return $this->belongsTo(Product::class); }
    public function user()    { return $this->belongsTo(User::class); }
}
