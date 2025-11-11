<?php
/*
 *本文件为控制面板功能性操作文件
 *©梦奈
*/
include("../MPHX/common.php");
@header('Content-Type: text/html; charset=UTF-8');
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
        if (!preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/', $yz_ip))exit('{"code":"源站IP不合法！"}');
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
            $get_host_xg = $apic->GetLogswh($host_wjnr,$l_ler_a);
            $get_fxdl_add = $apic->fxdl_add($url,$yhc['sqldz']);
            if($ke_url_fym) {
                $bs_tj=json_decode($ke_url_ym['json'],true);
                array_push($bs_tj,$yhc['user']);
                $tj_jg=json_encode($bs_tj,256);
                $ddxx_url=$ke_url_ym['id'];
                $sqlyecl="update `MN_ym` set `json` ='$tj_jg' where `id`='{$ddxx_url}'";
                $DB->query($sqlyecl);
            }
            exit('{"code":"添加成功"}');
        } else exit('{"code":"添加失败'.$r_data['msg'].'"}');
    } elseif($egn=='scurl') {
        $url=daddslashes($_POST['url']);
        $dk=daddslashes($_POST['port']);
        if($url==$yhc['sqldz']) {
            exit('{"code":"禁止删除主机名称"}');
        }
        include("./class.php");
        $apie = new bt_api_set($btipe,$btkeye);
        $apic = new bt_api($btipe,$btkeye);
        $r_data = $apie->btapi_delym($zjid,$yhc['sqldz'],$url,$dk);
        $are=$r_data['status'];
        if($are=='true') {
            $get_host_hq = $apic->GetLogswt($l_ler_a);
            $kh='
';
            //换行符
            $arysz=explode($kh,$get_host_hq['data']);
            foreach($arysz as $val) {
                if(!strpos($val,' '.$url) && $val!='') {
                    $ayrt.=$val.$kh;
                }
            }
            $get_host_xg = $apic->GetLogswh($ayrt,$l_ler_a);
            $get_fxdl_del = $apic->fxdl_del($url,$yhc['sqldz']);
            exit('{"code":"删除成功"}');
        } else exit('{"code":"删除失败'.$are['msg'].'"}');
    } 
    
    elseif($egn == "mailbd")
{
    $mailuser = daddslashes($_POST['mail']);
    $user = $yhc['user'];
    if($DB->query("UPDATE `MN_zj` SET `mailuser` = '$mailuser' WHERE `user` = '$user'"))
    {
        exit('{"code":"绑定成功"}');
    }
    else
    {
        exit('{"code":"绑定失败,请联系开发者查询失败原因"}');
    }
}
    
    elseif($egn=='seturl') {
        $url_zy=daddslashes($_POST['zym']);
        $url_jqz=daddslashes($_POST['jqz']);
        $url_xqz=daddslashes($_POST['xqz']);
        $xurl=$url_xqz.'.'.$url_zy;
        $durl=$url_jqz.'.'.$url_zy;
        $dk=daddslashes($_POST['port']);
        if($durl==$yhc['sqldz']) {
            exit('{"code":"禁止删除主机名称"}');
        }
        if(!preg_match('/^[0-9a-zA-Z]{1,24}$/',$url_xqz) ||    !preg_match('/^[0-9a-zA-Z]{1,24}$/',$url_jqz))exit('{"code":"域名前缀不合法！'.$url.'"}');
        include("./class.php");
        $apie = new bt_api_set($btipe,$btkeye);
        $apic = new bt_api($btipe,$btkeye);
        $ymzce = $apie->GetLogsy($zjid);
        $azxcr='0';
        foreach($ymzce as $val) {
            $azxcr++;
        }
        if($azxcr>$yhc['ymbds']+1 && $yhc['ymbds']!='0' && $yhc['ymbds']!='') {
            exit('{"code":"添加失败！域名已达到最大绑定数！请删除多限制域名"}');
        }
        $r_data = $apie->btapi_delym($zjid,$yhc['sqldz'],$durl,$dk);
        $are=$r_data['status'];
        if($are=='true') {
            $url=str_replace(' ','',$xurl);
            $url=str_replace('    ','',$url);
            preg_match("/\d+\.\d+\.\d+\.\d+/",$url,$ure);
            if($url==$cert['btip'] || $ure[0]==$cert['btip'])exit('{"code":"禁止添加本站IP！"}');
            $get_host_hq = $apic->GetLogswt($l_ler_a);
            $kh='
';
            //换行符
            $arysz=explode($kh,$get_host_hq['data']);
            $thhs=str_replace($durl,$url,$arysz);
            foreach($thhs as $val) {
                if($val!='') {
                    $ayrt.=$val.$kh;
                }
            }
            $get_host_xg = $apic->GetLogswh($ayrt,$l_ler_a);
            $get_fxdl_del = $apic->fxdl_del($durl,$yhc['sqldz']);
            $r_data = $apie->btapi_addym($zjid,$yhc['sqldz'],$url.':'.$dk);
            $get_fxdl_add = $apic->fxdl_add($url,$yhc['sqldz']);
            $are=$r_data['status'];
            if($are=='true')exit('{"code":"添加成功"}'); else exit('{"code":"添加失败'.$r_data['msg'].'"}');
        } else exit('{"code":"删除失败'.$are['msg'].'"}');
}elseif($egn=='indexconf'){           //控制面板首页-信息获取
    $webkj=json_decode($yhc['hxa'],true);
    $sqlkj=json_decode($yhc['hxb'],true);
    $llskj=json_decode($yhc['llmax'],true);
    //php版本获取
    include("./class.php");
    $apist = new bt_api_set($btipe,$btkeye);
    $r_data = $apist->btapi_listphp();
    unset($r_data[0]);            //由于纯静态通过APi切换后再切换为其他PHP版本部分宝塔会报错，等待宝塔官方修复这个问题，所以暂时关闭纯静态选项
    unset($r_data[1]);            //关闭自定义选项
    $r_datc = $apist->btapi_phpnowz($yhc['sqldz']);     //当前PHP版本
    $sitexx = $apist->sitemsg($yhc['sqldz']);     //网站信息
    $arr=[];
    $arr['qk']=$sitexx['msg']['status'];
    $arr['gg']=$conf['gg'];
    $arr['type']=$yhc['hxc'];
    $arr['web']=$webkj;
    $arr['sql']=$sqlkj;
    $arr['lls']=$llskj;
    $arr['config']['url']=$yhc['ymbds'];
    $arr['config']['ftp']['host']=$cert['ftpdz']==false?$cert['btip']:$cert['ftpdz'];
    $arr['config']['ftp']['user']=$yhc['user'];
    $arr['config']['ftp']['pass']=$yhc['pass'];
    $arr['config']['sql']['user']=$yhc['sqluser'];
    $arr['config']['sql']['pass']=$yhc['sqlpass'];
    $arr['php']=['dq'=>$r_datc['phpversion'],'list'=>$r_data];
    exit(json_encode(["qk"=>1,'code'=>"获取成功！",'msg'=>$arr],256));
    
    } else exit('{"code":"CDN产品无法进行此操作"}');
}
?>
<?php
if($egn=='urllist') {
    //站点域名列表
    $type=$_POST['type']==false ? 3 : $_POST['type'] ;
    include("./class.php");
    $apie = new bt_api_set($btipe,$btkeye);
    $api = new bt_api($btipe,$btkeye);
    $list = $apie->btapi_ym($zjid);
    $listz = $api->urlzmlls($zjid);
    //子目录域名
    $arr=[];
    if($type==2 || $type==3) {
        foreach ($list as $val) {
            //域名
            if($val['name']==$yhc['sqldz'])continue;
            $arr['url'][]=["name"=>$val['name'],"port"=>$val['port'],"addtime"=>$val['addtime'],"path"=>'/'];
        }
    }
    if($type==1 || $type==3) {
        foreach ($listz['binding'] as $val) {
            //子目录域名
            $arr['url'][]=["name"=>$val['domain'],"port"=>$val['port'],"addtime"=>$val['addtime'],"path"=>$val['path']];
        }
    }
    array_unshift($listz['dirs'],'/');
    $arr['dir']=$listz['dirs'];
    exit(json_encode($arr,256));
} elseif($egn=='hqzmlls') {
    //获取当前运行目录(非/)下的子目录列表
    $arr=[];
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $yxml = $api->yxmlrhq($zjid,$os_xt.$yhc['sqldz'])['runPath']['runPath'];
    //获取运行目录
    if($yxml!='/') {
        $listz = $api->urlzmlls($zjid);
        //子目录域名
        foreach ($listz['binding'] as $val) {
            //子目录
            if(substr($val['path'],0,3)!='../') {
                $arr[]=$val['domain'];
            }
        }
    }
    if(empty($arr)) {
        exit('false');
    } else {
        exit(json_encode($arr,256));
    }
} elseif($egn=='erurl') {
    $bym_list=$DB->query("SELECT * FROM MN_ym WHERE btdh='$ssbt' and qk='true' order by id desc limit 9999");
    $arr=[];
    while($res = $DB->fetch($bym_list)) {
        $arr[]=["url"=>$res['url'],"jg"=>$res['jg'],"jj"=>$res['js']];
    }
    exit(json_encode($arr,256));
} elseif($egn=='tjurl') {
    $path=$_POST['dirs'];
    if($path==false)exit('{"code":"子目录不得为空！"}');
    $url=str_replace(' ','',$_POST['url']);
    $url=str_replace('    ','',$url);
    preg_match("/\d+\.\d+\.\d+\.\d+/",$url,$ure);
    $mhend=strripos($url,':');
    if(is_numeric($mhend))$iful=mb_substr($url,0,$mhend);
    else $iful=$url;
        if($iful==$cert['btip'] || $ure[0]==$cert['btip'])exit('{"code":"禁止添加本站IP！"}');
    include("./class.php");
    $apie = new bt_api_set($btipe,$btkeye);
    $api = new bt_api($btipe,$btkeye);
    $ymzce = array_merge($apie->GetLogsy($zjid),$api->urlzmlls($zjid)['binding']);
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
                //if(!preg_match('/^[0-9a-zA-Z\d-]+(\.[0-9a-zA-Z\d-]+){3}$/',$url))exit('{"code":"域名前缀不合法！"}');
                $ke_url_fym=true;
                $ke_url_ym=$res;
            }
        }
    }
    if(strpos($path,'/') !==false) {
        $r_data = $apie->btapi_addym($zjid,$yhc['sqldz'],$url);
    } else {
        $r_data = $api->addzml($zjid,$url,$path,$os_xt.$yhc['sqldz']);
    }
    //print_r($r_data);
    $are=$r_data['status'];
    //logjl($user,'域名添加','添加的域名为'.$url.'','添加成功',$DB);
    if($are=='true') {
        if($ke_url_fym) {
            $bs_tj=json_decode($ke_url_ym['json'],true);
            array_push($bs_tj,$yhc['user']);
            $tj_jg=json_encode($bs_tj,256);
            $ddxx_url=$ke_url_ym['id'];
            $sqlyecl="update `MN_ym` set `json` ='$tj_jg' where `id`='{$ddxx_url}'";
            $DB->query($sqlyecl);
        }
        exit('{"code":"添加成功"}');
    } else exit('{"code":"添加失败'.$r_data['msg'].'"}');
} elseif($egn=='scurl') {
    $url=daddslashes($_POST['url']);
    $dk=daddslashes($_POST['port']);
    $path=daddslashes($_POST['dir']);
    if($url==$yhc['sqldz']) {
        exit('{"code":"禁止删除主机名称"}');
    }
    include("./class.php");
    $apie = new bt_api_set($btipe,$btkeye);
    $api = new bt_api($btipe,$btkeye);
    if($path=='/') {
        $r_data = $apie->btapi_delym($zjid,$yhc['sqldz'],$url,$dk);
    } else {
        $r_data = $api->delzml($zjid,$url,$os_xt.$yhc['sqldz']);
    }
    $are=$r_data['status'];
    if($are=='true')exit('{"code":"删除成功"}'); else exit('{"code":"删除失败'.$r_data['msg'].'"}');
} elseif($egn=='seturl') {
    $url_zy=daddslashes($_POST['zym']);
    //主域
    $url_jqz=daddslashes($_POST['jqz']);
    //旧前缀
    $url_xqz=daddslashes($_POST['xqz']);
    //新前缀
    $url_path=daddslashes($_POST['path']);
    //新前缀
    $xurl=$url_xqz.'.'.$url_zy;
    //新域名
    $durl=$url_jqz.'.'.$url_zy;
    //旧域名
    $dk=daddslashes($_POST['port']);
    if($durl==$yhc['sqldz']) {
        exit('{"code":"禁止删除主机名称"}');
    }
    if(!preg_match('/^[0-9a-zA-Z]{1,24}$/',$url_xqz) ||    !preg_match('/^[0-9a-zA-Z]{1,24}$/',$url_jqz))exit('{"code":"域名前缀不合法！'.$url.'"}');
    include("./class.php");
    $apie = new bt_api_set($btipe,$btkeye);
    $api = new bt_api($btipe,$btkeye);
    $ymzce = array_merge($apie->GetLogsy($zjid),$api->urlzmlls($zjid)['binding']);
    $azxcr='0';
    $jpath='/';
    foreach($ymzce as $val) {
        if($val['domain']==$durl)$jpath=$val['path'];
        $azxcr++;
    }
    if($azxcr>$yhc['ymbds']+1 && $yhc['ymbds']!='0' && $yhc['ymbds']!='') {
        exit('{"code":"添加失败！域名已达到最大绑定数！如想继续添加则请删除多余闲置域名！"}');
    }
    if($jpath=='/') {
        $r_data = $apie->btapi_delym($zjid,$yhc['sqldz'],$durl,$dk);
    } else {
        $r_data = $api->delzml($zjid,$durl,$os_xt.$yhc['sqldz']);
    }
    $are=$r_data['status'];
    if($are=='true') {
        $url=str_replace(' ','',$xurl);
        $url=str_replace('    ','',$url);
        preg_match("/\d+\.\d+\.\d+\.\d+/",$url,$ure);
        if($url==$cert['btip'] || $ure[0]==$cert['btip'])exit('{"code":"禁止添加本站IP！"}');
        $ymzce = array_merge($apie->GetLogsy($zjid),$api->urlzmlls($zjid)['binding']);
        $azxcr='0';
        foreach($ymzce as $val) {
            $azxcr++;
        }
        if($azxcr>=$yhc['ymbds']+1 && $yhc['ymbds']!='0' && $yhc['ymbds']!='') {
            exit('{"code":"添加失败！域名已达到最大绑定数！如想继续添加则请删除多余闲置域名！"}');
        }
        if(strpos($url_path,'/') !==false) {
            $r_data = $apie->btapi_addym($zjid,$yhc['sqldz'],$url.':'.$dk);
        } else {
            $r_data = $api->addzml($zjid,$url.':'.$dk,$url_path,$os_xt.$yhc['sqldz']);
        }
        $are=$r_data['status'];
        if($are=='true')exit('{"code":"添加成功"}'); else exit('{"code":"添加失败'.$r_data['msg'].'"}');
    } else exit('{"code":"删除失败'.$are['msg'].'"}');
} elseif($egn=='ftpsc') {
    //删除文件/目录
    $lx=daddslashes($_POST['lx']);
    $filepath=daddslashes($_POST['path']);
    $filename=trim(daddslashes($_POST['name']));
    if(substr($filepath,0,1)!='/')exit('{"code":"目录格式错误！"}');
    if(strpos($filename,'/')!==false)exit('{"code":"文件名格式错误！"}');
    if($filename=='.user.ini' && $lx=='file')exit('{"code":"错误！您在删除配置文件(.user.ini)！这是不被允许的！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    if($lx=='file') {
        $r_data = $api->delwj($os_xt.$yhc['sqldz'].$filepath.$filename);
    } else {
        $r_data = $api->delwjj($filepath,$filename,[$yhc['btid'],$os_xt.$yhc['sqldz']]);
    }
    echo $r_data['status']=='true' ? '{"code":"删除成功"}' : '{"code":"'.$r_data['msg'].'"}';
    exit;
} elseif($egn=='ftpscxz') {
    //删除多个文件/目录
    $idsze=daddslashes($_POST['idsz']);
    //被删除的文件(数组)
    $path=daddslashes($_POST['path']);
    if(substr($path,0,1)!='/')exit('{"code":"目录格式错误！"}');
    if(empty($idsze))exit('{"code":"您未选择需要删除的文件或目录！"}');
    if(in_array('.user.ini',$idsze))exit('{"code":"错误！您在删除配置文件(.user.ini)！这是不被允许的！"}');
    if($path==null)exit('{"code":"目录错误！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $wjne=json_encode($idsze,256);
    $r_data = $api->xzdelwj($path,$wjne,[$yhc['btid'],$os_xt.$yhc['sqldz']]);
    //print_r($wjlj);
    if($r_data['status'])exit('{"code":"删除成功"}'); else exit('{"code":"'.$r_data['msg'].'"}');
} elseif($egn=='phpxg') {
    //修改PHP版本
    $php=daddslashes($_POST['php']);
    include("./class.php");
    $apie = new bt_api_set($btipe,$btkeye);
    $r_data = $apie->btapi_setphp($yhc['sqldz'],$php);
    exit('{"code":"修改成功"}');
} elseif($egn=='sqldr') {
    //导入SQL文件
    $ml=daddslashes($_POST['path']);
    $name=daddslashes($_POST['filename']);
    if(substr($ml,0,1)!='/')exit('{"code":"目录格式错误！"}');
    if(strpos($name,'/')!==false)exit('{"code":"文件名格式错误！"}');
    if(substr(strtolower($name),'-4' , 4)!='.sql')exit('{"code":"错误！您导入的文件不是SQL文件！"}');
    $path = $os_xt.$yhc['sqldz'].$ml;
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $list=dirfiles($api->GetLogshqwjlo($path)['FILES'],'file')['file'];
    $file=false;
    foreach($list as $val) {
        if($val['name']==$name) {
            $file=$val;
            break;
        }
    }
    if(!$file)exit('{"code":"错误！文件不存在！"}');
    $sqlsize=json_decode($yhc['hxb'],true);
    $mbsize=round($file['size']/1048576);
    if($mbsize>$sqlsize['max'])exit('{"code":"错误！导入的文件大于您的最大可用数据库空间！"}');
    if($sqlsize['max']<=$sqlsize['dq'])exit('{"code":"错误！您的数据库空间已满！"}');
    if($mbsize>$sqlsize['max']-$sqlsize['dq'])exit('{"code":"错误！导入的文件大于您现在可用的数据库空间大小！请清除数据库空间至剩余'.$mbsize.'MB为止！"}');
    $r_datr = $api->drsql(array($path.$name,$yhc['sqluser']));
    exit('{"code":"'.$r_datr['msg'].'"}');
} elseif($egn=='scmmfw') {
    //删除密码访问
    $setname=$_POST['mb'];
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $r_data = $api->GetLogsr($zjid,$setname);
    exit('{"code":"'.$r_data['msg'].'"}');
} elseif($egn=='tjmmfw') {
    //添加密码访问
    $name=$_POST['name'];
    $ml=$_POST['mbml'];
    $zh=$_POST['user'];
    $mm=$_POST['pass'];
    if(substr($ml,0,1)!='/')exit('{"code":"目录格式错误！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $r_data = $api->GetLogst($zjid,$name,$ml,$zh,$mm);
    exit('{"code":"'.$r_data['msg'].'"}');
} elseif($egn=='xgmrwd') {
    //修改默认文档
    $index=$_POST['ml'];
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $r_data = $api->GetLogsea($zjid,$index);
    exit('{"code":"'.$r_data['msg'].'"}');
} elseif($egn=='hqjt') {
    //获取伪静态
    $tdxz=$_POST['xz']!='0.当前'?'rewrite/nginx/'.$_POST['xz']:'vhost/rewrite/'.$yhc['sqldz'];
    $jt='/www/server/panel/'.$tdxz.'.conf';
    if($cert['btos']=='1') {
        $jt='/www/server/panel/'.$tdxz.'.conf';
    } else {
        $jt='D:/BtSoft/panel/'.$tdxz.'.conf';
    }
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $r_data = $api->GetLogswt($jt);
    exit($r_data['data']);
} elseif($egn=='setwjt') {
    //设置伪静态
    include("./class.php");
    if($cert['btos']=='1') {
        //$jt='/www/server/panel/'.$tdxz.'.conf';
        $api = new bt_api($btipe,$btkeye);
        $r_data = $api->setwjt([$_POST['wb'],'/www/server/panel/vhost/rewrite/'.$yhc['sqldz'].'.conf']);
        exit('{"code":"'.$r_data['msg'].'"}');
    } else {
        //$jt='/www/server/panel/'.$tdxz.'.conf';
        $api = new win_bt_api($btipe,$btkeye);
        $r_data = $api->setwjt([$yhc['sqldz'],$_POST['wb']]);
        exit('{"code":"'.$r_data['msg'].'"}');
    }
} elseif($egn=='ftpjy') {
    //解压文件
    $ywj=$_POST['jywj'];
    $jyd=$_POST['jyd'];
    $jypass=$_POST['jymm'];
    $jybm=$_POST['wjbm'];
    if(substr($jyd,0,1)!='/')exit('{"code":"解压到的目录格式错误！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $r_data = $api->GetLogsjywj($os_xt.$yhc['sqldz'].$ywj,$os_xt.$yhc['sqldz'].$jyd,$jybm,$jypass);
    exit('{"code":"解压成功"}');
} elseif($egn=='xgpass') {
    //修改密码
    $ftpmm=daddslashes($_POST['ftp']);
    $sqlmm=daddslashes($_POST['sql']);
    if(mb_strlen($ftpmm)<6 && mb_strlen($ftpmm)!=0 || mb_strlen($sqlmm)<6 && mb_strlen($sqlmm)!=0 )exit('{"code":"错误！FTP密码和数据库密码都不能小于6位！"}');
    $user=$yhc['user'];
    if(empty($ftpmm) && empty($sqlmm))exit('{"code":"错误！FTP密码和SQL密码不能全为空！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    if(empty($ftpmm)) {
        $pass=$yhc['pass'];
    } else {
        $api->GetLogsftp($yhc['ftpid'],$yhc['user'],$ftpmm);
        $pass=$ftpmm;
    }
    if(empty($sqlmm)) {
        $gpwd=$yhc['sqlpass'];
    } else {
        $api->GetLogsworld($yhc['hxd'],$yhc['sqluser'],$sqlmm);
        $gpwd=$sqlmm;
    }
    $encryptedSqlPass = mn_encrypt($gpwd);
    $encryptedFtpPass = mn_encrypt($pass);
    $sql="update `MN_zj` set `sqlpass` ='{$encryptedSqlPass}', `pass` ='{$encryptedFtpPass}' where `user`='{$user}'";
    if($DB->query($sql)) exit('{"code":"修改成功"}'); else exit('{"code":"修改失败"}');
} elseif($egn=='setyxml') {
    //设置运行目录
    $szh=daddslashes($_POST['wb']);
    if(substr($szh,0,1)!='/')exit('{"code":"目录格式错误！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $abc=$api->setyxml([$yhc['btid'],$szh,$os_xt.$yhc['sqldz']]);
    exit('{"code":"'.$abc['msg'].'"}');
} elseif($egn=='yjbs') {
    //一键部署
    $id=daddslashes($_POST['id']);
    $zxwc_ms_h='3';
    //每次远程操作执行完成后等待的秒数
    $res=$DB->get_row("SELECT * FROM MN_bs WHERE id='$id' limit 1");
    if($res['qk']!=true)exit('{"code":"禁止部署已经下架的程序"}');
    $pz_jx_json=json_decode($res['sxpz'],true);
    $pz_web_a=$pz_jx_json[0];
    $pz_sql_a=$pz_jx_json[1];
    if($pz_web_a>json_decode($yhc['hxa'],true)['max'] || $pz_sql_a>json_decode($yhc['hxa'],true)['max'])exit('{"code":"您的配置未达到要求！"}');
    if(!in_array($yhc['user'],json_decode($res['tj'],true)) && $res['jg']!=0)exit('{"code":"您未购买该程序"}');
    include("./fn.php");
    //引入代码执行库
    include("./class.php");
    //引入数据处理库
    $api = new bt_api($btipe,$btkeye);
    //实例化类
    $userform=formcl($res['inp'],$_POST['bds']);
    //获取变量与用户提交数据的的对应数组
    if($userform['code']=='0')exit(json_encode(['qk'=>4,"code"=>$userform['msg']]));
    $file=$res['cxwz'];
    $type='application/zip';
    $name=basename($file);
    //获取文件名称
    $wj=$api->GetLogshqwjlo($os_xt.$yhc['sqldz']);
    //网站目录下所有文件
    $nameh=array();
    $sl=0;
    foreach ($wj['DIR'] as $val) {
        $nameb=explode(";",$val);
        array_push($nameh,$nameb[0]);
    }
    foreach ($wj['FILES'] as $val) {
        $nameb=explode(";",$val);
        if($nameb[0]!='.user.ini') {
            array_push($nameh,$nameb[0]);
        }
        //不删除防跨站配置文件
    }
    $json=json_encode($nameh);
    $c1=$api->xzdelwj('/',$json,[$yhc['btid'],$os_xt.$yhc['sqldz']]);
    //删除该站点所有文件
    sleep($zxwc_ms_h);
    $c2=$api->zswjsc(array('tmp_name'=>$file,'type'=>$type,'name'=>$name),$os_xt.$yhc['sqldz']);
    //上传源码
    sleep($zxwc_ms_h);
    $c3=$api->GetLogsjywj($os_xt.$yhc['sqldz'].'/'.$name,$os_xt.$yhc['sqldz'],'UTF-8','');
    //解压文件
    sleep($zxwc_ms_h);
    $c4=$api->delwj($os_xt.$yhc['sqldz'].'/'.$name);
    //删除源码
    $c5=$api->setyxml([$yhc['btid'],'/',$os_xt.$yhc['sqldz']]);
    //将运行目录设置为根目录
    $c6 = $api->setwjt(['','/www/server/panel/vhost/rewrite/'.$yhc['sqldz'].'.conf']);
    //将伪静态设置为空
    $bs=1;
    if($res['pz']!='null') {
        foreach (json_decode($res['pz'],true) as $val) {
            $funcz=$val['cz'];
            //获取该执行哪个函数
            $ab=$funcz($val,$yhc,$os_xt,$userform);
            //对数据进行处理
            if($funcz!='gettj') {
                $abc=$api->$funcz($ab);
                //执行操作
                if($abc['status']==false) {
                    exit(json_encode(['code'=>"部署失败：第{$bs}步出错，错误提示：{$abc['msg']}"]));
                    break;
                }
            }
            $bs++;
            sleep('0.5');
            //此处只用等待0.5秒即可
        }
    }
    $bs_tj=json_decode($res['tj'],true);
    if(!in_array($yhc['user'],$bs_tj)) {
        $bs_tj[]=$yhc['user'];
    }
    $tj=json_encode($bs_tj,256);
    $DB->query("update `MN_bs` set `tj` ='{$tj}'where `id`='{$id}'");
    if($res['alet']!=null) {
        exit(json_encode(['qk'=>1,"code"=>tihs($res['alet'],$userform,$yhc)]));
    } else {
        exit(json_encode(['qk'=>1,"code"=>'程序部署成功！']));
    }
} elseif($egn=='xjwj') {
    //新建文件
    $name=daddslashes($_POST['wjname']);
    $ml=daddslashes($_POST['ml']);
    if(substr($ml,0,1)!='/')exit('{"code":"目录格式错误！"}');
    if(strpos($name,'/')!==false)exit('{"code":"文件名格式错误！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $abc=$api->xjwj($os_xt.$yhc['sqldz'].$ml.$name);
    exit('{"code":"'.$abc['msg'].'"}');
} elseif($egn=='xjwjj') {
    //新建文件夹
    $name=daddslashes($_POST['wjname']);
    $ml=daddslashes($_POST['ml']);
    if(substr($ml,0,1)!='/')exit('{"code":"目录格式错误！"}');
    if(strpos($name,'/')!==false)exit('{"code":"文件名格式错误！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $abc=$api->xjwjj($os_xt.$yhc['sqldz'].$ml.$name);
    exit('{"code":"'.$abc['msg'].'"}');
} 
elseif($egn=='hqwj') {
    //获取文件内容
    $lw=daddslashes($_POST['wj']);
    if(substr($lw,0,1)!='/')exit('{"code":"文件不存在！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $abc=$api->hqwjnr($os_xt.$yhc['sqldz'].$lw);
    exit($abc['data']);
} elseif($egn=='setwj') {
    //修改文件内容
    $lm=daddslashes($_POST['wj']);
    if(substr($lm,0,1)!='/')exit('{"code":"被修改文件不存在！"}');
    $nr=$_POST['nr'];
    //赋值时不对文件内容进行转义
    if(strpos($lm,'.user.ini')!==false)exit('{"code":"错误！您在修改配置文件(.user.ini)！这是不被允许的！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $abc=$api->setwj(array($nr,$os_xt.$yhc['sqldz'].$lm));
    exit('{"code":"'.$abc['msg'].'"}');
} elseif($egn=='hqdx') {
    //获取文件大小
    $lw=daddslashes($_POST['dw']);
    if(substr($lw,0,1)!='/')exit('{"code":"文件所在目录错误！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $abc=$api->hqsize($os_xt.$yhc['sqldz'].$lw);
    exit('{"code":"'.$abc['size'].'"}');
} elseif($egn=='setname') {
    //重命名文件
    $name=daddslashes($_POST['wjmc']);
    $jmc=daddslashes($_POST['wjjm']);
    $lj=$_POST['lj']=='' ? '/' : daddslashes($_POST['lj']);
    if(substr($lj,0,1)!='/')exit('{"code":"目录格式错误！"}');
    if(strpos($jmc,'/')!==false)exit('{"code":"旧文件名格式错误！"}');
    if(strpos($name,'/')!==false)exit('{"code":"新文件名格式错误！"}');
    if($jmc=='.user.ini')exit('{"code":"错误！您在重命名配置文件(.user.ini)！这是不被允许的！"}');
    if($name=='.user.ini')exit('{"code":"错误！该文件(.user.ini)已存在！"}');
    if($name==null)exit('{"code":"文件名禁止为空！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $abc=$api->cxname(array($os_xt.$yhc['sqldz'],$lj,$jmc,$name));
    exit('{"code":"'.$abc['msg'].'"}');
} elseif($egn=='file_upload_size') {
    //判断文件是否为断点续传
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $path=$_POST['htl'];
    $file_name=trim($_POST['fename']);
    if(substr($path,0,1)!='/')exit('{"code":"目录格式错误！"}');
    if($file_name==='' || strpos($file_name,'/')!==false)exit('{"code":"文件名格式错误！"}');
    if($file_name==='.user.ini')exit('{"code":"禁止上传.user.ini配置文件！"}');
    $abcm=$api->fileupa($os_xt.$yhc['sqldz'].$path.$_POST['fename'].'.'.$_POST['size'].'.upload.tmp');
    $asei=$abcm['status'] ? $abcm['msg']['size'] : 0;
    echo json_encode(['code'=>1,'size'=>$asei]);
} elseif($egn=='fileupload') {
    //上传文件
    if(substr($_POST['htl'],0,1)!='/')exit(json_encode(['error'=>1,'size'=>4,'msg'=>'目录格式错误！']));
    if(strpos($_POST['tempfilename'],'/')!==false)exit(json_encode(['error'=>1,'size'=>4,'msg'=>'上传的文件名格式错误！']));
    
    if(in_array($_POST['tempfilename'],['.user.ini','.user.ini.upload.tmp']))exit(json_encode(['error'=>1,'size'=>4,'msg'=>'禁止上传.user.ini配置文件！']));
    
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    if($_FILES['file']==null)exit(json_encode(['error'=>1,'size'=>4,'msg'=>'上传的文件不能为空']));
    $websize=json_decode($yhc['hxa'],true);
    $mbsize=round($_POST['zsize']/1048576);
    if($mbsize>$websize['max'])exit(json_encode(['error'=>1,'size'=>4,'msg'=>'错误！上传的文件大于您的最大可用网页空间！故无法上传此文件']));
    if($websize['max']<=$websize['dq'])exit(json_encode(['error'=>1,'size'=>4,'msg'=>'错误！网页空间已满！']));
    if($mbsize>$websize['max']-$websize['dq'])exit(json_encode(['error'=>1,'size'=>4,'msg'=>'错误！上传的文件大于现在您当前可使用的网页空间！请清除空间至剩余'.$mbsize.'MB后再试']));
    $abc=$api->fileups($os_xt.$yhc['sqldz'].$_POST['htl'],$_FILES['file'],$_POST['fesw'],$_POST['tempfilename'],$_POST['zsize']);
    if(is_numeric($abc)) {
        echo json_encode(['error'=>0,'size'=>$abc]);
    } else {
        echo json_encode(['error'=>1,'size'=>1,'msg'=>'上传成功！']);
    }
} elseif($egn=='sxsyxx') {
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
        } elseif(substr($r_datb['data_size'],'-2' , 2)=='MB' || substr($r_datb['data_size'],'-2' , 2)=='mb' || substr($r_datb['data_size'],'-2' , 2)=='Mb' || substr($r_datb['data_size'],'-2' , 2)=='Mb') {
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
    $ll_kjr['dq']+=$g_size;
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
} elseif($egn=='listfile') {
    $sorting=$_POST['sortOrder']=='asc' ? 'False' : 'True';
    //顺序或倒序
    $paixu=$_POST['sort']=='type' ? 'name' : $_POST['sort'];
    //排序字段
    $pagesize=$_POST['limit'];
    $page=$_POST['page'];
    $path=$_POST['path'];
    if(substr($path,0,1)!='/')exit('{"code":"目录格式错误！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $contents=$api->GetLogshqwjlo($os_xt.$yhc['sqldz'].$path,$sorting,$paixu,$pagesize,$page);
    //当前目录下所有文件
    if($contents['PATH']==$conf['hxi'] || $contents['PATH']==$conf['hxo']) {
        $contents=$api->GetLogshqwjlo($os_xt.$yhc['sqldz'],$sorting,$paixu,$pagesize,$page);
        $paths='/';
    } else {
        $paths=$path;
    }
    $dir=dirfiles($contents['DIR'],'dir');
    $file=dirfiles($contents['FILES'],'file');
    $dirfile=array_merge($dir['file'],$file['file']);
    //合并数组
    $zzbds=preg_match('/共(\d+)条/', $contents['PAGE'], $matches);
    $val=$matches[1];
    $data=array("total"=>$val,"path"=>$paths);
    $data["rows"]=$dirfile;
    exit(json_encode($data,256));
} elseif($egn=='filecp') {
    //这里不使用宝塔自带的多文件复制，因为标记功能1个主机复制文件另外一个主机粘贴文件会出现跨站点复制文件
    $yfile=$_POST['yfile'];
    $ypath=$_POST['ypath'];
    $xpath=$_POST['xpath'];
    $type=$_POST['type'];
    //1为复制，2为剪切
    if(empty($yfile))exit('{"qk":"4","code":"错误！您未选择任何文件！"}');
    if(substr($ypath,0,1)!='/')exit('{"qk":"4","code":"原目录格式错误！"}');
    if(substr($xpath,0,1)!='/')exit('{"qk":"4","code":"新的目录格式错误！"}');
    if(in_array('.user.ini',$yfile))exit('{"qk":"4","code":"错误！您在操作根目录的配置文件(.user.ini)！这是不被允许的！"}');
    if(empty($yfile) || empty($ypath) || empty($xpath) || empty($type))exit('{"qk":"4","code":"错误！禁止留空！"}');
    if($ypath==$xpath)exit('{"qk":"4","code":"错误！原目录与粘贴目录不能相同！"}');
    if($xpath!='/') {
        foreach ($yfile as $val) {
            if(substr($xpath,0,mb_strlen($ypath.$val.'/'))==$ypath.$val.'/')exit('{"qk":"4","code":"错误的逻辑，从'.$ypath.$val.'粘贴到'.$xpath.'有包含关系，存在无限循环复制风险！"}');
        }
    }
    include("./class.php");
    $yes=0;
    $no=0;
    $api = new bt_api($btipe,$btkeye);
    foreach ($yfile as $val) {
        $abc=$api->filecopy($os_xt.$yhc['sqldz'].$ypath.$val,$os_xt.$yhc['sqldz'].$xpath.$val);
        if($abc['status']) {
            $yes++;
        } else {
            $no++;
        }
    }
    if($type==2) {
        $api->xzdelwj($ypath,json_encode($yfile,256),[$yhc['btid'],$os_xt.$yhc['sqldz']]);
        //删除原文件
        $czname='剪切';
    } else {
        $czname='复制';
    }
    if($no==0) {
        $msg=$czname.'成功！';
        $qk=1;
    } else {
        $msg="<span class='text-success'>{$czname}成功{$yes}个文件，</span>{$czname}失败{$no}个文件";
        $qk=4;
    }
    exit('{"qk":"'.$qk.'","code":"'.$msg.'"}');
} elseif($egn=='fileys') {
    //文件压缩
    $filename=$_POST['file'];
    $dpath=$_POST['dpath'];
    $type=$_POST['type'];
    $path=$_POST['path'];
    if(substr($dpath,0,1)!='/')exit('{"qk":"4","code":"目录格式错误！"}');
    if(substr($path,0,1)!='/')exit('{"qk":"4","code":"目录格式错误！"}');
    if(empty($filename) || empty($dpath) || empty($type) || empty($path))exit('{"qk":"4","code":"错误！禁止留空！"}');
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $zfc='';
    foreach ($filename as $val) {
        if($zfc=='') {
            $zfc.=$val;
        } else {
            $zfc.=','.$val;
        }
    }
    $data=$api->fileysr($zfc,$os_xt.$yhc['sqldz'].$dpath,$type,$os_xt.$yhc['sqldz'].$path);
    if($data['status']) {
        $qk=1;
    } else {
        $qk=4;
    }
    exit('{"qk":"'.$qk.'","code":"'.$data['msg'].'"}');
} elseif($egn=='listurl') {
    //获取域名列表(包含子目录)
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $data=$api->urllist($yhc['btid']);
    $arr=[];
    foreach ($data['domains'] as $val) {
        if($val['name']!=$yhc['sqldz']) {
            $arr['domains'][]=["name"=>$val['name']];
        }
    }
    exit(json_encode($arr,256));
} elseif($egn=='sqssl') {
    //申请/续签/SSL证书
    $urllist=$_POST['list'];
    $type=$_POST['type'];
    if(empty($urllist))exit(json_encode(["qk"=>4,'code'=>'未选择域名！'],256));
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    if($type=='false') {
        //申请
        $datas=$api->getsslpem($yhc['sqldz']);
        //获取SSL是否开启
        if($datas['status'])exit(json_encode(["qk"=>4,'code'=>'错误！SSL已开启！如需继续申请请先关闭SSL(申请成功后将覆盖现有密钥和证书)！'],256));
        $data=$api->sslsq(json_encode($urllist),$yhc['btid'],$yhc['sqldz'],false);
    } else {
        //续签
        $data=$api->sslsq(json_encode($urllist),$yhc['btid'],$yhc['sqldz'],true);
    }
    if($data['status']) {
        exit(json_encode(["qk"=>1,'code'=>$data['msg'][0]],256));
    } else {
        exit(json_encode(["qk"=>4,'code'=>$data['msg'][0]],256));
    }
} elseif($egn=='setssl') {
    //设置key和pem
    $key=$_POST['key'];
    $pem=$_POST['pem'];
    if($key==null || $pem==null)exit(json_encode(["qk"=>4,'code'=>'错误！禁止留空！'],256));
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $data=$api->setsslpem($yhc['sqldz'],$key,$pem);
    if($data['status']) {
        exit(json_encode(["qk"=>1,'code'=>$data['msg']],256));
    } else {
        exit(json_encode(["qk"=>4,'code'=>$data['msg']],256));
    }
} elseif($egn=='getssl') {
    //获取证书配置
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $data=$api->getsslpem($yhc['sqldz']);
    exit(json_encode(["key"=>$data['key'],"csr"=>$data['csr'],"httpTohttps"=>$data['httpTohttps'],"status"=>$data['status'],"cert_data"=>$data['cert_data'],"type"=>$data['type']],256));
} elseif($egn=='clossl') {
    //关闭SSL
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $data=$api->closessl($yhc['sqldz']);
    if($data['status']) {
        exit(json_encode(["qk"=>1,'code'=>$data['msg']],256));
    } else {
        exit(json_encode(["qk"=>4,'code'=>$data['msg']],256));
    }
} elseif($egn=='httpsqz') {
    //开启/关闭强制https
    $kg=$_POST['qk'];
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $data=$api->httpsqzf($yhc['sqldz'],$kg);
    if($data['status']) {
        exit(json_encode(["qk"=>1,'code'=>$data['msg']],256));
    } else {
        exit(json_encode(["qk"=>4,'code'=>$data['msg']],256));
    }
} elseif($egn=='yjbsform') {
    //一键部署-表单获取
    $id=$_POST['id'];
    $res=$DB->get_row("SELECT * FROM MN_bs WHERE id='$id' limit 1");
    if(!$res['qk'])exit(json_encode(["qk"=>4,'code'=>"无法部署已经下架从程序！"],256));
    $pz_jx_json=json_decode($res['sxpz'],true);
    $pz_web_a=$pz_jx_json[0];
    $pz_sql_a=$pz_jx_json[1];
    if($pz_web_a>json_decode($yhc['hxa'],true)['max'] || $pz_sql_a>json_decode($yhc['hxa'],true)['max'])exit(json_encode(["qk"=>4,'code'=>"您的配置未达到要求"],256));
    if(!in_array($yhc['user'],json_decode($res['tj'],true)) && $res['jg']!=0)exit(json_encode(["qk"=>4,'code'=>"您未购买该程序！"],256));
    exit(json_encode(["qk"=>1,'code'=>"获取成功！",'form'=>$res['inp']],256));
}
elseif($egn == "fdlkg")
{
    include("./class.php");
    $id = $zjid;
    $name = $yhc['sqldz'];
    $fix = $_POST['fix'];
    $domains = $_POST['domains'];
    $return_rule = $_POST['return_rule'];
    $httpsta = $_POST['http_status'];
    $status = $_POST['status'];
    $bt_api = new bt_api($btipe,$btkeye);
    $r_data = $bt_api->Setfdlkg($id,$name,$fix,$domains,$status,$return_rule,$httpsta);
    exit('{"code":"'.$r_data['msg'].'"}');
}
elseif($egn == "getfdl")
{
    include("./class.php");
    $bt_api = new bt_api($btipe,$btkeye);
    $r_data = $bt_api->getfdlkg($zjid,$yhc['sqldz']);
    exit(json_encode($r_data));
}
elseif($egn == "databasedel")
{
    include("./class.php");
    $id = $_POST['id'];
    $bt_api = new bt_api($btipe,$btkeye);
    $r_data = $bt_api->DatabaseDelete($id);
    if($r_data['msg'] == "删除成功")
    {
        $user = $yhc['user'];
        $backup_data = $DB->get_row("select * from MN_zj where user='$user'");
        $backup_max = json_decode($backup_data['backup'],true)["max"];
        $backup_dq = json_decode($backup_data['backup'],true)["dq"];
        if($backup_dq<=$backup_max && $backup_dq>0)
        {
            $backup_cz_array = json_decode($backup_data['backup'],true);
            $backup_cz_array['dq'] = $backup_dq - 1;
            $backup_cz_json = json_encode($backup_cz_array);
            $DB->query("UPDATE `MN_zj` SET `backup` = '$backup_cz_json' WHERE `user` = '$user'");
            exit('{"code":"'.$r_data['msg'].'"}');
        }
        exit('{"code":"'.$r_data['msg'].'"}');
    }
    else
    {
        exit('{"code":"宝塔那边出现了一点小问题请联系开发人员判断错误"}');
    }
}
elseif($egn == "databaseadd")
{
    include("./class.php");
    $id = daddslashes($_POST['id']);
    $user = $yhc['user'];
    $backup_data = $DB->get_row("select * from MN_zj where user='$user'");
    $backup_max = json_decode($backup_data['backup'],true)["max"];
    $backup_qd = json_decode($backup_data['backup'],true)["dq"];
    if($backup_qd>=$backup_max)
    {
        exit('{"code":"你的备份次数用完了"}');
    }
    else
    {
        $bt_api = new bt_api($btipe,$btkeye);
        $r_data = $bt_api->Databaseadd($id);
        if($r_data['msg'] == "备份成功!")
        {
            $backup_cz_array = json_decode($backup_data['backup'],true);
            $backup_cz_array['dq'] = $backup_qd + 1;
            $backup_cz_json = json_encode($backup_cz_array);
            $DB->query("UPDATE `MN_zj` SET `backup` = '$backup_cz_json' WHERE `user` = '$user'");
            exit('{"code":"'.$r_data['msg'].'"}');
        }
        else
        {
            exit('{"code":"宝塔那边出现了一点小问题请联系开发人员判断错误"}');
        }
    }
}
elseif($egn == "databaserestore")
{
    include("./class.php");
    $user = daddslashes($_POST['user']);
    $filename = daddslashes($_POST['filename']);
    $bt_api = new bt_api($btipe,$btkeye);
    $r_data = $bt_api->Databaserestore($filename,$user);

    exit('{"code":"'.$r_data['msg'].'"}');
}
elseif($egn == "databaseaq1")
{
    include("./class.php");
    $dataAccess = daddslashes($_POST['dataAccess']);
    $bt_api = new bt_api($btipe,$btkeye);
    $r_data = $bt_api->SetDatabaseAccess($yhc['sqluser'],$dataAccess);
    exit('{"code":"'.$r_data['msg'].'"}');
}
elseif($egn == "Delalldatabase")
{
    $name = $yhc['sqldz'];
    $mysqluser = $yhc['sqluser'];
    $mysqpassword = $yhc['sqlpass'];
    $mysqldb = $mysqluser;
    $conn = mysqli_connect("localhost",$mysqluser,$mysqpassword,$mysqldb);
    if (!$conn) {
        exit('{"code":"数据库连接失败请联系开发人员"}');
    }
    else
    {
        $data = mysqli_query($conn,"SHOW TABLES");
        while($row = mysqli_fetch_array($data))
        {
            $table = $row[0];
            mysqli_query($conn,"DROP TABLE IF EXISTS $table");
        }
        exit('{"code":"删除成功"}');
    }
}
elseif($egn == "mailbd")
{
    $mailuser = daddslashes($_POST['mail']);
    $user = $yhc['user'];
    if($DB->query("UPDATE `MN_zj` SET `mailuser` = '$mailuser' WHERE `user` = '$user'"))
    {
        exit('{"code":"绑定成功"}');
    }
    else
    {
        exit('{"code":"绑定失败,请联系开发者查询失败原因"}');
    }
}
elseif($egn == "fzjh")
{
    include("./class.php");
    $bt_api = new bt_api($btipe,$btkeye);
    $r_data = $bt_api->Getnginx($yhc['sqldz']);
    exit('{"code":"'.$r_data['msg'].'"}');
    
}elseif($egn=='indexconf'){           //控制面板首页-信息获取
    $webkj=json_decode($yhc['hxa'],true);
    $sqlkj=json_decode($yhc['hxb'],true);
    $llskj=json_decode($yhc['llmax'],true);
    //php版本获取
    include("./class.php");
    $apist = new bt_api_set($btipe,$btkeye);
    $r_data = $apist->btapi_listphp();
    unset($r_data[0]);            //由于纯静态通过APi切换后再切换为其他PHP版本部分宝塔会报错，等待宝塔官方修复这个问题，所以暂时关闭纯静态选项
    unset($r_data[1]);            //关闭自定义选项
    $r_datc = $apist->btapi_phpnowz($yhc['sqldz']);     //当前PHP版本
    $sitexx = $apist->sitemsg($yhc['sqldz']);     //网站信息
    $arr=[];
    $arr['qk']=$sitexx['msg']['status'];
    $arr['gg']=$conf['gg'];
    $arr['type']=$yhc['hxc'];
    $arr['web']=$webkj;
    $arr['sql']=$sqlkj;
    $arr['lls']=$llskj;
    $arr['config']['url']=$yhc['ymbds'];
    $arr['config']['ftp']['host']=$cert['ftpdz']==false?$cert['btip']:$cert['ftpdz'];
    $arr['config']['ftp']['user']=$yhc['user'];
    $arr['config']['ftp']['pass']=$yhc['pass'];
    $arr['config']['sql']['user']=$yhc['sqluser'];
    $arr['config']['sql']['pass']=$yhc['sqlpass'];
    $arr['php']=['dq'=>$r_datc['phpversion'],'list'=>$r_data];
    exit(json_encode(["qk"=>1,'code'=>"获取成功！",'msg'=>$arr],256));
    
}else {
    exit('{"code":"请求错误！"}');
}
?>