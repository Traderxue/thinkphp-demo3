<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Coins as CoinsModel;
use app\util\Res;

class Coins extends BaseController{
    private $result;

    function __construct(){
        $this->result = new Res();
    }

    function add(Request $request){
        $postData = $request->post();

        $coins = new CoinsModel([
            "type"=>$postData("type"),
            "add_time"=>date("Y-m-d H:i:s")
        ]);

        $res = $coins->save();

        if($res){
            return $this->result->success("添加币种成功",null);
        }
        return $this->result->error("添加币种失败");
    }

    function edit(Request $request){
        $id = $request->post("id");
        $type = $request->post("type");

        $coin = CoinsModel::where("id",$id)->find();
        $coin->update(["type"=>$type]);
        return $this->result->success("修改币种成功",$coin);
    }

    function delete($id){
        $res = CoinsModel::where("id",$id)->delete();
        if($res){
            return $this->result->success("删除成功",null);
        }
        return $this->result->error("删除失败");
    }

    function all(){
        $list = CoinsModel::select();
        return $list;
    }

    function page(Request $request){
        $page = $request->param("page",1);
        $pageSize = $request->param("pageSize",10);
        $type = $request->param("type");

        $list = CoinsModel::where("type",'like',$type)->order('add_time asc')->paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);
        return $this->result->success("获取数据成功",$list);
    }
}
