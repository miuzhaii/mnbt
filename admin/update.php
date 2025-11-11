<?php
include("../MPHX/common.php");
include("../MPHX/BL.php");
include("../MPHX/SQ.php");
include("../cf_up.php");
$title='MN宝塔主机系统更新';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$gxtj = array(
'url' => $_SERVER['HTTP_HOST'],
'authcode' => $authcode,
'ver' => $WEBQB,
);
$result = send_post($mn_conf['aet'].'://'.$mn_conf['url'].':'.$mn_conf['port'].'/check.php',$gxtj);
$content=json_decode($result, true);
$total ='V'.sprintf( "%.2f ",$WEBQB/1000);
?>
<body>
<div class="container-fluid p-t-16">

<div class="row">
    <div class="col-lg-8">
      <div class="card">
        <header class="card-header"><div class="card-title"><?=$content['msg']?></div></header>
        <div class="card-body">
        
<div class="row">
    <div class="col-md-6">
      <div class="card bg-primary text-white">
        <div class="card-body clearfix">
          <div class="flex-box">
            <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-dns fs-22"></i></span>
            <span class="fs-22 lh-22"><?=$total?></span>
          </div>
          <div class="text-right">系统版本</div>
        </div>
      </div>
    </div>
    
    <div class="col-md-6">
      <div class="card bg-purple text-white">
        <div class="card-body clearfix">
          <div class="flex-box">
            <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-image-filter-drama fs-22"></i></span>
            <span class="fs-22 lh-22"><?=$content['ver']?></span>
          </div>
          <div class="text-right">最新版本</div>
        </div>
      </div>
    </div>
    
      <?php if($content['code']=='1'){?>
<div class="container-fluid p-t-15">
          <button class="btn btn-primary form-control" type="button" onclick="up()"><label><i class="mdi mdi-arrow-up-bold-circle"></i></label>立刻更新</button>
   </div>
   <?php }?>
      </div>
    <div class="card-header"></div>
          <div class="card-body">
          <p><small><?=$content['uplog']?></small></p>
        </div>
    
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function up() {
msloading()  // 加载显示
let data = {};
data["gn"]="update";
$.post('./ajax.php', data, function (date) {    
var jsoe= JSON.parse(date);    
var qk= jsoe.code
if(qk=='更新成功～请手动刷新页面'){
msalert(1,qk,4000);
msloadingde();  // 隐藏
}else{
msalert(4,qk,4000);
msloadingde();  // 隐藏

}
})
}
</script>
</body>
</html>