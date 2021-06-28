<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    
    protected $table = 'shop';
    protected $fillable = [
        'shop_id' , 'name', 'type'
    ];
    public $timestamps = false;
}
