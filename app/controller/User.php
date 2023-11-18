<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\User as UserModel;
use app\util\Res;
use think\facade\Session;

class User extends BaseController{

    private $result;

    public function __construct(){
        $this->result = new Res();
    }


    function add(Request $request){
        $postData = $request->post();

        $u = UserModel::where("username",$postData['username'])->find();

        if($u){
            return $this->result->error("用户已存在");
        }

        $user = new UserModel([
            "username" => $postData["username"],
            "password"=> md5($postData["password"])
        ]);


        $res = $user->save();

        if(!$res){
            return $this->result->error("添加失败，请稍后重试");
        }
        return $this->result->success("添加用户成功",$res);
    }


    function login(Request $request){
        $username = $request->post("username");
        $password = md5($request->post("password"));

        $u = UserModel::where('username',$username)->where('password',$password)->find();

        if(!$u){
            return $this->result->error("用户名或密码不正确");
        }
        Session::set("user",$u);
        return $this->result->success("登录成功",$u);
    }

    function logout(){
        Session::delete("user");
    }

    function edit(Request $request){
        $postData = $request->post();

        $u = UserModel::where('id',$postData['id']);
        if(!$u){
            return $this->result->error("需要编辑的用户id不存在");
        }

        $postData['password'] = md5($postData['password']);
        $res = $u->update($postData);

        if($res){
            return $this->result->success("编辑成功",$res);
        }
        return $this->result->error("编辑失败");
        
    }

    function page(Request $request){
        $page = $request->param("page",1);
        $pageSize = $request->param("pageSize",10);
        $name = $request->param("name");
        $username = $request->param("username");

        $users = UserModel::where("name","like","%{$name}%")->where("username","like","%{$username}%")->paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);

        return $this->result->success("查询数据成功",$users);

    }

    function getById($id){
        $user = UserModel::where("id",$id)->find();
        if(!$user){
            return $this->result->error("没有查询到对应的用户");
        }
        return $this->result->success("查询成功",$user);
    }




}