<?php
namespace Home\Controller;

use Think\Controller;
use Home\Controller\CommonController;
class IndexController extends CommonController
{

    public function index()
    {
        echo session('classname');
    }
    public function welcome()
    {
        $this->assign('classname',session('classname'));
        $this->display('welcome');
    }
}