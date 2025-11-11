#!/bin/bash
# MN宝塔系统 - Git发布前清理脚本
# 此脚本用于清理敏感信息，准备发布到GitHub

echo "========================================="
echo "MN宝塔系统 Git发布清理脚本"
echo "========================================="
echo ""

# 获取脚本所在目录
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo "当前目录: $SCRIPT_DIR"
echo ""

# 1. 清理数据库配置文件
echo "1. 清理数据库配置..."
if [ -f "config.php" ]; then
    echo "   - 备份 config.php 到 config.php.backup"
    cp config.php config.php.backup
    echo "   ✓ 已备份"
fi

# 2. 清理授权码配置
echo "2. 清理授权码配置..."
if [ -f "MPHX/SQ.php" ]; then
    echo "   - 备份 MPHX/SQ.php 到 MPHX/SQ.php.backup"
    cp MPHX/SQ.php MPHX/SQ.php.backup
    echo "   ✓ 已备份"
fi

# 3. 清理用户上传的程序包
echo "3. 清理用户上传的程序包..."
if [ -d "filecx" ]; then
    echo "   - 删除 filecx 目录下的所有内容（保留目录结构）"
    find filecx -mindepth 1 ! -name '.gitkeep' -delete
    echo "   ✓ 已清理"
fi

# 4. 清理用户自定义Logo（可选）
echo "4. 清理用户自定义Logo..."
read -p "   是否清理用户自定义Logo？(y/n，默认n): " clean_logo
if [ "$clean_logo" == "y" ] || [ "$clean_logo" == "Y" ]; then
    if [ -d "imsetes/upload_logo" ]; then
        echo "   - 备份Logo文件到 imsetes/upload_logo_backup/"
        mkdir -p imsetes/upload_logo_backup
        cp imsetes/upload_logo/*.png imsetes/upload_logo_backup/ 2>/dev/null || true
        echo "   ✓ 已备份"
    fi
fi

# 5. 清理临时文件
echo "5. 清理临时文件..."
find . -name "*.tmp" -delete 2>/dev/null || true
find . -name "*.log" -delete 2>/dev/null || true
find . -name "*.zip" ! -path "./imsetes/*" -delete 2>/dev/null || true
echo "   ✓ 已清理"

# 6. 清理测试目录
echo "6. 清理测试目录..."
if [ -d "test" ]; then
    rm -rf test
    echo "   ✓ 已删除 test 目录"
fi
if [ -d "mnbt-main" ]; then
    rm -rf mnbt-main
    echo "   ✓ 已删除 mnbt-main 目录"
fi

echo ""
echo "========================================="
echo "清理完成！"
echo "========================================="
echo ""
echo "后续步骤："
echo "1. 复制 config.sample.php 为 config.php（安装时）"
echo "2. 复制 MPHX/SQ.sample.php 为 MPHX/SQ.php（安装时）"
echo "3. 确保数据库SQL文件已准备好（如需要）"
echo ""
echo "备份文件位置："
echo "- config.php.backup (原数据库配置)"
echo "- MPHX/SQ.php.backup (原授权码配置)"
if [ "$clean_logo" == "y" ] || [ "$clean_logo" == "Y" ]; then
    echo "- imsetes/upload_logo_backup/ (原Logo文件)"
fi
echo ""
echo "现在可以执行 git 操作了！"
echo ""
