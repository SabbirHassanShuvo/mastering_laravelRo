<?php

namespace App\Models;

use App\Models\GarageItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarageItemImage extends Model
{
    use HasFactory;

    protected $fillable = ['garage_item_id','photo'];

    public function item() {
        return $this->belongsTo(GarageItem::class,'garage_item_id');
    }
}
