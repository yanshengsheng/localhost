<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function do_yan(){
        $captcha=input('post.code');
        $yan=captcha_check($captcha);
        if($yan){
            echo "验证码正确";
        }else{
            echo "验证码错误";
        }
    }
}
