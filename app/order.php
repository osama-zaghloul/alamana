<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['order_number','order_owner','status','created_at','lat','lng','user_id','total'];
}
