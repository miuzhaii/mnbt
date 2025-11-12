# 🔐 安全配置指南

## 密钥管理安全说明

### 🚨 重要安全公告

在 2025-11-12 更新之前，本系统存在一个严重的安全漏洞：

**问题：** 所有安装实例使用相同的硬编码密钥 `'MNBT'` 进行数据加密。

**风险：**
- 任何获取源代码的人都能看到加密密钥
- 数据库泄露后，攻击者可轻易解密所有密码
- 无法为不同站点使用不同的密钥
- 无法定期更换密钥

**影响范围：**
- 虚拟主机 FTP 密码
- 虚拟主机数据库密码
- 宝塔面板密码
- 邮箱密码

---

## ✅ 新版本安全改进

### 1. 随机密钥生成

每个站点现在使用独立的64位随机密钥：

```php
// config.php
$dbconfig = array(
    'host' => 'localhost',
    'port' => 3306,
    'user' => 'database_user',
    'pwd' => 'database_password',
    'dbname' => 'database_name',
    'sys_key' => '生成的64位随机密钥', // 新增
);
```

### 2. 配置化存储

密钥不再硬编码在源代码中，而是存储在 `config.php` 配置文件中：

- ✅ 源代码可以公开，不泄露密钥
- ✅ 每个站点有独立的密钥
- ✅ 便于备份和恢复
- ✅ 可以定期更换密钥

### 3. 向后兼容

系统自动兼容未迁移的旧版本：

```php
// MPHX/common.php
if(isset($dbconfig['sys_key']) && !empty($dbconfig['sys_key'])) {
    define('SYS_KEY', $dbconfig['sys_key']); // 使用新密钥
} else {
    define('SYS_KEY', 'MNBT'); // 向后兼容旧版本
}
```

---

## 📋 配置指南

### 初始配置（新安装）

#### 步骤 1：准备配置文件

```bash
# 复制配置模板
cp config.php.example config.php
```

#### 步骤 2：生成随机密钥

使用以下任一方法生成64位随机密钥：

**方法 1：PHP 命令行**
```bash
php -r "echo bin2hex(openssl_random_pseudo_bytes(32));"
```

**方法 2：在线工具**
- 访问可信的随机字符串生成工具
- 生成64个十六进制字符（0-9, a-f）

#### 步骤 3：编辑配置文件

编辑 `config.php`，填写数据库信息和生成的密钥：

```php
$dbconfig = array(
    'host' => 'localhost',
    'port' => 3306,
    'user' => 'your_database_user',
    'pwd' => 'your_database_password',
    'dbname' => 'your_database_name',
    'sys_key' => '这里填入生成的64位随机密钥', // 重要！
);
```

#### 步骤 4：设置文件权限

```bash
chmod 640 config.php
chown www-data:www-data config.php
```

### 已有系统升级

如果您的系统已经在运行中：

1. **备份数据**
   ```bash
   mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
   cp config.php config.php.backup
   ```

2. **添加密钥配置**
   在现有的 `config.php` 中添加 `sys_key` 项：
   ```php
   'sys_key' => '生成的64位随机密钥',
   ```

3. **重新加密现有数据（如需要）**
   - 如果之前使用默认密钥 `'MNBT'` 加密了数据
   - 需要手动重新加密所有 `MNENC:` 格式的数据
   - 或者继续使用旧密钥（不推荐）

---

## 🔒 最佳安全实践

### 1. 保护配置文件

确保 `config.php` 不可通过 Web 访问：

```nginx
# Nginx 配置
location ~ /config\.php$ {
    deny all;
}
```

```apache
# Apache 配置 (.htaccess)
<Files "config.php">
    Require all denied
</Files>
```

### 2. 定期备份密钥

将 `config.php` 加入备份计划：

```bash
# 备份脚本示例
#!/bin/bash
BACKUP_DIR="/backup/configs"
DATE=$(date +%Y%m%d_%H%M%S)
cp /www/wwwroot/mnbt/config.php "$BACKUP_DIR/config_$DATE.php"
```

### 3. 文件权限设置

```bash
# 设置合适的文件权限
chmod 640 config.php
chown www-data:www-data config.php
```

### 4. 密钥轮换（可选）

如果需要定期更换密钥：

1. 备份数据库和配置文件
2. 生成新的随机密钥
3. 手动重新加密所有 `MNENC:` 格式的数据
4. 更新 `config.php` 中的 `sys_key`
5. 测试所有功能是否正常

### 5. 监控异常访问

监控对敏感文件的访问：

```bash
# 使用 fail2ban 监控
[mnbt-config]
enabled = true
filter = mnbt-config
action = iptables[name=mnbt-config, port=http,https, protocol=tcp]
logpath = /var/log/nginx/access.log
maxretry = 3
```

---

## 🛡️ 安全检查清单

配置完成后，请检查以下项目：

- [ ] 已在 `config.php` 中配置随机密钥
- [ ] 已备份 `config.php` 文件
- [ ] 测试系统登录功能正常
- [ ] 测试虚拟主机管理功能正常
- [ ] 验证加密数据读取正常
- [ ] `config.php` 文件权限设置正确（640）
- [ ] Web 服务器配置禁止直接访问 `config.php`
- [ ] 密钥妥善保存，防止丢失

---

## 🔍 技术细节

### 加密算法

**AES-256-CBC**
- 密钥长度：256位（64个十六进制字符）
- 初始化向量（IV）：16字节随机生成
- 密钥派生：SHA256(原始密钥)

### 加密流程

```php
function mn_encrypt($plaintext, $key = SYS_KEY) {
    $encryptionKey = hash('sha256', $key, true);      // SHA256 派生
    $iv = openssl_random_pseudo_bytes(16);             // 随机 IV
    $ciphertext = openssl_encrypt(
        $plaintext,
        'AES-256-CBC',
        $encryptionKey,
        OPENSSL_RAW_DATA,
        $iv
    );
    return 'MNENC:'.base64_encode($iv.$ciphertext);    // IV + 密文
}
```

### 解密流程

```php
function mn_decrypt($ciphertext, $key = SYS_KEY) {
    if(strpos($ciphertext, 'MNENC:') !== 0) return $ciphertext;
    $payload = base64_decode(substr($ciphertext, 6), true);
    $iv = substr($payload, 0, 16);                     // 提取 IV
    $raw = substr($payload, 16);                       // 提取密文
    $plaintext = openssl_decrypt(
        $raw,
        'AES-256-CBC',
        hash('sha256', $key, true),
        OPENSSL_RAW_DATA,
        $iv
    );
    return $plaintext;
}
```

### 数据库加密字段

以下字段使用 `mn_encrypt()` 加密：

| 表名 | 字段名 | 说明 |
|------|--------|------|
| MN_zj | pass | 虚拟主机 FTP 密码 |
| MN_zj | sqlpass | 虚拟主机数据库密码 |
| MN_config | pwd | 宝塔面板密码 |
| MN_config | mailpassword | 邮箱密码 |

---

## ❓ 常见问题

### Q1: 我已经安装了系统，现在需要添加密钥吗？

**A:** 不是必须的。系统会向后兼容，如果没有配置 `sys_key`，会使用默认密钥 `'MNBT'`。但为了安全，强烈建议配置随机密钥。

### Q2: 密钥丢失了怎么办？

**A:**
- 如果有 `config.php` 备份，可以恢复
- 如果完全丢失，无法解密已加密的数据
- **强烈建议定期备份 `config.php`**

### Q3: 可以修改密钥吗？

**A:** 可以，但需要注意：
1. 备份数据库和配置文件
2. 修改密钥后，之前加密的数据将无法解密
3. 需要手动重新加密所有 `MNENC:` 格式的数据
4. 或者继续使用旧密钥

### Q4: 如何生成安全的随机密钥？

**A:** 使用以下命令：
```bash
php -r "echo bin2hex(openssl_random_pseudo_bytes(32));"
```
这会生成一个64位的十六进制随机字符串。

### Q5: 为什么是64位字符？

**A:** 64个十六进制字符 = 32字节 = 256位，经过 SHA256 处理后用于 AES-256-CBC 加密，提供足够的安全强度。

### Q6: 不同站点可以使用相同的密钥吗？

**A:** 技术上可以，但强烈不建议。每个站点应使用独立的随机密钥，这样即使一个站点被攻破，也不会影响其他站点。

---

## 📞 技术支持

如果在迁移过程中遇到问题：

- 📧 提交 Issue：https://github.com/miuzhaii/mnbt/issues
- 📖 查看文档：https://github.com/miuzhaii/mnbt
- 💬 在迁移前备份所有数据

---

<div align="center">

**🔐 保护您的数据安全是我们的首要任务**

© 2023-2025 梦奈科技 版权所有

</div>
