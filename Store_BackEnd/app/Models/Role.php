<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Set primary key data type : string
    protected $keyType = 'string';

    // Set incrementing : false
    public $incrementing = false;

    // Set id as primary key
    protected $primaryKey ='id';

    // The object attribute
    protected $fillable=[
        'id',
        'role'
    ];
    public function users(){
        return $this->belongsToMany(User::class,'user_roles', 'role_id', 'user_id');
    }
}
