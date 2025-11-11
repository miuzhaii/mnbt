<?php
function curl_get($url)
{
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 4.4.1; zh-cn; R815T Build/JOP40D) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1');
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$content=curl_exec($ch);
curl_close($ch);
return($content);
}
function daddslashes($string, $force = 0, $strip = FALSE) {
    !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
    if(!MAGIC_QUOTES_GPC || $force) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = daddslashes($val, $force, $strip);
            }
        } else {
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
    return $string;
}

if(!defined('MN_ENCRYPT_PREFIX')) {
    define('MN_ENCRYPT_PREFIX', 'MNENC:');
}

function mn_encrypt($plaintext, $key = SYS_KEY) {
    if($plaintext === '' || $plaintext === null) return $plaintext;
    if(strpos($plaintext, MN_ENCRYPT_PREFIX) === 0) return $plaintext;
    if(!function_exists('openssl_encrypt')) return $plaintext;
    $encryptionKey = hash('sha256', $key, true);
    try {
        $iv = random_bytes(16);
    } catch (Exception $e) {
        $iv = function_exists('openssl_random_pseudo_bytes') ? openssl_random_pseudo_bytes(16) : null;
    }
    if($iv === false || $iv === null || strlen($iv) !== 16) return $plaintext;
    $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $encryptionKey, OPENSSL_RAW_DATA, $iv);
    if($ciphertext === false) return $plaintext;
    return MN_ENCRYPT_PREFIX.base64_encode($iv.$ciphertext);
}

function mn_decrypt($ciphertext, $key = SYS_KEY) {
    if($ciphertext === '' || $ciphertext === null) return $ciphertext;
    if(strpos($ciphertext, MN_ENCRYPT_PREFIX) !== 0) return $ciphertext;
    if(!function_exists('openssl_decrypt')) return $ciphertext;
    $payload = base64_decode(substr($ciphertext, strlen(MN_ENCRYPT_PREFIX)), true);
    if($payload === false || strlen($payload) <= 16) return $ciphertext;
    $iv = substr($payload, 0, 16);
    $raw = substr($payload, 16);
    $plaintext = openssl_decrypt($raw, 'AES-256-CBC', hash('sha256', $key, true), OPENSSL_RAW_DATA, $iv);
    if($plaintext === false) return $ciphertext;
    return $plaintext;
}

function mn_format_host_row($row) {
    if(!$row || !is_array($row)) return $row;
    foreach(['pass','sqlpass'] as $field) {
        if(array_key_exists($field, $row)) {
            $row[$field] = mn_decrypt($row[$field]);
        }
    }
    return $row;
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key ? $key : ENCRYPT_KEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}
function showmsg($content = '未知的异常',$type = 4,$back = false)
{
switch($type)
{
case 1:
    $panel="success";
break;
case 2:
    $panel="info";
break;
case 3:
    $panel="warning";
break;
case 4:
    $panel="danger";
break;
}

echo '<div class="panel panel-'.$panel.'">
      <div class="panel-heading">
        <h3 class="panel-title">提示信息</h3>
        </div>
        <div class="panel-body">';
echo $content;

if ($back) {
    echo '<hr/><a href="'.$back.'"><< 返回上一页</a>';
}
else
    echo '<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a>';

echo '</div>
    </div>';
}
function sysmsg($msg = '未知的异常',$die = true) {
    ?>  
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>站点提示信息</title>
        <style type="text/css">
html{background:#eee}body{background:#fff;color:#333;font-family:"微软雅黑","Microsoft YaHei",sans-serif;margin:2em auto;padding:1em 2em;max-width:700px;-webkit-box-shadow:10px 10px 10px rgba(0,0,0,.13);box-shadow:10px 10px 10px rgba(0,0,0,.13);opacity:.8}h1{border-bottom:1px solid #dadada;clear:both;color:#666;font:24px "微软雅黑","Microsoft YaHei",,sans-serif;margin:30px 0 0 0;padding:0;padding-bottom:7px}#error-page{margin-top:50px}h3{text-align:center}#error-page p{font-size:9px;line-height:1.5;margin:25px 0 20px}#error-page code{font-family:Consolas,Monaco,monospace}ul li{margin-bottom:10px;font-size:9px}a{color:#21759B;text-decoration:none;margin-top:-10px}a:hover{color:#D54E21}.button{background:#f7f7f7;border:1px solid #ccc;color:#555;display:inline-block;text-decoration:none;font-size:9px;line-height:26px;height:28px;margin:0;padding:0 10px 1px;cursor:pointer;-webkit-border-radius:3px;-webkit-appearance:none;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);vertical-align:top}.button.button-large{height:29px;line-height:28px;padding:0 12px}.button:focus,.button:hover{background:#fafafa;border-color:#999;color:#222}.button:focus{-webkit-box-shadow:1px 1px 1px rgba(0,0,0,.2);box-shadow:1px 1px 1px rgba(0,0,0,.2)}.button:active{background:#eee;border-color:#999;color:#333;-webkit-box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5);box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5)}table{table-layout:auto;border:1px solid #333;empty-cells:show;border-collapse:collapse}th{padding:4px;border:1px solid #333;overflow:hidden;color:#333;background:#eee}td{padding:4px;border:1px solid #333;overflow:hidden;color:#333}
        </style>
    </head>
    <body id="error-page">
        <?php echo '<h3>站点提示信息</h3>';
        echo $msg; ?>
    </body>
    </html>
    <?php
    if ($die == true) {
        exit;
    }
}

function logjl($czuser_hsk_v = '操作用户',$lx_hsk_v = '操作类型',$lr_hsk_v = '内容',$qk_hsk_v = '操作情况',$DBZHER = 'NULL') {
$ip_hxer_ipsz_envc = $_SERVER["REMOTE_ADDR"];
$rsql_sqlpo_sdft_vc=$DB->get_row("SELECT * FROM MN_log WHERE 1 order by id desc limit 1");
$id_hxer_idkire_nzv=$rsql_sqlpo_sdft_vc['id']+1; $data_time_rq_sjv = date("Y-m-d H:i:s");
$log_sql_olzef_cxe = "INSERT INTO `MN_log` (`id`, `czuser`, `date`, `lx`, `lr`, `ip`, `qk`) VALUES ('{$id_hxer_idkire_nzv}', '{$czuser_hsk_v}', '{$data_time_rq_sjv}', '{$lx_hsk_v}', '{$lr_hsk_v}', '{$ip_hxer_ipsz_envc}', '{$qk_hsk_v}');";
if($DBZHER->query($log_sql_olzef_cxe)) return '1';
else return '0'.$DBZHER->error();
}

function send_post($url, $post_data) {
  $postdata = http_build_query($post_data);
  $options = array(
    'http' => array(
      'method' => 'POST',
      'header' => 'Content-type:application/x-www-form-urlencoded',
      'content' => $postdata,
      'timeout' => 4// 超时时间（单位:s）
    )
  );
  $context = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  return $result;
}

function ary_asd($mn_conf){
    $fh_ry='"';
    foreach($mn_conf as $sne=>$via){
        $fh_ry_r='"';
        if(is_array($via)){
            $via='array('.ary_asd($via).')';
            $fh_ry_r='';
        }
        if(is_numeric($via)){
            $fh_ry_r='';
        }
        if($kr_sxy==""){
            $kr_sxy.=$fh_ry.$sne.$fh_ry.'=>'.$fh_ry_r.$via.$fh_ry_r;
        } else{
            $kr_sxy.=','.$fh_ry.$sne.$fh_ry.'=>'.$fh_ry_r.$via.$fh_ry_r;
        }
    }
    return $kr_sxy;
}

    function deldir($dir) {             //删除目录下的文件
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir.
                "/".$file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    function dirfiles($data, $type) {       //控制面板返回的文件数据处理$filename如果不为false则为获取指定文件名数据
    $userinisf=0;
    $printarry = [];
        foreach($data as $val) {
            $arr = []; //为本次循环新建一个数组存放本条次数据
            $valarr = explode(";", $val);
            if($valarr[0]=='.user.ini' && $type=='file'){$userinisf=1; continue;}        //防跨站配置文件不用显示给用户
            $arr['name'] = $valarr[0]; //文件名
            $arr['type'] = $type; //文件类型
            $arr['mtime'] = $valarr[2]; //修改时间戳
            $arr['size'] = $valarr[1]; //文件大小
            $arr['download']=$valarr[6];         //是否外链分享
            $printarry[] = $arr; //数组存储
            unset($arr);
        }
        return ["file"=>$printarry,"couts"=>$userinisf];
    }
    
    function delval_array($arr,$value,$path=''){         //删除一维数组中指定的值，同时取消名称不规范的目录/文件，并且判断执行完成后是否存在文件可删
        foreach ($arr as $k=>$v){
            if($path.$v==$value)unset($arr[$k]);      //删除指定的值的参数
            if(strpos($v,'/'))unset($arr[$k]);      //删除值不规范的参数
            $arr = array_merge($arr);
        }
        if(empty($arr)){            //判断是否为空数组
            return false;
        }else{
            return $arr;
        }
    }
    
function zipfile($path,$paths='/',$zipth,$filetext=false) {
    //压缩目录(被压缩的目录，此函数上一次执行的位置，压缩包存放处，程序配置文件内容)
    $zip = new \ZipArchive;
    $pathname=substr($path,strripos($path,'/')+1);
    //获取目录名
    if($zip->open($zipth, \ZIPARCHIVE::CREATE)===true) {
        //新建一个压缩包
        if($paths=='/') {
            $zip->addEmptyDir($pathname);
            //新建一个目录
        } else {
            $zip->addEmptyDir($pathname.$paths);
            //新建一个目录
        }
        if($filetext) {
            $zip->addFromString($pathname.$paths.'mnbt_file_conf.json', $filetext);
        }
        $list=scandir($path.$paths);
        foreach ($list as $val) {
            if($val=='.' || $val=='..')continue;
            if(is_dir($path.$paths.$val)) {
                //如果是目录则执行函数
                $zip->close();
                //关闭压缩包
                if($paths=='/') {
                    zipfile($path,$paths.$val.'/',$zipth,false);
                } else {
                    zipfile($path,$paths.'/'.$val.'/',$zipth,false);
                }
            } else {
                //文件
                $zip->addFile($path.$paths.$val,$pathname.$paths.$val);
            }
        }
    } else return ["code"=>4,"msg"=>"错误！创建压缩包失败！"];
    $zip->close();
    //关闭压缩包
    return ["code"=>1,"msg"=>"程序打包完成"];
}

?>