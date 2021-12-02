<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CNIC extends Model
{
    protected $table = 'cnic';
    protected $fillable = [
        'id_type','name','cnic','front_image','back_image'
    ];
    public $timestamps = false;
}
