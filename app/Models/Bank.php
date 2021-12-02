<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'bankdetails';
    public $timestamps = false;
    protected $fillable = [
        'account_title','account_num','bank_name','branch_code','cheque','tailor_id'
    ];
}
