<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Model\Order;
use App\Model\Address;
use App\Model\GoodSku;
use App\Model\OrderGoods;
use DB;


class OrderController extends Controller
{
    public function index(Request $req){
      
        $perPage = max(1, (int)$req->per_page);
        
        $data = Order::with('goods')
                        ->where('member_id',$req->jwt->id)
                        ->orderBy('id','desc')
                        ->paginate($perPage);
        
       
        return ok($data);
    }
    public function insert(Request $req){
        /* 1.表单验证 */

         $validator = Validator::make($req->all(), [
            'address_id' => 'required',
            'goods'=>'required|json',
        ]);
        
        // 如果验证失败返回 json 数据
        if ($validator->fails()) {
            
             return error($validator->errors(), 422);
        }
        $address = Address::find($req->address_id);
        if(!$address){
            return error([
                'address'=> '无效地址'
            ],422);
        }
       /* 2. 验证购物车中商品库存是否够*/

        $goodsInfo = json_decode($req->goods,true);
        $sumPrice = 0;
        //循环购物车的每件商品 检查库存量
        foreach($goodsInfo as $k=>$v){
            
           $skuInfo = GoodSku::select('stock','price','goods_id')->find($v['sku_id']);
           
            if($skuInfo->stock > $v['buy_count']){
                $sumPrice += $skuInfo->price * $v['buy_count'];
                //把这件商品的goods_id 和 price 放到购物车的数组中 后面下订单要用到
                $goodsInfo[$k]['price'] = $skuInfo->price;
                $goodsInfo[$k]['goods_id'] = $skuInfo->goods_id;

            }else{
                return error('库存不足',403);
            }
        }

        /*3.生成订单号 */
        $sn = getOrderSn();
        $data = [
            'sn'=>$sn,
            'name'=>$address->name,
            'tel'=>$address->tal,
            'province'=>$address->province,
            'city'=>$address->city,
            'area'=>$address->country,
            'address'=>$address->address,
            'total_fee'=>$sumPrice,
            'member_id'=>$req->jwt->id,
            'status'=>0,

        ];


        /*4.开始事务 */

        DB::beginTransaction();
        // DB::rollBack();
        // DB::commit();
        

        /* 5.把订单的基本信息保存到 订单表中*/
        $order = Order::create($data);
        if($order){
            /* 6.把购物车中的商品保存到订单商品表中*/
            //循环购物车 插入到订单商品表中
            $carts = [];
            foreach($goodsInfo as $v){
                $carts[] =  new \App\Model\OrderGoods([
                    'sku_id' => $v['sku_id'],
                    'goods_id'=>$v['goods_id'],
                    'price'=> $v['price'],
                    'goods_count'=> $v['buy_count'],

              ]);
             /* 减少相应的商品库存量 */
              if(! GoodSku::where('id',$v['sku_id'])->decrement('stock',$v['buy_count']))
              { 
                DB::rollBack();
                  return error('下单失败',500);
              }
            }
            // $order->goods()   取出订单模型关联的模型
            if(!$order->goods()->saveMany($carts)){
                DB::rollBack();
                return error('下单失败',500);
            }
            else{
                DB::commit();
                return ok($order);
            }
            
        }
        else
        {
            DB::rollBack();
            return error('下单失败',500);
        }
        

        
    }
}
