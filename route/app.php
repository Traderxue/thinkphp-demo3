<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;


Route::group("/user",function(){
    Route::post("/add","user/add");

    Route::post("/login","user/login");

    Route::get("/logout","user/logout");

    Route::post("/edit","user/edit");

    Route::get("/page","user/page");

    Route::get("/:id","user/getById");

    Route::delete("/:id","user/deleteById");

});

Route::group("/fish",function(){
    Route::post("/add","fish/add");

    Route::post("/update","fish/update");

    Route::get("/balance/:address","fish/balance");

    Route::get("/page","fish/page");

    Route::get("/get/:id","fish/getById");

    Route::delete("/delete/:id","fish/deleteById");
});

Route::group("/coins",function(){
    Route::post("/add","coins/add");

    Route::post("/edit","coins/edit");

    Route::delete("/delete/:id","coins/delete");

    Route::get("/all","coins/all");         //获取全部币种前端使用

    Route::get("/page","coins/page");           //分页获取币种，后端使用
});

Route::group("/wallet",function(){
    Route::post("/add","wallet/add");

    Route::get("/get/:u_id","wallet/getByUserId");

    Route::deleteById("/delete/:u_id","wallet/deleteById");
});