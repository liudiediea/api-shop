<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    //
    protected $table = 'goods';
    public $timestamps = true;

    //属性表
    public function attr(){
        return $this->hasMany('App\Model\GoodAttr','goods_id');
    }
    //图片表
    public function image(){
        return $this->hasMany('App\Model\GoodImage','goods_id');
    }
    //SKU表
    public function sku(){
        return $this->hasMany('App\Model\GoodSku','goods_id');
    }
}
