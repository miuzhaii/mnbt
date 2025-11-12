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

## 📋 迁移指南

### 现有用户（必须操作）

如果您的系统是在 2025-11-12 之前安装的，**必须执行密钥迁移**：

#### 步骤 1：备份数据

```bash
# 备份数据库
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# 备份配置文件
cp config.php config.php.backup
```

#### 步骤 2：执行迁移

1. 访问迁移工具：`http://yourdomain.com/migrate_key.php`
2. 确认旧密钥（默认为 `MNBT`）
3. 点击"开始迁移"按钮
4. 等待迁移完成（通常几秒钟）

#### 步骤 3：验证迁移

迁移成功后，系统会显示：

```
========================================
迁移成功完成！
========================================
更新记录数：X
新密钥（已保存到 config.php）：[64位随机字符串]

⚠️ 重要：请立即删除此迁移文件 migrate_key.php！
⚠️ 请妥善备份 config.php 文件！
```

#### 步骤 4：删除迁移工具

```bash
rm migrate_key.php
```

**⚠️ 警告：** 迁移工具必须在迁移完成后立即删除，否则存在严重安全风险！

### 新用户

如果您的系统是在 2025-11-12 之后安装的：

- ✅ 安装程序会自动生成随机密钥
- ✅ 无需手动迁移
- ✅ 开箱即安全

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

1. 确保 `migrate_key.php` 文件存在
2. 访问迁移工具
3. 输入当前密钥（从 `config.php` 中获取）
4. 执行迁移生成新密钥
5. 删除迁移工具

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

迁移完成后，请检查以下项目：

- [ ] 已成功执行密钥迁移
- [ ] 已删除 `migrate_key.php` 文件
- [ ] 已备份新的 `config.php` 文件
- [ ] 测试登录功能正常
- [ ] 测试虚拟主机管理功能正常
- [ ] 验证 FTP 密码同步正常
- [ ] 验证数据库密码同步正常
- [ ] `config.php` 文件权限设置正确（640）
- [ ] Web 服务器配置禁止直接访问 `config.php`

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

### Q1: 迁移会影响现有用户吗？

**A:** 不会。迁移过程只是更换加密密钥，用户的账号和密码不会改变。

### Q2: 迁移失败怎么办？

**A:**
1. 检查数据库备份是否存在
2. 恢复 `config.php.backup`
3. 查看迁移工具的错误信息
4. 联系技术支持

### Q3: 可以多次运行迁移工具吗？

**A:** 可以，但不建议。每次迁移都会生成新密钥并重新加密所有数据。

### Q4: 忘记删除迁移工具会怎样？

**A:** 严重的安全风险！攻击者可以通过迁移工具查看或更改您的加密密钥。

### Q5: 密钥丢失了怎么办？

**A:**
- 如果有 `config.php` 备份，可以恢复
- 如果完全丢失，无法解密已加密的数据
- **强烈建议定期备份 `config.php`**

### Q6: 可以手动修改密钥吗？

**A:** 可以，但必须同时重新加密所有数据：
1. 使用迁移工具完成
2. 或手动编写脚本解密后重新加密

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
