<?php

class bt_api {
var $BT_PANEL;
var $BT_KEY;

      //如果希望多台面板，可以在实例化对象时，将面板地址与密钥传入
    public function __construct($bt_panel = null,$bt_key = null){
        if($bt_panel) $this->BT_PANEL = $bt_panel;
        if($bt_key) $this->BT_KEY = $bt_key;
    }
        
        public function databascx($user){  //查询数据库
        $url = $this->BT_PANEL.'/data?action=getData';
        $p_data = $this->GetKeyData();        //取签名
        $p_data['table'] = 'databases';
        $p_data['search'] = $user;
        $p_data['limit'] = 100;
        $p_data['p'] = 1;
        $result = $this->HttpPostCookie($url,$p_data);
        $data = json_decode($result,true);
          return $data;
    }
        
        public function ftpcx($user){  //查询FTP
        $url = $this->BT_PANEL.'/data?action=getData';
        $p_data = $this->GetKeyData();        //取签名
        $p_data['table'] = 'ftps';
        $p_data['search'] = $user;
        $p_data['limit'] = 100;
        $p_data['p'] = 1;
        $result = $this->HttpPostCookie($url,$p_data);
        $data = json_decode($result,true);
          return $data;
    }
        
        public function hqwjnr($path){  //获取文件内容
        $url = $this->BT_PANEL.'/files?action=GetFileBody';
        $p_data = $this->GetKeyData();        //取签名
        $p_data['path'] = $path;
        $result = $this->HttpPostCookie($url,$p_data);
        $data = json_decode($result,true);
          return $data;
    }
    
    
    
        public function setwj($wjxx){  //修改文件内容
        $url = $this->BT_PANEL.'/files?action=SaveFileBody';
        $p_data = $this->GetKeyData();        //取签名
        $p_data['data'] = $wjxx[0];
        $p_data['encoding'] = 'utf-8';
        $p_data['path'] = $wjxx[1];
        $result = $this->HttpPostCookie($url,$p_data);
        $data = json_decode($result,true);
          return $data;
    }
    
    
    public function delsite($fte,$szf){            //删除网站
        $url = $this->BT_PANEL.'/site?action=DeleteSite';
        $p_data = $this->GetKeyData();        //取签名
        $p_data['id'] = $fte;
        $p_data['webname'] = $szf;
        $p_data['ftp'] = '1';
        $p_data['database'] = '1';
        $p_data['path'] = '1';
        $result = $this->HttpPostCookie($url,$p_data);
        $data = json_decode($result,true);
          return $data;
    }
    
    
      //示例取面板日志    
    public function setdqsj($zdid,$dqsj){            //设置到期时间
        //拼接URL地址
        $url = $this->BT_PANEL.'/site?action=SetEdate';
        
        //准备POST数据
        $p_data = $this->GetKeyData();        //取签名
        $p_data['id'] = $zdid;
        $p_data['edate'] = $dqsj;
        
        //请求面板接口
        $result = $this->HttpPostCookie($url,$p_data);
        
        //解析JSON数据
        $data = json_decode($result,true);
          return $data;
    }
    
    
    public function setftpzt($id,$username,$sta){        //设置ftp状态
        $url = $this->BT_PANEL.'/ftp?action=SetStatus';
        $p_data = $this->GetKeyData();        //取签名
        $p_data['id'] = $id;
        $p_data['username'] = $username;
        $p_data['status'] = $sta;
        $result = $this->HttpPostCookie($url,$p_data);
        $data = json_decode($result,true);
          return $data;
    }
    
    // 虚拟主机数据迁移功能
    public function migrateSite($sourceSite, $targetSite, $migrateOptions = array('files' => true, 'database' => true, 'ftp' => true)) {
        $results = array();
        
        // 迁移文件
        if ($migrateOptions['files']) {
            $results['files'] = $this->migrateFiles($sourceSite['path'], $targetSite['path']);
        }
        
        // 迁移数据库
        if ($migrateOptions['database'] && !empty($sourceSite['database']) && !empty($targetSite['database'])) {
            $results['database'] = $this->migrateDatabase($sourceSite['database'], $targetSite['database']);
        }
        
        // 迁移FTP设置
        if ($migrateOptions['ftp'] && !empty($sourceSite['ftp']) && !empty($targetSite['ftp'])) {
            $results['ftp'] = $this->migrateFtp($sourceSite['ftp'], $targetSite['ftp']);
        }
        
        return $results;
    }
    
    private function migrateFiles($sourcePath, $targetPath) {
        // 获取源文件列表
        $url = $this->BT_PANEL.'/files?action=GetDir';
        $p_data = $this->GetKeyData();
        $p_data['path'] = $sourcePath;
        $result = $this->HttpPostCookie($url, $p_data);
        $dirList = json_decode($result, true);
        
        if (!$dirList['status']) {
            return array('status' => false, 'msg' => '无法获取源文件列表');
        }
        
        // 这里简化处理，实际应该分批处理大文件
        $url = $this->BT_PANEL.'/files?action=CopyFile';
        $p_data = $this->GetKeyData();
        $p_data['sfile'] = $sourcePath;
        $p_data['dfile'] = $targetPath;
        $result = $this->HttpPostCookie($url, $p_data);
        
        return json_decode($result, true);
    }
    
    private function migrateDatabase($sourceDb, $targetDb) {
        // 备份源数据库
        $url = $this->BT_PANEL.'/database?action=ToBackup';
        $p_data = $this->GetKeyData();
        $p_data['id'] = $sourceDb['id'];
        $result = $this->HttpPostCookie($url, $p_data);
        $backup = json_decode($result, true);
        
        if (!$backup['status']) {
            return array('status' => false, 'msg' => '数据库备份失败');
        }
        
        // 获取备份文件内容
        $backupFile = $backup['file'];
        $url = $this->BT_PANEL.'/files?action=GetFileBody';
        $p_data = $this->GetKeyData();
        $p_data['path'] = $backupFile;
        $result = $this->HttpPostCookie($url, $p_data);
        $fileContent = json_decode($result, true);
        
        if (!$fileContent['status']) {
            return array('status' => false, 'msg' => '无法读取备份文件');
        }
        
        // 导入到目标数据库
        $url = $this->BT_PANEL.'/database?action=InputSql';
        $p_data = $this->GetKeyData();
        $p_data['name'] = $targetDb['name'];
        $p_data['file'] = $backupFile;
        $result = $this->HttpPostCookie($url, $p_data);
        
        return json_decode($result, true);
    }
    
    private function migrateFtp($sourceFtp, $targetFtp) {
        // 更新目标FTP密码为源FTP密码
        $url = $this->BT_PANEL.'/ftp?action=SetUserPassword';
        $p_data = $this->GetKeyData();
        $p_data['id'] = $targetFtp['id'];
        $p_data['ftp_username'] = $targetFtp['username'];
        $p_data['new_password'] = $sourceFtp['password'];
        $result = $this->HttpPostCookie($url, $p_data);
        
        return json_decode($result, true);
    }
    
    
    public function setftppass($id,$username,$sta){            //设置FTP密码
        $url = $this->BT_PANEL.'/ftp?action=SetUserPassword';
        $p_data = $this->GetKeyData();        //取签名
        $p_data['id'] = $id;
        $p_data['ftp_username'] = $username;
        $p_data['new_password'] = $sta;
        $result = $this->HttpPostCookie($url,$p_data);
        $data = json_decode($result,true);
          return $data;
    }
    
        
      //示例取面板日志    
    public function webkt($userq,$passq,$btserw,$cptypr,$ftpr,$sqlr,$mrbb,$mrml){            //开通主机
        //拼接URL地址
        $url = $this->BT_PANEL.'/site?action=AddSite';
        
        //准备POST数据
        $p_data = $this->GetKeyData();        //取签名
        $p_data['webname'] = '{"domain":"'.$btserw.'","domainlist":[],"count":0}';
        $p_data['path'] = $mrml;
        $p_data['type_id'] = '0';
        $p_data['type'] = 'PHP';
        $p_data['version'] = $mrbb;
        $p_data['port'] = '80';
        $p_data['ps'] = 'MNBT接口开通的'.$cptype;
        $p_data['ftp'] = $ftpr;
        $p_data['ftp_username'] = $userq;
        $p_data['ftp_password'] = $passq;
        $p_data['sql'] = $sqlr;
        $p_data['codeing'] = 'utf8';
        $p_data['datauser'] = $userq;
        $p_data['datapassword'] = $passq;
        
        //请求面板接口
        $result = $this->HttpPostCookie($url,$p_data);
        
        //解析JSON数据
        $data = json_decode($result,true);
          return $data;
    }
    
          //示例取面板日志    
    public function mysqlqx($saer,$xxip){            //设置数据库访问权限
        //拼接URL地址
        $url = $this->BT_PANEL.'/database?action=SetDatabaseAccess';
        
        //准备POST数据
        $p_data = $this->GetKeyData();        //取签名
        $p_data['name'] = $saer;
        $p_data['dataAccess'] = '%';
        $p_data['access'] = $xxip;

        //请求面板接口
        $result = $this->HttpPostCookie($url,$p_data);
        
        //解析JSON数据
        $data = json_decode($result,true);
          return $data;
    }
    
    
         //示例取面板日志    
    public function sjlist($gln){            //获取FTP/数据库列表
        //拼接URL地址
        $url = $this->BT_PANEL.'/data?action=getData';
        
        //准备POST数据
        $p_data = $this->GetKeyData();        //取签名
        $p_data['table'] = $gln;
        $p_data['limit'] = '9999';
        $p_data['p'] = '1';
        $p_data['search'] = '';
        
        //请求面板接口
        $result = $this->HttpPostCookie($url,$p_data);
        
        //解析JSON数据
        $data = json_decode($result,true);
          return $data;
    }
    
    
         //示例取面板日志    
    public function siteqt($gln,$name,$ms){            //启用/停用网站
        //拼接URL地址
        if($ms){
        $url = $this->BT_PANEL.'/site?action=SiteStart';
        }else{
        $url = $this->BT_PANEL.'/site?action=SiteStop';
        }
        //准备POST数据
        $p_data = $this->GetKeyData();        //取签名
        $p_data['id'] = $gln;
        $p_data['name'] = $name;
        
        //请求面板接口
        $result = $this->HttpPostCookie($url,$p_data);
        
        //解析JSON数据
        $data = json_decode($result,true);
          return $data;
    }
    
    
      //示例取面板日志    
    public function urllist($zdid){            //获取域名列表
        //拼接URL地址
        $url = $this->BT_PANEL.'/data?action=getData';
        
        //准备POST数据
        $p_data = $this->GetKeyData();        //取签名
        $p_data['search'] = $zdid;
        $p_data['list'] = 'True';
        $p_data['table'] = 'domain';
        
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
            'request_token'    =>    md5($now_time.''.md5($this->BT_KEY)),
            'request_time'    =>    $now_time
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