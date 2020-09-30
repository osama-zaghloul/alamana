<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class weight extends Model
{
    protected $table = 'weights';
    public $timestamps = false;
    protected $fillable = ['weight_name','item_id','price'];
}
