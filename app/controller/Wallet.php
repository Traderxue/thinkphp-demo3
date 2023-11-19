<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Wallet as WalletModel;
use app\model\Coins as CoinsMdoel;
use app\util\Res;

class Wallet extends BaseController{
    private $result;

    function __construct(){
        $this->result = new Res();
    }

    function add(Request $request){
        $postData = $request->post();
        $wallet = new WalletModel([
            "type"=>$postData["type"],
            "amount"=>100,
            "balance"=>1000,
            "u_id"=>3
        ]);

        $res = $wallet->save();

        if($res){
            return $this->result->success("添加钱包数据成功",$res);
        }
        return $this->result->error("添加钱包数据失败");
    }


    function getByUserId($u_id){
        $wallet = WalletModel::where("u_id",$u_id)->find();
        if(!$wallet){
            return $this->result->error("获取数据失败");
        }
        return $this->result->success("获取数据成功",$wallet);
    }

    function deleteBYUserId($u_id){
        $wallet = WalletModel::where("u_id",$u_id)->find();
        if($wallet->balance>0){
            return $this->result->error("用户存在余额大于0禁止删除");
        }
        $res = WalletModel::where("id",$u_id)->delete();

        if(!$res){
            return $this->result->error("删除失败");
        }
        return $this->result->success("删除成功",$res);
    }

}
