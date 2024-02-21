<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomUser extends Model
{
    use HasFactory;
    // protected $table = 'room_users';
    protected $guarded = [
        'id',
    ];
    protected $casts = [
        'room_id'=>'integer',
        'user_id'=>'integer',
    ];
}
