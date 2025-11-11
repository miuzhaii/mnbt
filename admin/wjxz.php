<?php
include("../MPHX/common.php");
@header('Content-Type: text/html; charset=UTF-8');
$egn=$_GET['gn'];
if($islogin==1){}else exit('{"code":"请登陆"}');
?>

<?php
$ifsz=$_GET['idh'];
if(isset($ifsz)){
$res=$DB->get_row("SELECT * FROM MN_bs WHERE id='$ifsz' limit 1");
$file=$res['cxwz'];//需要下载的文件
$file = iconv("utf-8","gbk//IGNORE",$file);
if(!file_exists($file)){//判断文件是否存在
    echo "文件不存在";
    exit();
}

$file_name=$res['name'].'.zip';
$file_name = iconv("utf-8","gbk//IGNORE",$file_name);
$file_size=filesize("$file");
ob_clean();
flush();
header("Content-Description: File Transfer");
header("Content-Type:application/force-download");
header("Content-Length: {$file_size}");
header("Content-Disposition:attachment; filename={$file_name}");
readfile("$file");
exit;
}elseif($egn=='cxfile'){
$file='../filecx/export_file.zip';//需要下载的文件
$file = iconv("utf-8","gbk//IGNORE",$file);
if(!file_exists($file)){//判断文件是否存在
    echo "导出的打包文件不存在！您可以重新导出！";
    exit();
}
$file_name='export_file'.mt_rand(10,100).'.zip';
$file_name= iconv("utf-8","gbk//IGNORE",$file_name);
$file_size=filesize("$file");
ob_clean();
flush();
header("Content-Description: File Transfer");
header("Content-Type:application/force-download");
header("Content-Length: {$file_size}");
header("Content-Disposition:attachment; filename={$file_name}");
readfile("$file");
//@unlink($file);
exit;
}
?>


<?php
if($_GET['ne']=='sw'){
$file_name='mnbt_swapidc.zip';
$file="./mnbt/mnbt.sw.zip";//需要下载的文件
}else{
$file_name='mnbt_mofang.zip';
$file="./mnbt/mnbt.mr.zip";//需要下载的文件
}
$file = iconv("utf-8","gbk//IGNORE",$file);
if(!file_exists($file)){//判断文件是否存在
    echo "文件不存在";
    exit();
}

$file_size=filesize("$file");
ob_clean();
flush();
header("Content-Description: File Transfer");
header("Content-Type:application/force-download");
header("Content-Length: {$file_size}");
header("Content-Disposition:attachment; filename={$file_name}");
readfile("$file");
exit;
?>