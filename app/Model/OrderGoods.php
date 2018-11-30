<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class OrderGoods extends Model
{
    //
    protected $table = 'order_goods';
    public $timestamps = false;
    protected $fillable = ['sku_id','goods_id','price','goods_count','order_id'];
    //关联order_goods表
    public function ordergoods(){
        return $this->belongsTo('App\Model\Order','order_id');
    }
}
