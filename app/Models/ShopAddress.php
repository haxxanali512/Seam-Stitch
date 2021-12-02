<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopAddress extends Model
{
   protected $table = 'shop_address';
   public $fillable =  [ 
     'address','country_region','state','area'   
   ];
   public $timestamps = true;
}
