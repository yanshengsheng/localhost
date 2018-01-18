<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function do_shangchuan(){
        $file=request()->file('photo');//接受图片
        $fileinfo=$file->move(config('upload_path'));//移动图片到（）文件 在config文件中设置图片的位置
        $data=$fileinfo->getSavename();//图片的文件夹及图片名
    }
}
