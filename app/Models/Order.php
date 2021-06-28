<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'order_id','orderdate','customer_id'
    ];
    public $timestamps = false;
}
