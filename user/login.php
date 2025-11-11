<?php
include("../MPHX/common.php");
$title='MN宝塔主机控制面板登录';
include './head.php';
if($conf['kzmbqk']=='false'){sysmsg('控制面板已经被关闭详细请联系站长QQ'.$conf['qqh']);}
if($islogins==0){}else exit("<script language='javascript'>window.location.href='./index.php';</script>");
?>

<style>
.login-box {
    background-color: rgba(255, 255, 255, .25);
}
.login-box p:last-child {
    margin-bottom: 0px;
}
.login-form .form-control {
    background: rgba(0, 0, 0, 0.3);
    color: #fff;
}
.login-form .has-feedback {
    position: relative;
}
.login-form .has-feedback .form-control {
    padding-left: 36px;
}
.login-form .has-feedback .mdi {
    position: absolute;
    top: 0;
    left: 0;
    right: auto;
    width: 36px;
    height: 36px;
    line-height: 36px;
    z-index: 4;
    color: #dcdcdc;
    display: block;
    text-align: center;
    pointer-events: none;
}
.login-form .has-feedback.row .mdi {
    left: 15px;
}
.login-form .form-control::-webkit-input-placeholder{ 
    color: rgba(255, 255, 255, .8);
} 
.login-form .form-control:-moz-placeholder{ 
    color: rgba(255, 255, 255, .8);
} 
.login-form .form-control::-moz-placeholder{ 
    color: rgba(255, 255, 255, .8);
} 
.login-form .form-control:-ms-input-placeholder{ 
    color: rgba(255, 255, 255, .8);
}
.login-form .custom-control-label::before {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(0, 0, 0, 0.1);
}
</style>
</head>
  
<body class="center-vh" style="background-image: url(../imsetes/images/1.jpg); background-size: cover;">
<div class="login-box p-5 w-420 mb-0 mr-2 ml-2">
  <div class="text-center mb-3">
    <a href="login.php"> <img alt="MNBT" src="../imsetes/upload_logo/logo.login.png?<?=$conf['auther']?>"> </a>
  </div>
  <form action="#!" method="post" class="login-form">
    <div class="form-group has-feedback">
      <span class="mdi mdi-account" aria-hidden="true"></span>
      <input type="text" class="form-control" id="username" placeholder="请输入您的用户名或账号">
    </div>

    <div class="form-group has-feedback">
      <span class="mdi mdi-lock" aria-hidden="true"></span>
      <input type="password" class="form-control" id="password" placeholder="请输入密码">
    </div>
    <?php if($conf['yzme']=='true'){?>
    <div class="form-group has-feedback row">
      <div class="col-7">
        <span class="mdi mdi-check-all form-control-feedback" aria-hidden="true"></span>
        <input type="text" name="captcha" id="csyzmiq" class="form-control" placeholder="请输入验证码">
      </div>
      <div class="col-5 text-right">
        <img id="captcha" src="./code.php?r=<?php echo time();?>" class="pull-right" style="cursor: pointer;" onclick="this.src='./code.php?r='+Math.random();" title="点击更换验证码" alt="点我刷新验证码">
      </div>
    </div>
      <?php }?>

    <div class="form-group">
      <button class="btn btn-block btn-primary" type="button" onclick="chkre()">立即登录</button>
    </div>
  </form>
  
  <p class="text-center text-white"><?=$conf['hxp']?></p>
</div>
<script type="text/javascript">

function chkre() {
var userq=username.value;
var passq=password.value;
var codeq='0000';
<?php if($conf['yzme']=='true'){
echo 'var codeq=csyzmiq.value;';
}?>
if(userq=="" || passq=="" || codeq=="" ){
msalert(3,"请将表单填写完整",2000);
}else{
msloading('正在加载中，请稍后...');  // 加载显示
let data = {};
data["gn"]="login";
data["user"]=userq;
data["pass"]=passq;
data["code"]=codeq;
$.post('./ajax.php', data, function (date) {
var jsoe= JSON.parse(date);    
var qk= jsoe.code

if(qk=='登陆成功'){
msalert(1,"登陆成功，页面即将自动跳转~",4000);
window.location.href="./index.php"
msloadingde();  // 隐藏
<?php if($conf['yzme']=='true'){
echo "captcha.src='./code.php?r='+Math.random();";
}?>

}else{
msalert(4,qk,4000);
msloadingde();  // 隐藏
<?php if($conf['yzme']=='true'){
echo "captcha.src='./code.php?r='+Math.random();";
}?>

}                        
})
}}
</script>
</body>
</html>