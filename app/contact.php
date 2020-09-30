<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class contact extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','email','message','created_at'];
}
