<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        if(session('classname'))
        {
            $this->display('welcome');
        }else{
            $this->display('index');
        }
    }
    public function welcome()
    {
        $this->assign('classname',session('classname'));
        $this->display('welcome');
    }
}