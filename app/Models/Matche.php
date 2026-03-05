<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matche extends Model
{
    protected $table = 'matches';    

    protected $fillable = [
        'product_id',
        'user_one_id',
        'user_two_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function userOne()
    {
        return $this->belongsTo(User::class,'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class,'user_two_id');
    }
}
