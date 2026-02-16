<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    protected $guarded = ['id'];

    // Photo belongs to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
