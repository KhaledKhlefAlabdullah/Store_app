<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankeCard extends Model
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
        'cardNumber',
        'expiryDate',
        'cardHolderName',
        'CVV',
        'user_Id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
