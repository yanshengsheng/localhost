<?php
namespace app\api\controller;

/**
* 
*/
class Min
{

	public function index()
	{
		$appid = "wx55e8522c83de3147";
$appsecret = "7409323f2f725c70a3b0395746425c53";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
$data=new min;
$output = $data->https_request($url);
$jsoninfo = json_decode($output, true);
// echo '<pre>';
// print_r($jsoninfo);exit;
$access_token = $jsoninfo["access_token"];
$jsonmenu = '{
      "button":[
      {
            "name":"精彩大智",
           "sub_button":[
            {
               "type":"view",
               "name":"荣誉榜",
               "url":"http://www.itbool.com/job.html"
            },
            {
               "type":"view",
               "name":"就业喜报",
               "url":"http://m.hao123.com/a/tianqi"
            },
            {
               "type":"view",
               "name":"精彩活动",
               "url":"http://m.hao123.com/a/tianqi"
            },
            {
               "type":"view",
               "name":"扩展训练",
               "url":"http://m.hao123.com/a/tianqi"
            },
            {
                "type":"view",
                "name":"本地天气",
                "url":"http://m.hao123.com/a/tianqi"
            }]
       },
       {
           "name":"走进大智",
           "sub_button":[
            {
               "type":"view",
               "name":"行业动态",
               "url":"http://www.sparkface.cn/api/vipcn/index"
            },
            {
               "type":"view",
               "name":"校园新闻",
               "url":"http://www.sparkface.cn/api/vipcn/xinwen"
            },
            {
                "type":"view",
                "name":"学习资料",
                "url":"http://www.sparkface.cn/api/vipcn/xinwen"
            },
            {
            	"type":"view",
                "name":"大智课程",
                "url":"http://www.sparkface.cn/api/vipcn/dz"
            }]
       },
       {
       		 "name":"在线咨询",
       		 "sub_button":[
       		 	{
	       		   "type":"click",
	               "name":"测试号",
	               "key":"ceshi"
       		 	},
       		 	{
	       		   "type":"view",
	               "name":"官方微博",
	               "url":"http://m.hao123.com/a/tianqi"
       		 	},
       		 	{
	       		   "type":"view",
	               "name":"微信咨询",
	               "url":"http://m.hao123.com/a/tianqi"
       		 	},
       		 	{
	       		   "type":"view",
	               "name":"立即报名",
	               "url":"http://www.sparkface.cn/api/vipcn/apply"
       		 	},
       		 ]
       }

   ]
 }';


	$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
	$result = $data->https_request($url, $jsonmenu);
	var_dump($result);
	}
    public function https_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
}
?>