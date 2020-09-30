<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;

class member extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    protected $table = 'members';
    protected $fillable = [
        'name', 'email','address','phone','password','user_hash','firebase_token','forgetcode','remember_token','suspensed'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}

