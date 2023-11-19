<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Coins as CoinsModel;
use app\model\Wallet as WalletModel;
use app\util\Res;

class Coins extends BaseController
{
    private $result;

    function __construct()
    {
        $this->result = new Res();
    }

    function add(Request $request)
    {
        $postData = $request->post();

        $coin = CoinsModel::where("coin_type", $postData["coin_type"])->find();

        if ($coin) {
            return $this->result->error("该币种已存在请添加其他币种");
        }

        $coins = new CoinsModel([
            "coin_type" => $postData["coin_type"],
            "add_time" => date("Y-m-d H:i:s")
        ]);

        $res = $coins->save();

        if ($res) {
            return $this->result->success("添加币种成功", $res);
        }
        return $this->result->error("添加币种失败");
    }

    function edit(Request $request)
    {
        $id = $request->post("id");
        $type = $request->post("coin_type");

        $coin = CoinsModel::where("id", $id)->find();
        $coin->update(["coin_type" => $type]);
        return $this->result->success("修改币种成功", $coin);
    }

    function delete($id)
    {
        $type = CoinsModel::where("id", $id)->field("coin_type")->find();

        echo $type;

        $wallet = WalletModel::where("type", $type["coin_type"])->find();

        if ($wallet) {
            return $this->result->error("该币种正在被使用，禁止删除");
        }

        $res = CoinsModel::where("id", $id)->delete();
        if ($res) {
            return $this->result->success("删除成功", null);
        }
        return $this->result->error("删除失败");
    }

    function all()
    {
        $list = CoinsModel::select();
        return $list;
    }

    function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);
        $type = $request->param("coin_type");

        $list = CoinsModel::where("coin_type", 'like', "%{$type}%")->order('add_time asc')->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);
        return $this->result->success("获取数据成功", $list);
    }
}
