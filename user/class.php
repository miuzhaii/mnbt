<?php 

class bt_api {
var $BT_PANEL;
var $BT_KEY;

  	//如果希望多台面板，可以在实例化对象时，将面板地址与密钥传入
	public function __construct($bt_panel = null,$bt_key = null){
		if($bt_panel) $this->BT_PANEL = $bt_panel;
		if($bt_key) $this->BT_KEY = $bt_key;
	}
	
	
  	//示例取面板日志	
	public function GetLogs($zdid){
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=get_dir_auth';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $zdid;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
		public function GetLogsr($zdid,$name){
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=delete_dir_auth';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $zdid;
		$p_data['name'] = $name;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
			public function GetLogst($zdid,$name,$dz,$zh,$mm){
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=set_dir_auth';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $zdid;
		$p_data['name'] = $name;
		$p_data['site_dir'] = $dz;
		$p_data['username'] = $zh;
		$p_data['password'] = $mm;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	    public function GetLogseb($zdid){
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=GetIndex';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $zdid;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
		    public function GetLogsea($zdid,$index){
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=SetIndex';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $zdid;
		$p_data['Index'] = $index;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	     public function GetLogswt($name){		//获取文件
		//拼接URL地址
		$url = $this->BT_PANEL.'/files?action=GetFileBody';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['path'] = $name;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	    public function GetLogswr($name){
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=GetRewriteList';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['siteName'] = $name;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	    public function GetLogswh($data,$datc){		//保存文件
		//拼接URL地址
		$url = $this->BT_PANEL.'/files?action=SaveFileBody';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['data'] = $data;
		$p_data['path'] = $datc;
		$p_data['encoding'] = 'utf-8';
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
		public function GetLogsjywj($yw,$dw,$bm,$mm){
		//拼接URL地址
		$url = $this->BT_PANEL.'/files?action=UnZip';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['sfile'] = $yw;
		$p_data['dfile'] = $dw;
		$p_data['coding'] = $bm;
		$p_data['password'] = $mm;
		$p_data['type'] = 'zip';
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
		public function drsql($wj){
		//拼接URL地址
		$url = $this->BT_PANEL.'/database?action=InputSql';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['file'] = $wj[0];
		$p_data['name'] = strtolower($wj[1]);
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
		public function webkjjs($wj){
		//拼接URL地址
		$url = $this->BT_PANEL.'/files?action=get_path_size';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['path'] = $wj;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
		public function sqlkjhq($sqlzh){
		//拼接URL地址
		$url = $this->BT_PANEL.'/database?action=GetInfo';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['db_name'] = $sqlzh;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
		public function ztweb($id,$name){
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=SiteStop';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $id;
		$p_data['name'] = $name;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
		public function qdweb($id,$name){
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=SiteStart';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $id;
		$p_data['name'] = $name;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
	public function ftpxg($id,$username,$sta){		//设置ftp状态
		$url = $this->BT_PANEL.'/ftp?action=SetStatus';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $id;
		$p_data['username'] = $username;
		$p_data['status'] = $sta;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
		public function getlog($name){
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=GetSiteLogs';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['siteName'] = $name;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	public function GetLogsworld($id,$username,$sta){
		$url = $this->BT_PANEL.'/database?action=ResDatabasePassword';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $id;
		$p_data['name'] = $username;
		$p_data['password'] = $sta;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
	public function GetLogsftp($id,$username,$sta){
		$url = $this->BT_PANEL.'/ftp?action=SetUserPassword';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $id;
		$p_data['ftp_username'] = $username;
		$p_data['new_password'] = $sta;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
		
	
	
	public function GetLogshqwjlo($stera,$sorting='False',$sort='name',$datasize='2000',$page='1'){  //获取当前目录下的文件
		$url = $this->BT_PANEL.'/files?action=GetDir';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['p'] = $page;             //第几页
		$p_data['showRow'] = $datasize;    //每页记录数量
		$p_data['path'] = $stera;       //目录位置
		$p_data['reverse'] = $sorting;      //顺序(True)或者倒序(False)
		$p_data['sort'] = $sort;            //排序字段名
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
		public function delwj($stera){  //删除文件
		$url = $this->BT_PANEL.'/files?action=DeleteFile';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['path'] = $stera;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
		public function delwjj($path,$name,$arridpath){  //删除目录
		$name=trim($name);
		if($name==='/' || $name=='')return ["status"=>false,"msg"=>'无法删除站点目录！'];
		if(strpos($name,'/'))return ["status"=>false,"msg"=>'目录名不规范！'];          //判断删除的目录名是否规范
    	$getfkz=$this->yxmlrhq($arridpath[0],$arridpath[1]);            //获取运行目录(id和网站目录)
    	if($getfkz['runPath']['runPath']==$path.$name)return ["status"=>false,"msg"=>'禁止删除运行目录！'];      //不能删除运行目录
    	$zmllist=$this->urlzmlls($arridpath[0])['binding'];         //获取子目录列表
    	$yxml=$getfkz['runPath']['runPath'];
    	if($yxml!='/'){$yxml.='/';}
    	foreach($zmllist as $val){
    	if(substr($val['path'],0,3)=='../'){$val['path']=substr($val['path'],3); $yxml='/';}        //如果子目录为根目录下则将运行目录赋值为根目录下
    	if($yxml.$val['path']==$path.$name)return ["status"=>false,"msg"=>'错误！您正在尝试删除的目录已被域名'.$val['domain'].'绑定为子目录，禁止删除子目录！'];
    	$yxml=$getfkz['runPath']['runPath'];            //恢复赋值以便下次循环
    	if($yxml!='/'){$yxml.='/';}
    	}
		$url = $this->BT_PANEL.'/files?action=DeleteDir';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['path'] = $arridpath[1].$path.$name;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
		public function xzdelwj($path,$file,$arridpath){  //选择删除文件
    	$getfkz=$this->yxmlrhq($arridpath[0],$arridpath[1]);            //获取运行目录(id和网站目录)
    	$arrfile=json_decode($file,true);
    	$file=delval_array($arrfile,$getfkz['runPath']['runPath'],$path);      //对删除的文件/目录进行规范判断，并且不删除运行目录
    	if(!$file)return ["status"=>false,"msg"=>'禁止删除运行目录！无法删除名称不规范的目录/文件！'];
    	$zmllist=$this->urlzmlls($arridpath[0])['binding'];         //获取子目录列表
    	$yxml=$getfkz['runPath']['runPath'];
    	if($yxml!='/'){$yxml.='/';}
    	foreach($zmllist as $val){
    	if(substr($val['path'],0,3)=='../'){$val['path']=substr($val['path'],3); $yxml='/';}        //如果子目录为根目录下则将运行目录赋值为根目录下
    	$file=delval_array($file,$yxml.$val['path'],$path);
    	$yxml=$getfkz['runPath']['runPath'];            //恢复赋值以便下次循环
    	if($yxml!='/'){$yxml.='/';}
    	}
    	if(!$file)return ["status"=>false,"msg"=>'禁止删除子目录！'];
		$url = $this->BT_PANEL.'/files?action=SetBatchData';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['data'] = json_encode($file,256);
		$p_data['type'] = '4';
		$p_data['path'] = $arridpath[1].$path;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
		public function zswjsc($file,$wz){  //上传文件
		$url = $this->BT_PANEL.'/files?action=UploadFile&path='.$wz.'&codeing=utf-8';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['zunfile'] = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
		public function yxmlrhq($stera,$wjmc){  //获取设置的运行目录和防跨站状态
		$url = $this->BT_PANEL.'/site?action=GetDirUserINI';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $stera;
		$p_data['path'] = $wjmc;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
    	public function fkzup($siteid,$path){    //开启防跨站
    	$fkzqk=$this->yxmlrhq($siteid,$path);
    	if(!$fkzqk['userini']){     //检测是否已经开启，如果未开启则开启
		$url = $this->BT_PANEL.'/site?action=SetDirUserINI';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['path'] = $path;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
    	}else{
    	return ["status"=>true,"msg"=>"防跨站设置情况为开启"];
    	}
	}
	
	
    	public function setyxml($dat){  //设置运行目录
    	$stera=$dat[0];
    	$wjmc=$dat[1];
    	$path=$dat[2];
		$getfkz=$this->yxmlrhq($stera,$path)['runPath']['runPath'];                      //切换前的运行目录
    	$zmllist=$this->urlzmlls($stera)['binding'];         //获取子目录列表
    	//删除绑定在当前运行目录下的域名子目录
    	if($getfkz!='/'){
    	foreach ($zmllist as $vals){
    	if(substr($vals['path'],0,3)!='../'){
    	$this->delzml($stera,$vals['domain'],$path);
    	}
    	}
    	}
		$url = $this->BT_PANEL.'/site?action=SetSiteRunPath';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $stera;
		$p_data['runPath'] = $wjmc;
		$result = $this->HttpPostCookie($url,$p_data);
    	$this->fkzup($stera,$path);          //开启防跨站
		//拷贝一份配置文件到所有子目录中
		$getfkz=$this->yxmlrhq($stera,$path);                      //重新获取运行目录
    	$zmllist=$this->urlzmlls($stera)['binding'];         //重新获取子目录列表
    	$yxml=$getfkz['runPath']['runPath'];
    	if($yxml!='/'){$yxml.='/';}
    	foreach($zmllist as $val){
    	if(substr($val['path'],0,3)=='../'){$val['path']=substr($val['path'],3); $yxml='/';}        //如果子目录为根目录下则将运行目录赋值为根目录下
    	if($getfkz['runPath']['runPath']=='/'){
    	$this->filecopy($path.$getfkz['runPath']['runPath'].'.user.ini',$path.$yxml.$val['path'].'/.user.ini');          //将防跨站配置文件拷贝到所有子目录中
    	}else{
    	$this->filecopy($path.$getfkz['runPath']['runPath'].'/.user.ini',$path.$yxml.$val['path'].'/.user.ini');          //将防跨站配置文件拷贝到所有子目录中
    	}
    	$yxml=$getfkz['runPath']['runPath'];            //恢复赋值以便下次循环
    	if($yxml!='/'){$yxml.='/';}
    	}
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
    	public function xjwj($path){  //新建文件
		$url = $this->BT_PANEL.'/files?action=CreateFile';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['path'] = $path;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
    	public function xjwjj($path){  //新建文件夹
		$url = $this->BT_PANEL.'/files?action=CreateDir';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['path'] = $path;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
    	public function hqwjnr($path){  //获取文件内容
		$url = $this->BT_PANEL.'/files?action=GetFileBody';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['path'] = $path;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
    	public function setwj($wjxx){  //修改文件内容
		$url = $this->BT_PANEL.'/files?action=SaveFileBody';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['data'] = $wjxx[0];
		$p_data['encoding'] = 'utf-8';
		$p_data['path'] = $wjxx[1];
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
    	public function setwjt($wjxx){  //修改伪静态
		$url = $this->BT_PANEL.'/files?action=SaveFileBody';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['data'] = $wjxx[0];
		$p_data['encoding'] = 'utf-8';
		$p_data['path'] = $wjxx[1];
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
    	public function hqsize($wjxx){  //获取文件夹大小
		$url = $this->BT_PANEL.'/files?action=get_path_size';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['path'] = $wjxx;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
    	public function fxdl_add($urlp,$site){           //反向代理_添加
		$url = $this->BT_PANEL.'/site?action=CreateProxy';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['type'] = '1';
		$p_data['proxyname'] = md5($urlp);
		$p_data['cachetime'] = '1';
		$p_data['proxydir'] = '/';
		$p_data['proxysite'] = 'http://'.$urlp;
		$p_data['todomain'] = $urlp;
		$p_data['cache'] = '0';
		$p_data['advanced'] = '0';
		$p_data['sitename'] = $site;
		$p_data['subfilter'] = '[{"sub1":"","sub2":""}]';
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
    	public function fxdl_del($urlp,$site){           //反向代理_删除
		$url = $this->BT_PANEL.'/site?action=RemoveProxy';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['sitename'] = $site;
		$p_data['proxyname'] = md5($urlp);
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	//  ----------------------------这一块内容由小乐编写-------------------------------
	//  ----------------------------这一块内容由小乐编写-------------------------------
	//  ----------------------------这一块内容由小乐编写-------------------------------
	
	
	public function getfdlkg($id,$name)//获取防盗链
	{
	    $url = $this->BT_PANEL.'/site?action=GetSecurity';
	    $p_data = $this->GetKeyData();		//取签名
	    $p_data["id"] = $id;
	    $p_data["name"] = $name;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;	   
	}
	public function Setfdlkg($id,$name,$fix,$domains,$status,$return_rule,$httpsta)  //设置防盗链开关
	{
	    $url = $this->BT_PANEL.'/site?action=SetSecurity';
	    $p_data = $this->GetKeyData();		//取签名
	    $p_data['id'] = $id;
	    $p_data['name'] = $name;
	    $p_data['fix'] = $fix;
	    $p_data['domains'] = $domains;
	    $p_data['status'] = $status;
	    $p_data['return_rule'] = $return_rule;
	    $p_data['http_status'] = $httpsta;
	    $result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	public function Databasebackuplist($id)  //获取数据库备份列表
	{
	    $url = $this->BT_PANEL.'/data?action=getData';
	    $p_data = $this->GetKeyData();		//取签名
        $p_data['table'] = "backup"; //不知道默认就行
        $p_data['search'] = $id; // 数据库id
        $p_data['type'] = "1";     //不知道默认就行
        $p_data['limit'] = "200";  //每页个数
        $p_data['p'] = "1";    //页数
	    $result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	public function DatabaseDelete($id)  //删除数据库备份列表
	{
	    $url = $this->BT_PANEL.'/database?action=DelBackup';
	    $p_data = $this->GetKeyData();		//取签名
        $p_data['id'] = $id; //备份的数据库id
	    $result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	public function Databaseadd($id)  //添加数据库备份
	{
	    $url = $this->BT_PANEL.'/database?action=ToBackup';
	    $p_data = $this->GetKeyData();		//取签名
        $p_data['id'] = $id; //主机id
	    $result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	public function Databaserestore($file,$user)  //恢复数据库备份
	{
	    $url = $this->BT_PANEL.'/database?action=InputSql';
	    $p_data = $this->GetKeyData();		//取签名
        $p_data['file'] = $file; //备份文件的路径
        $p_data['name'] = $user; // 主机名
	    $result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	public function Getnginx($path)  //获取网站nginx配置信息
	{
	    $url = $this->BT_PANEL.'/files?action=GetFileBody';
	    $p_data = $this->GetKeyData();		//取签名
	    $patha = "/www/server/panel/vhost/nginx/".$path.".conf";
	    $p_data['path'] = $patha;
	    $result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	public function GetDatabaseAccess($name)  //获取数据库权限
	{
	    $url = $this->BT_PANEL.'/database?action=GetDatabaseAccess';
	    $p_data = $this->GetKeyData();		//取签名
	    $p_data['name'] = $name;
	    $result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	public function SetDatabaseAccess($name,$dataAccess)  //设置数据库权限
	{
	    $url = $this->BT_PANEL.'/database?action=SetDatabaseAccess';
	    $p_data = $this->GetKeyData();		//取签名
	    $p_data['name'] = $name;
	    $p_data['dataAccess'] = $dataAccess;
	    $p_data['access'] = $dataAccess;
	    $result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
		public function 获取部署程序的列表()  //获取宝塔一键部署的列表
	{
	    $url = $this->BT_PANEL.'/deployment?action=GetList';
	    $p_data = $this->GetKeyData();		//取签名
	    $p_data['type'] = 0;
	    $result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	//  ----------------------------这一块内容由小乐编写-------------------------------
	//  ----------------------------这一块内容由小乐编写-------------------------------
	//  ----------------------------这一块内容由小乐编写-------------------------------
    	public function cxname($jwz){           //文件重命名        数组[主机目录，文件目录，旧名称，新名称]
    	global $yhc;         //获取类外部变量
		if(strpos($jwz[2],'/'))return ["status"=>false,"msg"=>'被重命名的文件不存在！'];          //判断被修改的文件名是否规范
		if(strpos($jwz[3],'/'))return ["status"=>false,"msg"=>'新文件名不规范！'];          //判断新的的文件名是否规范
    	$getfkz=$this->yxmlrhq($yhc['btid'],$jwz[0]);            //获取运行目录(id和网站目录)
    	if($getfkz['runPath']['runPath']==$jwz[1].$jwz[2])return ["status"=>false,"msg"=>'错误！您正在尝试重命名运行目录，这是不被允许的！'];      //不能重命名运行目录
    	if($getfkz['runPath']['runPath']==$jwz[1].$jwz[3])return ["status"=>false,"msg"=>'错误！此文件名已存在！'];      //检测重命名后的文件是否为运行目录
    	$zmllist=$this->urlzmlls($yhc['btid'])['binding'];         //获取子目录列表
    	$yxml=$getfkz['runPath']['runPath'];
    	if($yxml!='/'){$yxml.='/';}
    	foreach($zmllist as $val){
    	if(substr($val['path'],0,3)=='../'){$val['path']=substr($val['path'],3); $yxml='/';}        //如果子目录为根目录下则将运行目录赋值为根目录下
    	if($yxml.$val['path']==$jwz[1].$jwz[2])return ["status"=>false,"msg"=>'错误！您正在尝试重命名的目录已被域名'.$val['domain'].'绑定为子目录，禁止重命名子目录！'];
    	if($yxml.$val['path']==$jwz[1].$jwz[3])return ["status"=>false,"msg"=>'错误！此文件名已存在！'];
    	$yxml=$getfkz['runPath']['runPath'];            //恢复赋值以便下次循环
    	if($yxml!='/'){$yxml.='/';}
    	}
		$url = $this->BT_PANEL.'/files?action=MvFile';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['sfile'] = $jwz[0].$jwz[1].$jwz[2];
		$p_data['dfile'] = $jwz[0].$jwz[1].$jwz[3];
		$p_data['rename'] = true;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}


    	public function fileupa($fene){           //文件分片上传检测
		$url = $this->BT_PANEL.'/files?action=upload_file_exists';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['filename'] = $fene;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
    	public function fileups($uplj,$file,$dfrd,$filene,$se){           //文件分片上传
		$url = $this->BT_PANEL.'/files?action=upload';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['f_path'] = $uplj;
		$p_data['f_name'] = $filene;
		$p_data['f_size'] = $se;
		$p_data['f_start'] = $dfrd;
		$p_data['blob'] = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
    	public function filecopy($filef,$filed){           //文件复制粘贴
		$url = $this->BT_PANEL.'/files?action=CopyFile';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['sfile'] = $filef;
		$p_data['dfile'] = $filed;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
    	public function fileysr($filename,$filed,$type,$path){           //文件压缩
		$url = $this->BT_PANEL.'/files?action=Zip';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['sfile'] = $filename;
		$p_data['dfile'] = $filed;
		$p_data['z_type'] = $type;
		$p_data['path'] = $path;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
    	public function wailkq($path,$name){           //外链开启
		$url = $this->BT_PANEL.'/files?action=create_download_url';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['filename'] = $path.$name;
		$p_data['ps'] = $name;
		$p_data['password'] = '';
		$p_data['expire'] = 24;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
    	public function wailhq($wlid){           //外链获取
		$url = $this->BT_PANEL.'/files?action=get_download_url_find';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $wlid;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
    	public function wailgb($wlid){           //外链关闭
		$url = $this->BT_PANEL.'/files?action=remove_download_url';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $wlid;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
    	public function urllist($siteid){           //域名列表(包含子目录域名)
		$url = $this->BT_PANEL.'/site?action=GetSiteDomains';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $siteid;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
    	public function httpsfcz(){           //关闭https窜站
		$url = $this->BT_PANEL.'/site?action=get_https_mode';       //检测
		$p_data = $this->GetKeyData();		//取签名
		$result = $this->HttpPostCookie($url,$p_data);
		if($result=='false'){
		$url = $this->BT_PANEL.'/site?action=set_https_mode';
		$data = json_decode($this->HttpPostCookie($url,$p_data),true);            //关闭
		}else{
		    $data=["status"=>true,"msg"=>'https窜站未开启'];
		}
      	return $data;
	}
	
    	public function sslsq($urllist,$siteid,$sitename,$type=false){           //申请/续签Let's Encrypt的SSL证书，$sitename不为空则为续签
    	$this->httpsfcz();          //关闭窜站
		$url = $this->BT_PANEL.'/acme?action=apply_cert_api';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['domains'] = $urllist;
		$p_data['auth_type'] = 'http';
		$p_data['auth_to'] = $siteid;
		$p_data['auto_wildcard'] = 0;
		$p_data['id'] = $siteid;
		if($type){
		$p_data['siteName'] = $sitename;        //续签
		}
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
		if($data['status']){        //申请成功，配置并且开启ssl
		$zsps=$this->setsslpem($sitename,$data['private_key'],$data['cert'].'\n'.$data['root']);
		return ["status"=>$zsps['status'],"msg"=>[$zsps['msg']]];
		}else{
      	return $data;
		}
	}
	
    	public function setsslpem($sitename,$key,$csr){           //配置SSL证书
    	$this->httpsfcz();          //关闭窜站
		$url = $this->BT_PANEL.'/site?action=SetSSL';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['type'] = 1;
		$p_data['siteName'] = $sitename;
		$p_data['key'] = $key;
		$p_data['csr'] = $csr;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
    	public function getsslpem($sitename){           //获取SSL证书
		$url = $this->BT_PANEL.'/site?action=GetSSL';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['siteName'] = $sitename;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
    	public function closessl($sitename){           //关闭SSL
		$url = $this->BT_PANEL.'/site?action=CloseSSLConf';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['updateOf'] = 1;
		$p_data['siteName'] = $sitename;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
    	public function httpsqzf($sitename,$ksg){           //开启/关闭强制HTTPS
    	if($ksg=='true'){
		$url = $this->BT_PANEL.'/site?action=HttpToHttps';
    	}else{
    	$url = $this->BT_PANEL.'/site?action=CloseToHttps';
    	}
		$p_data = $this->GetKeyData();		//取签名
		$p_data['siteName'] = $sitename;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
    	public function urlzmlls($siteid){           //获取子目录域名列表，以及获取站点可选子目录
		$url = $this->BT_PANEL.'/site?action=GetDirBinding';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $siteid;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
    	public function addzml($siteid,$urls,$dir,$sitepath){           //添加子目录域名(宝塔站点id，域名，子目录，站点目录)
	    if (strpos($urls, "\n") !== false) {
            return ['status'=>false,'msg'=>'不能使用换行符！'];
        }
		$url = $this->BT_PANEL.'/site?action=AddDirBinding';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $siteid;
		$p_data['domain'] = $urls;
		$p_data['dirName'] = $dir;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
		if($data['status']){
    	$getyxml=$this->yxmlrhq($siteid,$sitepath);            //获取运行目录
    	if($getyxml['runPath']['runPath']!='/'){        //将防跨站文件拷贝到子目录里面
    	$this->filecopy($sitepath.$getyxml['runPath']['runPath'].'/.user.ini',$sitepath.$getyxml['runPath']['runPath'].'/'.$dir.'/.user.ini');
    	}else{
    	$this->filecopy($sitepath.$getyxml['runPath']['runPath'].'.user.ini',$sitepath.$getyxml['runPath']['runPath'].$dir.'/.user.ini');
    	}
		}
    	return $data;
	}
	
    	public function delzml($siteid,$urls,$sitepath){           //删除子目录域名(宝塔站点id，删除的域名，网站目录)
    	$zmllist=$this->urlzmlls($siteid);         //获取子目录域名列表
    	$getyxml=$this->yxmlrhq($siteid,$sitepath);            //获取运行目录
    	foreach ($zmllist['binding'] as $vals){         //获取域名id
    	if($vals['domain']==$urls){
    	$urlid=$vals['id'];
    	$urlpath=$vals['path'];
    	break;
    	}}
    	if(substr($urlpath,0,3)=='../')$urlpaths=substr($urlpath,3); 
    	if($urlid==false)return ["status"=>false,"msg"=>'域名不存在！'];
    	if($urlpath!=false){
    	if($getyxml['runPath']['runPath']!='/'.$urlpaths){           //如果运行目录为子目录则不删除防跨站(.user.ini)文件
    	if($getyxml['runPath']['runPath']!='/'){
    	if(substr($urlpath,0,3)=='../'){
    	$urlpath=substr($urlpath,3); 
    	$this->delwj($sitepath.'/'.$urlpath.'/.user.ini');     //删除根目录下的子目录的防跨站文件
    	}else{
    	$this->delwj($sitepath.$getyxml['runPath']['runPath'].'/'.$urlpath.'/.user.ini');     //删除运行目录下的子目录的防跨站文件
    	}
    	}else{
    	$this->delwj($sitepath.'/'.$urlpath.'/.user.ini');     //删除运行目录下的子目录的防跨站文件
    	}
    	}
    	}
		$url = $this->BT_PANEL.'/site?action=DelDirBinding';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $urlid;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
	
	
  	/**
     * 构造带有签名的关联数组
     */
  	private function GetKeyData(){
  		$now_time = time();
    	$p_data = array(
			'request_token'	=>	md5($now_time.''.md5($this->BT_KEY)),
			'request_time'	=>	$now_time
		);
    	return $p_data;    
    }
  	
  
  	/**
     * 发起POST请求
     * @param String $url 目标网填，带http://
     * @param Array|String $data 欲提交的数据
     * @return string
     */
    private function HttpPostCookie($url, $data,$timeout = 60)
    {
    	//定义cookie保存位置
        $cookie_file='../api/cookie/'.md5($this->BT_PANEL).'.cookie';
        if(!file_exists($cookie_file)){
            $fp = fopen($cookie_file,'w+');
            fclose($fp);
        }
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}



class bt_api_set {
var $BT_PANEL;
var $BT_KEY;

  	//如果希望多台面板，可以在实例化对象时，将面板地址与密钥传入
	public function __construct($bt_panel = null,$bt_key = null){
		if($bt_panel) $this->BT_PANEL = $bt_panel;
		if($bt_key) $this->BT_KEY = $bt_key;
	}
	
	
  	//示例取面板日志	
	public function btapi_ym($zdid){			//获取域名列表
		//拼接URL地址
		$url = $this->BT_PANEL.'/data?action=getData';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['search'] = $zdid;
		$p_data['list'] = 'True';
		$p_data['table'] = 'domain';
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
  	//示例取面板日志	
	public function btapi_addym($zdid,$name,$urle){					//添加域名
	    
	    if (strpos($urle, "\n") !== false) {
            return ['status'=>false,'msg'=>'不能使用换行符！'];
        }
	
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=AddDomain';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $zdid;
		$p_data['webname'] = $name;
		$p_data['domain'] = $urle;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data['domains'][0];
	}
	
	
	
  	//示例取面板日志	
	public function GetLogsy($zdid){
		//拼接URL地址
		$url = $this->BT_PANEL.'/data?action=getData&table=domain';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['search'] = $zdid;
		$p_data['list'] = 'true';
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
      	}
	
	
	public function sitemsg($sitename){					//获取主机信息
		$url = $this->BT_PANEL.'/data?action=getData';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['table'] = 'sites';
		$p_data['limit'] = 200;
		$p_data['p'] = 1;
		$p_data['search'] = $sitename;
		$p_data['type'] = -1;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true)['data'];
		if(empty($data))return ['code'=>false,'msg'=>'无主机信息！'];
		foreach ($data as $val){
		if($val['name']==$sitename)return ['code'=>true,'msg'=>$val];
		}
		return ['code'=>false,'msg'=>'无主机信息！'];
	}
      	
      	
      	
  	//示例取面板日志	
	public function btapi_delym($zdid,$name,$urle,$port){					//删除域名
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=DelDomain';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $zdid;
		$p_data['webname'] = $name;
		$p_data['domain'] = $urle;
		$p_data['port'] = $port;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
		//示例取面板日志	
	public function btapi_setphp($zdname,$phpbb){					//设置PHP版本
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=SetPHPVersion';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['siteName'] = $zdname;
		$p_data['version'] = $phpbb;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
		//示例取面板日志	
	public function btapi_listphp(){					//获取安装了的PHP版本列表
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=GetPHPVersion';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['s_type'] = '1';
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
		//示例取面板日志	
	public function btapi_phpnowz($wzname){					//获取当前主机的PHP版本
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=GetSitePHPVersion';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['siteName'] = $wzname;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
  	/**
     * 构造带有签名的关联数组
     */
  	private function GetKeyData(){
  		$now_time = time();
    	$p_data = array(
			'request_token'	=>	md5($now_time.''.md5($this->BT_KEY)),
			'request_time'	=>	$now_time
		);
    	return $p_data;    
    }
  	
  
  	/**
     * 发起POST请求
     * @param String $url 目标网填，带http://
     * @param Array|String $data 欲提交的数据
     * @return string
     */
    private function HttpPostCookie($url, $data,$timeout = 60)
    {
    	//定义cookie保存位置
        $cookie_file='../api/cookie/'.md5($this->BT_PANEL).'.cookie';
        if(!file_exists($cookie_file)){
            $fp = fopen($cookie_file,'w+');
            fclose($fp);
        }
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}


class win_bt_api {			//Windows的专用APi
var $BT_PANEL;
var $BT_KEY;

  	//如果希望多台面板，可以在实例化对象时，将面板地址与密钥传入
	public function __construct($bt_panel = null,$bt_key = null){
		if($bt_panel) $this->BT_PANEL = $bt_panel;
		if($bt_key) $this->BT_KEY = $bt_key;
	}
	
	
  	//示例取面板日志	
	public function setwjt($dat){			//设置伪静态
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=SetSiteRewrite';			
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['siteName'] = $dat[0];
		$p_data['data'] = $dat[1];
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
  	//示例取面板日志	
	public function wjt_hqdq($ne){					//获取当前伪静态
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=GetSiteRewrite';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['siteName'] = $ne;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
  	//示例取面板日志	
	public function wjt_hq($data){			//获取伪静态
		//拼接URL地址
		$url = $this->BT_PANEL.'/files?action=GetFileBody';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['path'] = $data;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
      	}
	
	
		//示例取面板日志	
	public function btapi_setphp($zdname,$phpbb){					//设置PHP版本
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=SetPHPVersion';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['siteName'] = $zdname;
		$p_data['version'] = $phpbb;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
		//示例取面板日志	
	public function btapi_listphp(){					//获取安装了的PHP版本列表
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=GetPHPVersion';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['s_type'] = '1';
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
		//示例取面板日志	
	public function btapi_phpnowz($wzname){					//获取当前主机的PHP版本
		//拼接URL地址
		$url = $this->BT_PANEL.'/site?action=GetSitePHPVersion';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['siteName'] = $wzname;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
  	/**
     * 构造带有签名的关联数组
     */
  	private function GetKeyData(){
  		$now_time = time();
    	$p_data = array(
			'request_token'	=>	md5($now_time.''.md5($this->BT_KEY)),
			'request_time'	=>	$now_time
		);
    	return $p_data;    
    }
  	
  
  	/**
     * 发起POST请求
     * @param String $url 目标网填，带http://
     * @param Array|String $data 欲提交的数据
     * @return string
     */
    private function HttpPostCookie($url, $data,$timeout = 60)
    {
    	//定义cookie保存位置
        $cookie_file='../api/cookie/'.md5($this->BT_PANEL).'.cookie';
        if(!file_exists($cookie_file)){
            $fp = fopen($cookie_file,'w+');
            fclose($fp);
        }
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}


class bt_api_rj {
var $BT_PANEL;
var $BT_KEY;

  	//如果希望多台面板，可以在实例化对象时，将面板地址与密钥传入
	public function __construct($bt_panel = null,$bt_key = null){
		if($bt_panel) $this->BT_PANEL = $bt_panel;
		if($bt_key) $this->BT_KEY = $bt_key;
	}
	
	
  	//示例取面板日志	
	public function api_sql_cf(){			//PHPMyAdmin配置获取
		//拼接URL地址
		$url = $this->BT_PANEL.'/plugin?action=get_soft_find';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['sName'] = 'phpmyadmin';
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
  	//示例取面板日志	
	public function api_sql_set($set_r){			//PHPMyAdmin修改是否允许使用外部管理链接
		//拼接URL地址
		$url = $this->BT_PANEL.'/system?action=ServiceAdmin';
		
		//准备POST数据
		$p_data = $this->GetKeyData();		//取签名
		$p_data['name'] = 'phpmyadmin';
		$p_data['type'] = $set_r;
		
		//请求面板接口
		$result = $this->HttpPostCookie($url,$p_data);
		
		//解析JSON数据
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
  	/**
     * 构造带有签名的关联数组
     */
  	private function GetKeyData(){
  		$now_time = time();
    	$p_data = array(
			'request_token'	=>	md5($now_time.''.md5($this->BT_KEY)),
			'request_time'	=>	$now_time
		);
    	return $p_data;    
    }
  	
  
  	/**
     * 发起POST请求
     * @param String $url 目标网填，带http://
     * @param Array|String $data 欲提交的数据
     * @return string
     */
    private function HttpPostCookie($url, $data,$timeout = 60)
    {
    	//定义cookie保存位置
        $cookie_file='../api/cookie/'.md5($this->BT_PANEL).'.cookie';
        if(!file_exists($cookie_file)){
            $fp = fopen($cookie_file,'w+');
            fclose($fp);
        }
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}

?>