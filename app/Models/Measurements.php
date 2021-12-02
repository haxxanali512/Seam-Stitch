<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurements extends Model
{
    protected $table = 'measurement';
    protected $fillable =  [
        'id','user_id', 'type', 'name','shoulder', 'arms','pantslength','shirtlength', 'chest','stomach','waist','images'
    ];
    public $timestamps = true;
}
