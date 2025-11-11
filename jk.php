<?php
/*
 * 网页空间及数据库空间和流量使用情况监控文件
 * 会自动判断是否超出大小以及暂停超出的站点
 * 建议10分钟执行一次（不建议低于1分钟）
 * 访问地址：http://搭建此系统的网站域名/jk.php?my=后台设置的api密钥&gn=需要监控的功能(wq为数据库空间和网页，fh为流量监控)
 * 2022©梦奈
 */
include("./MPHX/common.php");
include("./cf_up.php");
if($mn_conf['xf']['qk'])
{
    exit('由于更新后必须进行一次系统修复，暂时无法使用这功能！');
}
if($_GET['my']!=$conf['api'])  //判读api密钥是否正确
{
    exit('密钥错误'); //
}

?>
<?php
include("./class.php");

if($_GET['gn']=='web'){		//WEB/SQL监控
$rs=$DB->query("SELECT * FROM MN_zj");
$ztyh=''; $ztzj='0';
while($yhc = $DB->fetch($rs))
{
if(strtotime($date)-strtotime($yhc['datae'])>0 && $yhc['datae']!='0000-00-00'){continue;}
if($yhc['hxc']==1 || $yhc['qk']==false){continue;}

$web_kjr=json_decode($yhc['hxa'],true);
$k_qa=json_decode($yhc['llmax'],true);
$k_qe=json_decode($yhc['hxb'],true);
$ssbt=$yhc['ssbt'];
$cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$ssbt' limit 1");
$btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
$btkeye=$cert['btmy'];
if($cert['btos']=='1'){
$os_xt=$conf['hxi'].'/';
}else{
$os_xt=$conf['hxo'].'/';
}
$api = new bt_api($btipe,$btkeye);
$r_data = $api->webkjjs($os_xt.$yhc['sqldz']);
$webkj=$r_data['size']/(1024*1000);
$r_js=$web_kjr;
$r_js['dq']=$webkj;
$r_sy=json_encode($r_js,256);
$t_id=$yhc['id'];
$sql="update `MN_zj` set `hxa` ='{$r_sy}' where `id`='{$t_id}'";
if($DB->query($sql)){
if($webkj>$web_kjr['max']){
if($web_kjr['dq']<$web_kjr['max']){
$api->ztweb($yhc['btid'],$yhc['sqldz']);
$api->ftpxg($yhc['ftpid'],$yhc['user'],'0');
}
$ztzj++;
$ztyh.=$yhc['user'].'，';
}else{
if($web_kjr['dq']>=$web_kjr['max'] && $k_qa['dq']<=$k_qa['max'] && $k_qe['dq']<=$k_qe['max']){
$api->qdweb($yhc['btid'],$yhc['sqldz']);
$api->ftpxg($yhc['ftpid'],$yhc['user'],'1');
}
}
}
}
echo '执行完成，有'.$ztzj.'个主机由于网页空间超过被暂停他们分别是：'.$ztyh;

}elseif($_GET['gn']=='sql'){

$rs=$DB->query("SELECT * FROM MN_zj");
$ztyh=''; $ztzj='0';
while($yhc = $DB->fetch($rs))
{
if(strtotime($date)-strtotime($yhc['datae'])>0 && $yhc['datae']!='0000-00-00'){continue;}
if($yhc['hxc']==1 || $yhc['qk']==false){continue;}

$sql_kjr=json_decode($yhc['hxb'],true);
$k_qa=json_decode($yhc['llmax'],true);
$k_qe=json_decode($yhc['hxa'],true);
$ssbt=$yhc['ssbt'];
$cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$ssbt' limit 1");
$btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
$btkeye=$cert['btmy'];
if($cert['btos']=='1'){
$os_xt=$conf['hxi'].'/';
}else{
$os_xt=$conf['hxo'].'/';
}
$api = new bt_api($btipe,$btkeye);
$r_datb = $api->sqlkjhq($yhc['sqluser']);
if(substr($r_datb['data_size'],'-2' , 2)=='kb' || substr($r_datb['data_size'],'-2' , 2)=='KB' || substr($r_datb['data_size'],'-2' , 2)=='kB' || substr($r_datb['data_size'],'-2' , 2)=='Kb'){
$sqlkj= str_ireplace(substr($r_datb['data_size'],'-2' , 2),'',$r_datb['data_size']);
}elseif(substr($r_datb['data_size'],'-2' , 2)=='b' || substr($r_datb['data_size'],'-2' , 2)=='B'){
$sqlkj= str_ireplace(substr($r_datb['data_size'],'-2' , 2),'',$r_datb['data_size'])/1000;
}elseif(substr($r_datb['data_size'],'-2' , 2)=='MB' || substr($r_datb['data_size'],'-2' , 2)=='mb' || substr($r_datb['data_size'],'-2' , 2)=='Mb' || substr($r_datb['data_size'],'-2' , 2)=='Mb'){
$sqlkj= str_ireplace(substr($r_datb['data_size'],'-2' , 2),'',$r_datb['data_size'])*1000;
}else{$sqlkj='0';}
$adft=$sqlkj/1024;

$r_js=$sql_kjr;
$r_js['dq']=$adft;
$r_sy=json_encode($r_js,256);
$t_id=$yhc['id'];
$sql="update `MN_zj` set `hxb` ='{$r_sy}' where `id`='{$t_id}'";
if($DB->query($sql)){
if($adft>$sql_kjr['max']){
if($sql_kjr['dq']<$sql_kjr['max']){
$api->ztweb($yhc['btid'],$yhc['sqldz']);
$api->ftpxg($yhc['ftpid'],$yhc['user'],'0');
}
$ztzj++;
$ztyh.=$yhc['user'].'，';
}else{
if($sql_kjr['dq']>=$sql_kjr['max'] && $k_qa['dq']<=$k_qa['max'] && $k_qe['dq']<=$k_qe['max']){
$api->qdweb($yhc['btid'],$yhc['sqldz']);
$api->ftpxg($yhc['ftpid'],$yhc['user'],'1');
}
}
}
}
echo '执行完成，有'.$ztzj.'个主机由于数据库空间超过被暂停他们分别是：'.$ztyh;

}elseif($_GET['gn']=='fh'){		//流量监控
$rs=$DB->query("SELECT * FROM MN_zj");
$ztyh=''; $ztzj='0';
while($yhc = $DB->fetch($rs))
{
if(strtotime($date)-strtotime($yhc['datae'])>0 && $yhc['datae']!='0000-00-00'){continue;}
if($yhc['qk']==false){continue;}

$ssbt=$yhc['ssbt'];
$cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$ssbt' limit 1");
$btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
$btkeye=$cert['btmy'];
$r_js=json_decode($yhc['llmax'],true);
$k_qa=json_decode($yhc['hxa'],true);
$k_qe=json_decode($yhc['hxb'],true);
$api = new bt_api($btipe,$btkeye);
$s_data=$api->getlog($yhc['sqldz']);
if($s_data['status'] && $s_data['msg']!=''){
$sfyr=explode(' - - ',$s_data['msg']);
unset($sfyr[0]);
foreach($sfyr as $vfm){
$arrMatches=[];
preg_match_all('/(?:\[)(.*)(?:\])/i', $vfm, $arrMatches);
$e_size=explode(' ',$vfm);
if(!is_numeric($e_size[6]))continue;
$g_size+=$e_size[6];

if($arrMatches[0]==$r_js['statistics']){
$g_size=0;
}
$g_data=$arrMatches[0];
}
$r_jy=$r_js;
$r_js['dq']+=$g_size;
$r_js['statistics']=$g_data;
$r_sy=json_encode($r_js,256);
$t_id=$yhc['id'];
$sql="update `MN_zj` set `llmax` ='{$r_sy}' where `id`='{$t_id}'";
if($DB->query($sql)){
if($r_js['dq']>=$r_js['max']*1024*1024*1024){
$ztzj++;
$ztyh.=$yhc['user'].'，';
if($r_jy['dq']<$r_jy['max']*1024*1024*1024){
$api->ztweb($yhc['btid'],$yhc['sqldz']);
$api->ftpxg($yhc['ftpid'],$yhc['user'],'0');
}
}else{
if($r_jy['dq']>=$r_jy['max']*1024*1024*1024 && $k_qa['dq']<=$k_qa['max'] && $k_qe['dq']<=$k_qe['max']){
$api->qdweb($yhc['btid'],$yhc['sqldz']);
$api->ftpxg($yhc['ftpid'],$yhc['user'],'1');
}
}
}
unset($vfm); unset($g_size); unset($sfyr);
}
}
echo '执行完成，有'.$ztzj.'个主机由于流量超过被暂停他们分别是：'.$ztyh;

}elseif($_GET['gn']=='fhq'){		//清除统计的流量使用量（推荐每个月执行一次）
$rs=$DB->query("SELECT * FROM MN_zj");
$ztyh=''; $ztzj='0';
while($yhc = $DB->fetch($rs))
{
if(strtotime($date)-strtotime($yhc['datae'])>0 && $yhc['datae']!='0000-00-00'){continue;}
if($yhc['qk']==false){continue;}
$r_js=json_decode($yhc['llmax'],true);
$r_js['dq']=0;
$x_ll=json_encode($r_js,256);
$t_id=$yhc['id'];
$sql="update `MN_zj` set `llmax` ='{$x_ll}' where `id`='{$t_id}'";
$DB->query($sql);
unset($x_ll);
}
echo '执行完成，所有主机的月使用流量清零完毕！';

}
elseif($_GET['gn'] == "ywjkdel")
{
    include_once("./mail.php");
    $rs=$DB->query("SELECT * FROM MN_zj");
    while($yhc = $DB->fetch($rs)){
        $ssbt=$yhc['ssbt'];
        $cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$ssbt' limit 1");
        $btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
        $btkeye=$cert['btmy'];
        if($cert['btos']=='1')
        {
            $os_xt=$conf['hxi'].'/';
        }
        else
        {
        $os_xt=$conf['hxo'].'/';
        }
        $api = new bt_api($btipe,$btkeye);
        
        if($conf['ymjkkg'] == "true" || $conf['mtyxfskg'])
        {
            $r_dataaa = $api->Getymlist($yhc['btid']);
            $ymlistcount = count($r_dataaa);
            
            if($ymlistcount == 1 && $r_dataaa[0]['name'] == $yhc["sqldz"])
            {
                echo $yhc['user'] . "没有绑定域名";
                echo '<br>';
                $create_time = new DateTime($yhc['data']);
                $dq_time = new DateTime();
                $xc_time = $dq_time->diff($create_time);
                $xxx = $xc_time->days;
                if($conf['ymjkkg']=="true")//开启启删除
                {
                    if($yhc['mailuser'] != null || $yhc['mailuser'] != "")
                    {
                        if($xc_time->days > $conf['ymjktsyz'])
                        {
                            
                            if($conf['optionzc'] == "stop")
                            {
                                $r_dataa = $api->stopjq($yhc['btid'],$yhc['sqldz']);
                                echo($yhc['user'].$r_dataa['msg']);
                                $message = "检测到".$yhc['user']. "的主机".$xxx."天内未使用超过了预定的天数已经暂停机器";
                            }
                            elseif($conf['optionzc'] == "del")
                            {
                                $r_dataa = $api->delsite($yhc['btid'],$yhc['sqldz']);
                                echo($yhc['sqldz'].$r_dataa['msg']);
                                $message = "检测到".$yhc['user']. "的主机".$xxx."天内未使用超过了预定的天数已经删除机器";
                            }
                            else
                            {
                                echo("数据库配置不对,没有读取到正常的配置");
                            }
                            if(sendEmail($yhc['mailuser'],"MN系统",$message))
                            {
                                echo "邮箱发送成功";
                            }
                            else{
                                echo "邮箱发送失败";
                            }
                            
                        } 
                        else
                        {
                            $message = "检测到".$yhc['user']. "的主机".$xxx."天内未使用,超过".$conf['ymjktsyz']."天将会暂停或删除";
                            sendEmail($yhc['mailuser'],"MN系统",$message);
                        }
                    }
                    else
                    {
                        echo "邮箱为空";
                    }

                }
                else
                {
                    if($conf['mtyxfskg'] == "true")//开启只通知不删除
                    {
                        $message = "检测到".$yhc['user']. "的主机天".$xxx."未使用请尽快使用";
                        sendEmail($yhc['mailuser'],"MN系统",$message);
                    }
                }
                
            }
        }
    }
}
else{
exit('该功能不存在！');
}
?>