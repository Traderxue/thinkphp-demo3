<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Wallet as WalletModel;
use app\model\Coins as CoinsMdoel;
use app\util\Res;

class Wallet extends BaseController
{
    private $result;

    function __construct()
    {
        $this->result = new Res();
    }

    function add(Request $request)
    {
        $postData = $request->post();
        $w = WalletModel::where("type", $postData["type"])->where("u_id", $postData["u_id"])->find();
        if ($w) {
            return $this->result->error("该用户钱包下币种已存在，请更换币种");
        }
        $wallet = new WalletModel([
            "type" => $postData["type"],
            "amount" => $postData["amount"],
            "balance" => $postData["balance"],
            "u_id" => $postData["u_id"]
        ]);

        $res = $wallet->save();

        if ($res) {
            return $this->result->success("添加钱包数据成功", $res);
        }
        return $this->result->error("添加钱包数据失败");
    }


    function getByUserId($u_id)
    {
        $wallet = WalletModel::where("u_id", $u_id)->find();
        if (!$wallet) {
            return $this->result->error("获取数据失败");
        }
        return $this->result->success("获取数据成功", $wallet);
    }

    function deleteBYUserId($u_id)
    {
        $wallet = WalletModel::where("u_id", $u_id)->find();
        if ($wallet->balance > 0) {
            return $this->result->error("用户存在余额大于0禁止删除");
        }
        $res = WalletModel::where("id", $u_id)->delete();

        if (!$res) {
            return $this->result->error("删除失败");
        }
        return $this->result->success("删除成功", $res);
    }

    function edit(Request $request)
    {
        $postData = $request->post();
        $id = $request->post("id");
        $wallet = WalletModel::where("id", $id)->find();

        $res = $wallet->update([
            "type" => $postData["data"],
            "amount" => $postData["amount"],
            "balance" => $postData["balance"],
            "u_id" => $postData["u_id"]
        ]);

        if ($res) {
            return $this->result->success("数据编辑成功", $res);
        }
        return $this->result->error("数据编辑失败");
    }

    function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);
        $name = $request->param("name");
        $type = $request->param("type");

        $list = WalletModel::where("type","like","%{$type}%")->where("u_id","like","%{$name}%")->order("balance desc")->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);

        return $this->result->success("获取数据成功", $list);
    }

    function balance($u_id){
        $balance = WalletModel::where("u_id",$u_id)->where("type",'USDT')->field("balance")->find();
        return $this->result->success("查询育余额成功",$balance);
    }

}
