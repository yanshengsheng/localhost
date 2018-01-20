<?php
namespace app\index\controller;

use \think\Controller;

class Index extends Controller
{
    public function index()
    {
        $db=db('vowlist');
        $list=$db->field('id,type,content,address,member,pubtime,typecolor')->order('id desc')->paginate();
        $lists=$list->toArray();
        $vtype=config('vowtype');
        $ctype=config('typecolor');
        foreach($lists['data'] as &$v)
        {
            $v['type']=$vtype[$v['type']];
            $v['typecolor']=$ctype[$v['typecolor']-1];
        }
        $this->assign('list',$lists);
        $page=$list->render();
        $this->assign('page',$page);
        return $this->fetch();
    }
    //新建的index页面
    public function newindex()
    {
//        $list=db('vowlist')->page(1,2)->order('id desc')->select();
//        $vtype=config('vowtype');
//        $ttype=config('typecolor');
//        foreach($list as &$v)
//        {
//            $v['type']=$vtype[$v['type']];
//            $v['typecolor']=$ttype[$v['typecolor']-1];
//        }
//        $this->assign('list',$list);
//        $countpage=ceil(db('vowlist')->count()/2);
//        $this->assign('countpage',$countpage);
//        return $this->fetch();


        $list=db('vowlist')->field('id,type,content,address,member,pubtime,typecolor')->page(1,2)->order('id desc')->select();
        $vtype=config('vowtype');
        $ttype=config('typecolor');
        foreach($list as &$v)
        {
            $v['type']=$vtype[$v['type']];
            $v['typecolor']=$ttype[$v['typecolor']-1];
        }
        $this->assign('list',$list);
        $countpage=ceil(db('vowlist')->count()/2);
        $this->assign('countpage',$countpage);
        return $this->fetch();
    }
    //分页ajax请求
    public function paging()
    {
//        $page=input('post.page');
//        $list=db('vowlist')->page($page,2)->order('id desc')->select();
//        $vtype=config('vowtype');
//        $ttype=config('typecolor');
//        foreach($list as &$v)
//        {
//            $v['type']=$vtype[$v['type']];
//            $v['typecolor']=$ttype[$v['typecolor']-1];
//        }
//        $this->assign('list',$list);
//        return json($list);

        $page=input('post.page');
        $list=db('vowlist')->page($page,2)->order('id desc')->select();
        $vtype=config('vowtype');
        $ttype=config('typecolor');
        foreach($list as &$v)
        {
            $v['type']=$vtype[$v['type']];
            $v['typecolor']=$ttype[$v['typecolor']-1];
            $v['pubtime']=date('Y-m-d H:i:s',$v['pubtime']);
        }
        return json($list);
    }
    //添加页面
    public function add()
    {
        $typecolor=config('typecolor');
        $this->assign('typecolor',$typecolor);
        $vowtype=config('vowtype');
        $this->assign('vowtype',$vowtype);
        return $this->fetch();
    }
//    public function checkenname()
//    {
//        $captcha=input('post.names');
//        if(captcha_check($captcha)){
//            $adata=[
//                'status' => 1,
//                'msg' =>'验证码正确'
//            ];
//        }else{
//            $adata=[
//                'status' => 0,
//                'msg'=> '验证码错误'
//            ];
//        }
//        return json($adata);
//    }
    public function addlist()
    {
//      $data=input('post.');
//      $types=config('vowtype');
//      $vowtype=array_search($data['type'],$types);
//      $data['type']=$vowtype;
//      $data['address']='山东省临沂市兰山区';
//      $data['pubtime']=time();
//      $code=$data['code'];
//      unset($data['code']);
//      if(captcha_check($code))
//      {
//          $stu=db('vowlist');
//          $info=$stu->insert($data);
//          if($info)
//          {
//              $this->success('许愿成功！！','Index/index');
//          }else{
//              $this->error('许愿失败');
//          }
//      }else{
//          $this->error('验证码错误');
//      }



        $data=input('post.');
        $types=config('vowtype');
        $vowtype=array_search($data['type'],$types);
        $data['type']=$vowtype;
        $data['address']='山东省临沂市罗庄区';
        $data['pubtime']=time();
        $code=$data['code'];
        unset($data['code']);
        if(captcha_check($code))
        {
            $stu=db('vowlist');
            $info=$stu->insert($data);
            if($info)
            {
                $result=[
                    'status'=>1,
                    'msg'=>'许愿成功！！'
                ];
            }else{
                $result=[
                    'status'=>2,
                    'msg'=>'许愿失败！！'
                ];
            }
        }else{
            $result=[
                'status'=>3,
                'msg'=>'验证码错误！！'
            ];
        }
        return json($result);
    }
}
