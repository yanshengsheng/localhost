<?php
 namespace app\api\controller;

 use think\Controller;

 class Index extends Controller
 {
     public function index()
     {
         $info=db('wx_choose')->find();
         $this->assign('info',$info);
         return $this->fetch();
     }
     public function up_cloose()
     {
        $teacher=input('post.teacher');
        $student=input('post.student');
        $data['teacher']=isset($teacher)?$teacher:0;
        $data['student']=isset($student)?$student:0;
        $info=db('wx_choose')->where('id=1')->update($data);
        if($info!==false)
        {
            $this->success('设置成功',url('index/index'));
        }
     }
 }