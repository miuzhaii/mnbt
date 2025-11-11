<?php
include("../MPHX/common.php");
$title='MN宝塔主机系统教程';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<body>
<?php if($_GET['gn']=='' || !isset($_GET['gn'])){?>
<div class="container-fluid p-t-15">

<div class="row">
    <div class="col-lg-6">
      <div class="card">
        <header class="card-header"><div class="card-title">使用教程及监控</div></header>
        <div class="card-body">
          
          
          <ul class="nav nav-tabs nav-fill">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#jinyong-fill" aria-selected="true">监控教程</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#!" aria-haspopup="true" aria-expanded="false">使用教程</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#gulong-fill" data-toggle="tab">添加宝塔教程</a>
                <a class="dropdown-item" href="#liangyusheng-fill" data-toggle="tab">添加主机教程</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#!" aria-haspopup="true" aria-expanded="false">对接教程</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#djs-fill" data-toggle="tab">SWAPIDC对接教程</a>
                <a class="dropdown-item" href="#djm-fill" data-toggle="tab">魔方对接教程</a>
              </div>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade show active" id="jinyong-fill" >
              <p>
			  <span>  网站监控(并非摄像头)它是一个链接,用来定时执行而达到一定功能。您可以在宝塔设置定时任务来执行或者百度网页监控等执行。它很重要请您务必设置</span><br/>
			 <?php 
			   if($conf['api']==''){
			  echo '<span><code>请您在系统设置->APi设置里面把api密钥生成并且保存后再来此处设置监控！</code></span><br>';
			  }else{
			  echo '
			  <span>在您把系统设置->APi密钥修改后这里的链接也会重置！需要您重新设置定时任务(监控)中的链接！后才能正常运行！</span><br>
              <code class="wbcchh">'.($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/jk.php?my='.$conf['api'].'&gn=web</code><br/>
              <span>此为计算所有主机网页空间使用情况的链接，推荐设置为10分钟执行一次</span><br/>
              <code class="wbcchh">'.($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/'.'jk.php?my='.$conf['api'].'&gn=sql</code><br/>
              <span>此为计算所有主机数据库空间使用情况的链接，推荐设置为10分钟执行一次</span><br/>
              <code class="wbcchh">'.($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/'.'jk.php?my='.$conf['api'].'&gn=fh</code><br/>
              <span>此为计算所有主机流量使用情况的链接，推荐设置为10分钟执行一次</span><br/>
              <code class="wbcchh">'.($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/'.'jk.php?my='.$conf['api'].'&gn=fhq</code><br/>
              <span>此为清除所有主机流量使用情况的链接，推荐设置为每月1日执行一次</span><br/>
              <code class="wbcchh">'.($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/'.'jk.php?my='.$conf['api'].'&gn=ywjkdel</code><br/>
              <span>此为清除不使用的主机，推荐设置为每天执行一次</span><br/>
              ';
              }
              ?>
              </p>
            </div>
            <div class="tab-pane fade" id="gulong-fill" >
              <p>
                  1.登录服务器宝塔后台->面板设置<br/>
                  2.上方点击开启API接口<br/>
                  3.添加搭建本系统服务器的IP地址和127.0.0.1<br/>
                  4.记录下安装该宝塔服务器的IP地址和接口密钥<br/>
                  5.返回本系统后台->宝塔管理—>添加宝塔<br/>
                  6.宝塔IP填写刚才记录的IP地址<br/>
                  7.宝塔端口填写你宝塔面板登录的端口<br/>
                  8.宝塔密钥填写刚才记录的接口密钥<br/>
                  9.没特殊要求那域名解析说明可由系统默认<br/>
                  10.宝塔代号使用随机生成或者自己填写<br/>
                  11.如果面板打开了[面板SSL]则需要把安全访问打开<br/>
                  12.操作系统选择您搭建宝塔的服务器系统(不是Windows就是Linux)<br/>
                  13.点击下方的确认添加即可完成添加<br/>
              </p>
            </div>
            <div class="tab-pane fade" id="liangyusheng-fill" >
              <p>
                  1.选择好宝塔<br/>
                  2.填入主机的控制面板登录账号密码<br/>
                  3.填入网页空间和数据库空间及每月流量<br/>
                  4.填入域名最大绑定数<br/>
                  5.选择到期时间(不选择则为永久)<br/>
                  6.点击确认添加即可<br/>
                  7.<code>提示：请务必设置好监控！！！</code><br/>
              </p>
            </div>
            <div class="tab-pane fade" id="djs-fill" >
              <p>
              
                  1.下载对接文件<br/>
                  2.上传到搭建IDC网站的目录<code>/swap_mac/swap_lib/servers</code><br/>
                  3.然后解压刚才的文件然后删除压缩包即可<br/>
                  4.进入idc后台添加服务器<br/>
                  5.服务器插件选择MNBT<br/>
                  6.服务器主机名填写<code><?=$_SERVER['HTTP_HOST']?></code><br/>
                  7.用户名填写宝塔编号 密码填写网站API密钥<br/>
                  8.底下的哈希密码填写调用密钥(宝塔列表内将宝塔表滑到最后面即可)<br/>
                  9.安全访问/SSL访问之类的开关：<?=($_SERVER['SERVER_PORT'] == '443' ? '打开' : '关闭')?><br/>
                  10.填写需要的消息在宝塔列表里面(可以往后面滑动),保存即可<br/>
                  11.然后添加产品选择MNBT服务器插件然后填写消息即可<br/>
              </p>
              <p class="small">对接文件下载：<a href="./wjxz.php?ne=sw"/>点我前去下载</a></p>
            </div>
            
        <div class="tab-pane fade" id="djm-fill" >
              <p>
					1.下载对接插件<br/>
					2.上传到搭建IDC网站目录：<code>/public/plugins/servers</code><br/>
					3.然后解压刚才上传的文件然后删除压缩包即可<br/>
					4.进入后台填写服务器信息<br/>
					5.IP地址：宝塔编号<br/>
					6.服务器模块：梦奈宝塔对接模块<br/>
					7.主机名：<code><?=$_SERVER['HTTP_HOST']?></code><br/>
					8.用户名：宝塔调用密钥<br/>
					9.密码：API密钥<br/>
                    10.安全访问/SSL访问之类的开关：<?=($_SERVER['SERVER_PORT'] == '443' ? '打开' : '关闭')?><br/>
					11.然后您就能添加产品进行测试了<br/>
             </p>
              <p class="small">对接文件下载：<a href="./wjxz.php?ne=mr"/>点我前去下载</a></p>
              </p>
            </div>
            
          </div>
          
        </div>
      </div>
    </div>

          
        </div>
      </div>

<div class="container-fluid p-t-15">
  <?php }elseif($_GET['gn']=='sw'){
	$id=$_GET['sz']; $cres=$DB->get_row("SELECT * FROM MN_bt WHERE id='$id' limit 1");
	$eritvt=$cres['ktmy'].$cres['qmk'];
	$eritvf=md5($eritvt);
	?>
  <div class="container-fluid p-t-15">
            <div class="col-md-4">
              <code>[对接]SWAPIDC对接教程</code>
              <p class="small">
                  1.下载对接文件<br/>
                  2.上传到搭建IDC网站的目录<code>/swap_mac/swap_lib/servers</code><br/>
                  3.然后解压上传的压缩包然后删除压缩包即可<br/>
                  4.进入idc后台添加服务器<br/>
                  5.服务器插件选择MNBT<br/>
                  6.服务器主机名填写<code><?=$_SERVER['HTTP_HOST']?></code><br/>
                  7.用户名填写<code><?=$cres['btdh']?></code><br/>
                  8.密码填写<code><?=$conf['api']?></code><br/>
                  9.哈希密码填写<code><?=$eritvf?></code><br/>
                  10.安全访问/SSL访问之类的开关：<?=($_SERVER['SERVER_PORT'] == '443' ? '打开' : '关闭')?><br/>
                  11.然后保存更改即可<br/>
                  12.添加产品选择MNBT服务器插件然后填写消息即可<br/>
              </p>
              <p class="small">对接文件下载：<a href="./wjxz.php?ne=sw"/>点我前去下载</a></p>
            </div>
          </div>
  </div>
  
  
  
  <?php }else{
  	$id=$_GET['sz']; $cres=$DB->get_row("SELECT * FROM MN_bt WHERE id='$id' limit 1");
	$eritvt=$cres['ktmy'].$cres['qmk'];
	$eritvf=md5($eritvt);
	?>
  <div class="container-fluid p-t-15">
            <div class="col-md-4">
              <code>[对接]魔方对接教程</code>
              <p class="small">
					1.下载对接插件<br/>
					2.上传到搭建IDC网站目录：<code>/public/plugins/servers</code><br/>
					3.然后解压刚才上传的文件然后删除压缩包即可<br/>
					4.进入后台填写服务器信息<br/>
					5.IP地址填写：<code><?=$cres['btdh']?></code><br/>
					6.服务器模块：<code>梦奈宝塔对接模块</code><br/>
					7.主机名：<code><?=$_SERVER['HTTP_HOST']?></code><br/>
					8.用户名：<code><?=$eritvf?></code><br/>
					9.密码：<code><?=$conf['api']?></code><br/>
                    10.安全访问/SSL访问之类的开关：<?=($_SERVER['SERVER_PORT'] == '443' ? '打开' : '关闭')?><br/>
					11.然后您就能添加产品进行测试了<br/>
              </p>
              <p class="small">对接文件下载：<a href="./wjxz.php?ne=mr"/>点我前去下载</a></p>
            </div>
          </div>
  </div>
  
  
  
  
  
  
  
<?php }?>
</body>
</html>