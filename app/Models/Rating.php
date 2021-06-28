<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'rating';
    protected $fillable = [
        'rating_id' , 'rating', 'tailor_id' , 'customer_id'
    ];
    public $timestamps = false;
}
