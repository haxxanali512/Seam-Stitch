<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $table = 'product_specs';
    protected $fillable = ['name', 'value','product_id'];
    public $timestamps= false;

}
