<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurements extends Model
{
    protected $table = 'measurement';
    protected $fillable =  [
        'measurement_id','customer_id', 'neck', 'chest','waist', 'hip','frontwaist','backwaist', 'shoulder','armlength','wrist'
    ];
    public $timestamps = false;
}
