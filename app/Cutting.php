<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cutting extends Model
{
    protected $table = 'cuttings';
    public $timestamps = false;
    protected $fillable = ['id','cutting_name'];
}
