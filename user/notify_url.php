<?php
include("../MPHX/common.php");
require_once(SYSTEM_ROOT."lib/notify.class.php");
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {
$out_trade_no = $_GET['out_trade_no'];
$trade_no = $_GET['trade_no'];
$trade_status = $_GET['trade_status'];
$type = $_GET['type'];
$money = $_GET['money'];
if($_GET['trade_status'] == 'TRADE_SUCCESS') {
}else{
echo "trade_status=".$_GET['trade_status'];
}
sysmsg('支付成功',false);
$ddxx = $DB->get_row("SELECT * FROM `MN_dd` WHERE `ddh` = '$out_trade_no' limit 1");
if(constant($ddxx['qk'])){
@header('Content-Type: text/html; charset=UTF-8');
exit("<script language='javascript'>alert('该订单已被系统处理！');window.location.href='./';</script>");
}else{
$ddxx_cs=json_decode($ddxx['cs'],true);
if($ddxx['lx']=='yjbs'){
//程序部署处理
$ddxx_xid=$ddxx_cs['gmid'];
$bscx = $DB->get_row("SELECT * FROM `MN_bs` WHERE `id` = '$ddxx_xid' limit 1");
$bs_tj=json_decode($bscx['tj'],true);
array_push($bs_tj,$ddxx_cs['user']);
$tj_jg=json_encode($bs_tj,256);
$sqlyecl="update `MN_bs` set `tj` ='$tj_jg' where `id`='{$ddxx_xid}'";
$DB->query($sqlyecl);
}else{
//域名购买处理
$ddxx_url=$ddxx_cs['url_zd'];
$user=$ddxx_cs['user'];
$yhc=mn_format_host_row($DB->get_row("SELECT * FROM MN_zj WHERE user='$user' limit 1"));//获取主机信息
$zjid=$yhc['btid'];
$ssbt=$yhc['ssbt'];
$bscx = $DB->get_row("SELECT * FROM `MN_ym` WHERE `url` = '$ddxx_url' limit 1");
include("./class.php");
$cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$ssbt' limit 1");
$btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
$btkeye=$cert['btmy'];
if($cert['btos']=='1'){
$os_xt=$conf['hxi'].'/';
}else{
$os_xt=$conf['hxo'].'/';
}
$ul_url_ym=$ddxx_cs['url_zy'];
$path=$ddxx_cs['path'];
$apie = new bt_api_set($btipe,$btkeye);
$api = new bt_api($btipe,$btkeye);
if(strpos($path,'/') !==false){
$r_data = $apie->btapi_addym($zjid,$yhc['sqldz'],$ul_url_ym);
}else{
$r_data = $api->addzml($zjid,$ul_url_ym,$path,$os_xt.$yhc['sqldz']);
}
$are=$r_data['status'];
if($are!='true'){
$yr_c=true;
for($yr_a=1;$yr_c;$yr_a++){
$hskr=mt_rand(4,10);
$rqsj=md5($date.$user.$yr_a.mt_rand(100,999));
$wjler=substr($rqsj, $hskr , 5);
$ul_url_ym=$wjler.'.'.$ddxx_cs['url_zd'];
if(strpos($path,'/') !==false){
$r_data = $apie->btapi_addym($zjid,$yhc['sqldz'],$ul_url_ym);
}else{
$r_data = $api->addzml($zjid,$ul_url_ym,$path,$os_xt.$yhc['sqldz']);
}
$yr_c=$r_data['status']=='true' ? false : true;
}
}

if($ddxx_cs['type']=='1'){
    $hhf='
';        //换行符
    $apic = new bt_api($btipe,$btkeye);
    $get_host_hq = $apic->GetLogswt($ddxx_cs['hostly']);
    $host_wjnr=$get_host_hq['data'].$hhf.$ddxx_cs['yz_ip'].' '.$ul_url_ym;
    $get_host_xg = $apic->GetLogswh($host_wjnr,$ddxx_cs['hostly']);
    $get_fxdl_add = $apic->fxdl_add($ul_url_ym,$yhc['sqldz']);
}
$bs_tj=json_decode($bscx['json'],true);
array_push($bs_tj,$ddxx_cs['user']);
$tj_jg=json_encode($bs_tj,256);
$sqlyecl="update `MN_ym` set `json` ='$tj_jg' where `url`='{$ddxx_url}'";
$DB->query($sqlyecl);

}
$sqlzfqk="update `MN_dd` set `qk` ='true' where `ddh`='{$out_trade_no}'";
$DB->query($sqlzfqk);
}
Header("Location:./");
}else{
echo '<!DOCTYPE html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link href="https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet"><script src="https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js"></script><script src="https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert-dev.min.js"></script><title>支付失败！</title></head><body></body><script type="text/javascript">swal({title: "支付失败",text: "支付失败", type: "error"},function(){ window.location.href="./";});</script></body></html>';
Header("Location:./");
}
?>