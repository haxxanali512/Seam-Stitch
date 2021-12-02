<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    public $fillable = [
        'user_id','product_id','components','quantity','shipping_type','final_price','created_at','updated_at'
    ];
    public $timestamps = true;
}
