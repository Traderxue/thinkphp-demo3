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
            $transfer = WalletModel::where("id", $transferId)->where("type", $type)->find();
            $transferBalance = (float) $transfer->balance;

            $transferTo = WalletModel::where("id", $transferToId)->where("type", $type)->find();
            $transferToBalance = (float) $transferTo->balance;

            if ($transferBalance < $num) {
                return $this->result->error("余额不足，请充值后转账");
            }

            $transfer->update(["balance" => $transferBalance - $num]);
            $transferTo->update(["balance" => $transferToBalance + $num]);
            Db::commit();
        } catch (\Exception $e) {
            return $this->result->error("出错了，请稍后重试");
        }
        return $this->result->success("转账成功", null);
    }

}