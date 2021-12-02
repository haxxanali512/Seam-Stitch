<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'completed_orders';
    protected $fillable = [
        'user_id','tailor_id','product','status','quantity','shipping_type','tracking_number','final_price'
    ];
    public $timestamps = true;
}
