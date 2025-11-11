<?php 
class bt_api {
var $BT_PANEL;
var $BT_KEY;

  	//如果希望多台面板，可以在实例化对象时，将面板地址与密钥传入
	public function __construct($bt_panel = null,$bt_key = null){
		if($bt_panel) $this->BT_PANEL = $bt_panel;
		if($bt_key) $this->BT_KEY = $bt_key;
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
	
	//------------------------这一块小乐写----------------------------------
	//------------------------这一块小乐写----------------------------------
	//------------------------这一块小乐写----------------------------------
	
	  public function Getymlist($id){			//获取域名列表
		    //拼接URL地址
		    $url = $this->BT_PANEL.'/data?action=getData';
		
		    //准备POST数据
		    $p_data = $this->GetKeyData();		//取签名
		    $p_data['search'] = $id;
		    $p_data['list'] = 'True';
		    $p_data['table'] = 'domain';
		
		    //请求面板接口
		    $result = $this->HttpPostCookie($url,$p_data);
		
		    //解析JSON数据
		    $data = json_decode($result,true);
      	    return $data;
	    }
	    
	  public function stopjq($id){			//暂停机器
		    //拼接URL地址
		    $url = $this->BT_PANEL.'/site?action=SiteStop';
		
		    //准备POST数据
		    $p_data = $this->GetKeyData($id,);		//取签名
            $p_data['id'] = $id;
            $p_data['name'] = $name;
		    //请求面板接口
		    $result = $this->HttpPostCookie($url,$p_data);
		
		    //解析JSON数据
		    $data = json_decode($result,true);
      	    return $data;
	    }
	public function delsite($fte,$szf){			//删除机器 
		$url = $this->BT_PANEL.'/site?action=DeleteSite';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $fte;
		$p_data['webname'] = $szf;
		$p_data['ftp'] = '1';
		$p_data['database'] = '1';
		$p_data['path'] = '1';
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	
	public function GetHostDirectory($path){			//获取网站目录
		$url = $this->BT_PANEL.'/files?action=GetDir';
		$p_data = $this->GetKeyData();		//取签名
        $p_data['p'] = "1";
        $p_data['showRow'] = "500";
        $p_data['path'] = $path;
        $p_data['is_operating'] = "true";
        $p_data['search'] = "";
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
    public function GetFileContent($path){			//获取文件内容
		$url = $this->BT_PANEL.'/files?action=GetFileBody';
		$p_data = $this->GetKeyData();		//取签名
        $p_data['path'] = $path;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	


	private function shell_zx($id){			//执行任务
		$url = $this->BT_PANEL.'/crontab?action=StartTask';          //执行任务
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $id;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
		return $data;
	}
	
	
	private function shell_del($id){			//删除任务
		$url = $this->BT_PANEL.'/crontab?action=DelCrontab';          //删除任务
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $id;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
		return $data;
	}
	
	
	private function shell_list($name){			//获取指定的任务名的id以及是否存在
		$url = $this->BT_PANEL.'/crontab?action=GetCrontab';
		$p_data = $this->GetKeyData();		//取签名
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
		foreach ($data as $v){
		if($v['name']==$name)return ['code'=>1,'msg'=>'此任务存在','id'=>$v['id']];
		}
      	return ['code'=>0,'msg'=>'此任务不存在'];
	}
	
	
	private function shell_log($id){			//获取任务执行结果(返回)并且解析
		$url = $this->BT_PANEL.'/crontab?action=GetLogs';
		$p_data = $this->GetKeyData();		//取签名
		$p_data['id'] = $id;
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
		if(!$data['status'])return ['code'=>0,'msg'=>$data['msg']];
		$jg=explode("\n",$data['msg']);
		foreach ($jg as $k=>$v){
		if(strpos($v,'★') !== false && strpos($jg[$k+1],'-------') !== false && strpos($jg[$k-1],'-------') !== false) {
		unset($jg[$k]);unset($jg[$k+1]);unset($jg[$k-1]);
		$jg[$k]=trim($jg[$k]);
		}
		}
		$jg=array_merge($jg);
		$dat=count($jg);
      	return ['code'=>1,'msg'=>$jg[$dat-2]];
	}
	
	
	private function shell_add($shell){			//添加一个任务
		$url = $this->BT_PANEL.'/crontab?action=AddCrontab';        //添加任务
		$p_data = $this->GetKeyData();		//取签名
		$p_data['name'] = 'MNBT的shell脚本';
		$p_data['type'] = 'day-n';
		$p_data['where1'] = 30;
		$p_data['hour'] = 1;
		$p_data['minute'] = 30;
		$p_data['week'] = '';
		$p_data['sType'] = 'toShell';
		$p_data['sBody'] = $shell;
		$p_data['sName'] = '';
		$p_data['backupTo'] = '';
		$p_data['save'] = '';
		$p_data['urladdress'] = '';
		$p_data['save_local'] = 1;
		$p_data['notice'] = '';
		$p_data['notice_channel'] = '';
		$p_data['datab_name'] = '';
		$p_data['tables_name'] = '';
		$result = $this->HttpPostCookie($url,$p_data);
		$data = json_decode($result,true);
      	return $data;
	}
	
	
	private function shell_yx($shell,$cljgty=true){			//执行指定Shell命令	，是否需要返回处理结果(true/false)
		if(!$shell)return ['code'=>0,'msg'=>'脚本错误！'];
		$re_rw=$this->shell_list('MNBT的shell脚本');
		if($re_rw['code']){$rs_del=$this->shell_del($re_rw['id']);if(!$rs_del['status'])return ['code'=>0,'msg'=>'原任务删除失败，返回：'.$rs_del['msg']];}
		$re_add=$this->shell_add($shell);
		if(!$re_add['status'])return ['code'=>0,'msg'=>'任务添加失败，返回：'.$re_add['msg']];
		$re_zx=$this->shell_zx($re_add['id']);
		if(!$re_zx['status']){$this->shell_del($re_add['id']);return ['code'=>0,'msg'=>'任务执行失败，返回：'.$re_zx['msg']];}
		if($cljgty){
		sleep(1);       //等待执行处理结果
		$re_jg=$this->shell_log($re_add['id']);
		if(!$re_jg['code'])return $re_jg;
		}
		$this->shell_del($re_add['id']);
		return ['code'=>1,'msg'=>$re_jg['msg']];
	}
	
	
	
	public function GetAllFile()//获取指定路径的全部文件
	{
	    include('./bash.conf.php');
	    $file_list=$this->shell_yx($shell_get_file);
        return $file_list;	    
	}
	
	
	public function GetFileCentent($name)//获取指定文件内容
	{
	    include('./bash.conf.php');
	    $shell_cat_file = '
	    #!/bin/bash
        content=$(cat '."$name".')
        echo "$content"  
	    ';
	    $file_centent=$this->shell_yx($shell_cat_file);
        return $file_centent;	    
	}
	//------------------------这一块小乐写----------------------------------
	//------------------------这一块小乐写----------------------------------
	//------------------------这一块小乐写----------------------------------
	
	
	
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