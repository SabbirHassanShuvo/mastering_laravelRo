<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductLove;
use App\Models\ProductPhoto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{

    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_SOLD = 'sold';
    const STATUS_ARCHIVED = 'archived';
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'product_type',
        'price',
        'description',
        'pickup_location',
        'pickup_latitude',
        'pickup_longitude',
        'status',
        'sold_at',
        'product_image',
        'condition_status',
        'is_urgent',
        'urgent_pickup_date',
        'urgent_pickup_notes',
        'posted_at',
        'expires_at'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'posted_at'  => 'datetime',
        'spotlight_start_date' => 'datetime',
        'spotlight_end_date'   => 'datetime',
        'sold_at'    => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeArchived($query)
    {
        return $query->where('status', self::STATUS_ARCHIVED);
    }

    // Check if product expired
    public function isExpired()
    {
        return $this->status === self::STATUS_ACTIVE && $this->expires_at && $this->expires_at->lt(now());
    }

    public function loves()
    {
        return $this->hasMany(ProductLove::class);
    }

    public function spotlightPayments()
    {
        return $this->hasMany(SpotlightPayment::class);
    }

    public function getProductImageAttribute($value)
    {
        if ($value) {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            return asset('storage/' . ltrim($value, '/'));
        }

        $photo = $this->photos()->first();
        if ($photo && $photo->photo_url) {
            // photo_url already has an accessor logic that resolves to full path
            return $photo->photo_url;
        }

        return null;
    }
}
