<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
   
    protected $table = 'review';
    protected $fillable = [
        'review_id' , 'commentsdescription', 'customer_id' , 'product_id'
    ];
    public $timestamps = false;
}
