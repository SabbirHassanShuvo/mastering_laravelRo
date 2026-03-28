<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Contact;
use App\Models\GarageArchived;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use App\Models\Pickup;
use App\Models\Report;
use App\Models\Review;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject; 

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasSpatial;
    use SoftDeletes, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    
    protected static function booted()
    {
        static::created(function ($user) {
            $user->profile()->create([
                'user_id' => $user->id
            ]);
        });
    }
    
    protected $fillable = [
        'name',
        'email',
        'role',
        'address',
        'avatar',
        'password',
        'latitude', 'longitude', 'interests'
    ];

    /** Get the identifier that will be stored in the subject claim of the JWT.
     * @return mixed */
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    /** Return a key value array, containing any custom claims to be added to the JWT.
     * @return array */
    public function getJWTCustomClaims()
    {
        return [
        ];
    }

    public static function roles(){
        return [
            'ADMIN' => env('DEFAULT_ADMIN_ROLE', 'admin'),
            'USER' => env('DEFAULT_USER_ROLE', 'user')
        ];
    } 

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'interests' => 'array',   // JSON → array auto convert
            'location' => Point::class,
            'area' => Polygon::class,
        ];
    }


    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function archivedGarages()
    {
        return $this->hasMany(GarageArchived::class);
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function lovedProducts()
    {
        return $this->belongsToMany(Product::class,'product_loves');
    }

    /**
     * Automatically verify user based on:
     * 1. At least 3 successful pickups.
     * 2. No reports.
     * 3. Minimum average rating of 4.
     */
    public function checkVerifyStatus()
    {
        // 1. Successful pickups count >= 3
        $completedPickupsCount = Pickup::where('status', 'completed')
            ->where(function($q) {
                $q->where('requester_id', $this->id)
                  ->orWhere('receiver_id', $this->id);
            })
            ->count();

        // 2. No reports (reported_id is this user)
        $reportsCount = Report::where('reported_id', $this->id)->count();

        // 3. Minimum average rating of 4
        $averageRating = Review::where('reviewee_id', $this->id)->avg('rating');

        $isEligible = ($completedPickupsCount >= 3) && 
                      ($reportsCount === 0) && 
                      ($averageRating !== null && $averageRating >= 4);

        if ($this->is_verified !== $isEligible) {
            $this->update(['is_verified' => $isEligible]);
        }
    }
}
