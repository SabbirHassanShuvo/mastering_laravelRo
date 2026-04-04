<?php

namespace App\Models;

use App\Models\GarageItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GarageItemImage extends Model
{
    use HasFactory;

    protected $fillable = ['garage_item_id','photo'];

    public function getPhotoAttribute($value)
    {
        if ($value) {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            return asset('storage/' . ltrim($value, '/'));
        }
        return null;
    }

    public function item() {
        return $this->belongsTo(GarageItem::class,'garage_item_id');
    }
}
