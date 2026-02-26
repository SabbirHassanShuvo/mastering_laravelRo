<?php

namespace App\Models;

use App\Models\GarageSale;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GarageArchived extends Model
{
    protected $table = 'garage_archived';

    protected $fillable = ['user_id', 'garage_id'];

    public function garageSale()
    {
        return $this->belongsTo(GarageSale::class, 'garage_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}