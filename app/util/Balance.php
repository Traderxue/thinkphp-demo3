<?php
namespace app\util;

use GuzzleHttp\Client;
use think\Request;

class Balance
{

    function getBalance($address)
    {
        $apikey = "3EYAonFCzHGIUQPHTBsm";
        $url = "https://services.tokenview.io/vipapi/usdt/addressdetail/{$address}?apikey={$apikey}";


        $client = new Client(['verify' => false]);

        $res =  $client->get($url)->getBody();

        $data = json_decode($res, true);  // 将 JSON 转换为关联数组

        return $data["data"]["balance"];

    }
}

