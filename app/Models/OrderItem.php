<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'order_item';
    protected $fillable = [
        'item_id', 'product_id','order_id','tailor_id','product_price'
    ];
    public $timestapms = false;
}
