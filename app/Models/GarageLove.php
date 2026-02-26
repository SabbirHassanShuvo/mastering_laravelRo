<?php

namespace App\Models;

use App\Models\GarageSale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarageLove extends Model
{
    use HasFactory;
    protected $table = 'garage_loves';

    protected $fillable = ['garage_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function garage()
    {
        return $this->belongsTo(GarageSale::class, 'garage_id');
    }
}
