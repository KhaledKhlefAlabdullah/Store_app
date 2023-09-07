<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Set primary key data type : string
    protected $keyType = 'string';

    // Set incrementing : false
    public $incrementing = false;

    // Set id as primary key
    protected $primaryKey ='id';

    // The object attribute
    protected $fillable = [
        'id',
        'name',
        'email',
        'profile_img',
        'password',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function bankeCard(){
        return $this->hasOne(BankeCard::class);
    }
    public function roles(){
        return $this->belongsToMany(Role::class,'user_roles', 'user_id', 'role_id');
    }
    public function orders(){
        return $this->hasMany(Orders::class);
    }
}
