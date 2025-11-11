# MN宝塔 安装指南

## 环境要求

- PHP 7.0+
- MySQL 5.6+ 或 SQLite
- 宝塔面板 6.0+
- PHP扩展：curl, mysqli, mbstring, json, zip

## 安装步骤

### 1. 下载源码

```bash
git clone https://github.com/your-username/mnbt.git
cd mnbt
```

### 2. 配置数据库

复制配置文件模板：

```bash
cp config.sample.php config.php
```

编辑 `config.php`，填写数据库信息：

```php
$dbconfig=array(
    'host' => 'localhost',      // 数据库服务器
    'port' => 3306,             // 数据库端口
    'user' => 'your_db_user',   // 数据库用户名
    'pwd' => 'your_db_pass',    // 数据库密码
    'dbname' => 'your_db_name', // 数据库名
);
```

### 3. 导入数据库

使用提供的SQL文件创建数据库表结构：

```bash
mysql -u your_db_user -p your_db_name < database.sql
```

### 4. 配置授权码

```bash
cp MPHX/SQ.sample.php MPHX/SQ.php
```

编辑 `MPHX/SQ.php`：
- 免费版：保持 `$authcode='无需验证';`
- 正式版：填写购买的授权码

### 5. 设置目录权限

```bash
chmod -R 755 .
chmod -R 777 filecx
chmod -R 777 imsetes/upload_logo
```

### 6. 配置宝塔面板

在宝塔面板中：
1. 进入"面板设置" -> "API接口"
2. 开启API接口
3. 添加服务器IP到白名单
4. 复制API密钥

### 7. 访问安装

首次访问管理后台，系统会引导你完成初始化设置：

1. 访问 `http://yourdomain.com/admin/`
2. 创建管理员账户
3. 配置宝塔面板连接信息
4. 完成初始化

## 配置说明

### 管理后台

- URL: `http://yourdomain.com/admin/`
- 默认需要在首次访问时创建管理员账户

### 用户面板

- URL: `http://yourdomain.com/user/`
- 用户通过管理员分配的账号登录

### API接口

- URL: `http://yourdomain.com/api/`
- 需要API密钥才能访问
- 详见 [API文档](api.md)

## 常见问题

### 1. 登录后提示"请求错误"

检查 `config.php` 数据库配置是否正确。

### 2. 无法连接宝塔面板

确保：
- 宝塔面板API接口已开启
- 服务器IP已添加到白名单
- API密钥正确

### 3. 文件上传失败

检查目录权限：
```bash
chmod -R 777 filecx
```

### 4. 密码显示为加密字符串

这是正常的，系统使用加密存储。管理员可以在后台查看和修改。

## 安全建议

1. **修改默认管理员账号**：安装后立即修改管理员账号和密码
2. **启用HTTPS**：建议在生产环境中使用HTTPS
3. **限制API访问**：通过IP白名单限制API访问
4. **定期备份**：定期备份数据库和关键配置文件
5. **更新密钥**：定期更新SYS_KEY（在MPHX/function.php中）

## 从GitHub安装后的清理

如果你从GitHub克隆了代码，以下文件需要配置：

1. `config.php` - 数据库配置（从 config.sample.php 复制）
2. `MPHX/SQ.php` - 授权码（从 MPHX/SQ.sample.php 复制）
3. `filecx/` - 用户上传目录（自动创建）

## 升级说明

升级时请注意：
1. 备份数据库
2. 备份 `config.php` 和 `MPHX/SQ.php`
3. 替换文件
4. 执行升级SQL（如有）
5. 清除缓存

## 技术支持

遇到问题请提交Issue或联系技术支持。

---

© 梦奈科技 版权所有
