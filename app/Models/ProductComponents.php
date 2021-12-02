<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComponents extends Model
{
    protected $table = 'product_components';
    protected $fillable = [
        'id' , 'name', 'product_id'
    ];
    public $timestamps = false;
    public function attributes(){
        return $this->hasMany('App/ProductAttributes');
    }

}
