<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Address;
use Validator;

class AddressController extends Controller
{
    //

    public function index(){
        $data = Address::where('member_id',$req->jwt->id)->get();
        return ok($data);
    }
    public function insert(Request $req){
       // 生成验证器对象
        // 参数一、表单中的数据
        // 参数二、验证规则
        $validator = Validator::make($req->all(), [
            'name'=>'required',
            'tal'=>'required|regex:/^1[34578][0-9]{9}$/',
            'province'=>'required',
            'city'=>'required',
            'country'=>'required',
            'address'=>'required',
            'is_default'=>'required|min:0|max:1',
        ]);

        // 如果失败
        if($validator->fails())
        {
            // 获取错误信息
            $errors = $validator->errors();
            // 返回 JSON 对象以及 422 的状态码
            return error($errors, 422);
        }
        //接收表单中的数据
        $data = $req->all();
        //在中间件中解析令牌 并且把令牌保存到 Request 对象中 的jwt属性上
        $data['member_id'] = $req->jwt->id;
        //插入数据库
        $address = Address::create($data);

        return ok($address);
       
       
    }
}
