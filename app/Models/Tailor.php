<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tailor extends Model
{
   
    protected $table = 'tailor';
    protected $fillable = [
        'tailor_id' , 'shop_id'
    ];
    public $timestamps = false;
}