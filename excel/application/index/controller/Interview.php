<?php
/**
 * Created by PhpStorm.
 * User: 颜胜胜
 * Date: 2018/1/10
 * Time: 9:53
 */
namespace app\index\controller;

use think\Controller;
class Interview extends Controller
{
    public function test2()
    {
        $is_mobile = false;
        $ip = $_SERVER["REMOTE_ADDR"];
        $data = date('Y-m-d H:i:s', time());
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = array("240x320", "acer", "acoon", "acs-", "abacho", "ahong", "airness", "alcatel", "amoi",
            "android", "anywhereyougo.com", "applewebkit/525", "applewebkit/532", "asus", "audio",
            "au-mic", "avantogo", "becker", "benq", "bilbo", "bird", "blackberry", "blazer", "bleu",
            "cdm-", "compal", "coolpad", "danger", "dbtel", "dopod", "elaine", "eric", "etouch", "fly ",
            "fly_", "fly-", "go.web", "goodaccess", "gradiente", "grundig", "haier", "hedy", "hitachi",
            "htc", "huawei", "hutchison", "inno", "ipad", "ipaq", "iphone", "ipod", "jbrowser", "kddi",
            "kgt", "kwc", "lenovo", "lg ", "lg2", "lg3", "lg4", "lg5", "lg7", "lg8", "lg9", "lg-", "lge-", "lge9", "longcos", "maemo",
            "mercator", "meridian", "micromax", "midp", "mini", "mitsu", "mmm", "mmp", "mobi", "mot-",
            "moto", "nec-", "netfront", "newgen", "nexian", "nf-browser", "nintendo", "nitro", "nokia",
            "nook", "novarra", "obigo", "palm", "panasonic", "pantech", "philips", "phone", "pg-",
            "playstation", "pocket", "pt-", "qc-", "qtek", "rover", "sagem", "sama", "samu", "sanyo",
            "samsung", "sch-", "scooter", "sec-", "sendo", "sgh-", "sharp", "siemens", "sie-", "softbank",
            "sony", "spice", "sprint", "spv", "symbian", "tablet", "talkabout", "tcl-", "teleca", "telit",
            "tianyu", "tim-", "toshiba", "tsm", "up.browser", "utec", "utstar", "verykool", "virgin",
            "vk-", "voda", "voxtel", "vx", "wap", "wellco", "wig browser", "wii", "windows ce",
            "wireless", "xda", "xde", "zte");
        foreach ($mobile_agents as $device) {
            if (stristr($user_agent, $device)) {
                //手机访问
                $is_mobile = true;
                break;
            }
        }
        $file=$is_mobile?'手机':'电脑';
        $date = date('w', time());
        switch ($date) {
            case 0: $dates= '星期日';break;
            case 1: $dates= '星期一';break;
            case 2: $dates= '星期二';break;
            case 3: $dates= '星期三';break;
            case 4: $dates= '星期四';break;
            case 5: $dates= '星期五';break;
            case 6: $dates= '星期六';break;
        }
        $files= $ip.' '.$data.' '.$dates.' '.$file."\n ";
        $tex='log.txt';
        $text=fopen($tex,'a+');
        fwrite($text,$files);
        fclose($text);
    }
    public function index()
    {
        /*
         * 冒泡排序法
         * */
        $array=array('5','3','1','2','4');
        for($i=0; $i<count($array); $i++){
            for($j=count($array)-1; $j>$i; $j--){
                if ($array[$j] > $array[$j-1]){
                    $tmp = $array[$j];
                    $array[$j] = $array[$j-1];
                    $array[$j-1] = $tmp;
                }
            }
        }
        echo '<pre>';
        print_r($array);
        }
        public function regular()
        {
            return $this->fetch();
        }
}