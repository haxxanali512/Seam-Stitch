<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;
    protected $table = 'complaint';
    protected $fillable = [
        'user_id','tailor_id','complaint_name','description','photo','created_at','updated_at'
    ];
    public $timestamps = true;
}
