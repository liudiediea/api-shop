<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
        // 对应的表单
        protected $table = 'members';
        // 表中是否有两个时间字段（created_at和updated_at)
        public $timestamps = true;
        // 设置允许填充的字段
        protected $fillable = ['username','password'];
         // 需要隐藏的字段（不会发给前端的字段）
         protected $hidden = ['password','updated_at','created_at'];
  

}
