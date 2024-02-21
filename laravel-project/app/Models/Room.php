<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'name',
    //     'start',
    //     'end',
    //     'penalty',
    //     'punishment_game_performer',
    //     'punishment_game_target',
    //     'punishment_game_action',
    // ]; gurded以外はfillableになる
    protected $guarded = [
        'id',
    ];
    protected $casts = [
        'room_account' => 'string',
        'name' => 'string',
        'start' => 'string',
        'end' => 'string',
        'penalty' => 'string',
        'punishment_game_performer' => 'string',
        'punishment_game_target' => 'string',
        'punishment_game_action' => 'string',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'room_users', 'room_id', 'user_id');
    }
}
