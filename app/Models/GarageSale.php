<?php

namespace App\Models;

use App\Models\GarageArchived;
use App\Models\GarageItem;
use App\Models\GarageLove;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarageSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','event_title','description','date','pickup_location',
        'latitude','longitude','sale_start_date','sale_end_date',
        'status','posting_fee','total_fee','is_spotlighted','expires_at', 'notified_before_delete'
    ];

    protected $dates = ['expires_at','sale_start_date','sale_end_date'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(GarageItem::class);
    }

    public function archivedByUsers()
    {
        return $this->hasMany(GarageArchived::class, 'garage_id');
    }

    public function loves()
    {
        return $this->hasMany(GarageLove::class, 'garage_id');
    }

    public function lovedUsers()
    {
        return $this->belongsToMany(User::class, 'garage_loves', 'garage_id', 'user_id');
    }
}
