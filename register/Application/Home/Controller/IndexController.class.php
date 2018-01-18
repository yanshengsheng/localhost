<?php
namespace Home\Controller;

use Think\Controller;
use Home\Controller\CommonController;
class IndexController extends CommonController
{

    public function index()
    {
        echo session('classname');
        $this->display('index');
    }
    public function welcome()
    {
        $this->assign('classname',session('classname'));
        $this->display('welcome');
    }
    //退出登陆
    public function up_drop()
    {
        $_SESSION = array(); //清除SESSION值.
        if(isset($_COOKIE[session_name('classname')])){  //判断客户端的cookie文件是否存在,存在的话将其设置为过期.
            setcookie(session_name(),'',time()-1,'/');
        }
        session_destroy();  //清除服务器的sesion文件
        $this->display('Login/index');
    }
}