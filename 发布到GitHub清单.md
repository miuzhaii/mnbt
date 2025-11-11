# MN宝塔系统 发布到GitHub清单

## 📋 需要清理的文件和目录

### ✅ 必须清理（包含敏感信息）

1. **`/config.php`** - 数据库配置文件
   - 包含数据库用户名和密码
   - ⚠️ 已创建 `config.sample.php` 作为模板

2. **`/MPHX/SQ.php`** - 授权码配置
   - 包含授权验证信息
   - ⚠️ 已创建 `MPHX/SQ.sample.php` 作为模板

3. **`/filecx/`** - 用户上传的程序包
   - 包含用户上传的源码和程序
   - ⚠️ 目录结构保留，内容清空

### 🔧 建议清理（用户数据）

4. **`/imsetes/upload_logo/`** - 用户自定义Logo（可选）
   - 如果使用了自定义Logo，建议清理
   - 系统会使用默认Logo

5. **临时文件**
   - `*.tmp` - 临时文件
   - `*.log` - 日志文件
   - `*.zip` - 导入导出的压缩包
   - `*.backup` - 备份文件

6. **测试和备份目录**
   - `/test/` - 测试目录
   - `/mnbt-main/` - 备份目录

### ✓ 可以保留的文件

- `/cf_up.php` - 更新配置（无敏感信息）
- `/MPHX/BL.php` - 核心库文件
- 所有源代码文件
- 前端资源文件（CSS、JS、图片等）

## 🚀 快速清理步骤

### 方法1：使用自动清理脚本（推荐）

```bash
cd /www/wwwroot/38.12.4.241
bash cleanup_for_git.sh
```

脚本会自动：
- ✅ 备份敏感配置文件（`.backup`后缀）
- ✅ 清理用户数据
- ✅ 清理临时文件
- ✅ 询问是否清理Logo
- ✅ 保留目录结构

### 方法2：手动清理

```bash
cd /www/wwwroot/38.12.4.241

# 1. 备份并删除敏感配置
cp config.php config.php.backup
rm config.php

cp MPHX/SQ.php MPHX/SQ.php.backup
rm MPHX/SQ.php

# 2. 清理用户上传内容
find filecx -mindepth 1 ! -name '.gitkeep' -delete

# 3. 清理临时文件
find . -name "*.tmp" -delete
find . -name "*.log" -delete
find . -name "*.backup" -delete

# 4. 清理测试目录
rm -rf test mnbt-main
```

## 📝 发布前检查清单

- [ ] 已运行清理脚本或手动清理
- [ ] 确认 `config.php` 已删除，`config.sample.php` 存在
- [ ] 确认 `MPHX/SQ.php` 已删除，`MPHX/SQ.sample.php` 存在
- [ ] 确认 `/filecx/` 目录已清空（但保留 `.gitkeep` 文件）
- [ ] 确认 `.gitignore` 文件已创建
- [ ] 已更新 `README.md` 添加了今日的更新日志
- [ ] 已创建 `INSTALL.md` 安装指南
- [ ] 准备数据库SQL文件（如需要）

## 🔐 已创建的文件

### 配置模板文件

1. **`config.sample.php`** - 数据库配置模板
   ```php
   'user' => 'your_database_user',
   'pwd' => 'your_database_password',
   'dbname' => 'your_database_name',
   ```

2. **`MPHX/SQ.sample.php`** - 授权码配置模板
   ```php
   $authcode='无需验证';  // 免费版
   ```

### 文档文件

3. **`.gitignore`** - Git忽略规则
   - 自动排除敏感配置
   - 排除用户上传文件
   - 排除临时文件

4. **`INSTALL.md`** - 完整安装指南
   - 环境要求
   - 详细安装步骤
   - 常见问题解答

5. **`cleanup_for_git.sh`** - 自动清理脚本
   - 一键清理所有敏感信息
   - 自动备份配置文件

## 📤 Git操作步骤

清理完成后，执行以下Git操作：

```bash
cd /www/wwwroot/38.12.4.241

# 初始化Git仓库（如果还没有）
git init

# 添加所有文件
git add .

# 检查哪些文件会被提交
git status

# 确认没有敏感文件后，提交
git commit -m "Initial commit - MN宝塔虚拟主机管理系统

- 完整的虚拟主机管理功能
- 宝塔面板API集成
- 用户和管理员分级管理
- 文件管理、域名管理、SSL证书等功能
- 2025-01-12 安全性与稳定性重大更新"

# 添加远程仓库
git remote add origin https://github.com/your-username/mnbt.git

# 推送到GitHub
git push -u origin main
```

## ⚠️ 重要提醒

### 绝对不要提交的内容

1. ❌ 真实的数据库密码
2. ❌ 真实的授权码
3. ❌ 用户上传的文件
4. ❌ 包含真实用户信息的数据库备份
5. ❌ 任何 `.backup` 备份文件

### 发布前最后检查

```bash
# 检查是否包含敏感信息
grep -r "i633PbfcbiEpP2GD" . 2>/dev/null || echo "✓ 未发现数据库密码"
grep -r "38_12_4_241" . --exclude-dir=.git 2>/dev/null | grep -v "sample" || echo "✓ 未发现数据库用户名"

# 检查配置文件
ls -la config.php 2>/dev/null && echo "❌ config.php 仍然存在！" || echo "✓ config.php 已清理"
ls -la MPHX/SQ.php 2>/dev/null && echo "❌ SQ.php 仍然存在！" || echo "✓ SQ.php 已清理"

# 检查示例文件
ls -la config.sample.php 2>/dev/null && echo "✓ config.sample.php 存在" || echo "❌ 缺少 config.sample.php"
ls -la MPHX/SQ.sample.php 2>/dev/null && echo "✓ SQ.sample.php 存在" || echo "❌ 缺少 SQ.sample.php"
```

## 📦 备份文件位置

清理脚本会创建以下备份（这些文件不会被提交到Git）：

- `config.php.backup` - 原数据库配置
- `MPHX/SQ.php.backup` - 原授权码配置
- `imsetes/upload_logo_backup/` - 原Logo文件（如果选择清理）

这些备份文件保存在本地，方便你恢复配置。

## ✨ 完成！

清理完成后，你的代码仓库将：
- ✅ 不包含任何敏感信息
- ✅ 保持完整的目录结构
- ✅ 包含详细的安装文档
- ✅ 提供配置文件模板
- ✅ 可以安全地公开发布

---

© 梦奈科技 版权所有
