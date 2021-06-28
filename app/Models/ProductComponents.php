<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComponents extends Model
{
    protected $table = 'product_components';
    protected $fillable = [
        'components_id' , 'name', 'catalog_url' , 'product_id'
    ];
    public $timestamps = false;
    
}
