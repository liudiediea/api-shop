<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $table = 'address';
    public $timestamps = false;
    protected $fillable = ['name','tal','province','city','country','address','is_default','member_id'];
}
