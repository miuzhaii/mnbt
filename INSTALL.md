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

### 2. 上传到Web服务器

将代码上传到你的Web服务器目录（如 `/www/wwwroot/yourdomain.com/`）

### 3. 设置目录权限

```bash
chmod -R 755 .
chmod -R 777 filecx
chmod -R 777 imsetes/upload_logo
```

### 4. 配置数据库和密钥

#### 4.1 复制配置文件

```bash
cp config.sample.php config.php
```

#### 4.2 生成随机加密密钥

**⚠️ 重要：这是安全的关键步骤！**

使用以下命令生成64位随机密钥：

```bash
php -r "echo bin2hex(openssl_random_pseudo_bytes(32));"
```

这会输出类似这样的字符串：
```
a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7b8c9d0e1f2
```

**记下这个密钥，下一步会用到！**

#### 4.3 编辑配置文件

编辑 `config.php`，填写以下信息：

```php
$dbconfig = array(
    'host' => 'localhost',           // 数据库服务器
    'port' => 3306,                  // 数据库端口
    'user' => 'your_db_user',        // 数据库用户名
    'pwd' => 'your_db_password',     // 数据库密码
    'dbname' => 'your_db_name',      // 数据库名
    'sys_key' => '上一步生成的64位密钥', // 填入生成的密钥
);
```

**安全提示：**
- 每个站点必须使用不同的随机密钥
- 不要使用示例中的密钥
- 妥善保管 `config.php` 文件
- 定期备份配置文件

#### 4.4 设置文件权限

```bash
chmod 640 config.php
chown www-data:www-data config.php
```

### 5. 导入数据库

执行数据库初始化SQL文件（如果提供）：

```bash
mysql -u your_db_user -p your_db_name < install.sql
```

### 6. 配置宝塔面板（安装后）

在宝塔面板中：
1. 进入"面板设置" -> "API接口"
2. 开启API接口
3. 添加服务器IP到白名单
4. 复制API密钥

### 7. 登录系统

1. 访问管理后台：`http://yourdomain.com/admin/`
2. 使用默认账号登录：`admin` / `123456`
3. **立即修改默认密码**
4. 在后台配置宝塔面板连接信息
5. 开始使用

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

## 注意事项

1. **config.sample.php 和 MPHX/SQ.sample.php**
   - 这些是配置文件模板，仅供参考
   - **安装向导会自动生成真实的配置文件**，无需手动复制

2. **安装后删除 install 目录**
   - 安装完成后建议删除 `/install/` 目录，防止重复安装

3. **filecx 目录**
   - 用于存储用户上传的程序包
   - 已包含示例程序（彩虹外链网盘）供测试使用

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
