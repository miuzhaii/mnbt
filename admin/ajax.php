<?php
include("../MPHX/common.php");
@header('Content-Type: text/html; charset=UTF-8');
$egn=$_POST['gn'];
if($islogin==1 || $egn=='login') {
} else exit('{"code":"请登陆"}');
?>
<?php
if($egn=='login') {
    if(isset($_POST['user']) && isset($_POST['pass'])) {
        $user=daddslashes($_POST['user']);
        $pass=daddslashes($_POST['pass']);
        $code=daddslashes($_POST['code']);
        if ($conf['yzm']=='true' && $code != $_SESSION['authcode']) {
            unset($_SESSION['authcode']);
            @header('Content-Type: text/html; charset=UTF-8');
            exit('{"code":"验证码错误"}');
        } elseif($user==$conf['user'] && $pass==$conf['pwd']) {
            unset($_SESSION['authcode']);
            $session=md5($user.$pass.$password_hash);
            $token=authcode("{$user}\t{$session}", 'ENCODE', SYS_KEY);
            setcookie("admin_token", $token, time() + 604800);
            @header('Content-Type: text/html; charset=UTF-8');
            exit('{"code":"登陆成功"}');
        } else {
            unset($_SESSION['authcode']);
            @header('Content-Type: text/html; charset=UTF-8');
            exit('{"code":"用户名或密码错误"}');
        }
    } elseif(isset($_POST['logout'])) {
        setcookie("admin_token", "", time() - 604800);
        @header('Content-Type: text/html; charset=UTF-8');
        exit('{"code":"您已成功注销本次登陆"}');
    }
} elseif($egn=='xtxf') {
    include("./class.php");
    $xf_xx=json_decode($_POST['xx']);
    foreach($xf_xx as $val) {
        if($val=='1') {
            $sql=[];
            $rs=$DB->query("SELECT * FROM MN_bt");
            while($res = $DB->fetch($rs)) {
                //修复网站/修复FTP/数据库ID
                $bt_dh=$res['btdh'];
                $btipe=($res['ptl']=='true'?'https':'http').'://'.$res['btip'].':'.$res['btdk'];
                $btkeye=$res['btmy'];
                $api = new bt_api($btipe,$btkeye);
                $r_sites = $api->sjlist('sites');
                $r_ftps = $api->sjlist('ftps');
                $r_databases = $api->sjlist('databases');

                $sql=[];

                foreach($r_sites['data'] as $v) {
                    $sql[]="update `MN_zj` set `btid` ='{$v['id']}' where `sqldz`='{$v['name']}' and `ssbt`='{$res['btdh']}'";
                }

                foreach($r_ftps['data'] as $v) {
                    $sql[]="update `MN_zj` set `ftpid` ='{$v['id']}' where `user`='{$v['name']}' and `ssbt`='{$res['btdh']}'";
                }

                foreach($r_databases['data'] as $v) {
                    $sql[]="update `MN_zj` set `hxd` ='{$v['id']}' where `user`='{$v['name']}' and `ssbt`='{$res['btdh']}'";
                }
            }
            $sql_text=implode('; ',$sql);
            $DB->query_multi($sql_text);
        } elseif($val=='3') {
            if(file_exists('../user/cs.php') || file_exists('../user/mysql/qadmin.php')) {
                @unlink('../user/cs.php');
                //无用文件删除
                @unlink('../user/mysql/qadmin.php');
                //旧的数据库操作文件删除
                @rmdir('../user/mysql');
                //旧的数据库操作文件删除
                @unlink('../xy.php');
                //协议文件名已经从V1.6版本后改名为xy.html
                @unlink('./log.php');
                //log日志列表文件删除，因为日志功能已暂时废弃
            }
        }
    }
    //程序配置文件修改
    include("../cf_up.php");
    if($_POST['xe']==$mn_conf['xf']['gne']) {
        $mn_conf['xf']['qk']=0;
        $kr_sxy=ary_asd($mn_conf);
        file_put_contents("../cf_up.php",'<?php $mn_conf=array('.$kr_sxy.');?>');
    }
    exit('{"code":"修复完成"}');
} elseif($egn=='setwz') {
    $copy=daddslashes($_POST['gg']);
    $sitename=daddslashes($_POST['qq']);
    $kfqq=daddslashes($_POST['yzm']);
    $zjyx = daddslashes($_POST['zjyx']);
    $sql="update `MN_config` set `gg` ='{$copy}',`qqh` ='{$sitename}',`yzm` ='{$kfqq}',`zjyxbd`='{$zjyx}' where `id`='{$siteid}'";
    //logjl($user,'网站设置','对网站的设置进行了修改','修改成功',$DB);
    if($DB->query($sql))exit('{"code":"修改成功"}'); else exit('{"code":"修改失败'.$DB->error().'"}');
} elseif($egn=='setapi') {
    $apikey=daddslashes($_POST['apikey']);
    $apiqk=daddslashes($_POST['apiqk']);
    $php=daddslashes($_POST['php']);
    $lin=daddslashes($_POST['linux']);
    $win=daddslashes($_POST['windows']);
    //logjl($user,'API设置','对网站的API设置进行了修改','修改成功',$DB);
    $sql="update `MN_config` set `api` ='{$apikey}', `apiqk` ='{$apiqk}', `hxu` ='{$php}', `hxi` ='{$lin}', `hxo` ='{$win}' where `id`='{$siteid}'";
    if($DB->query($sql))exit('{"code":"修改成功"}'); else exit('{"code":"修改失败'.$DB->error().'"}');
} elseif($egn=='setkzmb') {
    $name=daddslashes($_POST['name']);
    $ftp=daddslashes($_POST['ftp']);
    $yzm=daddslashes($_POST['yzm']);
    $kg=daddslashes($_POST['kg']);
    $bq=daddslashes($_POST['bq']);
    if(isset($_FILES['loa'])) {
        move_uploaded_file($_FILES['loa']['tmp_name'],'../imsetes/upload_logo/logo.login.png');
    }
    if(isset($_FILES['lob'])) {
        move_uploaded_file($_FILES['lob']['tmp_name'],'../imsetes/upload_logo/logo.index.png');
    }
    if(isset($_FILES['loc'])) {
        move_uploaded_file($_FILES['loc']['tmp_name'],'../imsetes/upload_logo/logo.head.png');
    }
    $auther=md5($date);
    //logjl($user,'控制面板设置','对主机的控制面板进行了修改','修改成功',$DB);
    $sql="update `MN_config` set `name` ='{$name}', `hxw` ='{$ftp}', `yzme` ='{$yzm}', `kzmbqk` ='{$kg}', `hxp` ='{$bq}', `auther` ='{$auther}' where `id`='{$siteid}'";
    if($DB->query($sql))exit('{"code":"修改成功"}'); else exit('{"code":"修改失败'.$DB->error().'"}');
} elseif($egn=='setzf') {
    $url=daddslashes($_POST['url']);
    $id=daddslashes($_POST['id']);
    $key=daddslashes($_POST['key']);
    $sql="update `MN_config` set `hxe` ='{$url}',`hxr` ='{$id}',`hxt` ='{$key}' where `id`='{$siteid}'";
    //logjl($user,'支付设置','修改了支付对接信息','修改成功',$DB);
    if($DB->query($sql))exit('{"code":"修改成功"}'); else exit('{"code":"修改失败'.$DB->error().'"}');
} elseif($egn=='gl') {
    $yuser=daddslashes($_POST['yuser']);
    $ypass=daddslashes($_POST['ypass']);
    $xuser=daddslashes($_POST['xuser']);
    $xpass=daddslashes($_POST['xpass']);
    if(mb_strlen($xuser)<4 && mb_strlen($xuser)!=0 || mb_strlen($xpass)<6 && mb_strlen($xpass)!=0 )exit('{"code":"错误！新的账号必须大于或等于4位！新的密码必须大于或等于6位！"}');
    if(empty($xuser) && empty($xpass))exit('{"code":"新的账号或密码不能都为空！"}');
    if($yuser!=$conf['user'] || $ypass!=$conf['pwd']) {
        exit('{"code":"您输入的原账号或密码错误！"}');
    }
    if(empty($xuser)) {
        $guser=$conf['user'];
    } else {
        $guser=$xuser;
    }
    if(empty($xpass)) {
        $gpwd=$conf['pwd'];
    } else {
        $gpwd=$xpass;
    }
    $encryptedAdminPwd = mn_encrypt($gpwd);
    $sql="update `MN_config` set `user` ='{$guser}', `pwd` ='{$encryptedAdminPwd}' where `id`='{$siteid}'";
    //logjl($user,'管理修改','修改前账号'.$yuser.'修改前密码'.$ypass,'登陆成功',$DB);
    if($DB->query($sql))exit('{"code":"修改成功"}'); else exit('{"code":"修改失败'.$DB->error().'"}');
} elseif($egn=='logsc') {
    $id=$_POST['id'];
    $sql="DELETE FROM MN_log WHERE id='$id' limit 1";
    if($DB->query($sql))exit('{"code":"删除成功"}'); else exit('{"code":"删除失败'.$DB->error().'"}');
} elseif($egn=='logscxz') {
    $idsz=$_POST['idsz'];
    preg_match_all("/[,]{1}/",$idsz,$arrNum);
    $szgs=count($arrNum[0]);
    $str_arr = explode(',',$idsz);
    $er=0;
    $scqke=0;
    $scqkr=0;
    for ($er=0;$er<$szgs;$er++) {
        $id = $str_arr[$er];
        $sql="DELETE FROM MN_log WHERE id='$id' limit 1";
        if($DB->query($sql)) $scqke++; else $scqkr++;
    }
    exit('{"code":"'.$scqke.'","codr":"'.$scqkr.'"}');
} elseif($egn=='addbt') {
    $ip=daddslashes($_POST['ip']);
    $dk=daddslashes($_POST['dk']);
    $key=daddslashes($_POST['key']);
    $bh=daddslashes($_POST['bh']);
    $btos=daddslashes($_POST['btos']);
    $urlla=daddslashes($_POST['urlla']);
    $ftpdz=daddslashes($_POST['ftpdz']);
    $xieyi=daddslashes($_POST['xieyi']);
    $kg=daddslashes($_POST['kg']);
    $rowe=$DB->get_row("SELECT * FROM MN_bt WHERE 1 order by id desc limit 1");
    $id=$rowe['id']+1;
    $dati='ikj'.$date.mt_rand(100,10000).mt_rand(10,99999).'sql';
    $kiterw=mt_rand(1,100);
    $ktmy=md5($dati);
    $qmk=md5($kiterw);
    $sql = "INSERT INTO `MN_bt` (`id`, `btip`, `btdk`, `btmy`, `date`, `ktmy`, `qmk`, `btdh`, `qk`, `btos`, `als`, `ptl`, `ftpdz`) VALUES ('{$id}', '{$ip}', '{$dk}', '{$key}', '{$date}', '{$ktmy}', '{$qmk}', '{$bh}', '{$kg}', '{$btos}', '{$urlla}', '{$xieyi}', '{$ftpdz}');";
    //logjl($user,'添加宝塔','添加了一个编号为'.$bh.'的宝塔','添加成功',$DB);
    if($DB->query($sql))exit('{"code":"添加成功"}'); else exit('{"code":"添加失败'.$DB->error().'"}');
} elseif($egn=='btsc') {
    $id=$_POST['id'];
    //logjl($user,'删除宝塔','删除了ID为'.$id.'的宝塔',$DB);
    $cres=$DB->get_row("SELECT * FROM MN_bt WHERE id='$id' limit 1");
    $ssbt=$cres['btdh'];
    $sql="DELETE FROM MN_bt WHERE id='$id' limit 1";
    if($DB->query($sql)) {
        $rs=$DB->query("SELECT * FROM MN_zj WHERE ssbt='$ssbt' order by id desc limit 9999");
        while($res = $DB->fetch($rs)) {
            $bjyr=$res['id'];
            $sql="DELETE FROM MN_zj WHERE id='$bjyr' limit 1";
            $DB->query($sql);
        }
        exit('{"code":"删除成功"}');
    } else {
        exit('{"code":"删除失败'.$DB->error().'"}');
    }
} elseif($egn=='btztjc') {
    //宝塔状态检测
    $id=$_POST['btid'];
    $cert=$DB->get_row("SELECT * FROM MN_bt WHERE id='$id' limit 1");
    $btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
    $btkeye=$cert['btmy'];
    include("./class.php");
    $api = new bt_api($btipe,$btkeye);
    $data=$api->ftpcx('MNBT');
    //通过查询FTP数据的方法判断是否可与宝塔正常通信
    if($data['page']) {
        exit('{"qk":1,"titco":"green","code":"通信正常！"}');
    } else {
        if(!$data['msg']) {
            exit('{"qk":4,"titco":"red","code":"通信失败！无任何返回信息！<br/><strong><u>可能</strong>的错误原因</u>：宝塔信息填写错误：<br/>1.宝塔IP那不用填写http://和/！<br/>2.如果面板开启了域名访问则需要在宝塔IP那填写域名。<br/>3.宝塔端口填写的是默认为8888那个！<br/>4.如果你修改了这个端口就请把修改后的端口填写在宝塔端口那！<br/>5.如果您的面板开启了[面板SSL]则请点击右边编辑按钮把安全访问打开！"}');
        } else {
            if(strpos($data['msg'],'您的访问IP为')) {
                exit('{"qk":4,"titco":"red","code":"通信失败！宝塔返回信息：<b>'.$data['msg'].'</b>，错误解决办法：请前往宝塔面板->宝塔设置->API接口处将[ ]中的IP填写进白名单即可！"}');
            } else {
                exit('{"qk":4,"titco":"red","code":"通信失败！宝塔返回信息：<b>'.$data['msg'].'</b>"}');
            }
        }
    }
    print_r($data);
    //exit('{"code":"删除成功"}');
} elseif($egn=='btscxz') {
    $idsz=$_POST['idsz'];
    $scqkr=0;
    $scqke=0;
    foreach($idsz as $id) {
        $cres=$DB->get_row("SELECT * FROM MN_bt WHERE id='$id' limit 1");
        $ssbt=$cres['btdh'];
        $sql="DELETE FROM MN_bt WHERE id='$id' limit 1";
        //logjl($user,'删除宝塔','ID为'.$id.'的宝塔被删除了',$DB);
        if($DB->query($sql)) {
            $rs=$DB->query("SELECT * FROM MN_zj WHERE ssbt='$ssbt' order by id desc limit 9999");
            while($res = $DB->fetch($rs)) {
                $bjyr=$res['id'];
                $sql="DELETE FROM MN_zj WHERE id='$bjyr' limit 1";
                $DB->query($sql);
            }
            $scqke++;
        } else {
            $scqkr++;
        }
    }
    exit('{"code":"'.$scqke.'","codr":"'.$scqkr.'"}');
} elseif($egn=='btsj') {
    $id=$_POST['id'];
    $cres=$DB->get_row("SELECT * FROM MN_bt WHERE id='$id' limit 1");
    exit('{"code":"1","ip":"'.$cres['btip'].'","dk":"'.$cres['btdk'].'","my":"'.$cres['btmy'].'","kg":"'.$cres['qk'].'","btos":"'.$cres['btos'].'"}');
} elseif($egn=='xgjl') {
    $id=daddslashes($_POST['id']);
    $ip=daddslashes($_POST['ip']);
    $dk=daddslashes($_POST['dk']);
    $key=daddslashes($_POST['key']);
    $kg=daddslashes($_POST['kg']);
    $btos=daddslashes($_POST['btos']);
    $urlla=daddslashes($_POST['urlla']);
    $ftpdz=daddslashes($_POST['ftpdz']);
    $xieyi=daddslashes($_POST['xieyi']);
    //logjl($user,'修改宝塔','对ID为'.$id.'的宝塔进行了修改','修改成功',$DB);
    $sql="update `MN_bt` set `btip` ='{$ip}',`btdk` ='{$dk}',`btmy` ='{$key}',`qk` ='{$kg}',`btos` ='{$btos}',`als` ='{$urlla}',`ptl` ='{$xieyi}',`ftpdz` ='{$ftpdz}' where `id`='{$id}'";
    if($DB->query($sql))exit('{"code":"修改成功"}'); else exit('{"code":"修改失败'.$DB->error().'"}');
} elseif($egn=='zjsc') {
    //删除主机
    $id=$_POST['id'];
    $cres=mn_format_host_row($DB->get_row("SELECT * FROM MN_zj WHERE id='$id' limit 1"));
    $ftr=$cres['btid'];
    $sza=$cres['sqldz'];
    $yrez=$cres['ssbt'];
    $cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$yrez' limit 1");
    $btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
    $btkeye=$cert['btmy'];
    if($cert['btos']=='1') {
        $l_ler_a='/etc/hosts';
    } else {
        $l_ler_a='C:\Windows\System32\drivers\etc\hosts';
    }
    include("./class.php");
    //实例化对象
    $api = new bt_api($btipe,$btkeye);
    //获取面板日志
    if($cres['hxc']=='1') {
        $r_datad = $api->urllist($ftr);
        foreach($r_datad as $are) {
            if($are!='' && $are['name']!=$cres['sqldz']) {
                $get_host_hq = $api->hqwjnr($l_ler_a);
                $kh='
';
                //换行符
                $arysz=explode($kh,$get_host_hq['data']);
                foreach($arysz as $val) {
                    if(!strpos($val,' '.$are['name']) && $val!='') {
                        $ayrt.=$val.$kh;
                    }
                }
                $get_host_xg = $api->setwj(array($ayrt,$l_ler_a));
                unset($ayrt);
                unset($val);
                unset($arysz);
                unset($get_host_hq);
            }
        }
    }
    $r_data = $api->delsite($ftr,$sza);
    if($r_data['status']=='1' || $r_data['status']=='true' || $r_data['msg']=='指定站点不存在!') {
        $sql="DELETE FROM MN_zj WHERE id='$id' limit 1";
        //logjl($user,'删除主机','删除了ID为'.$id.'的主机',$DB);
        if($DB->query($sql))exit('{"code":"删除成功"}'); else exit('{"code":"删除失败'.$DB->error().'"}');
    } else {
        exit(json_encode(['code'=>"删除失败！宝塔返回信息：{$r_data['msg']}"],256));
    }
} elseif($egn=='zjscxz') {
    //删除多个主机
    include("./class.php");
    $idsz=$_POST['idsz'];
    $hst_ary=array();
    $scqkr=0;
    $scqke=0;
    foreach($idsz as $id) {
        $cres=mn_format_host_row($DB->get_row("SELECT * FROM MN_zj WHERE id='$id' limit 1"));
        $ftr=$cres['btid'];
        $sza=$cres['sqldz'];
        $yrez=$cres['ssbt'];
        $cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$yrez' limit 1");
        $btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
        $btkeye=$cert['btmy'];
        //实例化对象
        $api = new bt_api($btipe,$btkeye);
        //获取面板日志
        if($cres['hxc']=='1') {
            $r_datad = $api->urllist($ftr);
            foreach($r_datad as $are) {
                if($are!='' && $are['name']!=$sza) {
                    $hst_ary[]=$are['name'];
                }
            }
        }
        $r_data = $api->delsite($ftr,$sza);
        if($r_data['status']=='1' || $r_data['status']=='true') {
            $sql="DELETE FROM MN_zj WHERE id='$id' limit 1";
            //logjl($user,'删除主机','删除了ID为'.$id.'的主机',$DB);
            if($DB->query($sql)) $scqke++; else $scqkr++;
        } else {
            $scqkr++;
        }
    }
    if(isset($hst_ary)) {
        $get_host_hq = $api->hqwjnr('/etc/hosts');
        $kh='
';
        //换行符
        function in_aray($xcz,$arrayr) {
            $fh=0;
            foreach($arrayr as $vav) {
                if(strpos($xcz,' '.$vav)) {
                    $fh++;
                }
            }
            if($fh>0) {
                return true;
            } else {
                return false;
            }
        }
        $arysz=explode($kh,$get_host_hq['data']);
        foreach($arysz as $val) {
            if(!in_aray($val,$hst_ary) && $val!='') {
                $ayrt.=$val.$kh;
            }
        }
        $get_host_xg = $api->setwj(array($ayrt,'/etc/hosts'));
        unset($ayrt);
        unset($val);
        unset($arysz);
        unset($get_host_hq);
    }
    exit('{"code":"'.$scqke.'","codr":"'.$scqkr.'"}');
} elseif($egn=='zjxgjl') {
    //修改主机
    $id=daddslashes($_POST['id']);
    $user=daddslashes($_POST['user']);
    $pass=daddslashes($_POST['pass']);
    $sqluser=daddslashes($_POST['sqluser']);
    $sqlpass=daddslashes($_POST['sqlpass']);
    $ymbds=daddslashes($_POST['ymbds']);
    $datar=daddslashes($_POST['datar']);
    $webkj=daddslashes($_POST['webkj']);
    $sqlkj=daddslashes($_POST['sqlkj']);
    $lldx=daddslashes($_POST['llmax']);
    $kg=daddslashes($_POST['kg']);
    $cres=mn_format_host_row($DB->get_row("SELECT * FROM MN_zj WHERE id='$id' limit 1"));
    $btbh=$cres['ssbt'];
    $cxbt=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$btbh' limit 1");
    $btipe=($cxbt['ptl']=='true'?'https':'http').'://'.$cxbt['btip'].':'.$cxbt['btdk'];
    if(is_numeric($ymbds)) {
    } else {
        $ymbds=0;
    }
    include("./class.php");
    //实例化对象
    $api = new bt_api($btipe,$cxbt['btmy']);
    //获取面板日志
    $r_data = $api->setdqsj($cres['btid'],$datar);
    $de=$r_data['status'];
    if($de=='1' || $de=='true') {
        if($kg=='true') {
            $styi='1';
        } else {
            $styi='0';
        }
        $api->setftpzt($cres['ftpid'],$user,$styi);
        $api->setmysqlpass($cres['hxd'],$sqluser,$sqlpass);
        $api->setftppass($cres['ftpid'],$user,$pass);
        if($kg!=$cres['qk']) {
            if($kg=='true') {
                $api->siteqt($cres['btid'],$cres['sqldz'],true);
                $api->setftpzt($cres['ftpid'],$cres['user'],'1');
            } else {
                $api->siteqt($cres['btid'],$cres['sqldz'],false);
                $api->setftpzt($cres['ftpid'],$cres['user'],'0');
            }
        }
        if($kg=='true' && strtotime($date)-strtotime($datar)<0 && $datar!='0000-00-00') {
            $api->siteqt($cres['btid'],$cres['sqldz'],true);
            $api->setftpzt($cres['ftpid'],$cres['user'],'1');
        }
        $llyl=json_decode($cres['llmax'],true);
        $llyl['max']=$lldx;
        $llde=json_encode($llyl);
        $s_weba=json_decode($cres['hxa'],true);
        $s_weba['max']=$webkj;
        $s_web=json_encode($s_weba);
        $s_sqla=json_decode($cres['hxb'],true);
        $s_sqla['max']=$sqlkj;
        $s_sql=json_encode($s_sqla);
        $encryptedSqlPass = mn_encrypt($sqlpass);
        $encryptedFtpPass = mn_encrypt($pass);
        //logjl($user,'修改主机','对ID为'.$id.'的主机进行了修改','修改成功',$DB);
        @header('Content-Type: text/html; charset=UTF-8');
        $sql="update `MN_zj` set `datae` ='{$datar}', `ymbds` ='{$ymbds}', `hxa` ='{$s_web}', `hxb` ='{$s_sql}', `sqlpass` ='{$encryptedSqlPass}', `pass` ='{$encryptedFtpPass}', `qk` ='{$kg}', `llmax` ='{$llde}' where `id`='{$id}'";
        if($DB->query($sql)) exit('{"code":"修改成功"}'); else exit('{"code":"修改失败"}');
    } else {
        exit('{"code":"修改失败"}');
    }
} elseif($egn=='addzj') {
    //添加主机
    $btdh=daddslashes($_POST['btdh']);
    $user=daddslashes($_POST['user']);
    $pass=daddslashes($_POST['pass']);
    $datae=$_POST['datae']=='' ? '0000-00-00' : daddslashes($_POST['datae']);
    $webdx=json_encode(array('max'=>daddslashes($_POST['webkj']),'dq'=>0));
    $sqldx=json_encode(array('max'=>daddslashes($_POST['sqlkj']),'dq'=>0));
    ;
    $cptype=daddslashes($_POST['cplx']);
    $flowratemax=json_encode(array('max'=>daddslashes($_POST['ll']),'dq'=>0,'statistics'=>false));
    $ymbds=$cptype=='1' ? '1' : daddslashes($_POST['ymbds']);
    $kg=daddslashes($_POST['kg']);
    if($cptype=='1') {
        $cp_eh_lx='CDN';
        $cp_eh_ftp='false';
        $cp_eh_sql='false';
    } else {
        $cp_eh_lx='主机';
        $cp_eh_ftp='true';
        $cp_eh_sql='true';
    }
    $cxbt=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$btdh' limit 1");
    $btipe=($cxbt['ptl']=='true'?'https':'http').'://'.$cxbt['btip'].':'.$cxbt['btdk'];
    $btkeye=$cxbt['btmy'];
    $rowe=$DB->get_row("SELECT * FROM MN_zj WHERE 1 order by id desc limit 1");
    $id=$rowe['id']+1;
    //以下是计算创建网站的名称(防止重复创建失败)
    $hskr=mt_rand(4,10);
    $rqsj=md5($date.$user);
    $wjler=substr($rqsj, $hskr , 3);
    $btserw='mnbt.'.$id.mt_rand(1,999).$wjler;
    include("./class.php");
    //实例化对象
    $api = new bt_api($btipe,$btkeye);
    //目录设置
    $mrwww=$cxbt['btos']=='1' ? $conf['hxi'].'/'.$btserw : $conf['hxo'].'/'.$btserw;
    if($cptype!='1') {
        if(mb_strlen($user)<6 || mb_strlen($pass)<6)exit('{"code":"错误！账号和密码位数均不能小于6位！"}');
        if(!empty($api->databascx($user)['data']) || !empty($api->ftpcx($user)['data']))exit('{"code":"错误！该账号的数据库/FTP已存在！请更换账号！"}');
    }
    //开通网站
    $r_data = $api->webkt($user,$pass,$btserw,$cp_eh_lx,$cp_eh_ftp,$cp_eh_sql,$conf['hxu'],$mrwww);
    $cjqk=$r_data['siteStatus'];
    //创建情况
    $zdide=$r_data['siteId'];
    //站点ID
    //$api->mysqlqx($user,'%');
    //新版本不再需要将MySQL设置为外部访问这种危险行为。
    if($cjqk) {
        //设置到期时间
        $r_datan = $api->setdqsj($zdide,$datae);
        $de=$r_datan['status'];
        if($de=='1' || $de=='true') {
            //获取FTP/数据库列表
            $r_datn = $api->sjlist('ftps');
            $r_datp = $api->sjlist('databases');
            foreach($r_datn['data'] as $val) {
                if($val['name']===$user) {
                    $aedfs=$val['id'];
                    break;
                }
            }
            foreach($r_datp['data'] as $val) {
                if($val['name']===$user) {
                    $sqlfs=$val['id'];
                    break;
                }
            }
            $rowe=$DB->get_row("SELECT * FROM MN_zj WHERE 1 order by id desc limit 1");
            $id=$rowe['id']+1;
            $encryptedPass = mn_encrypt($pass);
            $encryptedSqlPass = mn_encrypt($pass);
            $sql = "INSERT INTO `MN_zj` (`id`, `ssbt`, `user`, `pass`, `sqluser`, `sqlpass`, `data`, `datae`, `qk`, `btid`, `sqldz`, `ftpid`, `ymbds`, `hxa`, `hxb`, `hxc`, `hxd`, `llmax`) VALUES ('{$id}', '{$btdh}', '{$user}', '{$encryptedPass}', '{$user}', '{$encryptedSqlPass}', '{$date}', '{$datae}', '{$kg}', '{$zdide}', '{$btserw}', '{$aedfs}', '{$ymbds}', '{$webdx}', '{$sqldx}', '{$cptype}', '{$sqlfs}', '{$flowratemax}');";
            //logjl($user,'添加主机','添加了一个ID为'.$id.'的主机','添加成功',$DB);
            if($DB->query($sql))exit('{"code":"添加成功"}'); else exit('{"code":"添加失败'.$DB->error().'"}');
        } else {
            exit('{"code":"添加失败"}');
        }
    } else {
        exit('{"code":"网站创建失败！'.$r_data['msg'].'"}');
    }
} elseif($egn=='update') {
    include("../MPHX/BL.php");
    include("../MPHX/SQ.php");
    include("../cf_up.php");
    $gxtj = array(
        'url' => $_SERVER['HTTP_HOST'],
        'authcode' => $authcode,
        'ver' => $WEBQB,
        );
    $result = send_post($mn_conf['aet'].'://'.$mn_conf['url'].':'.$mn_conf['port'].'/check.php',$gxtj);
    $query=json_decode($result, true);
    if($query['code']=="1") {
        //file_put_contents("gxwj.zip",$blh);
        copy($query['file'],'gxwj.zip');
        $url = $_SERVER['PHP_SELF'];
        $filenamet= str_ireplace('ajax.php', '', $url);
        $filename= str_ireplace('/', '', $filenamet);
        //exit('{"code":"'.$filename.'"}');
        if(rename('../'.$filename.'/','../admin/')) {
            $file = "gxwj.zip";
            $outPath = "../";
            $zip = new ZipArchive();
            $openRes = $zip->open($file);
            if ($openRes === TRUE) {
                $zip->extractTo($outPath);
                $zip->close();
                unlink($file);
                rename('../admin/','../'.$filename.'/');
                $path='../update/update.sql';
                if(file_exists($path)) {
                    function insert($file,$database,$name,$root,$pwd) {
                        header("Content-type: text/html; charset=utf-8");
                        $_sql = file_get_contents($file);
                        $_arr = explode(';', $_sql);
                        $_mysqli = new mysqli($name,$root,$pwd,$database);
                        //第一个参数为域名，第二个为用户名，第三个为密码，第四个为数据库名字 
                        if (mysqli_connect_errno()) {
                            exit('连接数据库出错');
                        } else {
                            $_mysqli->query('set names utf8;');
                            foreach ($_arr as $_value) {
                                $_mysqli->query($_value.';');
                            }
                        }
                        $_mysqli->close();
                        $_mysqli = null;
                    }
                    insert($path,$dbconfig['user'],$dbconfig['host'],$dbconfig['dbname'],$dbconfig['pwd']);
                    unlink($path);
                    @rmdir('../update/');
                }
                exit('{"code":"更新成功～请手动刷新页面"}');
            } else {
                exit('{"code":"解压失败"}');
            }
        } else {
            exit('{"code":"失败"}');
        }
    } else {
        exit('{"code":"无法更新"}');
    }
} elseif($egn=='cxtj') {
    //一键部署添加
    $cxname=daddslashes($_POST['cxname']);
    //程序名称
    $cxjs=daddslashes($_POST['cxjs']);
    //程序介绍
    $cxrmb=daddslashes($_POST['cxrmb']);
    //程序价格
    $cxwebkj=daddslashes($_POST['cxwebkj']);
    //程序所需最低网页空间
    $cxsqlkj=daddslashes($_POST['cxsqlkj']);
    //程序所需最低数据库空间
    $alerts=daddslashes($_POST['alerts']);
    //程序部署完成后的提示
    $kg=daddslashes($_POST['kg']);
    //程序是否上架
    if($kg=='on' || $kg=='true') {
        $kg='true';
    } else {
        $kg='false';
    }
    //开关所代表的意思进行修改
    if(!isset($cxname) || !isset($cxjs) || !isset($cxrmb) || !isset($cxwebkj) || !isset($cxsqlkj) || !isset($kg)   || $_FILES['filecx']=='') {
        exit('{"code":100,"msg":"禁止留空"}');
    }
    $rowse=$DB->get_row("SELECT * FROM MN_bs WHERE name='{$cxname}' limit 1");
    if(isset($rowse)) {
        exit('{"code":100,"msg":"该程序名称已被其他程序占用"}');
    }
    $wjdhlmym=strtolower(substr(strrchr($_FILES['filecx']['name'], '.'), 1));
    //获取文件标识名并转换为小写
    if($wjdhlmym!='zip' && $wjdhlmym!='7z' && $wjdhlmym!='rar' && $wjdhlmym!='gzip' && $wjdhlmym!='jar') {
        exit('{"code":100,"msg":"源码类型错误：目前仅支持zip,7z,rar,gzip,jar类型的压缩包"}');
    }
    if(is_dir('../filecx')) {
    } else {
        mkdir('../filecx');
    }
    //判断根目录下的filecx文件夹是否存在
    for ($isx=1;$isx<200;$isx++) {
        $sf = $date.mt_rand(16,999).$cxname.$cxjs.$cxarmb;
        $rqsj=md5($sf);
        $hsks=mt_rand(0,5);
        $hskr=mt_rand(6,10);
        $wjjm=substr($rqsj, $hsks , 8);
        //算法计算文件夹名称
        if(!is_dir('../filecx/'.$wjjm)) {
            $isx=2000;
            break;
        }
        //避免目录名称重复
    }
    $sfjgf='../filecx/'.$wjjm;
    //此程序所在的文件夹
    mkdir($sfjgf);
    //新建文件夹
    //下面开始程序源码的保存
    $cxxiname=$sfjgf.'/'.'cxym.'.$wjdhlmym;
    //源码文件的路径以及名称
    copy($_FILES['filecx']['tmp_name'],$cxxiname);
    //拷贝文件
    unlink($_FILES['filecx']['tmp_name']);
    //删除原文件
    //程序的保存到此结束 程序的源码名称统一为cxym标识名取原名称
    unset($_FILES['filecx']);
    //去掉上传的程序源码的数组
    mkdir($sfjgf.'/tp');
    //在程序文件夹中新建一个文件夹用来存储图片
    $acft='0';
    //新建一个变量用来标识每张图片的名称每次循环完成后都会+1
    $szaie=array();
    //创建一个空的数组
    foreach ($_FILES['imgfile']['tmp_name'] as $aryqs=>$_value) {
        //下面开始图片的保存
        $cxxname=$sfjgf.'/tp/'.$acft.'.'.substr(strrchr($_FILES['imgfile']['name'][$aryqs], '.'), 1);
        //图片文件的路径以及名称
        copy($_value,$cxxname);
        //拷贝图片
        unlink($_value);
        //删除原文件
        //图片的保存到此结束 图片的名称为1，2，3依次排序
        $acft++;
        //变量+1
        array_push($szaie,$cxxname);
        //插入一个字符串
    }
    $fn_cz=$_POST['czf'];
    foreach ($fn_cz as $xbd=>$val) {
        if($val['cz']=='setwj' || $val['cz']=='setwjt') {
            //判断操作是否为修改文件内容
            $wjszwz=$sfjgf.'/setwj';
            //此操作文件的所在位置
            mkdir($wjszwz);
            //新建文件
            file_put_contents ($wjszwz.'/'.$xbd.'.setwj',$val['nr']);
            //写入文件内容
            $fn_cz[$xbd]['nr']=$wjszwz.'/'.$xbd.'.setwj';
            //修改数组
        }
    }
    $jsonzd=json_encode(array($cxwebkj,$cxsqlkj));
    //程序所需最低配置
    $jsonsjz=json_encode($fn_cz,256);
    //程序的安装配置
    $jsonipt=daddslashes(json_encode($_POST['bdf'],256));
    //安装前表单填写的配置
    $jsontp=daddslashes(json_encode($szaie,256));
    //程序的图片
    $rowe=$DB->get_row("SELECT * FROM MN_bs WHERE 1 order by id desc limit 1");
    $id=$rowe['id']+1;
    $tj=json_encode(array());
    $sql = "INSERT INTO `MN_bs` (`id`, `name`, `jc`, `src`, `date`, `cxwz`, `sxpz`, `tj`, `jg`, `inp`, `pz`, `alet`, `qk`) VALUES ('{$id}', '{$cxname}', '{$cxjs}', '{$jsontp}', '{$date}', '{$cxxiname}', '{$jsonzd}', '{$tj}', '{$cxrmb}', '{$jsonipt}', '{$jsonsjz}', '{$alerts}', '{$kg}');";
    if($DB->query($sql)) {
        exit('{"code":200,"msg":"添加成功！"}');
    } else {
        exit('{"code":100,"msg":"添加失败'.$DB->error().'"}');
    }
} elseif($egn=='cxxgjl') {
    $id=daddslashes($_POST['id']);
    $name=daddslashes($_POST['cxname']);
    $jc=daddslashes($_POST['cxjc']);
    $web=daddslashes($_POST['webkj']);
    $sql=daddslashes($_POST['sqlkj']);
    $jg=daddslashes($_POST['cxrmb']);
    $alerts=daddslashes($_POST['alerts']);
    $kg=daddslashes($_POST['cxkg']);
    $websql=json_encode(array($web,$sql));
    $cres=$DB->get_row("SELECT * FROM MN_bs WHERE id='$id' limit 1");
    $fn_cz=$_POST['czf'];
    foreach ($fn_cz as $xbd=>$val) {
        if($val['cz']=='setwj' || $val['cz']=='setwjt') {
            //判断操作是否为修改文件内容
            $sfjgf='../filecx/'.explode("/",$cres['cxwz'])[2];
            $wjszwz=$sfjgf.'/setwj';
            //此操作文件的所在位置
            @unlink($wjszwz.'/'.$xbd.'.setwj');
            //删除文件
            if(is_dir($wjszwz)) {
            } else {
                mkdir($wjszwz);
            }
            //判断setwj文件夹是否存在
            file_put_contents ($wjszwz.'/'.$xbd.'.setwj',$val['nr']);
            //新建并写入文件内容
            $fn_cz[$xbd]['nr']=$wjszwz.'/'.$xbd.'.setwj';
            //修改数组
        }
    }
    $jsonsjz=json_encode($fn_cz,256);
    //程序的安装配置
    $jsonipt=json_encode($_POST['bdf'],256);
    //安装前表单填写的配置
    $sql="update `MN_bs` set `name` ='{$name}', `jc` ='{$jc}', `jg` ='{$jg}', `inp` ='{$jsonipt}', `sxpz` ='{$websql}', `qk` ='{$kg}', `pz`='{$jsonsjz}', `alet`='{$alerts}' where `id`='{$id}'";
    if($DB->query($sql))exit('{"code":"修改成功"}'); else exit('{"code":"修改失败！"}');
} elseif($egn=='cxsc') {
    $id=$_POST['id'];
    $val=$DB->get_row("SELECT * FROM MN_bs WHERE id='$id' limit 1");
    $sql="DELETE FROM MN_bs WHERE id='$id' limit 1";
    if(explode("/",$val['cxwz'])[2]!=null) {
        deldir('../filecx/'.explode("/",$val['cxwz'])[2]);
    }
    if($DB->query($sql))exit('{"code":"删除成功"}'); else exit('{"code":"删除失败'.$DB->error().'"}');
} elseif($egn=='cxscxz') {
    $idsz=$_POST['idsz'];
    $scqkr=0;
    $scqke=0;
    foreach($idsz as $id) {
        $val=$DB->get_row("SELECT * FROM MN_bs WHERE id='$id' limit 1");
        $sql="DELETE FROM MN_bs WHERE id='$id' limit 1";
        if($DB->query($sql)) {
            if(explode("/",$val['cxwz'])[2]!=null) {
                deldir('../filecx/'.explode("/",$val['cxwz'])[2]);
            }
            $scqke++;
        } else $scqkr++;
    }
    exit('{"code":"'.$scqke.'","codr":"'.$scqkr.'"}');
} elseif($egn=='ddsc') {
    $id=$_POST['id'];
    //logjl($user,'删除订单','删除了ID为'.$id.'的订单',$DB);
    $sql="DELETE FROM MN_dd WHERE id='$id' limit 1";
    if($DB->query($sql))exit('{"code":"删除成功"}'); else exit('{"code":"删除失败'.$DB->error().'"}');
} elseif($egn=='ddscxz') {
    $idsz=$_POST['idsz'];
    $scqkr=0;
    $scqke=0;
    foreach($idsz as $id) {
        $sql="DELETE FROM MN_dd WHERE id='$id' limit 1";
        //logjl($user,'删除订单','ID为'.$id.'的订单被删除了',$DB);
        if($DB->query($sql)) $scqke++; else $scqkr++;
    }
    exit('{"code":"'.$scqke.'","codr":"'.$scqkr.'"}');
} elseif($egn=='addym') {
    $ip=daddslashes($_POST['url']);
    $dk=daddslashes($_POST['bt']);
    $key=daddslashes($_POST['jg']);
    $kg=daddslashes($_POST['kg']);
    $ymjs=daddslashes($_POST['ymjs']);
    $rowe=$DB->get_row("SELECT * FROM MN_ym WHERE 1 order by id desc limit 1");
    $id=$rowe['id']+1;
    $sql = "INSERT INTO `MN_ym` (`id`, `url`, `btdh`, `jg`, `date`, `js`, `json`, `qk`) VALUES ('{$id}', '{$ip}', '{$dk}', '{$key}', '{$date}', '{$ymjs}', '[]', '{$kg}');";
    //logjl($user,'添加域名','添加了'.$url,'添加成功',$DB);
    if($DB->query($sql))exit('{"code":"添加成功"}'); else exit('{"code":"添加失败'.$DB->error().'"}');
} elseif($egn=='xgym') {
    $id=daddslashes($_POST['id']);
    $js=daddslashes($_POST['js']);
    $jg=daddslashes($_POST['jg']);
    $kg=daddslashes($_POST['kg']);
    //logjl($user,'修改宝塔','对ID为'.$id.'的宝塔进行了修改','修改成功',$DB);
    $sql="update `MN_ym` set `js` ='{$js}',`jg` ='{$jg}',`qk` ='{$kg}' where `id`='{$id}'";
    if($DB->query($sql))exit('{"code":"修改成功"}'); else exit('{"code":"修改失败'.$DB->error().'"}');
} elseif($egn=='ymsc') {
    $id=$_POST['id'];
    //logjl($user,'删除域名','删除了ID为'.$id.'的域名',$DB);
    $sql="DELETE FROM MN_ym WHERE id='$id' limit 1";
    if($DB->query($sql))exit('{"code":"删除成功"}'); else exit('{"code":"删除失败'.$DB->error().'"}');
} elseif($egn=='ymscxz') {
    $idsz=$_POST['idsz'];
    $scqkr=0;
    $scqke=0;
    foreach($idsz as $id) {
        $sql="DELETE FROM MN_ym WHERE id='$id' limit 1";
        //logjl($user,'删除域名','ID为'.$id.'的域名被删除了',$DB);
        if($DB->query($sql)) $scqke++; else $scqkr++;
    }
    exit('{"code":"'.$scqke.'","codr":"'.$scqkr.'"}');
} elseif($egn=='gglist') {
    include("../cf_up.php");
    $result = send_post($mn_conf['aet'].'://'.$mn_conf['url'].':'.$mn_conf['port'].'/'.$mn_conf['install_wj'].'/guanggao.php','null');
    echo $result;
} elseif($egn=='mnbt') {
    include("../MPHX/BL.php");
    include("../MPHX/SQ.php");
    include("../cf_up.php");
    $gxtj = array(
        'url' => $_SERVER['HTTP_HOST'],
        'authcode' => $authcode,
        'ver' => $WEBQB,
        );
    $result = send_post($mn_conf['aet'].'://'.$mn_conf['url'].':'.$mn_conf['port'].'/check.php',$gxtj);
    $content=json_decode($result, true);
    $total ='V'.sprintf( "%.2f ",$WEBQB/1000);
    if($content['code']=='0') {
        $cl='mdi-bookmark-check';
        $gx='您使用的已是最新版本！';
    } elseif($content['code']=='1') {
        $cl='mdi-arrow-up-bold-circle';
        $gx='已经有新版本推出！请前往系统管理->系统更新处进行更新';
    } elseif($content['code']=='-1') {
        $cl='mdi-account-off';
        $gx='离线模式不提供更新！！！';
    }
    echo json_encode(['cl'=>$cl,'gx'=>$gx,'vs'=>$total,'gg'=>$content['gg']],256);
} elseif($egn=='listbt') {
    //宝塔列表
    $sorting=$_POST['sortOrder'];
    //顺序或倒序
    $paixu=$_POST['sort'];
    //排序字段
    $pagesize=$_POST['limit'];
    $pageu=($_POST['page']-1) * $pagesize;
    $countdata=$DB->count("SELECT count(*) from MN_bt WHERE 1");
    $data=array("total"=>$countdata);
    $rs=$DB->query("SELECT * FROM MN_bt order by $paixu $sorting limit $pageu,$pagesize");
    while($res = $DB->fetch($rs)) {
        $data["rows"][]=$res;
    }
    exit(json_encode($data));
} elseif($egn=='listzj') {
    //主机列表
    $sorting=$_POST['sortOrder'];
    //顺序或倒序
    $paixu=$_POST['sort'];
    //排序字段
    $pagesize=$_POST['limit'];
    $pageu=($_POST['page']-1) * $pagesize;
    $where=json_decode($_POST['where'],true);       //搜索(type中1为精确，2为模糊)
    $pswhere='';
    if($where['name']!=false && $where['type']!=false && $where['value']!=false){
        if($where['type']!='1' && $where['type']!='2')exit(json_encode(['code'=>4,'msg'=>'搜索方式错误！只能为模糊或者精确搜索！']));
        $zdm=['ssbt','sqldz','user'];
        if(!in_array($where['name'],$zdm))exit(json_encode(['code'=>4,'msg'=>'错误！不存在的搜索字段！']));
        $pswhere='and '.$where['name'].($where['type']=='1'?"='{$where['value']}'":" LIKE '%{$where['value']}%'");
    }
    $countdata=$DB->count("SELECT count(*) from MN_zj WHERE 1 {$pswhere}");
    $data=array("total"=>$countdata);
    $rs=$DB->query("SELECT * FROM MN_zj where 1 {$pswhere} order by $paixu $sorting limit $pageu,$pagesize");
    while($res = $DB->fetch($rs)) {
        // 解密密码字段用于前端显示
        $res['pass_decrypted'] = mn_decrypt($res['pass']);
        $res['sqlpass_decrypted'] = mn_decrypt($res['sqlpass']);
        $data["rows"][]=$res;
    }
    exit(json_encode($data));
} elseif($egn=='listym') {
    //域名列表
    $sorting=$_POST['sortOrder'];
    //顺序或倒序
    $paixu=$_POST['sort'];
    //排序字段
    $pagesize=$_POST['limit'];
    $pageu=($_POST['page']-1) * $pagesize;
    $countdata=$DB->count("SELECT count(*) from MN_ym WHERE 1");
    $data=array("total"=>$countdata);
    $rs=$DB->query("SELECT * FROM MN_ym order by $paixu $sorting limit $pageu,$pagesize");
    while($res = $DB->fetch($rs)) {
        $data["rows"][]=$res;
    }
    exit(json_encode($data));
} elseif($egn=='listbs') {
    //程序列表
    $sorting=$_POST['sortOrder'];
    //顺序或倒序
    $paixu=$_POST['sort'];
    //排序字段
    $pagesize=$_POST['limit'];
    $pageu=($_POST['page']-1) * $pagesize;
    $countdata=$DB->count("SELECT count(*) from MN_bs WHERE 1");
    $data=array("total"=>$countdata);
    $rs=$DB->query("SELECT * FROM MN_bs order by $paixu $sorting limit $pageu,$pagesize");
    while($res = $DB->fetch($rs)) {
        $res['cxdx']=sprintf( "%.2f ",filesize($res['cxwz'])/1048576);
        $fn_cz=json_decode($res['pz'],true);
        if($fn_cz!=null) {
            foreach ($fn_cz as $xbd=>$val) {
                if($val['cz']=='setwj' || $val['cz']=='setwjt') {
                    //判断操作是否为修改文件内容
                    $fn_cz[$xbd]['nr']=file_get_contents($val['nr']);
                    //修改数组
                }
            }
            $res['pz']=json_encode($fn_cz,256);
        }
        $data["rows"][]=$res;
    }
    exit(json_encode($data));
} elseif($egn=='listdd') {
    //订单列表
    $sorting=$_POST['sortOrder'];
    //顺序或倒序
    $paixu=$_POST['sort'];
    //排序字段
    $pagesize=$_POST['limit'];
    $pageu=($_POST['page']-1) * $pagesize;
    $countdata=$DB->count("SELECT count(*) from MN_dd WHERE 1");
    $data=array("total"=>$countdata);
    $rs=$DB->query("SELECT * FROM MN_dd order by $paixu $sorting limit $pageu,$pagesize");
    while($res = $DB->fetch($rs)) {
        if($res['lx']=='yjbs') {
            $ret=json_decode($res['cs'],true);
            $rcx=$ret['gmid'];
            $cres="一键部署-".$DB->get_row("SELECT * FROM MN_bs WHERE id='$rcx' limit 1")['name'];
        } else {
            $cres="域名购置";
        }
        $res["spname"]=$cres;
        $data["rows"][]=$res;
    }
    exit(json_encode($data));
} elseif($egn=='cxdc') {
    //程序导出
    include("../MPHX/BL.php");
    $ids=daddslashes($_POST['id']);
    $dccg=0;
    $dcsb=0;
    //导出成功次数和导出失败次数
    $zipfile='../filecx/export_file.zip';
    //压缩包存放目录
    @unlink($zipfile);
    //删除原来的一个压缩包
    foreach ($ids as $id) {
        $cres=$DB->get_row("SELECT * FROM MN_bs WHERE id='$id' limit 1");
        if($cres==false)exit(json_encode(["code"=>4,"code"=>"错误！程序不存在！"]));
        $arr=[];
        $arr['vs']=$WEBQB;
        $arr['config']=$cres;
        $fileconfig=json_encode($arr,256);
        //配置信息，中文不转义
        $cxpath='../filecx/'.explode("/",$cres['cxwz'])[2];
        //程序所在目录
        $zipqk=zipfile($cxpath,'/','../filecx/export_file.zip',$fileconfig);
        if($zipqk['code']==1) {
            $dccg++;
        } else {
            $dcsb++;
        }
    }
    exit(json_encode(['code'=>1,'msg'=>"打包成功<b>$dccg</b>个程序，打包失败<b class='text-danger'>$dcsb</b>个程序"],256));
} elseif($egn=='cxfiledru') {
    //程序导入
    include("../MPHX/BL.php");
    $ysize=daddslashes($_POST['fesw']);
    //已经上传的大小
    $zsize=daddslashes($_POST['zsize']);
    //总大小
    $file=$_FILES['file'];
    //文件
    $tmp_path='../filecx/file_import_tmp';
    //导入文件解压后的临时存放位置
    $filepath='../filecx/import_file.zip';
    //压缩包存放目录和文件名
    if(is_dir($tmp_path))deldir($tmp_path);
    //删除临时目录
    if(!is_dir($tmp_path))mkdir($tmp_path);
    //新建目录
    if($ysize==0) {
        @unlink($filepath);
        //删除上一次的导入文件
    }
    if(filesize($filepath)<$zsize) {
        $files=file_get_contents($file['tmp_name']);
        //读取
        file_put_contents($filepath, $files, FILE_APPEND | LOCK_EX);
        //写入
        @unlink($file['tmp_name']);
        //删除
    }
    $filesizes=filesize($filepath);
    if($filesizes>$zsize)exit(json_encode(['error'=>1,'size'=>4,'msg'=>'抱歉，我们遇见了一个未知的错误！请重新导入！']));
    if($filesizes<$zsize) {
        //上传未完成，通知继续上传
        exit(json_encode(['error'=>0,'size'=>$filesizes]));
    }
    //解压文件
    $zip = new \ZipArchive;
    if($zip->open($filepath)===true) {
        //打开
        $zip->extractTo($tmp_path);
        //解压
        $zip->close();
        //关闭
        @unlink($filepath);
        //删除压缩包
    } else {
        @unlink($filepath);
        //删除压缩包
        exit(json_encode(['error'=>1,'size'=>4,'msg'=>'错误！压缩包打开失败！']));
    }
    //读取数据导入文件
    $imptlist=scandir($tmp_path);
    //导入的文件列表
    $filelist=scandir('../filecx');
    //原来的文件列表
    if($imptlist[0]=='.')unset($imptlist[0]);
    if($imptlist[1]=='..')unset($imptlist[1]);
    if(empty($imptlist))exit(json_encode(['error'=>1,'size'=>4,'msg'=>'错误！导入文件不存在！']));
    $drcgf=0;
    $drsbf=0;
    //成功，失败
    foreach ($imptlist as $val) {
        if(in_array($val,$filelist)) {
            $drsbf++;
            continue;
        }
        $path=$tmp_path.'/'.$val;
        //导入的文件的目录
        $config_file=$path.'/mnbt_file_conf.json';
        //配置文件
        if(!is_file($config_file)) {
            $drsbf++;
            continue;
        }
        $config=json_decode(file_get_contents($config_file),true)['config'];
        //读取配置文件
        if(!$config) {
            $drsbf++;
            continue;
        }
        @unlink($config_file);
        //删除配置文件
        $sql_name='';
        $sql_nr='';
        foreach ($config as $k=>$v) {
            if($k=='id') {
                //修改id
                $rowe=$DB->get_row("SELECT * FROM MN_bs WHERE 1 order by id desc limit 1");
                $v=$rowe['id']+1;
                unset($rowe);
            }
            if($k=='qk')$v=$_POST['sxj'];
            //修改上下架
            if($k=='name') {
                $rowse=$DB->get_row("SELECT * FROM MN_bs WHERE name='{$cxname}' limit 1");
                if(isset($rowse)) {
                    $sql_name=false;
                    $sql_nr=false;
                    break;
                }
                //判断名称是否重复
            }
            if($sql_name=='') {
                //SQL语句组合
                $sql_name.='`'.$k.'`';
                $sql_nr.="'$v'";
            } else {
                $sql_name.=',`'.$k.'`';
                $sql_nr.=",'$v'";
            }
        }
        if(!$sql_name || !$sql_nr) {
            $drsbf++;
            continue;
        }
        $sql = "INSERT INTO `MN_bs` ({$sql_name}) VALUES ({$sql_nr});";
        if(!rename($path,'../filecx/'.$val)) {
            $drsbf++;
            continue;
        }
        //移动文件
        //写入到数据库中
        if($DB->query($sql)) {
            $drcgf++;
            //成功
        } else {
            $drsbf++;
            //失败
            if(is_dir('../filecx/'.$val))deldir('../filecx/'.$val);
        }
    }
    //删除临时目录
    if(is_dir($tmp_path))deldir($tmp_path);
    exit(json_encode(['error'=>1,'size'=>1,'msg'=>'导入成功<b>'.$drcgf.'</b>个程序！导入失败<b class="text-danger">'.$drsbf.'</b>个程序！']));
} 
elseif($egn == "mailmode")
{
    $host = daddslashes($_POST['host']);
    $mailuser = daddslashes($_POST['user']);
    $passwrod = daddslashes($_POST['password']);
    $port = daddslashes($_POST['port']);
    $encryptedMailPassword = mn_encrypt($passwrod);
    if(!$DB->query("UPDATE `MN_config` SET `mailhost` = '$host', `mailuser` = '$mailuser', `mailpassword` = '$encryptedMailPassword', `mailport` = '$port' WHERE `id` = 1"))
    {
        exit('{"code":"数据库操作失败请联系开发人员判断错误"}');
    }
    else
    {
        exit('{"code":"修改成功"}');
    }
}
elseif($egn == "jkscsz")
{
    $ymkg = daddslashes($_POST['ymkg']);
    $ymyjkg = daddslashes($_POST['ymyjkg']);
    $ymtsyz = daddslashes($_POST['ymtsyz']);
    $wjkg = daddslashes($_POST['wjkg']);
    $wjyjkg = daddslashes($_POST['wjyjkg']);
    $wjtsyz = daddslashes($_POST['wjtsyz']);
    $opention = daddslashes($_POST['option']);
    $sql = "UPDATE `MN_config` SET `ymjkkg` = '$ymkg', `mtyxfskg` = '$ymyjkg', `ymjktsyz` = '$ymtsyz', `wjjkkg` = '$wjkg', `mtwjfskg` = '$wjyjkg', `wjjktsyz` = '$wjtsyz', `optionzc` = '$opention' WHERE `id` = 1";
    if(!$DB->query($sql))
    {
        exit('{"code":"数据库操作失败请联系开发人员判断错误"}');
    }
    else
    {
        exit('{"code":"修改成功"}');
    }
}
else {
    exit('{"code":"系统指令不存在！"}');
}
?>