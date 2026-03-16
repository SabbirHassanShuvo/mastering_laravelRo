<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductPhoto extends Model
{
    protected $guarded = ['id'];

    // Photo belongs to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPhotoUrlAttribute($value)
    {
        if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
            return Storage::disk('public')->url($value);
        }
        return $value;
    }
}
