<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $table = 'coversation';
    protected $fillable = [
        'sender_id','reciever_id','last_message'
    ];
    public $timestamps = true;

}
