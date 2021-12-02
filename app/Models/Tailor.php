<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;




class Tailor extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'tailor';
    protected $fillable = [
        'id' , 'username', 'first_name','last_name','image_path','address','city','postal_code','user_id','tailor_avg_rating','is_allowed'
    ];

    public $timestamps = false;
}
