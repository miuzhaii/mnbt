<?php
/*数据库配置*/
$dbconfig=array(
	'host' => 'localhost', //数据库服务器
	'port' => 3306, //数据库端口
	'user' => 'your_database_user', //数据库用户名
	'pwd' => 'your_database_password', //数据库密码
	'dbname' => 'your_database_name', //数据库名

	// 系统加密密钥 - 用于加密敏感数据（FTP密码、数据库密码等）
	// 请使用以下命令生成64位随机密钥：
	// php -r "echo bin2hex(openssl_random_pseudo_bytes(32));"
	//
	// ⚠️ 安全警告：
	// 1. 必须使用随机密钥，不要使用示例值
	// 2. 每个站点应使用不同的密钥
	// 3. 密钥一旦设置后不要轻易修改
	// 4. 妥善保管此文件，定期备份
	'sys_key' => 'CHANGE_THIS_TO_RANDOM_64_CHAR_HEX_STRING', // 必须修改！
);
?>
