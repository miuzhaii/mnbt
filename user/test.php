<?php
include("../MPHX/common.php");
@header('Content-Type: text/html; charset=UTF-8');
include('./class.php');
$cert=$DB->get_row("SELECT * FROM MN_bt WHERE btdh='$ssbt' limit 1");
$btipe=($cert['ptl']=='true'?'https':'http').'://'.$cert['btip'].':'.$cert['btdk'];
$btkeye=$cert['btmy'];
#var_dump($btipe);

#var_dump($btkeye);
$api = new bt_api($btipe,$btkeye);
$abc=$api->获取部署程序的列表();

foreach ($abc['list'] as $values)
{
    var_dump($values);
    echo $values['name'];
    break;
}


