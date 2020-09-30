<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transfer extends Model
{
     protected $table = 'transfers';
     public $timestamps = false;
     protected $fillable = ['id','bill_number','name','phone','image','bank_name'];
    
}
