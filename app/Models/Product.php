<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     protected $table = 'product';
    protected $fillable = [
        'product_id','name', 'description','type','catalogurl','sub_category_id','quantity','price','discount','sold_product','shipping_type','average_rating'
    ];
    public function products(){
        return $this->hasManyThrough(Category::class, Subcategory::class,'category_id','sub_category_id','product_id');
    }
    public function tailor()
    {
        return $this->hasMany('App\Tailor');
    }
    public function component()
    {
        return $this->hasMany('App\ProductComponent');
    }
}
