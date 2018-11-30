<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodSku extends Model
{
    protected $table = 'goods_sku';
    public $timestamps = false;
    //属性表
    public function goods(){
        return $this->belongsTo('App\Model\Goods','goods_id');
    }
}
