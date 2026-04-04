<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'slug', 'image', 'status'];
    protected $guarded = ['id'];

    public function getImageAttribute($value)
    {
        if ($value) {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            return asset('storage/' . ltrim($value, '/'));
        }
        return null;
    }

    public static function _status()
    {
        return [
            'ACTIVE'   => '1',
            'INACTIVE' => '0',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
