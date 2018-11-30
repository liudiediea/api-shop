<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['jwt'])->group(function () {

    Route::post('order', 'MemberController@order');
    Route::post('addresses', 'AddressController@insert');
    Route::get('address', 'AddressController@index');

    Route::post('orders', 'OrderController@insert');
    Route::get('orders', 'OrderController@index');




});
Route::post('members','MemberController@insert');
Route::post('authentication','MemberController@login');

Route::get('goods','GoodController@index');

Route::get('test',function(){
    return getOrderSn();
});