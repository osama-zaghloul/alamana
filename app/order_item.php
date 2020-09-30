<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order_item extends Model
{
    protected $table = 'order_items';
    public $timestamps = false;
    protected $fillable = ['order_id','order_item','qty','price','cutting_id','weight_id','skin','packaging','minced','notes','choco','deliverDay'];
}
