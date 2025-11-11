<?php
include("../MPHX/common.php");
@header('Content-Type: text/html; charset=UTF-8');
if($islogins==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
set_time_limit(0);
ignore_user_abort();
ini_set('memory_limit', '-1');
?>
<?php
$gn=$_GET['dowtype'];
if($gn=='dowbtfile'){           //下载主机上的文件
    $path=$_GET['filepath'];
    $name=$_GET['filename'];
    if(empty($path) || empty($name))exit('文件或目录为空！');
    $cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$ssbt' limit 1");
    $btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
    $btkeye=$cert['btmy'];
    if($cert['btos']=='1'){
        $os_xt=$conf['hxi'].'/';
        $l_ler_a='/etc/hosts';
    }else{
        $os_xt=$conf['hxo'].'/';
        $l_ler_a='C:\Windows\System32\drivers\etc\hosts';
    }
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $filedata=$api->GetLogshqwjlo($os_xt.$yhc['sqldz'].$path);      //获取指定目录下所有文件
    $filelist=dirfiles($filedata['FILES'],'file')['file'];
    $file=false;
    foreach($filelist as $val){
        if($val['name']==$name){
            $file=$val;
            break;
        }
    }
    if(!$file)exit('文件不存在！');
    if($file['download']!=0){
        //外链已经开启，获取外链
        $data=$api->wailhq($file['download']);
        if($data['password']!=""){
            //外链有密码，立刻关闭并且重新开启外链
            $api->wailgb($file['download']);
            $data=$api->wailkq($os_xt.$yhc['sqldz'].$path,$name)['msg'];
        }
    }else{
        //外链未开启，开启外链
        $data=$api->wailkq($os_xt.$yhc['sqldz'].$path,$name)['msg'];
    }
    $dowfile_url=$btipe.'/down/'.$data['token'];

    //以下为流量计算，下载文件所消耗的流量也记录为本月消耗流量。
    $llzd=json_decode($yhc['llmax'],true);
    $llzd['dq']+=$file['size'];
    $llshiy=json_encode($llzd,256);
    $sql="update `MN_zj` set `llmax` ='{$llshiy}' where `id`='{$yhid}'";
    $DB->query($sql);
    //流量计算和写入结束

    echo json_encode(['code'=>1,'msg'=>'下载文件','url'=>$dowfile_url],256);
}
?>