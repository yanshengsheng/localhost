<?php
namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function index()
    {
        if($_COOKIE['classname']!='')
        {
            session('classname',$_COOKIE['classname']);
        }
        if(session('classname')=='')
        {
            $this->display('index');
        }else{
            $this->redirect('Index/welcome');
        }
    }
    public function do_login()
    {
        $classname=I('post.classname');
        $password=md5(md5(I('post.password')).'login');
        $info=M('iclass')->field('id,classname,password')->where('classname="'.$classname.'"')->find();
        if($info)
        {
            if($info['password']==$password)
            {
                session('classname',$classname);
                session('id',$info['id']);
                if(I('post.ischecks'))
                {
                    cookie('classname',$classname,864000);
                }
                $result=[
                    'msg'=>'登录成功',
                    'status'=>1
                ];
                $this->ajaxReturn($result,'JSON');
            }else{
                $result=[
                    'msg'=>'密码错误',
                    'status'=>0
                ];
                $this->ajaxReturn($result,'JSON');
            }
        }else{
            $result=[
                'msg'=>'用户名不存在',
                'status'=>0
            ];
            $this->ajaxReturn($result,'JSON');
        }
    }
}