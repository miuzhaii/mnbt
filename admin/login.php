<?php
include("../MPHX/common.php");
$title='MN宝塔主机系统后台登录';
include './head.php';
if($islogin==0){}else exit("<script language='javascript'>window.location.href='./index.php';</script>");
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
<body class="center-vh" style="background-image: url(../imsetes/images/login-bg-6.jpg?1); background-size: cover;">
<div class="login-box p-5 w-420 mb-0 mr-2 ml-2">
  <div class="text-center mb-3">
    <a href="./"> <img alt="MNBT admin" src="../imsetes/admin_logo/logo.login.png?1"> </a>
  </div>
  <form class="login-form">
    <div class="form-group has-feedback">
      <span class="mdi mdi-account" aria-hidden="true"></span>
      <input type="text" class="form-control" id="username" placeholder="用户名">
    </div>

    <div class="form-group has-feedback">
      <span class="mdi mdi-lock" aria-hidden="true"></span>
      <input type="password" class="form-control" id="password" placeholder="密码">
    </div>
<?php if($conf['yzm']=='true'){?>

        <div class="form-group has-feedback row">
          <div class="col-7">
            <span class="mdi mdi-check-all form-control-feedback" aria-hidden="true"></span>
            <input type="text" name="captcha" id="csyzmiq" class="form-control" placeholder="验证码">
          </div>
          <div class="col-5 text-right">
            <img src="./code.php?r=<?php echo time();?>" class="pull-right" id="captcha" style="cursor: pointer;" onclick="this.src='./code.php?r='+Math.random();" title="点击刷新" alt="captcha">
          </div>
        </div>
    
    <?php }?>
    <div class="form-group">
      <button class="btn btn-block btn-primary" type="button" id="example-three" onclick="chkre()">立即登录</button>
    </div>
  </form>
  

</div>
</body>
<script type="text/javascript">
<?php if($conf['yzm']=='true'){?>
function chkre() {
var userq=username.value;
var passq=password.value;
var codeq=csyzmiq.value;
//alert(codeq);
if(userq=="" || passq=="" || codeq=="" ){
msalert(4,'请将表单填写完整！',2000);
}else{
msloading('正在登录中，请稍后...','text-info','text-info');
let data = {};
data["gn"]="login";
data["user"]=userq;
data["pass"]=passq;
data["code"]=codeq;
//alert(codeq);
$.post('./ajax.php', data, function (date) {
var jsoe= JSON.parse(date);    
var qk= jsoe.code

if(qk=='登陆成功'){
msalert(1,'登陆成功，页面即将自动跳转~',2000);
window.location.href="./index.php"
msloadingde();
captcha.src='./code.php?r='+Math.random();
}else{
msalert(4,qk,2000);
msloadingde();
captcha.src='./code.php?r='+Math.random();
}                        
})
}}
<?php }else{?>
function chkre() {
var userq=username.value;
var passq=password.value;
if(userq=="" || passq==""){
msalert(4,'请将表单填写完整！',2000);
}
else
{
msloading('正在登录中，请稍后...','text-info','text-info');
let data = {};
data["gn"]="login";
data["user"]=userq;
data["pass"]=passq;
//alert(codeq);
$.post('./ajax.php', data, function (date) {
var jsoe= JSON.parse(date);    
var qk= jsoe.code

if(qk=='登陆成功'){
msalert(1,'登陆成功，页面即将自动跳转~',2000);
window.location.href="./index.php"
msloadingde();
captcha.src='./code.php?r='+Math.random();
}else{
msalert(4,qk,2000);
msloadingde();
captcha.src='./code.php?r='+Math.random();
}                        
})
}}


<?php }?>



</script>
</html>
