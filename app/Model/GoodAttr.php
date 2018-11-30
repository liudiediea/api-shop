<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodAttr extends Model
{
    //
    protected $table = 'goods_attribute';
    public $timestamps = false;
    //属性表
    public function goods(){
        return $this->belongsTo('App\Model\Goods','goods_id');
    }
}
