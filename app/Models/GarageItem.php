<?php

namespace App\Models;

use App\Models\GarageItemImage;
use App\Models\GarageSale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarageItem extends Model
{
    use HasFactory;

    protected $fillable = ['garage_sale_id','title','price','description'];

    public function garageSale() {
        return $this->belongsTo(GarageSale::class);
    }

    public function images() {
        return $this->hasMany(GarageItemImage::class);
    }
}

