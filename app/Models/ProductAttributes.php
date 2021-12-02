<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    protected $table = 'product_attributes';
    public $fillable = [
        'name','photo','price','component_id'
    ];
    public $timestamps = false;
}
