<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductLove;
use App\Models\ProductPhoto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_SOLD = 'sold';
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'product_type',
        'price',
        'description',
        'pickup_latitude',
        'pickup_longitude',
        'status'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
        'posted_at'  => 'datetime',
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

    public function lovedUsers()
    {
        return $this->belongsToMany(User::class, 'product_loves');
    }
}
