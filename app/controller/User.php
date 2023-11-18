<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\modle\User as UserModel;
use app\util\Res;

class User extends BaseController{
    public function test(){
        $res = new Res();
        return $res->success("获取数据成功","data");
    }
}