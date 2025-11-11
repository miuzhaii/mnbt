<?php
@header('Content-Type: text/html; charset=UTF-8');
include("../cf_up.php");
if($mn_conf['xf']['qk']){exit('由于更新后必须进行一次系统修复，暂时无法使用本系统！请联系管理员前往后台使用修复功能！');}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title><?=$title?></title>
<link rel="icon" href="../imsetes/upload_logo/logo.head.png?<?=$conf['auther']?>" type="image/ico">
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
<script type="text/javascript" src="../imsetes/js/fn-hs.js?1.74"></script>

<!--表格样式-->
<link href="../imsetes/js/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
<link href="../imsetes/js/jquery-confirm/jquery-confirm.min.css" rel="stylesheet">

<style>
.CodeMirror {
      width: 300px;
      height: 200px;
      border: 1px solid #dcdcdc;
      margin-bottom: 10px;
    }
.dqb {
    
    margin: 0 auto;
    border: 1px solid #000000;
    border-radius: 20px;
    margin: 0 auto;
    margin-top: 10px;
    background: #FFFFFF;
}
.dbl {
    height:20px;
    width: 100%;
    background: #128129;
    border-top-left-radius: 22px;
    border-top-right-radius: 22px;
    margin-bottom: 20px;
}
.list p {
    margin-left: 5px;
}
.tp{
height: 200px;
}

.a1{
width: 280px;
margin: 0 auto;
}
.wqbr{          /*设置对其，清除DIV的自动换行*/
word-wrap:normal;
display: inline-block;
}
.zcwj{
color: #B22222;
text-align: center;
font-size: 140%;
}

.imgjz{
margin: 0 auto;
}

/*以下为一键部署的css样式*/
.card-pricing-row {
    padding: 40px 0px;
}
.card-pricing {
    text-align: center;
}
.card-pricing .specification-list {
    list-style: none;
    padding-left: 0;
    margin-top: 30px;
    margin-bottom: 30px;
}
.card-pricing .specification-list li {
    padding: 8px 0 12px;
    border-bottom: 1px solid rgba(77, 82, 89, 0.05);
    text-align: left;
    font-size: 12px;
    margin-bottom: 5px;
}
.card-pricing .specification-list li .name-specification {
    font-weight: 700;
}
.card-pricing .specification-list li .status-specification {
    margin-left: auto;
    float: right;
    font-weight: 400;
}
.card-pricing.card-pricing-focus {
    padding: 40px 5px;
    margin-top: -40px;
}
@media screen and (max-width: 970px) {
    .card-pricing.card-pricing-focus {
        margin-top: 0px;
    }
    .card-pricing-row {
        padding: 0px;
    }
}

</style>
</head>
<body>