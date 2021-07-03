<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     protected $table = 'product';
    protected $fillable = [
        'product_id','name', 'description','type','catalogurl','sub_category_id','quantity'
    ];
    public function products(){
        return $this->hasManyThrough(Category::class, Subcategory::class,'category_id','sub_category_id','product_id');
    }
   
    public $timestamps = false;
}
