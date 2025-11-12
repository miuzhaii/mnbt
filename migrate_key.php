<?php
/**
 * 密钥迁移工具 - 用于从旧密钥迁移到新密钥
 *
 * 使用说明：
 * 1. 访问此文件 http://yourdomain.com/migrate_key.php
 * 2. 输入旧密钥（默认：MNBT）
 * 3. 系统会自动生成新密钥并重新加密所有数据
 * 4. 迁移成功后请立即删除此文件！
 *
 * ⚠️ 安全警告：迁移完成后必须删除此文件，否则存在严重安全风险！
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 检查是否已经完成迁移
if(!file_exists('./config.php')) {
    die('错误：找不到 config.php 文件！');
}

require_once './config.php';

// 检查是否已经有新密钥
if(isset($dbconfig['sys_key']) && $dbconfig['sys_key'] !== 'MNBT') {
    die('检测到已设置新密钥，无需重复迁移。如需重新迁移，请手动修改 config.php 中的 sys_key。<br><br><strong style="color:red;">⚠️ 请立即删除此文件！</strong>');
}

// 引入必要的函数
define('IN_CRONLITE', true);
define('SYSTEM_ROOT', dirname(__FILE__).'/MPHX/');
define('ROOT', dirname(__FILE__).'/');

// 加密解密函数
function mn_decrypt_old($ciphertext, $key = 'MNBT') {
    if($ciphertext === '' || $ciphertext === null) return $ciphertext;
    if(strpos($ciphertext, 'MNENC:') !== 0) return $ciphertext;
    if(!function_exists('openssl_decrypt')) return $ciphertext;
    $payload = base64_decode(substr($ciphertext, strlen('MNENC:')), true);
    if($payload === false || strlen($payload) <= 16) return $ciphertext;
    $iv = substr($payload, 0, 16);
    $raw = substr($payload, 16);
    $plaintext = openssl_decrypt($raw, 'AES-256-CBC', hash('sha256', $key, true), OPENSSL_RAW_DATA, $iv);
    if($plaintext === false) return $ciphertext;
    return $plaintext;
}

function mn_encrypt_new($plaintext, $key) {
    if($plaintext === '' || $plaintext === null) return $plaintext;
    if(strpos($plaintext, 'MNENC:') === 0) return $plaintext;
    if(!function_exists('openssl_encrypt')) return $plaintext;
    $encryptionKey = hash('sha256', $key, true);
    $iv = openssl_random_pseudo_bytes(16);
    if($iv === false || $iv === null || strlen($iv) !== 16) return $plaintext;
    $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $encryptionKey, OPENSSL_RAW_DATA, $iv);
    if($ciphertext === false) return $plaintext;
    return 'MNENC:'.base64_encode($iv.$ciphertext);
}

// 数据库连接
require_once SYSTEM_ROOT.'db.class.php';
$DB = new DB($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['migrate'])) {
    $old_key = $_POST['old_key'] ?? 'MNBT';

    // 生成新密钥
    $new_key = bin2hex(openssl_random_pseudo_bytes(32));

    echo "<h2>开始迁移...</h2>";
    echo "<pre>";

    $success = true;
    $updated_count = 0;

    // 1. 迁移 MN_zj 表中的 pass 和 sqlpass 字段
    echo "正在迁移 MN_zj 表中的密码字段...\n";
    $hosts = $DB->query("SELECT id, pass, sqlpass FROM MN_zj WHERE pass LIKE 'MNENC:%' OR sqlpass LIKE 'MNENC:%'");
    if($hosts) {
        while($row = $hosts->fetch()) {
            $id = $row['id'];
            $updates = [];

            if(!empty($row['pass']) && strpos($row['pass'], 'MNENC:') === 0) {
                $plain_pass = mn_decrypt_old($row['pass'], $old_key);
                if($plain_pass !== $row['pass']) {
                    $new_pass = mn_encrypt_new($plain_pass, $new_key);
                    $updates[] = "pass='".$DB->escape($new_pass)."'";
                    echo "  - 主机 ID {$id}: pass 字段已重新加密\n";
                }
            }

            if(!empty($row['sqlpass']) && strpos($row['sqlpass'], 'MNENC:') === 0) {
                $plain_sqlpass = mn_decrypt_old($row['sqlpass'], $old_key);
                if($plain_sqlpass !== $row['sqlpass']) {
                    $new_sqlpass = mn_encrypt_new($plain_sqlpass, $new_key);
                    $updates[] = "sqlpass='".$DB->escape($new_sqlpass)."'";
                    echo "  - 主机 ID {$id}: sqlpass 字段已重新加密\n";
                }
            }

            if(!empty($updates)) {
                $sql = "UPDATE MN_zj SET ".implode(', ', $updates)." WHERE id={$id}";
                if($DB->query($sql)) {
                    $updated_count++;
                } else {
                    echo "  ✗ 主机 ID {$id} 更新失败: ".$DB->error()."\n";
                    $success = false;
                }
            }
        }
    }

    // 2. 迁移 MN_config 表中的 pwd 和 mailpassword 字段
    echo "\n正在迁移 MN_config 表中的密码字段...\n";
    $configs = $DB->query("SELECT id, pwd, mailpassword FROM MN_config WHERE pwd LIKE 'MNENC:%' OR mailpassword LIKE 'MNENC:%'");
    if($configs) {
        while($row = $configs->fetch()) {
            $id = $row['id'];
            $updates = [];

            if(!empty($row['pwd']) && strpos($row['pwd'], 'MNENC:') === 0) {
                $plain_pwd = mn_decrypt_old($row['pwd'], $old_key);
                if($plain_pwd !== $row['pwd']) {
                    $new_pwd = mn_encrypt_new($plain_pwd, $new_key);
                    $updates[] = "pwd='".$DB->escape($new_pwd)."'";
                    echo "  - 配置 ID {$id}: pwd 字段已重新加密\n";
                }
            }

            if(!empty($row['mailpassword']) && strpos($row['mailpassword'], 'MNENC:') === 0) {
                $plain_mailpwd = mn_decrypt_old($row['mailpassword'], $old_key);
                if($plain_mailpwd !== $row['mailpassword']) {
                    $new_mailpwd = mn_encrypt_new($plain_mailpwd, $new_key);
                    $updates[] = "mailpassword='".$DB->escape($new_mailpwd)."'";
                    echo "  - 配置 ID {$id}: mailpassword 字段已重新加密\n";
                }
            }

            if(!empty($updates)) {
                $sql = "UPDATE MN_config SET ".implode(', ', $updates)." WHERE id={$id}";
                if($DB->query($sql)) {
                    $updated_count++;
                } else {
                    echo "  ✗ 配置 ID {$id} 更新失败: ".$DB->error()."\n";
                    $success = false;
                }
            }
        }
    }

    if($success) {
        // 更新 config.php 文件
        echo "\n正在更新 config.php...\n";
        $config_content = file_get_contents('./config.php');

        // 在数据库配置数组中添加 sys_key
        $new_config_content = str_replace(
            "'dbname' => '{$dbconfig['dbname']}',",
            "'dbname' => '{$dbconfig['dbname']}',\n\t'sys_key' => '{$new_key}', // 系统加密密钥（请妥善保管）",
            $config_content
        );

        if(file_put_contents('./config.php', $new_config_content)) {
            echo "✓ config.php 已更新\n";
            echo "\n<strong style='color:green;'>========================================\n";
            echo "迁移成功完成！\n";
            echo "========================================</strong>\n";
            echo "更新记录数：{$updated_count}\n";
            echo "新密钥（已保存到 config.php）：<span style='color:blue;'>{$new_key}</span>\n\n";
            echo "<strong style='color:red; font-size:16px;'>⚠️ 重要：请立即删除此迁移文件 migrate_key.php！</strong>\n";
            echo "<strong style='color:red;'>⚠️ 请妥善备份 config.php 文件！</strong>\n";
            echo "</pre>";
            echo "<br><a href='javascript:location.reload()' style='padding:10px 20px; background:#dc3545; color:white; text-decoration:none; border-radius:5px;'>刷新页面检查状态</a>";
        } else {
            echo "✗ 写入 config.php 失败！请检查文件权限\n";
            echo "</pre>";
        }
    } else {
        echo "\n<strong style='color:red;'>迁移过程中出现错误，请检查上述错误信息</strong>\n";
        echo "</pre>";
    }

    exit;
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>密钥迁移工具 - MN宝塔</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Microsoft YaHei", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 25px;
            color: #856404;
        }
        .warning strong {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .info-box {
            background: #d1ecf1;
            border: 2px solid #17a2b8;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 25px;
            color: #0c5460;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .steps {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #eee;
        }
        .steps h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .steps ol {
            padding-left: 20px;
        }
        .steps li {
            margin-bottom: 10px;
            color: #666;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 密钥迁移工具</h1>
        <p class="subtitle">MN宝塔虚拟主机管理系统</p>

        <div class="warning">
            <strong>⚠️ 安全警告</strong>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>此工具用于将数据从旧密钥迁移到新的随机密钥</li>
                <li>迁移过程会重新加密所有敏感数据</li>
                <li><strong>迁移完成后必须立即删除此文件！</strong></li>
                <li>请在非高峰时段进行迁移操作</li>
                <li>建议先备份数据库</li>
            </ul>
        </div>

        <div class="info-box">
            <strong>📋 迁移说明</strong>
            <p style="margin-top: 10px;">本工具将会：</p>
            <ul style="margin-left: 20px; margin-top: 5px;">
                <li>使用旧密钥解密所有 MNENC: 格式的数据</li>
                <li>生成一个新的64位随机密钥</li>
                <li>使用新密钥重新加密所有数据</li>
                <li>自动更新 config.php 配置文件</li>
            </ul>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="old_key">旧密钥（当前密钥）</label>
                <input type="text" id="old_key" name="old_key" value="MNBT" required>
                <small style="color: #999; display: block; margin-top: 5px;">
                    如果您之前修改过密钥，请输入旧的密钥，否则保持默认值 MNBT
                </small>
            </div>

            <button type="submit" name="migrate" class="btn">
                🚀 开始迁移
            </button>
        </form>

        <div class="steps">
            <h3>迁移步骤：</h3>
            <ol>
                <li>确认旧密钥正确（默认为 MNBT）</li>
                <li>点击"开始迁移"按钮</li>
                <li>等待迁移完成（通常几秒钟）</li>
                <li>记录新密钥（已自动保存到 config.php）</li>
                <li><strong style="color: red;">立即删除此 migrate_key.php 文件</strong></li>
            </ol>
        </div>
    </div>
</body>
</html>
