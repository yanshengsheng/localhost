<?php

namespace app\api\controller;

use think\Controller;

define('Token','robots');
class Vipcn extends Controller
{
    private $appid='wx55e8522c83de3147';
    private $secret='7409323f2f725c70a3b0395746425c53';
    public function vipcn(){
        $data=db('vipcn')->select();
        $text='';
        $image='';
        $video='';
        $voice='';
        for($i=0;$i<=count($data)-1;$i++){
            if(!empty($data[$i]['text'])){
                $text[]=$data[$i];
            }
            if(!empty($data[$i]['image'])){
                $image[]=$data[$i];
            }
            if(!empty($data[$i]['voice'])){
                $voice[]=$data[$i];
            }
            if(!empty($data[$i]['video'])){
                $video[]=$data[$i];
            }
        }
        $this->assign('text',$text);
        $this->assign('image',$image);
        $this->assign('voice',$voice);
        $this->assign('video',$video);
        // echo "<pre>";
        // print_r($text);
        // exit;
        return $this->fetch();
    }
    public function robot(){
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        libxml_disable_entity_loader(true);//是设置是否禁止从外部加载XML实体，设为true就是禁止
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $ToUserName=$postObj->ToUserName;
        $FromUserName=$postObj->FromUserName;
        $content=$postObj->Content;
        $MsgType=$postObj->MsgType;
        $nowtime=time();
        switch ($MsgType) {
            // case 'event':
            // $xmltpl="
            // 	<xml>
            // 		<ToUserName><![CDATA[%s]]></ToUserName>
            // 		<FromUserName><![CDATA[%s]]></FromUserName>
            // 		<CreateTime>%s</CreateTime>
            // 		<MsgType><![CDATA[%s]]></MsgType>
            // 		<Event><![CDATA[%s]]></Event>
            // 		<EventKey><![CDATA[]]></EventKey>
            // 		<Ticket><![CDATA[TICKET]]></Ticket>
            // 	</xml>
            // 	";
            // 	if()
            // break;
            case 'text':
                $url="http://www.tuling123.com/openapi/api?key=8137cf2b1b674f8b83bb3d25df3acf8d&info=".$content;
                $replay=file_get_contents($url);
                $recontent=json_decode($replay);
                $xmltpl="
					<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>
		    	";
                $repaycontent=$recontent->text;
                $data['text']=$content;//内容
                $data['time']=$nowtime;//时间
                $data['openid']=$FromUserName;//
                $data['reply']=$repaycontent;//回复内容
                $data=db('vipcn')->insert($data);

                $resultStr = sprintf($xmltpl,$FromUserName,$ToUserName,$nowtime,'text',$repaycontent);
                echo $resultStr;
                break;
			//图片
			case 'image':
                $xmltpl="
					<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Image>
							<MediaId><![CDATA[%s]]></MediaId>
						</Image>
					</xml>
		    	";
                $data['image']=$postObj->PicUrl;//内容
                $data['time']=$nowtime;//时间
                $data['openid']=$FromUserName;//
                $data['reply']='';//回复内容
                $data=db('vipcn')->insert($data);

                $resultStr = sprintf($xmltpl,$FromUserName,$ToUserName,$nowtime,'image',"bF9mSqNG2gIJeJRxAgvIEyrNWCIRxCkSnwdybK-fJk3CuR5FIZyuaNuSl8n2FeXK");
                echo $resultStr;
                break;
			//语音voice
			case 'voice':
                $xmltpl="
					<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Voice>
							<MediaId><![CDATA[%s]]></MediaId>
						</Voice>
					</xml>
		    	";
                $data['voice']=$postObj->MediaId;//内容
                $data['time']=$nowtime;//时间
                $data['openid']=$FromUserName;//
                $data['reply']='';//回复内容
                $data=db('vipcn')->insert($data);
                $resultStr = sprintf($xmltpl,$FromUserName,$ToUserName,$nowtime,'voice',"dfLGUzockD2AFBZ_mtJIA5_PpDG2RNzx18ksJMNbUwAScoIzxuhuZrLLtfjPM8oP");
                echo $resultStr;
                break;

			//视频video
			case 'video':
                $xmltpl="
					<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Video>
						<MediaId><![CDATA[%s]]></MediaId>
						<Title><![CDATA['123']]></Title>
						<Description><![CDATA['456']]></Description>
					</Video>
					</xml>
		    	";
                $data['video']=$postObj->ThumbMediaId;//内容
                $data['time']=$nowtime;//时间
                $data['openid']=$FromUserName;//
                $data['reply']='';//回复内容
                $data=db('vipcn')->insert($data);
                $resultStr = sprintf($xmltpl,$FromUserName,$ToUserName,$nowtime,'video',"mI_n6H1Bsu3MEH_nJypQQGQlzv49aIi9pX0-UbcmbET-rGI8AB5YQtcxyz7DquGl");
                echo $resultStr;
                break;
			//位置location
			case 'location':
                $xmltpl="
					<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
					</xml>
		    	";
                $resultStr = sprintf($xmltpl,$FromUserName,$ToUserName,$nowtime,'text',"这是我的位置");
                echo $resultStr;
                break;
			default:
                $url="http://www.tuling123.com/openapi/api?key=8137cf2b1b674f8b83bb3d25df3acf8d&info=".$content;
                $replay=file_get_contents($url);
                $recontent=json_decode($replay);
                $xmltpl="
					<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>
		    	";
                $repaycontent=$recontent->text;
                $resultStr = sprintf($xmltpl,$FromUserName,$ToUserName,$nowtime,'text',"我啥也不是");
                echo $resultStr;
                break;
		}
    }
    public function robots(){
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        libxml_disable_entity_loader(true);//是设置是否禁止从外部加载XML实体，设为true就是禁止
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $ToUserName=$postObj->ToUserName;
        $FromUserName=$postObj->FromUserName;
        $content=$postObj->Content;
        $url="http://www.tuling123.com/openapi/api?key=8137cf2b1b674f8b83bb3d25df3acf8d&info=".$content;
        $replay=file_get_contents($url);
        $recontent=json_decode($replay);
        $nowtime=time();
        $xmltpl="
			<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>
    	";
        $repaycontent=$recontent->text;

        $resultStr = sprintf($xmltpl,$FromUserName,$ToUserName,$nowtime,'text',$repaycontent);
        echo $resultStr;
        //}
        //验证token
        // $signature=$_GET["signature"];
        // $timestamp=$_GET["timestamp"];
        // $nonce=$_GET["nonce"];
        // $echostr=$_GET['echostr'];
        // $array=array(Token,$timestamp,$nonce);
        // sort($array,SORT_STRING);
        // $str=implode('',$array);
        // $str=sha1($str);
        // if($str==$signature)
        // {
        // echo $echostr;
        // }
        // else
        // {
        // return false;
        // }
    }
    //内容页
    public function content()
    {
        $id=input('id');
        $info=db('ly_news')->alias('n')
            ->join('ly_news_data a','n.id=a.id')
            ->where("n.id='".$id."'")
            ->find();
        $this->assign('info',$info);
        $result=db('discuss')->where('essayid="'.$id.'"')->limit(0,4)->order('id desc')->select();
        $this->assign('result',$result);
        return $this->fetch();
    }
    public function index()
    {
        $db=db('ly_news')->field('id,url,title,thumb')->where('catid=11')->select();
        $this->assign('db',$db);
        return $this->fetch();
    }
    public function xinwen()
    {
        $db=db('ly_news')->field('id,url,title,thumb')->where('catid=7')->select();
        $this->assign('db',$db);
        return $this->fetch();
    }
    //立即报名
    public function apply()
    {
        return $this->fetch();
    }
    //立即报名添加
    public function addapply()
    {
        $data=input('post.');
//        echo '<pre>';
//        print_r($data);exit;
        $db=db('apply')->insert($data);
        if($db!==false)
        {
            $this->success('报名成功');
        }
    }
    public function dz()
    {
        $redirect_uri=urlencode('http://www.sparkface.cn/api/vipcn/news');
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
        header('Location:'.$url);
//        //链接地址做加密处理
//        $redirectUri = urlencode('http://www.sparkface.cn/api/vipcn/news');
//        //获取code的地址
//        $getCodeUrl="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri={$redirectUri}&response_type=code&scope=snsapi_userinfo&state=23333#wechat_redirect";
//        //如果接收到get传来的code值
//        if(isset($_GET['code'])){
//            $code = $_GET['code'];
//            //获取token的地址
//            $getTokenUrl ="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->secret}&code={$code}&grant_type=authorization_code";
//            $tokenInfo = json_decode($this -> httpGet($getTokenUrl));
//            $access_token = $tokenInfo -> access_token;
//            $openid = $tokenInfo -> openid;
//            //获取user信息的地址
//            $getUserUrl ="https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}";
//            $userinfo = json_decode($this -> httpGet($getUserUrl));
//            return $userinfo;
//        }
//        header('Location:'.$getCodeUrl);
    }
    public function news()
    {
        $code=input('get.code');
        $newsurl="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->secret."&code=".$code."&grant_type=authorization_code";
        $objuser=file_get_contents($newsurl);
        $objtoken=json_decode($objuser);
        $access_token=$objtoken->access_token;
        $openid=$objtoken->openid;
        $getinfourl="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $info=file_get_contents($getinfourl);
        $users=json_decode($info);
        $data['openid']=$users->openid;
        $data['nickname']=$users->nickname;
        $data['sex']=$users->sex;
        $data['city']=$users->city;
        $data['province']=$users->province;
        $data['country']=$users->country;
        $db=db('apply')->insert($data);
        if($db!==false)
        {
            $this->success('授权成功');
        }
    }
    //评论插入
    public function add_discuss()
    {
        $data['content']=input('post.name');
        $data['essayid']=input('post.id');
        $data['discusstime']=time();
        $data['username']=db('apply')->where('openid=openid')->value('nickname');
        $db=db('discuss')->insert($data);
        if($db)
        {
            $result=[
                'mag'=>1,
            ];
        }else{
            $result=[
                'msg'=>0,
            ];
        }
        $result=implode($result);
        return json($result);
    }
    public function numlike()
    {
        $data['essayid']=input('id');
        $db=db('like')->insert($data);
        if($db)
        {
            $result=[
                'msg'=>1,
            ];
        }else{
            $result=[
              'msg'=>0
            ];
        }
        $result=implode($result);
        return json($result);
    }
}