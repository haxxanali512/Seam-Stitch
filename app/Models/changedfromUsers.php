<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
   
    protected $table = 'user';
    protected $fillable = [
        'user_id' , 'firstname', 'lastname' , 'password' , 'profilepicture' , 'email_address' , 'gender' , 'phone_number' , 'isActive' , 'tupe' , 'location_id'
    ];
    public $timestamps = false;
}
