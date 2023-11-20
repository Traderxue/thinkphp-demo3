<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\util\Res;
use think\Facade\Filesystem;

class file extends BaseController
{
    private $result;

    function __construct()
    {
        $this->result = new Res();
    }

    function upload(Request $request)
    {

        $files = $request->file("files");


        foreach ($files as $file) {
            $ext = $file->getOriginalExtension();

            $folder = config('filesystem.disks.folder') . '/uploads/' . $ext; //以文件后缀名作为存文件的存放目录

            if (!file_exists($folder))
                mkdir($folder, 0700, TRUE); //如果文件夹不存在，则创建

            $savename = Filesystem::disk("public")->putFile('', $file, 'md5');

            if (!$savename) {
                return $this->result->error("文件上传失败");
            }
            $res = $request->domain() . '/uploads/' . $savename;
            $savename = '' . str_replace("\\", "/", $savename);
            return $this->result->success("文件上传成功", $res);
        }


    }
}