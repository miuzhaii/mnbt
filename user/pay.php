<?php
@header('Content-Type: text/html; charset=UTF-8');
include("../MPHX/common.php");
if($islogins==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
include("../MPHX/lib/submit.class.php");
if($_POST['pay_lx']=='yjbs'){
$bs_id=daddslashes($_POST['id']);
$bs_cx = $DB->get_row("SELECT * FROM `MN_bs` WHERE `id` = '$bs_id' limit 1");
}else{
$ym_a=daddslashes($_POST['urla']);			//一级域名
$ym_b=daddslashes($_POST['urlb']);			//域名前缀
$bs_cx = $DB->get_row("SELECT * FROM `MN_ym` WHERE `url` = '$ym_a' limit 1");
}
$out_trade_no = date("YmdHis").mt_rand(100,999);
$type = isset($_POST['type']) ? $_POST['type'] : 'wxpay';
$name = 'MNBT订单支付支付';
$money = $bs_cx['jg'];
$dada = date("Y-m-d H-i-s");
$ip = $_SERVER["REMOTE_ADDR"];
$parameter = array(
		"pid" => trim($alipay_config['partner']),
		"type" => $type,
		"out_trade_no"	=> $out_trade_no,
		"name"	=> $name,
		"money"	=> $money,
		"sitename"	=> "MNBT宝塔主机系统站点",
		"notify_url"	=> $siteurl.'notify_url.php',
		"return_url"	=> $siteurl.'return_url.php'
);
if($_POST['pay_lx']=='yjbs'){
//一键部署
if($bs_cx['qk']==false)exit("<script language='javascript'>alert('该程序已下架！');history.go(-1);</script>");
if(in_array($yhc['user'],json_decode($bs_cx['tj'],true)))exit("<script language='javascript'>alert('您已购买该程序！');history.go(-1);</script>");
$cs=json_encode(array('user'=>$yhc['user'],'gmid'=>$_POST['id']),256);               //user：购买该程序的主机账号；gmid：部署程序的id
$row1=$DB->get_row("SELECT * FROM MN_dd WHERE 1 order by id desc limit 1");
$id = $row1['id']+1;
$sql = "INSERT INTO `MN_dd` (`id`, `cs`, `date`, `zffs`, `je`, `ddh`, `lx`, `qk`, `ip`) VALUES ('{$id}', '{$cs}', '{$date}', '{$type}', '{$money}', '{$out_trade_no}', 'yjbs', 'false', '{$ip}');";
$DB->query($sql);

}elseif($_POST['pay_lx']=='ymgm'){
//域名购买
if($bs_cx['qk']==false)exit("<script language='javascript'>alert('该域名已下架！');history.go(-1);</script>");
if(!preg_match('/^[0-9a-zA-Z]{1,24}$/',$ym_b))exit("<script language='javascript'>alert('前缀不合法！');history.go(-1);</script>");

$cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$ssbt' limit 1");
$btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
$btkeye=$cert['btmy'];
include("./class.php");
$apie = new bt_api_set($btipe,$btkeye);
$ymzce = $apie->GetLogsy($zjid);
$azxcr='0';
foreach($ymzce as $val){
$azxcr++;
}
if($cert['btos']=='1'){
$l_ler_a='/etc/hosts';
}else{
$l_ler_a='C:\Windows\System32\drivers\etc\hosts';
}
$urlpath=$_POST['urlzml']==false ? '/' : $_POST['urlzml'];
if($azxcr>=$yhc['ymbds']+1 && $yhc['ymbds']!='0' && $yhc['ymbds']!='')exit("<script language='javascript'>alert('您域名绑定数已达最大！');history.go(-1);</script>");
$cs=json_encode(array('user'=>$yhc['user'],'url_qz'=>$ym_b,'url_zd'=>$ym_a,'path'=>$urlpath,'url_zy'=>$ym_b.'.'.$ym_a,'type'=>$yhc['hxc'],'yz_ip'=>$_POST['yzdip'],'hostly'=>$l_ler_a),256);               //user：购置该域名的主机账号；url_qz：域名前缀；url_zd：一级域名；url_zy：购置的域名；type：用户的产品类型；path：子目录位置
$row1=$DB->get_row("SELECT * FROM MN_dd WHERE 1 order by id desc limit 1");
$id = $row1['id']+1;
$sql = "INSERT INTO `MN_dd` (`id`, `cs`, `date`, `zffs`, `je`, `ddh`, `lx`, `qk`, `ip`) VALUES ('{$id}', '{$cs}', '{$date}', '{$type}', '{$money}', '{$out_trade_no}', 'ymgm', 'false', '{$ip}');";
$DB->query($sql);

}else{exit('404 NOT！');}
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter);
echo $html_text;
?>