<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = ['sn','name','tel','province','city','area','address','total_fee','member_id','status'];


    public function goods(){
        return $this->hasMany('App\Model\OrderGoods','order_id');
    }
}
