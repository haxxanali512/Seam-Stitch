<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
   protected $table = 'location';
   public $timestamps = false;
   protected $fillable = [
       'id','mobile_number', 'province','city','area'
   ];
}
