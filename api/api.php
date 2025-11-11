<?php
@header('Content-Type: text/html; charset=UTF-8');
include("../MPHX/common.php");
include("../MPHX/BL.php");
include("./api.class.php");
?>
<?php
if($conf['apiqk']=='false')exit(json_encode(['code'=>100,'msg'=>'错误！APi已关闭！开启方法：系统后台的系统设置->api密钥处开启即可'],256));
$gn=$_GET['gn'];
$bh=$_POST['mn_bh'];
$key=$_POST['mn_key'];
$keye=$_POST['mn_keye'];
$mn_vser=$_POST['mn_vs'];
$user=$_POST['username'];
if($mn_vser<15)exit(json_encode(['code'=>300,'msg'=>'您的版插件本不支持当前MNBT版本！当前MNBT版本：'.intval($WEBQB/100).'，插件所支持的版本'.$mn_vser],256));         //当前API和1500没多大变化所以15的插件能够继续运行
if(isset($gn) || isset($bh) || isset($key) || isset($keye) || isset($user)){}else{exit(json_encode(['code'=>100,'msg'=>'错误！表单填写不完整！'],256));}
if(empty($user))exit(json_encode(['code'=>100,'msg'=>'错误！表单填写不完整！'],256));
if($key!=$conf['api'])exit(json_encode(['code'=>100,'msg'=>'错误！您的密钥与网站APi密钥不匹配！请您仔细核对后再试！您的密钥为'.$key],256));
$cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$bh' limit 1");//获取宝塔配置
$et_zj=mn_format_host_row($DB->get_row("SELECT * FROM MN_zj WHERE user='$user' limit 1"));    //获取主机配置
if($cert=='' || $cert['qk']=='false')exit(json_encode(['code'=>100,'msg'=>'错误！该宝塔不存在或该宝塔已经被关闭'],256));
$adyjm=$cert['ktmy'].$cert['qmk'];$mdjm=md5($adyjm);
if($keye!=$mdjm)exit(json_encode(['code'=>100,'msg'=>'错误！您所传输的宝塔调用密钥与该宝塔的调用密钥不匹配！您所传输的密钥为'.$keye],256));
$btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
$btkeye=$cert['btmy'];
?>
<?php
if($gn=='cfif'){
    exit(json_encode(['code'=>200,'msg'=>'连接验证成功！'],256));
}elseif($gn=='kt'){
    $pass=daddslashes($_POST['password']);        //密码
    $cptype=daddslashes($_POST['type']);        //1为CDN2为主机
    $flowratemax=json_encode(array('max'=>daddslashes($_POST['sizemax']),'dq'=>0,'statistics'=>false));
    $datae=$_POST['dqtime']=='0' ? '0000-00-00' : daddslashes($_POST['dqtime']);
    $webdx=json_encode(array('max'=>daddslashes($_POST['webdx']),'dq'=>0));
    $sqldx=json_encode(array('max'=>daddslashes($_POST['sqldx']),'dq'=>0));;
    $ymbds=$cptype=='1' ? '1' : daddslashes($_POST['ymbds']);
    if($et_zj!='' || $et_zj!=false)exit(json_encode(['code'=>100,'msg'=>'错误！该主机已经存在，请重新开通！'],256));
    if($cptype=='1'){$cplxch='CDN'; $cp_eh_ftp='false'; $cp_eh_sql='false';}else{$cplxch='主机'; $cp_eh_ftp='true'; $cp_eh_sql='true';}

    //实例化对象
    $api = new bt_api($btipe,$btkeye);
    if($cptype!='1'){
    if(mb_strlen($user)<6 || mb_strlen($pass)<6)exit(json_encode(['code'=>100,'msg'=>'错误！账号和密码位数均不能小于6位！'],256));
    if(!empty($api->databascx($user)['data']) || !empty($api->ftpcx($user)['data']))exit(json_encode(['code'=>100,'msg'=>'错误！该账号的数据库/FTP已存在！请更换账号后重新尝试开通！'],256));
    }
    //开始工作
    $rowe=$DB->get_row("SELECT * FROM MN_zj WHERE 1 order by id desc limit 1");
    $id=$rowe['id']+1;    
    //以下是计算创建网站的名称(防止重复创建失败)
    $hskr=mt_rand(4,10);
    $rqsj=md5($date.$user);
    $wjler=substr($rqsj, $hskr , 3);
    $btserw='mnbt.'.$id.mt_rand(1,999).$wjler;
    $mrwww=$cert['btos']=='1' ? $conf['hxi'].'/'.$btserw : $conf['hxo'].'/'.$btserw;
    $r_data = $api->webkt($user,$pass,$btserw,$cplxch,$cp_eh_ftp,$cp_eh_sql,$conf['hxu'],$mrwww);
    
    $cjqk=$r_data['siteStatus'];
    $zdide=$r_data['siteId'];
    //$api->mysqlqx($user,'%');
    if($cjqk=='1' || $cjqk=='true'){
    if($_POST['dqtime']!='0'){
    $r_datan = $api->setdqsj($zdide,$datae);
    $de=$r_datan['status'];
    }
    $r_datn = $api->sjlist('ftps');
    $r_datp = $api->sjlist('databases');
   foreach($r_datn['data'] as $val){
   if($val['name']===$user){
   $aedfs=$val['id'];
   break;
   }
   }
   foreach($r_datp['data'] as $val){
   if($val['name']===$user){
   $sqlfs=$val['id'];
   break;
   }
   }
    $id=$rowe['id']+1;
    $encryptedFtpPass = mn_encrypt($pass);
    $encryptedSqlPass = mn_encrypt($pass);
    $sql = "INSERT INTO `MN_zj` (`id`, `ssbt`, `user`, `pass`, `sqluser`, `sqlpass`, `data`, `datae`, `qk`, `btid`, `sqldz`, `ftpid`, `ymbds`, `hxa`, `hxb`, `hxc`, `hxd`, `llmax`) VALUES ('{$id}', '{$bh}', '{$user}', '{$encryptedFtpPass}', '{$user}', '{$encryptedSqlPass}', '{$date}', '{$datae}', 'true', '{$zdide}', '{$btserw}', '{$aedfs}', '{$ymbds}', '{$webdx}', '{$sqldx}', '{$cptype}', '{$sqlfs}', '{$flowratemax}');";
    if($DB->query($sql))exit(json_encode(['code'=>200,'msg'=>'主机开通成功！'],256));
    else{
    $r_data = $api->delsite($zdide,$btserw);
    exit(json_encode(['code'=>100,'msg'=>'错误！网站添加失败(数据写入数据库失败)！请尝试重试或加官Q群询问！'],256));}
    }else exit(json_encode(['code'=>100,'msg'=>'错误！网站创建失败！宝塔返回信息：'.$r_data['msg']],256));

}elseif($gn=='zt'){
    $api = new bt_api($btipe,$btkeye);
    $api->siteqt($et_zj['btid'],$et_zj['sqldz'],false);
    $api->setftpzt($et_zj['ftpid'],$et_zj['user'],'0');
    $sql="update `MN_zj` set `qk` ='false' where `user`='{$user}'";
    $DB->query($sql);
    exit(json_encode(['code'=>200,'msg'=>'主机暂停成功！'],256));
}elseif($gn=='xf'){
    //续费：必须保证主机没被管理员后台或者使用API接口暂停，如果到期后续费那么就设置将网站打开。
    $x_dq_date=$_POST['setdate']=='0' ? '0000-00-00' : $_POST['setdate'];
    $api = new bt_api($btipe,$btkeye);
    $r_data = $api->setdqsj($et_zj['btid'],$x_dq_date);
    if(strtotime($date)-strtotime($x_dq_date)<0 && $x_dq_date!='0000-00-00' && $et_zj['qk']){
    $api->siteqt($et_zj['btid'],$et_zj['sqldz'],true);
    $api->setftpzt($et_zj['ftpid'],$et_zj['user'],'1');
    }
    $sql="update `MN_zj` set `datae` ='$x_dq_date' where `user`='{$user}'";
    $DB->query($sql);
    exit(json_encode(['code'=>200,'msg'=>'主机续费成功！'],256));
}elseif($gn=='jc'){
    $api = new bt_api($btipe,$btkeye);
    $api->siteqt($et_zj['btid'],$et_zj['sqldz'],true);
    $api->setftpzt($et_zj['ftpid'],$et_zj['user'],'1');
    $sql="update `MN_zj` set `qk` ='true' where `user`='{$user}'";
    if($DB->query($sql))exit(json_encode(['code'=>200,'msg'=>'主机暂停解除成功！'],256));
    else exit(json_encode(['code'=>100,'msg'=>'主机暂停解除成功！但是写入数据库时出现错误！请站长排查！'],256));
}elseif($gn=='tz'){
    if($cert['btos']=='1'){
    $l_ler_a='/etc/hosts';
    }else{
    $l_ler_a='C:\Windows\System32\drivers\etc\hosts';
    }
    //实例化对象
    $api = new bt_api($btipe,$btkeye);
    //获取面板日志
    if($et_zj['hxc']=='1'){
    $r_datad = $api->urllist($et_zj['btid']);
    foreach($r_datad as $are){
    if($are!='' && $are['name']!=$et_zj['sqldz']){
    $get_host_hq = $api->hqwjnr($l_ler_a);
    $kh='
';        //换行符
    $arysz=explode($kh,$get_host_hq['data']);
    foreach($arysz as $val){
    if(!strpos($val,' '.$are['name']) && $val!=''){
    $ayrt.=$val.$kh;
    }}
    $get_host_xg = $api->setwj(array($ayrt,$l_ler_a));
    unset($ayrt);unset($val);unset($arysz);unset($get_host_hq);
    }}
    }
    $r_data = $api->delsite($et_zj['btid'],$et_zj['sqldz']);
    if($r_data['status']){
    $sql="DELETE FROM MN_zj WHERE user='$user' limit 1";
    if($DB->query($sql))exit(json_encode(['code'=>200,'msg'=>'主机删除成功！'],256));
    else exit(json_encode(['code'=>100,'msg'=>'主机删除成功，但是写入数据库失败，请站长排查！'],256));}else exit(json_encode(['code'=>100,'msg'=>'主机删除失败！因为'.$r_data['msg']],256));
}elseif($gn=='czmm'){
    $x_up_pass=$_POST['password'];
    $api = new bt_api($btipe,$btkeye);
    $api->setftppass($et_zj['ftpid'],$user,$x_up_pass);
    $encryptedResetPass = mn_encrypt($x_up_pass);
    $sql="update `MN_zj` set `pass` ='{$encryptedResetPass}' where `user`='{$user}'";
    if($DB->query($sql))exit(json_encode(['code'=>200,'msg'=>'主机FTP及控制面板登陆密码重置成功！'],256));
    else exit(json_encode(['code'=>100,'msg'=>'主机FTP密码重置成功！但是控制面板登陆密码重置失败，因为数据写入数据库失败！请站长排查'],256));
}
elseif($gn == 'zjmode')#修改主机
{
    $zjdata=mn_format_host_row($DB->get_row("SELECT * FROM MN_zj WHERE user='$user'"));//获取主机配置
    if($zjdata == null)
    {
        exit(json_encode(['code'=>100,'msg'=>'不存在主机用户名'],256));
    }
    else
    {
        $js_hxa = $_POST['websize'];
        $js_hxb = $_POST['sqlsize'];
        $js_ll = $_POST['ll'];
        
        $hxa = $zjdata['hxa'];
        $hxb = $zjdata['hxb'];
        $llmax = $zjdata['llmax'];
        
        $llmax_array = json_decode($llmax,true);
        $hxa_array = json_decode($hxa,true);
        $hxb_array = json_decode($hxb,true);
        
        $hxa_array['max'] = $js_hxa;
        $hxb_array['max'] = $js_hxb;
        $llmax_array['max'] = $js_ll;
        $sql = "UPDATE `MN_zj` SET `hxa` = '".json_encode($hxa_array)."', `hxb` = '".json_encode($hxb_array)."', `llmax` = '".json_encode($llmax_array)."' WHERE `user` = '".$user."'";
         if($DB->query($sql))
         {
            exit(json_encode(['code'=>200,'msg'=>'主机修改成功'],256));
         }
         else
         {
             exit(json_encode(['code'=>100,'msg'=>'主机修改失败我也不知道什么问题，请联系开发者'],256));
         }
    }
}
else{
    exit(json_encode(['code'=>100,'msg'=>'此功能不存在！请仔细核对开发文档！'],256));
}
?>