<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductPhoto;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    // Product belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Product belongs to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Product has many photos
    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }
}
