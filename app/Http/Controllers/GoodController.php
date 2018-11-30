<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Goods;

class GoodController extends Controller
{
    //
    public function index(Request $req){
        
        if($req->id){
            $id = max(1, (int)$req->id);
            $data = Goods::with('attr','image','sku')
                            ->where('id',$id)
                            ->where('is_on_sale','y')
                            ->first();
            if($data){
                return ok($data);
            }else{
                return error('商品不存在',404);
            }
        }else if($req->ids){
            $data = Goods::with('attr','image','sku')
                            ->whereIn('id',explode(',',$req->ids))
                            ->where('is_on_sale','y')
                            ->get();
             if($data){
                  return ok($data);
             }else{
                  return error('商品不存在',404);
             }
        }
        else{
            $perPage = max(1, (int)$req->per_page);
            $sortBy = ($req->sortby == 'id' || $req->sortby == 'created_at') ? $req->sortby :'id';
            $order = ($req->order == 'asc' || $req->order =='desc') ? $req->order : 'desc';
    
            $data = Goods::with('attr','image','sku')
                            ->where('goods_name','like','%'.$req->keywords.'%')
                            ->where('is_on_sale','y')
                            ->orderBy($sortBy,$order)
                            ->paginate($perPage);
        }
       
        return ok($data);
    }
}
