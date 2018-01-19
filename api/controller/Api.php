<?php
 namespace app\api\controller;

 class Api
 {
     public function getopenid()
     {
         $id=input('id');
         $code=input('code');
         $url='https://api.weixin.qq.com/sns/jscode2session?appid=wx23e24fb2d76021df&secret=a4d36c1d0871e224a133b98b98755a9c&js_code='.$code.'&grant_type=authorization_code';
         //初始化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($ch),true) ;
        curl_close($ch);
        $data['openid']=$output['openid'];
        if($id==1)
        {
            $info=db('wx_teacher')->insert($data);
        }else{
            $info=db('wx_users')->insert($data);
        }
        if($info)
        {
            $result='succ';
        }else{
            $result='fail';
        }
        return json($result);
     }
     public function getopenids()
     {
         $code=input('code');
         $url='https://api.weixin.qq.com/sns/jscode2session?appid=wx23e24fb2d76021df&secret=a4d36c1d0871e224a133b98b98755a9c&js_code='.$code.'&grant_type=authorization_code';
         //初始化
         $ch = curl_init();
         curl_setopt($ch,CURLOPT_URL,$url);
         curl_setopt($ch,CURLOPT_HEADER,0);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
         $res = curl_exec($ch);
         curl_close($ch);
         return $res;
     }
     public function choserule()
     {
         $info=db('wx_choose')->find();
         return json($info);
     }

     public function rules()
     {
         $code=input('code');
         $url='https://api.weixin.qq.com/sns/jscode2session?appid=wx23e24fb2d76021df&secret=a4d36c1d0871e224a133b98b98755a9c&js_code='.$code.'&grant_type=authorization_code';
         //初始化
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL,$url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $output = json_decode(curl_exec($ch),true) ;
         curl_close($ch);
         $info=db('wx_teacher')->where('openid="'.$output['openid'].'"')->find();
         if($info)
         {
             $result=[
                 'flags'=>1
             ];
         }else{
             $info=db('wx_users')->where('openid="'.$output['openid'].'"')->find();
             if($info)
             {
                 $result=[
                     'flags'=>2
                 ];
             }else{
                 $result=[
                     'flags'=>0
                 ];
             }
         }
         return json($result);
     }
     //教师个人信息
     public function teacher()
     {
         $openid=input('openid');
         $result=db('wx_teacher')->where('openid="'.$openid.'"')->find();
         return json($result);
     }
     public function refer()
     {
         $data=input('datas');
         $openid=input('openid');
         $datas=json_decode($data,true);
         $result=db('wx_teacher')->where('openid="'.$openid.'"')->update($datas);
         return json($result);
     }
     //学生个人信息
     public function student()
     {
         $openid=input('openid');
         $result=db('wx_users')->where('openid="'.$openid.'"')->find();
         return json($result);
     }
     //学生个人信息更改
     public function students()
     {
         $openid=input('openid');
         $data=input('data');
         $datas=json_decode($data,true);
         $result=db('wx_users')->where('openid="'.$openid.'"')->update($datas);
         return json($result);
     }
     //学生图片上传
     public function choosephoto()
     {
         $data=$_FILES['photo'];
         $openid=input('openid');
         $path=ROOT_PATH."./uploads/student";
         !is_dir($path)?mkdir($path):'';
         $info=move_uploaded_file($_FILES['photo']['tmp_name'],ROOT_PATH.'./uploads/student/"'.$openid.'"'.'.jpg');
         if($info!==false)
         {
             return 'succ';
         }
     }
     //老师图像上传
     public function choosephotos()
     {
         $data=$_FILES['photo'];
         $openid=input('openid');
         $path=ROOT_PATH."./uploads/teacher";
         !is_dir($path)?mkdir($path):'';
         $info=move_uploaded_file($_FILES['photo']['tmp_name'],ROOT_PATH.'./uploads/teacher/"'.$openid.'"'.'.jpg');
         if($info!==false)
         {
             return 'succ';
         }
     }
     //学生列表
     public function stulist()
     {
         $openid=input('openid');
         $page=input('page');
         $nums=6;
         $teacher=db('wx_teacher')->where('openid="'.$openid.'"')->find();
         $tid=$teacher['id'];
         $stu=db('wx_iclass')->where('tid="'.$tid.'"')->find();
         $iclass=$stu['id'];
         $info=db('wx_users')->where('iclass="'.$iclass.'"')->select();
         $overall=ceil(count($info)/$nums);
         if($page>=1){
             if($overall<$page){
                 $data=[
                     'msg'=>1
                 ];
                 return json($data);
             }else{
                 $info2=db('wx_users')->page($page,$nums)->where("iclass='".$iclass."'")->select();
                 return json($info2);
             }
         }else {
             $data = [
                 'msg' => 0
             ];
             return json($data);
         }
     }
     public function optstudent()
     {
         $id = input('id');
         $info = db('wx_users')->where('id="' . $id . '"')->find();
         return json($info);
     }
     //请假
    public function stuleave()
    {
        $openid=input('openid');
        $info=db('wx_users')->where('openid="'.$openid.'"')->find();
        $infos=db('wx_leave')->where('sid="'.$info['id'].'"')->find();
        return json($infos);
    }
    //请假提交
    public function subleave()
    {
        $openid=input('openid');
        $data=input('data');
        //return json($data);
        $datas=json_decode($data,true);
        $info=db('wx_users')->where('openid="'.$openid.'"')->find();
        $datas['opplytime']=time();
        $datas['sid']=$info['id'];
        $infos=db('wx_leave')->where('sid="'.$info['id'].'"')->update($datas);
        return json($infos);
    }
     //回家方式提交
     public function subbackhome()
     {
         $openid=input('openid');
         $data=input('data');
         $datas=json_decode($data,true);
         $datas['weeks']=date('W');
         $info=db('wx_users')->where('openid="'.$openid.'"')->find();
         $datas['opplytime']=time();
         $datas['sid']=$info['id'];
         $infos=db('wx_backhome')->where('sid="'.$info['id'].'"')->insert($datas);
         return json($infos);
     }
     //撤销
    public function repeal()
    {
        $openid=input('openid');
        $date=date('W');
        $sid=db('wx_users')->where('openid="'.$openid.'"')->value('id');
        $info=db('wx_backhome')->where('sid="'.$sid.'" and weeks="'.$date.'"')->delete();
        return $info;
    }
    //判断回家方式
     public function subfalse()
     {
         $openid=input('openid');
         $date=date('W');
        // return json($date);
         $sid=db('wx_users')->where('openid="'.$openid.'"')->value('id');
         $gohome=db('wx_backhome')->where('sid="'.$sid.'" and weeks="'.$date.'"')->find();
         if($gohome>0)
         {
             $result=[
                 'flag'=>1
             ];
         }else{
             $result=[
               'flag'=>0
             ];
         }
         return json($result);
     }
     //获取学生家庭住址
     public function address()
     {
         $openid=input('openid');
         $result=db('wx_users')->where('openid="'.$openid.'"')->value('adress');
         return json($result);
     }
     //确认到家
     public function verify()
     {
         $openid=input('openid');
         $data=input('data');
         $datas=json_decode($data,true);
         $datas['contime']=time();
         $datas['verify']=1;
         $sid=db('wx_users')->where('openid="'.$openid.'"')->value('id');
         $datas['sid']=$sid;
         $info=db('wx_conhome')->where('sid="'.$sid.'"')->insert($datas);
         return json($info);
     }
     //请假列表
     public function leavelist()
     {
         $openid=input('openid');
         $page=input('page');
         $num=1;
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_leave d','d.sid=c.id')
             ->where('a.openid="'.$openid.'"')
             ->select();
         $nums=ceil(count($info)/$num);
         if($page>0)
         {
             if($nums<$page)
             {
                 $result=[
                     'flag'=>1
                 ];
             }else{
                 $info=db('wx_teacher')->alias('a')
                     ->join('wx_iclass b','a.id=b.tid')
                     ->join('wx_users c','c.iclass=b.id')
                     ->join('wx_leave d','d.sid=c.id')
                     ->page($page,$num)
                     ->where('a.openid="'.$openid.'"')
                     ->select();
                 foreach($info as &$v)
                 {
                     $v['opplytime']=date('Y-m-d H:i:s',$v['opplytime']);
                 }
                 return json($info);
             }
         }else{
             $result=[
                 'flag'=>0
             ];
         }
         return json($result);
     }
     //请假详细信息
     public function leaveminute()
     {
         $openid=input('openid');
         $id=input('id');
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_leave d','d.sid=c.id')
             ->where('a.openid="'.$openid.'" and d.id="'.$id.'"')
             ->find();
         return json($info);
     }
     //本班回家统计列表
     public function statistics()
     {
         $openid=input('openid');
         //return json($openid);
         $page=input('page');
         $num=1;
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_backhome d','d.sid=c.id')
             ->where('a.openid="'.$openid.'"')
             ->select();
         $nums=ceil(count($info)/$num);
         if($page>0)
         {
             if($nums<$page)
             {
                 $result=[
                     'flag'=>1,
                 ];
             }else{
                 $info=db('wx_teacher')->alias('a')
                     ->join('wx_iclass b','a.id=b.tid')
                     ->join('wx_users c','c.iclass=b.id')
                     ->join('wx_backhome d','d.sid=c.id')
                     ->page($page,$num)
                     ->where('a.openid="'.$openid.'"')
                     ->select();
                 return json($info);
             }
         }else{
             $result=[
                 'flag'=>0
             ];
         }
         return json($result);
     }
     //全部回家统计
     public function complete()
     {
         $page=input('page');
         $num=1;
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_backhome d','d.sid=c.id')
             ->select();
         $nums=ceil(count($info)/$num);//总共多少页
         if($page>0)
         {
             if($nums<$page)
             {
                 $result=[
                     'flag'=>1
                 ];
             }else{
                 $info=db('wx_teacher')->alias('a')
                     ->join('wx_iclass b','a.id=b.tid')
                     ->join('wx_users c','c.iclass=b.id')
                     ->join('wx_backhome d','d.sid=c.id')
                     ->page($page,$num)
                     ->select();
                 return json($info);
             }
         }else{
             $result=[
                 'flag'=>0
             ];
         }
         return json($result);
     }
     //回家确认表
     public function homelist()
     {
         $openid=input('openid');
         $num=1;
         $page=input('page');
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_conhome d','d.sid=c.id')
             ->where('a.openid="'.$openid.'"')
             ->select();
         $nums=ceil(count($info)/$num);
         if($page>0)
         {
             if($nums<$page)
             {
                 $result=[
                     'flag'=>1
                 ];
             }else{
                 $info=db('wx_teacher')->alias('a')
                     ->join('wx_iclass b','a.id=b.tid')
                     ->join('wx_users c','c.iclass=b.id')
                     ->join('wx_conhome d','d.sid=c.id')
                     ->page($page,$num)
                     ->where('a.openid="'.$openid.'"')
                     ->select();
                 foreach($info as &$v)
                 {
                     $v['contime']=date('Y-m-d H:i:s',$v['contime']);
                 }
                 return json($info);
             }
         }else{
             $result=[
                 'flag'=>0
             ];
         }
         return json($result);
     }
     //回家确认列表详细信息
     public function homelistminute()
     {
         $openid=input('openid');
         //return json($openid);
         $id=input('id');
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_conhome d','d.sid=c.id')
             ->where('a.openid="'.$openid.'" and d.id="'.$id.'"')
             ->find();
             $info['contime']=date('Y-m-d H:i:s',$info['contime']);
         return json($info);
     }
     //本班留校名单
     public function stayschool()
     {
         $openid=input('openid');
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_backhome d','d.sid=c.id','left')
             ->field('c.id,username')
             ->where('a.openid="'.$openid.'" and d.sid is null')
             ->select();
         return json($info);
     }
     //全部留校名单
     public function completes()
     {
         $page=input('page');
         $num=6;
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_backhome d','d.sid=c.id','left')
             ->field('b.classname,c.username')
             ->where('d.sid is null')
             ->select();
         $nums=ceil(count($info)/$num);
         if($page>0)
         {
             if($nums<$page)
             {
                 $result=[
                     'flag'=>1
                 ];
             }else{
                 $info=db('wx_teacher')->alias('a')
                     ->join('wx_iclass b','a.id=b.tid')
                     ->join('wx_users c','c.iclass=b.id')
                     ->join('wx_backhome d','d.sid=c.id','left')
                     ->field('c.id,b.classname,c.username')
                     ->page($page,$num)
                     ->where('d.sid is null')
                     ->select();
                 return json($info);
             }
         }else{
             $result=[
                 'flag'=>0
             ];
         }
         return json($result);
     }
     //获取老师手机号
     public function te_telphone()
     {
         $openid=input('openid');
         //return json($openid);
         $info=db('wx_users')->alias('a')
             ->join('wx_iclass b','a.iclass=b.id')
             ->join('wx_teacher c','c.id=b.tid')
             ->field('c.telphone')
             ->where('a.openid="'.$openid.'"')
             ->select();
         return json($info);
     }
     //households 住户
     public function households()
     {
         //注：应该查询的表为dorm宿舍表，介于数据直接存入的users表中  故直接查users表
         //楼号louid
         $louid=input('louid');
         // return $louid;
         $where['dorm_id'] = array('like',$louid.'%');
         $info=db('wx_users')->where($where)->field('dorm_id')->group('dorm_id')->select();
         foreach ($info as  $k=>$v) {
             // print_r($v);
             foreach ($v as $value) {
                 $info[$k][]=$value;
             }
             $info[$k]['zhi']=db('wx_users')->where("dorm_id='".$v['dorm_id']."'")->count();
             $info[$k]['ban']=db('wx_users')
                 ->alias('u')
                 ->join('wx_iclass c','u.iclass=c.id')
                 ->where("dorm_id='".$v['dorm_id']."'")
                 ->value('classname');
         }
         // print_r($info);exit;
         return json($info);
     }

     //详细宿舍统计dz_20
     public function classInfo()
     {
         $cid=input('cid');
         $info=db('wx_users')->where("dorm_id='".$cid."'")->field('username,bed')->select();
         return json($info);
     }
     //本周
     public function dates()
     {
         $date=date('W');
         return json($date);
     }
     //管理员请假列表
     public function leaveslist()
     {
         $page=input('page');
         $num=1;
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_leave d','d.sid=c.id')
             ->select();
         $nums=ceil(count($info)/$num);
         if($nums<$page)
         {
             $result=[
                 'flag'=>1
             ];
         }else{
             $info=db('wx_teacher')->alias('a')
                 ->join('wx_iclass b','a.id=b.tid')
                 ->join('wx_users c','c.iclass=b.id')
                 ->join('wx_leave d','d.sid=c.id')
                 ->page($page,1)
                 ->select();
             return json($info);
         }
         return json($result);
     }
     //管理员周末回家统计
     public function admin_gohome()
     {
         $page=input('page');
         $num=1;
         $info = db('wx_teacher')->alias('a')
             ->join('wx_iclass b', 'a.id=b.tid')
             ->join('wx_users c', 'c.iclass=b.id')
             ->join('wx_backhome d', 'd.sid=c.id')
             ->group('b.classname')
             ->select();
         $nums=ceil(count($info)/$num);
         if($page>0)
         {
             if($nums<$page)
             {
                 $result=[
                     'flag'=>1
                 ];
             }else{
                 $info = db('wx_teacher')->alias('a')
                     ->join('wx_iclass b', 'a.id=b.tid')
                     ->join('wx_users c', 'c.iclass=b.id')
                     ->join('wx_backhome d', 'd.sid=c.id')
                     ->page($page,$num)
                     ->group('b.classname')
                     ->select();
                 return json($info);
             }
         }else{
             $result=[
                 'flag'=>0
             ];
         }
        return json($result);
     }
     //管理员周末回家统计(班级)
     public function message()
     {
         $page=input('page');
         $num=1;
         $id=input('id');
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','b.tid=a.id')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_backhome d','d.sid=c.id')
             ->where('b.classname="'.$id.'"')
             ->select();
         $nums=ceil(count($info)/$num);
         if($page>0)
         {
             if($nums<$page)
             {
                 $result=[
                     'flag'=>1
                 ];
             }else{
                 $info=db('wx_teacher')->alias('a')
                     ->join('wx_iclass b','b.tid=a.id')
                     ->join('wx_users c','c.iclass=b.id')
                     ->join('wx_backhome d','d.sid=c.id')
                     ->page($page,$num)
                     ->where('b.classname="'.$id.'"')
                     ->select();
                 return json($info);
             }
         }else{
             $result=[
                 'flag'=>0
             ];
         }
         return json($result);
     }
     //管理员周末留校名单
     public function admin_stayschool()
     {
         $page=input('page');
         $num=1;
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','b.tid=a.id')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_backhome d','d.sid=c.id','left')
             ->field('c.id,b.classname')
             ->where('d.sid is null')
             ->group('b.classname')
             ->select();
         $nums=ceil(count($info)/$num);
         if($page>0)
         {
             if($nums<$page)
             {
                 $result=[
                     'flag'=>1,
                 ];
             }else{
                 $info=db('wx_teacher')->alias('a')
                     ->join('wx_iclass b','b.tid=a.id')
                     ->join('wx_users c','c.iclass=b.id')
                     ->join('wx_backhome d','d.sid=c.id','left')
                     ->field('c.id,b.classname')
                     ->page($page,$num)
                     ->where('d.sid is null')
                     ->group('b.classname')
                     ->select();
                 return json($info);
             }
         }else{
             $result=[
                 'flag'=>0
             ];
         }
         return json($result);
 }
     //管理员周末留校名单(班级)
     public function messages()
     {
         $id=input('id');
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','b.tid=a.id')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_backhome d','d.sid=c.id','left')
             ->field('c.id,b.classname,c.username,c.sex')
             ->where('b.classname="'.$id.'" and d.sid is null')
             ->select();
         return json($info);
     }
     //管理员回家确认信息
     public function affirm()
     {
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','b.tid=a.id')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_conhome d','d.sid=c.id')
             ->group('b.classname')
             ->select();
         return json($info);
     }
     //管理员回家确认信息（班级）
     public function affirms()
     {
         $id=input('id');
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','b.tid=a.id')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_conhome d','d.sid=c.id')
             ->where('b.classname="'.$id.'"')
             ->select();
         foreach($info as &$v)
         {
             $v['tripmode']=db('wx_backhome')->where('sid="'.$v['sid'].'"')->value('tripmode');
             $v['contime']=date('Y-m-d H:i:s',$v['contime']);
         }
         return json($info);
     }
     //今年历史记录列表
     public function weekHistory(){
         $arr = [];
         $arr['week'] = date('W',time()).'周';
         $arr[0] = db('wx_backhome')->field('weeks as weekNum')->group('weeks')->order('weeks desc')->select();
         foreach ($arr[0] as &$v){
             $v['weekNum'] .= '周';
         }
         return json($arr);
     }
     //请假回家确认
     public function leavehome()
     {
         $openid=input('openid');
         $info=db('wx_users')->where('openid="'.$openid.'"')->value('adress');
         return json($info);
     }
     //请假回家确认提交
     public function leavesub()
     {
         $openid=input('openid');
         $data=input('data');
         $datas=json_decode($data,true);
         $datas['leavetime']=time();
         $datas['verify']=1;
         $info=db('wx_users')->where('openid="'.$openid.'"')->find();
         $datas['sid']=$info['id'];
         $result=db('wx_leavehome')->insert($datas);
         return json($result);
     }
     //请假确认列表(本班)
     public function leaveverify()
     {
         $openid=input('openid');
         $num=1;
         $page=input('page');
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_leavehome d','d.sid=c.id')
             ->where('a.openid="'.$openid.'"')
             ->select();
         $nums=ceil(count($info)/$num);
         if($page>0)
         {
             if($nums<$page)
             {
                 $result=[
                     'flag'=>1
                 ];
             }else{
                 $info=db('wx_teacher')->alias('a')
                     ->join('wx_iclass b','a.id=b.tid')
                     ->join('wx_users c','c.iclass=b.id')
                     ->join('wx_leavehome d','d.sid=c.id')
                     ->page($page,$num)
                     ->where('a.openid="'.$openid.'"')
                     ->select();
                 foreach($info as &$v)
                 {
                     $v['leavetime']=date('Y-m-d H:i:s',$v['leavetime']);
                 }
                 return json($info);
             }
         }else{
             $result=[
                 'flag'=>0
             ];
         }
         return json($result);
     }
     //请假回家确认查看
     public function leavelistminute()
     {
         $openid=input('openid');
         //return json($openid);
         $id=input('id');
         $info=db('wx_teacher')->alias('a')
             ->join('wx_iclass b','a.id=b.tid')
             ->join('wx_users c','c.iclass=b.id')
             ->join('wx_leavehome d','d.sid=c.id')
             ->where('a.openid="'.$openid.'" and d.id="'.$id.'"')
             ->find();
         $info['leavetime']=date('Y-m-d H:i:s',$info['leavetime']);
         return json($info);
     }
 }