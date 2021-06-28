<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = [
        'product_id','name', 'description','type','catalogurl','category_id','inventory_id'
    ];
    public $timestamps = false;
}
