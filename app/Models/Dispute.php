<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $table = 'dispute';

    protected $fillable = [
        'dispute_id', 'product_id', 'order_id','tailor_id'
    ];
    public $timestamps = false;
}
