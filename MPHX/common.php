<?php
error_reporting(0);
define('IN_CRONLITE', true);
define('SYSTEM_ROOT', dirname(__FILE__).'/');
define('ROOT', dirname(SYSTEM_ROOT).'/');
define('SYS_KEY', 'MNBT');
define('CC_Defender', 1); //防CC攻击开关(1为session模式)
date_default_timezone_set("PRC");
$date = date("Y-m-d H:i:s");
session_start();
$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$sitepath.'/';
if(is_file(SYSTEM_ROOT.'360safe/360webscan.php')){//360网站卫士
require_once(SYSTEM_ROOT.'360safe/360webscan.php');
}
require ROOT.'config.php';
if(!defined('SQLITE') && (!$dbconfig['user']||!$dbconfig['pwd']||!$dbconfig['dbname']))//检测安装
{
header("Location: /install");
exit();
}
//连接数据库
include_once(SYSTEM_ROOT."db.class.php");
$DB=new DB($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd'],$dbconfig['dbname'],$dbconfig['port']);
if($DB->query("select * from MN_config where 1")==FALSE)//检测安装2
{
header("Location: /install");
exit();
}
    
$siteid=1;
$conf=$DB->get_row("SELECT * FROM MN_config WHERE id='$siteid' limit 1");//获取系统配置
$alipay_config['apiurl']    = $conf['hxe'];
$alipay_config['partner']        = $conf['hxr'];
$alipay_config['key']            = $conf['hxt'];
$alipay_config['sign_type']    = strtoupper('MD5');
$alipay_config['input_charset']= strtolower('utf-8');
$alipay_config['transport']    = 'http';
$password_hash='!@#%!s!0';
include_once(SYSTEM_ROOT."function.php");
if($conf) {
    $conf['pwd']=mn_decrypt($conf['pwd']);
    if(isset($conf['mailpassword'])) {
        $conf['mailpassword']=mn_decrypt($conf['mailpassword']);
    }
}
include_once(SYSTEM_ROOT."member.php");
require_once(SYSTEM_ROOT."lib/core.function.php");
require_once(SYSTEM_ROOT."lib/md5.function.php");
?>