<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'phone',
    //     'birthday',
    //     'sex'
    // ];
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthday' => 'string'
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_users', 'user_id', 'room_id');
    }
    public function records()
    {
        return $this->hasMany(Record::class, 'user_id', 'id')
        ->join('users', function ($join) {
            $join->on('users.selected_room_id', '=', 'records.room_id')
                ->whereColumn('users.id', '=', 'records.user_id');
        });
    }
}
