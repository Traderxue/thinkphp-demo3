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

    Route::get("/getbyid/:id","user/getById");

});