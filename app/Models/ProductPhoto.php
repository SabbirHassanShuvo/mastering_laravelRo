<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductPhoto extends Model
{
    protected $fillable = ['product_id', 'photo_url', 'uploaded_at'];
    protected $guarded = ['id'];

    // Photo belongs to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPhotoUrlAttribute($value)
    {
        if ($value) {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            return asset('storage/' . ltrim($value, '/'));
        }
        return null;
    }
}
