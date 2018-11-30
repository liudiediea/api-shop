<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Model\Member;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;


class MemberController extends Controller
{
    public function order(Request $req){
        echo $req->jwt->id;
    }
    public function insert(Request $req){
        // 自己创建验证器
        $validator = Validator::make($req->all(), [
            'username' => 'required|max:10|unique:members',
            'password'=>'required|max:18|confirmed',
        ]);
        
        // 如果验证失败返回 json 数据
        if ($validator->fails()) {
        
             return error($validator->errors(), 422);
        }
       
        //插入数据库
        $member = Member::create([
            'username'=>$req->username,
            'password'=>bcrypt($req->password),
        ]); 
        return ok($member);
    }
    //登陆验证
    public function login(Request $req){
          // 自己创建验证器
          $validator = Validator::make($req->all(), [
            'username' => 'required|max:10',
            'password'=>'required|max:18',
        ]);
        
        // 如果验证失败返回 json 数据
        if ($validator->fails()) {
        
             return error($validator->errors(), 422);
        }

        $member = Member::select('id','password')->where('username',$req->username)->first();
        if($member){
       
            //判断密码
            if(Hash::check($req->password, $member->password))
            {
                //把用户信息保存到令牌中 把令牌发送到前端
                $now = time();
                $key = env('JWT_KEY');
                $expire = $now + env('JWT_EXPIRE');
                 // 定义令牌中的数据
                 $data = [
                     'iat' => $now,        // 当前时间
                     'exp' => $expire,     // 过期时间
                     'id' => $member->id,
                 ];
                     // 生成令牌
                 $jwt = JWT::encode($data, $key);
                // 发给前端
                 return ok([
                     'ACCESS_TOKEN' => $jwt,
                 ]);


            }else{
                return error('密码不正确',400);
            }
        }else{
            return error('用户名不存在',404);
        }
    }

}
