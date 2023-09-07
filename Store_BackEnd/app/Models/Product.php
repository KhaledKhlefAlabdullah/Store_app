<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
        'category_id',
        'product_name',
        'product_description',
        'quantity',
        'price',
        'product_img'
    ];

    public function orders(){
        return $this->belongsToMany(Orders::class,'order_details','product_id','order_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
