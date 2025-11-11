<?php
@header('Content-Type: text/html; charset=UTF-8');
include("../cf_up.php");
if($mn_conf['xf']['qk'] && $islogin!=0){exit('由于更新后必须进行一次系统修复，暂时无法使用这功能！修复方法：进入管理后台->点击右上角系统管理员->点击系统修复->选择要修复的功能->点击确认修复即可！');}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title><?=$title?></title>
<link rel="icon" href="../imsetes/images/logo-ico.png" type="image/ico">
<meta name="author" content="yinqi">
<link href="../imsetes/css/bootstrap.min.css" rel="stylesheet">
<link href="../imsetes/css/materialdesignicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../imsetes/js/bootstrap-multitabs/multitabs.min.css">
<link href="../imsetes/css/animate.min.css" rel="stylesheet">
<link href="../imsetes/css/style.min.css" rel="stylesheet">
<script type="text/javascript" src="../imsetes/js/jquery.min.js"></script>

<script type="text/javascript" src="../imsetes/js/popper.min.js"></script>
<script type="text/javascript" src="../imsetes/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../imsetes/js/lyear-loading.js"></script>

<!--消息提示-->
<script src="../imsetes/js/bootstrap-notify.min.js"></script>
<script type="text/javascript" src="../imsetes/js/main.min.js"></script>
<script type="text/javascript" src="../imsetes/js/fn-hs.js"></script>

<!--表格样式-->
<link href="../imsetes/js/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
<link href="../imsetes/js/jquery-confirm/jquery-confirm.min.css" rel="stylesheet">

<style type="text/css">
.ajhggtr1 {
height:100px;
width:100px;
}
.tjnr1{
width: 96%;
border: 1px solid #8f8c8c;
border-radius: 20px;
margin: 0 auto;
/*box-shadow: 2px 2px 25px #000; 这是阴影*/
}
.nbvbk{/*一键部署添加操作*/
width: 100%;
border-radius: 5px;
margin-bottom: 10px;
}
.nbvbks{/*一键部署添加表单*/
width: 100%;
margin: 0 auto;
border-radius: 5px;
margin-bottom: 10px;
}
.dspj{/*一键部署添加表单(间隔)*/
margin-bottom: 10px;
}
.a2{
height: 20px;
width: 80%;
}
.a1 b{
margin-left: 4px;
text-align: left;
line-height: 40px;
}
.a3{
float:right;
margin-block: -15px;
margin-right: 4px;
}
.listscr{
width: 150px;
}
.listscr img{
height: 50px;
width: 50px;
float: left;
border: 2px solid #FFFFFF;
}
.wbcchh{
word-wrap:break-word; 

word-break:break-all; 

overflow: hidden;
}
.textmntti{
 margin: 2px 10px;
}
.mdi-delete:hover{
    color: red;
}
</style>
</head>
<body>