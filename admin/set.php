<?php
include("../MPHX/common.php");
$title='MN宝塔主机系统设置';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
header("Cache-Control: no-cache, must-revalidate");
?>
  <script type="text/javascript" src="../imsetes/js/md5.js"></script> 
  <script type="text/javascript" src="../imsetes/js/xtset.js?hc=<? echo $date?>"></script>
   <div class="container" style="padding-top:10%;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">

<?php
$set=isset($_GET['gn'])?$_GET["gn"]:NULL;
if($set=='wz'){?>
<div class="border border-primary rounded-top">
<div class="bg-primary border border-gray"><h3 class="ml-2">网站配置</h3></div>
<div class="bg-white px-2">
    
  <div class="form-group">
	  <label for="web_site_logo">网站公告📢</label>
	  <textarea name="wzgg" rows="10" id="wzgg" class="form-control" placeholder="请在这填写网站公告"><?php echo $conf['gg']; ?></textarea>
	</div><br/>
	<div class="form-group">
	  <label for="web_site_logo">站长QQ</label>
	  <input type="text" name="qq" id="qq" value="<?php echo $conf['qqh']; ?>" class="form-control" placeholder="请在这填写您的QQ号" required/>
	  </div><br/>
	<div class="form-group">
	  <label class="btn-block" for="web_site_status">后台登陆验证码开关</label>
	  <div class="col-xs-6">
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="yzmkg" <?php if($conf['yzm']=='true')echo 'checked';?> >
            <label class="custom-control-label" for="yzmkg"></label>
          </div>
          
          	<div class="form-group">
	  <label class="btn-block" for="web_site_status">主机邮箱绑定开关</label>
	  <div class="col-xs-6">
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="zjyxbd" <?php if($conf['zjyxbd']=='true')echo 'checked';?> >
            <label class="custom-control-label" for="zjyxbd"></label>
          </div>
          
              </div>
              </div>
          <button class="mb-2 btn btn-primary form-control" type="button" onclick="setwz()"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认修改</button>
</div>
</div>
</div>
<?php }elseif($set=='api'){?>
<div class="border border-primary rounded-top">
<div class="bg-primary border border-gray"><h3 class="ml-2">API密钥设置</h3></div>
<div class="bg-white px-2">
<div class="form-group">
                <label for="web_site_logo">API密钥</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="apimy" id="apimy" value="<?php echo $conf['api']; ?>" placeholder="API密钥(推荐随机生成)"/>
                  <div class="input-group-btn"><button class="btn btn-default" type="button" onclick="apisc()">随机生成</button></div>
                </div>
              </div>
              </br>
              <div class="form-group">
	  <label for="web_site_icp">默认PHP版本</label>
                <select class="form-control" id="mrphp" name="mrphp" size="1">
                  <option value="52" <?php if($conf['hxu']=='52')echo 'selected';?>>PHP-5.2</option>
                  <option value="53" <?php if($conf['hxu']=='53')echo 'selected';?>>PHP-5.3</option>
                  <option value="54" <?php if($conf['hxu']=='54')echo 'selected';?>>PHP-5.4</option>
                  <option value="55" <?php if($conf['hxu']=='55')echo 'selected';?>>PHP-5.5</option>
                  <option value="56" <?php if($conf['hxu']=='56')echo 'selected';?>>PHP-5.6</option>
                  <option value="70" <?php if($conf['hxu']=='70')echo 'selected';?>>PHP-7.0</option>
                  <option value="71" <?php if($conf['hxu']=='71')echo 'selected';?>>PHP-7.1</option>
                  <option value="72" <?php if($conf['hxu']=='72')echo 'selected';?>>PHP-7.2</option>
                  <option value="73" <?php if($conf['hxu']=='73')echo 'selected';?>>PHP-7.3</option>
                  <option value="74" <?php if($conf['hxu']=='74')echo 'selected';?>>PHP-7.4</option>
                  <option value="80" <?php if($conf['hxu']=='80')echo 'selected';?>>PHP-8.0</option>
                  <option value="81" <?php if($conf['hxu']=='81')echo 'selected';?>>PHP-8.1</option>
                </select>
            </div>
              </br>
  <div class="form-group">
	  <label for="web_site_logo">Linux建站目录</label>
	  <input type="text" name="linuxml" id="linuxml" value="<?php echo $conf['hxi']; ?>" class="form-control" placeholder="Linux宝塔面板的建站目录" required/>
	<small>默认/www/wwwroot</small></div><br/>
  <div class="form-group">
	  <label for="web_site_logo">Windows建站目录</label>
	  <input type="text" name="winml" id="winml" value="<?php echo $conf['hxo']; ?>" class="form-control" placeholder="Windows宝塔面板的建站目录" required/>
	<small>默认D:/wwwroot</small></div><br/>
	
	<div class="form-group">
	  <label class="btn-block" for="web_site_status">API接口开关</label>
	  <div class="col-xs-6">
	
	          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="apikg" <?php if($conf['apiqk']=='true')echo 'checked';?> >
            <label class="custom-control-label" for="apikg"></label>
          </div>
          </div>
          </div>
          
          <button class="mb-2 btn btn-primary form-control" type="button" onclick="setapi()"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认修改</button>
</div>

<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span> 注意：<b><code>Linux和Windows建站目录请勿随时任意修改，有必要时才进行修改！如果在旧配置下已经开通了要修改目录系统的主机 那么就请别轻易修改！会出现旧配置主机部分操作无法使用的情况(比如上传文件)，请记住修改前的配置！出现错误后可以尝试修改回来</code></b><br/><hr/><code><b>API密钥修改后网址监控的URL和其它系统的对接均需要改为现在的密钥才能使用！</code><br/>默认PHP版本即是网站开通时的默认PHP版本 <code>请确保您的宝塔安装了该版本！ </code> 安装方法：宝塔面板->软件商店->搜索PHP就会显示能够安装的版本
</div>

</div>
<?php }elseif($set=='kzmb'){?>
<div class="border border-primary rounded-top">
<div class="bg-primary border border-gray"><h3 class="ml-2">控制面板设置</h3></div>
<div class="bg-white px-2">
  <div class="form-group">
	  <label for="web_site_logo">控制面板名称</label>
	  <input type="text" name="kzmbname" id="kzmbname" value="<?php echo $conf['name']; ?>" class="form-control" placeholder="请在这填写控制面板的名称" required/>
	  </div><br/>
	<div class="form-group">
	  <label for="web_site_logo">请选择FTP操作面板</label>
                <select class="form-control" id="ftp" name="ftp" size="1">
                <?php
                $acd='';$acd2='';
				if($conf['hxw']=='' || $conf['hxw']=='amftp'){$acd='selected';}else{$acd2='selected';}
                  echo '
                  <option value="amftp" '.$acd.'>AMFTP操作面板</option>
                  <option value="mnftp" '.$acd2.'>MN操作面板(推荐)</option>
                  ';
                  ?>
                </select>
            </div>
              </br>
	<div class="form-group">
	  <label for="web_site_logo">控制面板显示版权</label>
	  <input type="text" name="bq" id="bq" value="<?php echo $conf['hxp']; ?>" class="form-control" placeholder="可以使用HTML标签" required/>
	  <small>比如：Copyright ©梦奈云 2022</small></div><br/>
  <div class="form-group">
	  <label for="web_site_icp">控制面板登陆页面logo</label>
  <div class="custom-file">
      <input type="file" name="logoa" id="logoa" class="custom-file-input" required>
      <label class="custom-file-label" for="logoa">选择文件...</label>
    </div></div>
  <div class="form-group">
	  <label for="web_site_icp">控制面板logo</label>
  <div class="custom-file">
      <input type="file" name="logob" id="logob" class="custom-file-input" required>
      <label class="custom-file-label" for="logob">选择文件...</label>
    </div></div>
  <div class="form-group">
	  <label for="web_site_icp">控制面板用户头像logo</label>
  <div class="custom-file">
      <input type="file" name="logoc" id="logoc" class="custom-file-input" required>
      <label class="custom-file-label" for="logoc">选择文件...</label>
    </div></div>
	<div class="form-group">
	  <label class="btn-block" for="web_site_status">控制面板用户登录验证码</label>
	  <div class="col-xs-6">
	      
	                <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="yzmkzmb" <?php if($conf['yzme']=='true')echo 'checked';?> >
            <label class="custom-control-label" for="yzmkzmb"></label>
          </div>
              </div>
              </div>
	<div class="form-group">
	  <label class="btn-block" for="web_site_status">控制面板开关</label>
	  <div class="col-xs-6">
	      
	      	                <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="kzmbkg" <?php if($conf['kzmbqk']=='true')echo 'checked';?> >
            <label class="custom-control-label" for="kzmbkg"></label>
          </div>
              </div>
          <button class="mb-2 btn btn-primary form-control" type="button" onclick="setkzmb()"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认修改</button>
</div>

<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span> 注意：
AMFTP操作面板不支持远程宝塔仅支持搭建本系统的宝塔</br>MN操作面板支持本地和远程！</br>
不上传logo则继续沿用原logo！<br/>
上传logo后只有您清除缓存后才会显示(如果套了CDN那么CDN也要清理缓存)<br/>
</div>

</div>
</div>
<?php }elseif($set=='gl'){?>

<div class="border border-primary rounded-top">
<div class="bg-primary border border-gray"><h3 class="ml-2">管理配置</h3></div>
<div class="bg-white px-2">
  <div class="form-group">
	  <label for="web_site_logo">原账号</label>
	  <input type="text" name="ysuser" id="ysuser" class="form-control" placeholder="原来的账号" required/>
	  </div><br/>
	<div class="form-group">
	  <label for="web_site_logo">原密码</label>
	  <input type="text" name="yspass" id="yspass" class="form-control" placeholder="原来的密码" required/>
	  </div><br/>
	<div class="form-group">
	  <label for="web_site_logo">修改后的账号</label>
	  <input type="text" name="huser" id="huser" placeholder="不修改请留空" class="form-control" placeholder="请在这填写您的QQ号" required/>
	  </div><br/>
	<div class="form-group">
	  <label for="web_site_logo">修改后的密码</label>
	  <input type="text" name="hpass" id="hpass" placeholder="不修改请留空" class="form-control" placeholder="请在这填写您的QQ号" required/>
	  </div><br/>
          <button class="mb-2 btn btn-primary form-control" type="button" onclick="setgl()"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认修改</button>
</div>
</div>
</div>

<?php }elseif($set=='yzf'){?>

<div class="border border-primary rounded-top">
<div class="bg-primary border border-gray"><h3 class="ml-2">支付配置</h3></div>
<div class="bg-white px-2">
  <div class="form-group">
	  <label for="web_site_logo">易支付地址</label>
	  <input type="text" name="yurl" id="yurl" value="<?php echo $conf['hxe']; ?>" class="form-control" placeholder="易支付对接地址" required/>
	  </div><br/>
  <div class="form-group">
	  <label for="web_site_logo">易支付ID</label>
	  <input type="text" name="yid" id="yid" value="<?php echo $conf['hxr']; ?>" class="form-control" placeholder="易支付商户ID" required/>
	  </div><br/>
  <div class="form-group">
	  <label for="web_site_logo">易支付KEY</label>
	  <input type="text" name="ykey" id="ykey" value="<?php echo $conf['hxt']; ?>" class="form-control" placeholder="易支付站点中您的密钥（KEY）" required/>
	  </div><br/>
          <button class="m-2 btn btn-primary form-control" type="button" onclick="setzf()"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认修改</button>
</div>
</div>
</div>
<?php }
elseif($set == "mail")
{
?>
<div class="border border-primary rounded-top">
<div class="bg-primary border border-gray"><h3 class="ml-2">邮箱配置</h3></div>
<div class="bg-white px-2">
  <div class="form-group">
	  <label for="web_site_logo">邮箱服务器地址</label>
	  <input type="text" name="mailhost" id="mailhost" class="form-control" value="<?php echo $conf['mailhost']; ?>" placeholder="请输入邮箱服务器地址" required/>
	  </div><br/>
	<div class="form-group">
	  <label for="web_site_logo">邮箱账号</label>
	  <input type="text" name="mailuser" id="mailuser" class="form-control" value="<?php echo $conf['mailuser']; ?>" placeholder="请输入邮箱账号" required/>
	  </div><br/>
	<div class="form-group">
	  <label for="web_site_logo">邮箱密码ss</label>
	  <input type="text" name="mailpassword" id="mailpassword" placeholder="请输入邮箱密码" value="<?php echo $conf['mailpassword']; ?>" class="form-control" placeholder="请在这填写您的QQ号" required/>
	  </div><br/>
	<div class="form-group">
	  <label for="web_site_logo">邮箱端口</label>
	  <input type="text" name="mailport" id="mailport" placeholder="请输入邮箱端口" value="<?php echo $conf['mailport']; ?>" class="form-control" placeholder="请在这填写您的QQ号" required/>
	  </div><br/>
          <button class="mb-2 btn btn-primary form-control" type="button" onclick="mailmode()"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认修改</button>
</div>
</div>
</div>

<?php
}
elseif($set=="jk")
{
?>
<div class="border border-primary rounded-top">
<div class="bg-primary border border-gray"><h3 class="ml-2">自动删除主机配置</h3></div>
<div class="bg-white px-2">
	<div class="form-group">
	  <label class="btn-block" for="web_site_status">域名监控主机删除开关</label>
	  <div class="col-xs-6">
	      
	    <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="ymkga" <?php if($conf['ymjkkg']=='true')echo 'checked';?> >
            <label class="custom-control-label" for="ymkga"></label>
        </div>
      </div>
    </div>


	<div class="form-group">
	  <label class="btn-block" for="web_site_status">域名监控主机发送邮件开关</label>
	  <div class="col-xs-6">
	      
	    <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="ymyjkga" <?php if($conf['mtyxfskg']=='true')echo 'checked';?> >
            <label class="custom-control-label" for="ymyjkga"></label>
        </div>
      </div>
    </div>
    
    
    <div class="form-group">
	  <label for="web_site_logo">域名删除主机天数阈值</label>
	  <input type="text" name="ymtsyza" id="ymtsyza" value="<?php echo $conf['ymjktsyz']; ?>" class="form-control" placeholder="请输入天数" required/>
    </div><br/>


	<div class="form-group">
	  <label class="btn-block" for="web_site_status">文件监控主机删除开关</label>
	  <div class="col-xs-6">
	      
	    <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="wjkga" <?php if($conf['wjjkkg']=='true')echo 'checked';?> >
            <label class="custom-control-label" for="wjkga"></label>
        </div>
      </div>
    </div>
    

	<div class="form-group">
	  <label class="btn-block" for="web_site_status">文件监控主机发送邮件开关</label>
	  <div class="col-xs-6">
	      
	    <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="wjyjkga" <?php if($conf['mtwjfskg']=='true')echo 'checked';?> >
            <label class="custom-control-label" for="wjyjkga"></label>
        </div>
      </div>
    </div>


	<div class="form-group">
	  <label for="web_site_logo">文件删除主机天数阈值</label>
	  <input type="text" name="wjtsyza" id="wjtsyza" value="<?php echo $conf['wjjktsyz']; ?>" class="form-control" placeholder="请输入天数" required/>
	</div>
	  
	  

                    <div class="form-group">
                      <select class="form-control selectpicker" name="option1" id="option1">
                        <?php
                        if($conf['optionzc'] == "del")
                        {
                            echo '<option value="del" selected>删除主机</option>';
                            echo '<option value="stop">暂停主机</option>';
                        }
                        elseif($conf['optionzc'] == "stop")
                        {
                            echo '<option value="stop" selected>暂停主机</option>';     
                            echo '<option value="del">删除主机</option>';
                                              
                        }
                        ?>
                      </select>
                    </div>

          <button class="mb-2 btn btn-primary form-control" type="button" onclick="jkscsz()"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认修改</button>
    </div>


<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span> 注意：
开启删除按钮会根据设置的天数进行删除</br>如果只想邮件通知不想删除请不要勾选删除开关</br>
在开启删除按钮时请不要在天数阈值里填0或者负数<br/>
要删除的前一天会给你的邮箱发邮件<br/>
</div>

</div>
</div>
<?php
}
?>
