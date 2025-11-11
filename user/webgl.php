<?php
include("../MPHX/common.php");
$title='MN宝塔主机网站管理';
include './head.php';
if($islogins==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<!--对话框-->
<script type="text/javascript" src="../imsetes/js/jquery-confirm/jquery-confirm.min.js"></script>
<script type="text/javascript" src="../imsetes/js/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="../imsetes/js/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
   <div class="container" style="padding-top:5%;">
<div class="row card-pricing-row">
<?php
$set=isset($_GET['gn'])?$_GET["gn"]:NULL;
if($set=='yjbs'){
$rs=$DB->query("SELECT * FROM MN_bs WHERE qk='true' order by id desc limit 100");
while($res = $DB->fetch($rs))
{
$img=''; $imgsl='';$szf='0';
foreach (json_decode($res['src']) as $vasel){
if($img==''){$fz=' active';}else{$fz='';}
$img.='<div class="carousel-item'.$fz.'"><img src="'.$vasel.'"></div>';
$imgsl.='<li data-target="#'.md5($res['name']).'" data-slide-to="'.$szf.'" class="'.$fz.'"></li>';
$szf++;
}
if(json_decode($res['sxpz'],true)[0]>json_decode($yhc['hxa'],true)['max'] || json_decode($res['sxpz'],true)[1]>json_decode($yhc['hxb'],true)['max']){
$pz='<p class="zcwj"><span class="mdi mdi-alert-decagram"></span><b>您的配置不支持（最低所需网页空间：'.json_decode($res['sxpz'])[0].'MB，最低所需数据库空间：'.json_decode($res['sxpz'])[1].'MB）</b></p><hr color="#211402"/>';
$ann='<button class="btn btn-label btn-danger btn-block btn-round"><label><i class="mdi mdi-close"></i></label> 您的配置不够哦</button>';
}else{
$ann=$res['jg']!=0 ? '<button class="btn btn-label btn-primary btn-block btn-round" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" onclick="zfs(id='.$res['id'].',jg='.$res['jg'].',name=`'.$res['name'].'`)"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认支付'.$res['jg'].'元部署</button>' : '<button class="btn btn-label btn-primary btn-block btn-round" onclick="qrbs(id='.$res['id'].',name=`'.$res['name'].'`)"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认免费部署</button>';
if(in_array($yhc['user'],json_decode($res['tj'],true))){ unset($ann);$ann='<button class="btn btn-label btn-primary btn-block btn-round" onclick="qrbs(id='.$res['id'].',name=`'.$res['name'].'`)"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认部署</button>';}
}
//exit($img);
echo '
  	<div class="col-md-6">
  	  <div class="card card-pricing">
  	    <div class="card-body">
  	         <h4>'.$res['name'].'</h4>
  	      <div class="container">
      <div class="card">
          
          <div id="s'.md5($res['name']).'" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
             '.$imgsl.'
            </ol>
            <div class="carousel-inner">
              '.$img.'
            </div>
            
            <a class="carousel-control-prev" href="#s'.md5($res['name']).'" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span></a>
            <a class="carousel-control-next" href="#s'.md5($res['name']).'" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span></a>
          </div>
          
      </div>
      
  	      </div>
  	      <ul class="specification-list">
  	        <li>
  	          <span class="name-specification">程序介绍：</span>
  	          <span class="status-specification">'.$res['jc'].'</span>
  	        </li>
  	      </ul>
  	      '.$pz.'
  	      '.$ann.'
  	    </div>
  	  </div>
  	</div>
      
';
unset($pz);
}
?>
            <!--程序购置-->
          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
          <h6 class="modal-title" id="exampleModalChangeTitle">程序部署-购置</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                    <h5 id="ts"></h5><br/>
                    <h5 id="hf"></h5><br/>
                    <h5>支付完成后返回该页面即可部署该程序！</h5><br/>
                    <h4 align="center">是否确认支付？</h4>
               <form action="pay.php" method="post" target="_blank" role="form">
        	     <input type="hidden" name="id" id="id"/>
        	     <input type="hidden" name="pay_lx" value="yjbs"/>
        	     
	  <label for="web_site_logo">请选择支付方式</label>
          <div class="example-box">
              <div class="row">
            <label class="lyear-radio radio-inline radio-primary col">
              <input type="radio" name="type" value="alipay"><i class="mdi mdi-puzzle"></i><span>支付宝支付</span>
            </label>
            <label class="lyear-radio radio-inline radio-primary col">
              <input type="radio" name="type" value="qqpay"><i class="mdi mdi-qqchat"></i><span>QQ支付</span>
            </label>
            <label class="lyear-radio radio-inline radio-primary col">
              <input type="radio" name="type" value="wxpay"><i class="mdi mdi-wechat"></i><span>微信支付</span>
            </label>
          </div>
          </div>
          
    
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                  <button type="submit" class="btn btn-primary">确认支付</button>
            </form>
                </div>
              </div>
            </div>
          </div>
          
          <!--表单填写-->
          <div class="modal fade" id="inputforms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
          <h6 class="modal-title" id="exampleModalChangeTitle">程序部署-信息填写</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                    <h6>在部署前需要您先填写以下表单！(带<span class="text-danger">*</span>的为必填/必选项)</h6><br/>
                    <h6 align="center"><b>请认真填写，这关系到您的程序是否能够正常运行</b></h6>
               <form id="user-forms">
                   <div id="form-txx">
                   
                   
                   </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                  <button type="button" class="btn btn-primary" onclick="bscx()">确认部署</button>
            </form>
                </div>
              </div>
            </div>
          </div>
</body>
<script type="text/javascript" src="../imsetes/js/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
<script type="text/javascript" src="../imsetes/js/formbs.js?<?=$date?>"></script>
<script type="text/javascript">


function zfs(id,jg,name){
msloading('正在加载中，请稍后...');  // 加载显示
document.getElementById("ts").innerHTML = '您正在部署：'+name+' <b>此操作将会删除您主机中原有的文件以及清空数据库！！！</b>';
document.getElementById("hf").innerHTML = '部署此程序将会花费您<b>'+jg+'</b>元';
document.getElementById("id").value = id;
msloadingde();  // 隐藏
}


function ts(msg) {
if (confirm(msg)==true){
return true;
}else{
return false;
}
}

function qrbs(id,name){
msloading('正在部署中，请稍后...');  // 加载显示
cs_id=id
cs_name=name
$("#form-txx").html('');
let data = {};
data["gn"]="yjbsform";
data["id"]=id;
$.post('./ajax.php', data, function (date) {
var json= JSON.parse(date);
var bds= JSON.parse(json.form);
if(json.qk=1){
//表单获取成功
if(bds!=null){
//添加表单
$.each(bds,function(is,val){
var rowr=JSON.stringify(val);
eval('add_inp("'+val.cz+'",'+is+','+rowr+')');
})
$('#inputforms').modal();		//弹出弹窗

}else{
//不用填写表单，直接部署
bscx(false);
}
}else{
msalert(4,json.qk,4000);
msloadingde();  // 隐藏
}
})
}

function bscx(cs=true){
if(!ts('您正在部署：'+cs_name+'\n此操作将会删除您主机中原有的文件以及清空数据库！！！')){msloadingde(); return;}
let data={};
data["gn"]="yjbs";
data["id"]=cs_id;
if(cs){
var tr = $('#user-forms').serializeArray();
$.each(tr, function() {              //用户填写表单获取
data[this.name]=this.value;
});
$('#inputforms').modal('hide');		//关闭弹窗
msloading('正在部署中，请稍后...');  // 加载显示
}
$.post('./ajax.php', data, function(date){
var jsoe= JSON.parse(date);
if(jsoe.qk==1){
    $.alert({
        title: '部署完成',
        content: jsoe.code,
        icon: 'mdi mdi-rocket',
        animation: 'scale',
        closeAnimation: 'scale',
        type:'green',
        buttons: {
            okay: {
                text: '我知道了',
                btnClass: 'btn-blue'
            }
        }
    });
msloadingde();  // 隐藏
}else{
msalert(4,jsoe.code,4000);
msloadingde();  // 隐藏
}
})
}

</script>
</html>
<?php }?>