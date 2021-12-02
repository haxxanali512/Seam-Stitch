<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    use HasFactory;
    public $table = 'product_rating';
    public $fillable = ([
        'rating','review','user_id','product_id','images','updated_at','created_at'
    ]);
    public $timestamps = true;
}
