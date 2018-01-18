<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller
{

    public function _initialize()
    {
        if($_COOKIE['classname']!='')
        {
            session('classname',$_COOKIE['classname']);
        }
        if(session('classname')=='')
        {
            $this->display('Login/index');
        }
    }
}