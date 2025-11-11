<?php
/*
 *本文件为控制面板功能性操作文件
 *©梦奈
 */
include("../MPHX/common.php");
@header('Content-Type: text/html; charset=UTF-8');

/**
 * 验证运行目录
 */
function validateRunPath($path) {
    // 检查路径是否包含中文字符
    if (preg_match('/[\x{4e00}-\x{9fff}]/u', $path)) {
        return ['valid' => false, 'error' => '运行目录不能包含中文字符，请使用英文目录名'];
    }
    
    // 检查路径是否包含危险字符
    $dangerous_chars = ['..', '\\', ';', '|', '&', '$', '`', '"', "'", '<', '>', '*', '?'];
    foreach ($dangerous_chars as $char) {
        if (strpos($path, $char) !== false) {
            return ['valid' => false, 'error' => '运行目录包含非法字符：' . $char];
        }
    }
    
    // 检查路径长度
    if (strlen($path) > 255) {
        return ['valid' => false, 'error' => '运行目录路径过长'];
    }
    
    // 检查是否为系统目录
    $system_dirs = ['/bin', '/boot', '/dev', '/etc', '/lib', '/proc', '/root', '/sbin', '/sys', '/usr', '/var'];
    foreach ($system_dirs as $dir) {
        if (strpos($path, $dir) === 0) {
            return ['valid' => false, 'error' => '不能设置为系统目录：' . $dir];
        }
    }
    
    // 检查路径格式
    if (!preg_match('/^\/[a-zA-Z0-9_\-\/]*$/', $path)) {
        return ['valid' => false, 'error' => '运行目录格式不正确，只能包含字母、数字、下划线、连字符和斜杠'];
    }
    
    return ['valid' => true];
}

$egn=$_POST['gn'];
if($islogins==1 || $egn=='login') {
} else exit('{"code":"请登陆"}');
if($islogins==1) {
    $cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$ssbt' limit 1");
    $btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
    $btkeye=$cert['btmy'];
    if($cert['btos']=='1') {
        $os_xt=$conf['hxi'].'/';
        $l_ler_a='/etc/hosts';
    } else {
        $os_xt=$conf['hxo'].'/';
        $l_ler_a='C:\Windows\System32\drivers\etc\hosts';
    }
}
?>
<?php
if($egn=='login') {
    if(isset($_POST['user']) && isset($_POST['pass'])) {
        $user=daddslashes($_POST['user']);
        $pass=daddslashes($_POST['pass']);
        $code=daddslashes($_POST['code']);
        if(strpos($user,'"') || strpos($user,"'") || strpos($user,',') || strpos($user,'/') || strpos($user,"\\"))exit('{"code":"账号不能包含危险字符！"}');
        $wedsv=mn_format_host_row($DB->get_row("SELECT * FROM MN_zj WHERE user='$user' limit 1"));
        if ($conf['yzme']!='false' && $code != $_SESSION['authcode']) {
            unset($_SESSION['authcode']);
            @header('Content-Type: text/html; charset=UTF-8');
            exit('{"code":"验证码错误！"}');
        } elseif($user==$wedsv['user'] && $pass==$wedsv['pass']) {
            unset($_SESSION['authcode']);
            $session=md5($user.$pass.$password_hash);
            $token=authcode("{$user}\t{$session}", 'ENCODE', SYS_KEY);
            setcookie("user_token", $token, time() + 604800);
            @header('Content-Type: text/html; charset=UTF-8');
            exit('{"code":"登陆成功"}');
        } else {
            @header('Content-Type: text/html; charset=UTF-8');
            exit('{"code":"用户不存在或密码错误！"}');
        }
    } elseif(isset($_POST['logout'])) {
        setcookie("user_token", "", time() - 604800);
        @header('Content-Type: text/html; charset=UTF-8');
        exit('{"code":"您已成功注销本次登陆！"}');
    }
}
?>
<?php
if($yhc['hxc']=='1') {
    //CDN用户能进行的操作
    if($egn=='tjurl') {
        $yz_ip=daddslashes($_POST['yz_ip']);
        $url=str_replace(' ','',$_POST['url']);
        $url=str_replace('    ','',$url);
        preg_match("/\d+\.\d+\.\d+\.\d+/",$url,$ure);
        preg_match("/\d+\.\d+\.\d+\.\d+/",$yz_ip,$ip_yzp);
        if (!preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d))))$/', $yz_ip))exit('{"code":"源站IP不合法！"}');
        if($url==$cert['btip'] || $ure[0]==$cert['btip'] || $ip_yzp[0]==$cert['btip'])exit('{"code":"禁止添加宝塔IP！"}');
        $mhend=strripos($url,':');
    if(is_numeric($mhend))$iful=mb_substr($url,0,$mhend);
    else $iful=$url;
        if($iful==$cert['btip'] || $ure[0]==$cert['btip'])exit('{"code":"禁止添加本站IP！"}');
        
        include("./class.php");
        $apie = new bt_api_set($btipe,$btkeye);
        $apic = new bt_api($btipe,$btkeye);
        $ymzce = $apie->GetLogsy($zjid);
        $azxcr='0';
        foreach($ymzce as $val) {
            $azxcr++;
        }
        if($azxcr>=$yhc['ymbds']+1 && $yhc['ymbds']!='0' && $yhc['ymbds']!='') {
            exit('{"code":"添加失败！域名已达到最大绑定数！"}');
        }
        $bym_list=$DB->query("SELECT * FROM MN_ym order by id desc limit 9999");
        while($res = $DB->fetch($bym_list)) {
            if(strpos($url,$res['url'])!==false) {
                if($res['jg']>0) {
                    exit('{"code":"禁止使用自定义添加本站的售卖二级域名"}');
                } else {
                    //if(!preg_match('/^[0-9a-zA-Z\d-]+(\.[0-9a-zA-Z\d-]+){3}$/',$url))exit('{"code":"CDN域名前缀不合法！'.$url.'"}');
                    $ke_url_fym=true;
                    $ke_url_ym=$res;
                }
            }
        }
        $r_data = $apie->btapi_addym($zjid,$yhc['sqldz'],$url);
        $are=$r_data['status'];
        if($are=='true') {
            $hhf='
';
            //换行符
            $get_host_hq = $apic->GetLogswt($l_ler_a);
            $host_wjnr=$get_host_hq['data'].$hhf.$yz_ip.' '.$url;
            $api->setwj(array($host_wjnr,$l_ler_a));
            $DB->query("INSERT INTO `MN_ym` (`btdh`, `url`, `jg`, `qk`) VALUES ('$ssbt','$url','0','true')");
            exit('{"code":"添加成功"}');
        }else{
            exit('{"code":"添加失败！"}');
        }
    }elseif($egn=='scurl') {
        $url=daddslashes($_POST['url']);
        $port=daddslashes($_POST['port']);
        include("./class.php");
        $apie = new bt_api_set($btipe,$btkeye);
        $r_data = $apie->btapi_delym($zjid,$yhc['sqldz'],$url,$port);
        $are=$r_data['status'];
        if($are=='true') {
            $DB->query("DELETE FROM `MN_ym` WHERE `url`='$url'");
            exit('{"code":"删除成功"}');
        }else{
            exit('{"code":"删除失败！"}');
        }
    }elseif($egn=='seturl') {
        $zym=daddslashes($_POST['zym']);
        $jqz=daddslashes($_POST['jqz']);
        $xqz=daddslashes($_POST['xqz']);
        $yip=daddslashes($_POST['yz_ip']);
        $port=daddslashes($_POST['port']);
        include("./class.php");
        $apie = new bt_api_set($btipe,$btkeye);
        $r_data = $apie->btapi_xgym($zjid,$yhc['sqldz'],$jqz,$xqz,$port);
        $are=$r_data['status'];
        if($are=='true') {
            $hhf='
';
            //换行符
            $get_host_hq = $apie->GetLogswt($l_ler_a);
            $host_wjnr=$get_host_hq['data'];
            $host_wjnr=str_replace($zym.' '.$yip,$xqz.' '.$yip,$host_wjnr);
            $api->setwj(array($host_wjnr,$l_ler_a));
            $DB->query("UPDATE `MN_ym` SET `url` = '$xqz' WHERE `url` = '$zym'");
            exit('{"code":"修改成功"}');
        }else{
            exit('{"code":"修改失败！"}');
        }
    }
} else {
    //主机用户能进行的操作
    if($egn=='sxsyxx') {
        //刷新网页空间，数据库空间，流量使用情况
        include("../class.php");
        $sql_kjr=json_decode($yhc['hxb'],true);
        $web_kjr=json_decode($yhc['hxa'],true);
        $ll_kjr=json_decode($yhc['llmax'],true);
        $api = new bt_api($btipe,$btkeye);
        if($yhc['hxc']!='1') {
            $r_data = $api->webkjjs($os_xt.$yhc['sqldz']);
            $webkj=$r_data['size']/(1024*1000);
            $r_js_web=$web_kjr;
            $r_js_web['dq']=sprintf("%.2f",$webkj);
            $r_sy=json_encode($r_js_web,256);
            $t_id=$yhc['id'];
            $sql_w="update `MN_zj` set `hxa` ='{$r_sy}' where `id`='{$t_id}'";
            $r_datb = $api->sqlkjhq($yhc['sqluser']);
            if(substr($r_datb['data_size'],'-2' , 2)=='kb' || substr($r_datb['data_size'],'-2' , 2)=='KB' || substr($r_datb['data_size'],'-2' , 2)=='kB' || substr($r_datb['data_size'],'-2' , 2)=='Kb') {
                $sqlkj= str_ireplace(substr($r_datb['data_size'],'-2' , 2),'',$r_datb['data_size']);
            } elseif(substr($r_datb['data_size'],'-2' , 2)=='b' || substr($r_datb['data_size'],'-2' , 2)=='B') {
                $sqlkj= str_ireplace(substr($r_datb['data_size'],'-2' , 2),'',$r_datb['data_size'])/1000;
            } elseif(substr($r_datb['data_size'],'-2' , 2)=='MB' || substr($r_datb['data_size'],'-2' , 2)=='mb' || substr($r_datb['data_size'],'-2' , 2)=='Mb' || substr($r_datb['data_size'],'-2' , 2)=='mB') {
                $sqlkj= str_ireplace(substr($r_datb['data_size'],'-2' , 2),'',$r_datb['data_size'])*1000;
            } else {
                $sqlkj='0';
            }
            $adft=$sqlkj/1024;
            $r_js_sql=$sql_kjr;
            $r_js_sql['dq']=sprintf("%.2f",$adft);
            $r_sy=json_encode($r_js_sql,256);
            $t_id=$yhc['id'];
            $sql_s="update `MN_zj` set `hxb` ='{$r_sy}' where `id`='{$t_id}'";
        }
        $s_data=$api->getlog($yhc['sqldz']);
        if($s_data['status'] && $s_data['msg']!='') {
            $sfyr=explode(' - - ',$s_data['msg']);
            unset($sfyr[0]);
            foreach($sfyr as $vfm) {
                $arrMatches=[];
                preg_match_all('/(?:\[)(.*)(?:\])/i', $vfm, $arrMatches);
                $e_size=explode(' ',$vfm);
                if(!is_numeric($e_size[6]))continue;
                $g_size+=$e_size[6];
                if($arrMatches[0]==$r_js['statistics']) {
                    $g_size=0;
                }
                $g_data=$arrMatches[0];
            }
        } else {
            $g_size='0';
            $g_size=$ll_kjr['statistics'];
        }
        $r_jy=$ll_kjr;
        $ll_kjr['dq']=$g_size;
        $ll_kjr['statistics']=$g_size;
        $r_sy=json_encode($ll_kjr,256);
        $t_id=$yhc['id'];
        $sql="update `MN_zj` set `llmax` ='{$r_sy}' where `id`='{$t_id}'";
        if($yhc['hxc']!='1') {
            $DB->query($sql);
            $DB->query($sql_w);
        }
        $DB->query($sql_s);
        if($ll_kjr['dq']<=$ll_kjr['max']*1024*1024*1024 && $r_js_web['dq']<=$r_js_web['max'] && $r_js_sql['dq']<=$r_js_sql['max']) {
            //依旧是超出
            $api->qdweb($yhc['btid'],$yhc['sqldz']);
            $api->ftpxg($yhc['ftpid'],$yhc['user'],'1');
        } else {
            //解除超出
            $api->ztweb($yhc['btid'],$yhc['sqldz']);
            $api->ftpxg($yhc['ftpid'],$yhc['user'],'0');
        }
        exit('{"code":"刷新成功！"}');
    }elseif($egn=='setyxml') {
        //设置运行目录
        $szh=daddslashes($_POST['wb']);
        if(substr($szh,0,1)!='/')exit('{"code":"目录格式错误！"}');
        
        // 验证运行目录
        $validation = validateRunPath($szh);
        if (!$validation['valid']) {
            exit('{"code":"' . $validation['error'] . '"}');
        }
        
        include("./class.php");
        $api = new bt_api($btipe,$btkeye);
        $abc=$api->setyxml([$yhc['btid'],$szh,$os_xt.$yhc['sqldz']]);
        exit('{"code":"'.$abc['msg'].'"}');
    }elseif($egn == "databasedownload")
    {
        include("./class.php");
        $filename = daddslashes($_POST['filename']);
        $name = daddslashes($_POST['name']);
        $bt_api = new bt_api($btipe,$btkeye);
        $r_data = $bt_api->Databasedownload($filename);
        if($r_data['status'])
        {
            // 创建临时下载文件
            $temp_path = sys_get_temp_dir() . '/' . $name . '_' . time() . '.sql';
            file_put_contents($temp_path, $r_data['data']);
            
            // 返回下载URL
            $download_url = 'data:application/sql;base64,' . base64_encode($r_data['data']);
            exit(json_encode(["qk"=>1,'url'=>$download_url,'name'=>$name],256));
        }
        else
        {
            exit(json_encode(["qk"=>4,'code'=>"下载失败，请联系管理员"],256));
        }
    }
}
else {
    exit('{"code":"请求错误！"}');
}
?>