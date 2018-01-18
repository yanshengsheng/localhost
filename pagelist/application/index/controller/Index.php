<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {

        $page=input('page')?input('page'):1;
        $db=db('users');
        $pa=$db->paginate(config('paginate.list_rows'));
        $sum=$db->field('id')->count();
        $tiao=6;
        $pages=ceil($sum/$tiao);
        // if($page>$pages){
        //     $page=$pages;
        // }
        // if($page<1){
        //     $page=1;
        // }
        $list=$db->field("id,username,famtel,sex")->page($page,$tiao)->select();
        $this->assign('page',$page);
        $this->assign('list',$list);
        $this->assign('pages',$pages);
        return $this->fetch();
    }
}
