<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [
        'id',
    ];
    protected $casts = [
        'room_id'=>'integer',
        'user_id'=>'integer',
        'date'=>'string',
        'time'=>'string',
        'type'=>'string',
        'amount'=>'integer',
        'memo'=>'string'
    ];
}
