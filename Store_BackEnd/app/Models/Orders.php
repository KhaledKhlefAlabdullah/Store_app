<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Orders extends Model
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
        'title',
        'user_id',
        'total_price',
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class,'order_details','order_id','product_id');
    }
}
