<?php
namespace app\controller;

use think\Request;
use app\model\Wallet as WalletModel;
use app\util\Res;
use think\Facade\Db;
use app\BaseController;

class Transfer extends BaseController
{
    private $result;

    function __construct()
    {
        $this->result = new Res();
    }

    function transferTo(Request $request)
    {
        $num = (float) $request->post("num");       //转账数量
        $transferId = $request->post("transferId");
        $transferToId = $request->post("transferToId");
        $type = $request->post("type");

        Db::startTrans();
        try {
            $transfer = WalletModel::where("u_id", $transferId)->where("type", $type)->lock(true)->find();
            $transferBalance = (float) $transfer->balance;
            $transferTo = WalletModel::where("u_id", $transferToId)->where("type", $type)->lock(true)->find();
            $transferToBalance = (float) $transferTo->balance;

            if ($transferBalance < $num) {
                return $this->result->error("余额不足，请充值后转账");
            }

            $transfer->save(["balance" => $transferBalance - $num]);
            $transferTo->save(["balance" => $transferToBalance + $num]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return $this->result->error("出错了，请稍后重试");
        }
        return $this->result->success("转账成功", null);
    }

    function transferCoin(Request $request){
        $num = (float) $request->post("num");
        $type = $request->post("type");
        $transferId = $request->post("transferId");
        $transferToId = $request->post("transferToId");

        Db::startTrans();
        try {
            $transfer = WalletModel::where("u_id",$transferId)->where("type",$type)->lock(true)->find();
            $transfer_amount = (float) $transfer->amount;

            $transferTo = WalletModel::where("u_id",$transferToId)->where("type",$type)->lock(true)->find();
            $transferTo_amount = (float) $transferTo->amount;

            if($transfer_amount<$num){
                return $this->result->error("余额不足，请充值后转账");
            }

            $transfer->save(["amount"=>$transfer_amount-$num]);
            $transferTo->save(["amount"=>$transferTo_amount+$num]);

            Db::commit();

        } catch (\Throwable $th) {
            Db::rollback();
            return $this->result->error("出错了，请稍后重试");
        }

        return $this->result->success("转账成功",null);
    }

}