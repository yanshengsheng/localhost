<?php
    namespace app\index\controller;

    use think\Controller;
    use think\Cache;
    class Index extends Controller
    {
        //三级导航
        public function index()
        {
            $db=db('region');
            $region=$db->where('parent_id=1')->select();
            $this->assign('region',$region);
            return $this->fetch();
        }
        //三级导航
        public function city()
        {
            $rid=input('post.rid');
            $region=db('region')->where('parent_id='.$rid)->select();
            return json($region);
        }

        public function leave()
        {
            $db=db('leave1');
            $info=$db->field('class')->select();
            $this->assign('info',$info);
            return $this->fetch();
        }
        public function addleave()
        {
            $data=input('post.');
            $db=db('leave');
            $info=$db->insert($data);
            if($info)
            {
                $this->success('succ');
            }else{
                $this->error('fail');
            }
        }
        public function uname()
        {
            $names=input('post.names');
            if($names)
            {
                $data=[
                    'status'=>1,
                    'msg'=>'合格'
                ];
            }else{
                $data=[
                    'status'=>2,
                    'msg'=>'不合格'
                ];
            }
            return json($data);
        }
        public function leaves()
        {
            return $this->fetch();
        }
        //memcached练习
        public function memcached()
        {
          //  phpinfo();
            Cache::set('name','ergou');
            dump(Cache::get('name'));
        }
    }
?>