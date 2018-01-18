<?php
namespace app\index\controller;

use think\Controller;
use Think\Db;
use think\Loader;
class Index extends Controller
{
    public function index()
    {
        echo "<a href='".url('excel')."'>导出</a>";
    }
    public function excel()
    {
        Loader::import('Classes.PHPExcel');
        Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory'); //引入IOFactory.php
        $excel=new \PHPExcel();
        $iclasslist=db('iclass')->select();
        foreach ($iclasslist as $key=>$v)
        {
            $excel->createSheet();
            $excel->setActiveSheetIndex($key);
            $sheet = $excel->getActiveSheet();
            $sheet->setTitle($v['classname']);
            $cloumn=Db::query('show full fields from wx_users');
            echo '<pre>';
            print_r($cloumn);exit;
            $letarr=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            foreach ($cloumn as $key=>$t)
            {
                $Comment=$t['Comment']?$t['Comment']:$t['Field'];
                $sheet->setCellValue($letarr[$key].'1',$Comment);
            }
            $userlist=db('users')->where('iclass='.$v['id'])->select();
            $i=2;
            foreach($userlist as $ulist)
            {
                $j=0;
                foreach($ulist as $key=> $v){
                    $sheet->setCellValue($letarr[$j].$i,$v);
                    $j++;
                }
                $i++;
            }
        }
        $PHPWriter = \PHPExcel_IOFactory::createWriter($excel, "Excel2007"); //创建生成的格式
        header('Content-Disposition: attachment;filename="excel列表'.'.xlsx"'); //下载下来的表格名
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件
    }
    public function email()
    {
        return $this->fetch();
    }
    public function reg()
    {
        $email=input('post.email');
        $username=input('post.username');
        $title="你好,".$username.'欢迎注册相亲网';
        $body="你好，".$username.',相亲网欢迎你的加入，以下是激活链接：http://localhost/tp5';
        sendmail($email,$title,$body);
    }
}