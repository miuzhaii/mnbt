<?php
/*
 * 配额监控和邮件通知脚本
 * 定期检查用户配额使用情况，超限时发送邮件通知
 */

include("../MPHX/common.php");

// 检查是否启用了邮件功能
if (empty($conf['mailhost']) || empty($conf['mailuser']) || empty($conf['mailpassword'])) {
    echo "邮件功能未配置，跳过通知\n";
    exit;
}

require_once('../mail.php');

// 获取所有用户列表
$users = $DB->query("SELECT * FROM MN_zj WHERE dq = '1'"); // 只检查正常用户

while ($user = $DB->fetch($users)) {
    $notifications = array();
    
    // 检查网页空间配额
    $web_quota = json_decode($user['hxa'], true);
    if ($web_quota && $web_quota['dq'] > 0) {
        $web_usage_percent = ($web_quota['dq'] / $web_quota['max']) * 100;
        if ($web_usage_percent >= 90) {
            $notifications['web_space'] = array(
                'type' => '网页空间',
                'current' => round($web_quota['dq'], 2),
                'max' => $web_quota['max'],
                'percent' => round($web_usage_percent, 2),
                'unit' => 'MB'
            );
        }
    }
    
    // 检查数据库空间配额
    $db_quota = json_decode($user['hxb'], true);
    if ($db_quota && $db_quota['dq'] > 0) {
        $db_usage_percent = ($db_quota['dq'] / $db_quota['max']) * 100;
        if ($db_usage_percent >= 90) {
            $notifications['db_space'] = array(
                'type' => '数据库空间',
                'current' => round($db_quota['dq'], 2),
                'max' => $db_quota['max'],
                'percent' => round($db_usage_percent, 2),
                'unit' => 'MB'
            );
        }
    }
    
    // 检查流量配额
    $traffic_quota = json_decode($user['llmax'], true);
    if ($traffic_quota && $traffic_quota['dq'] > 0) {
        $traffic_max_bytes = $traffic_quota['max'] * 1024 * 1024 * 1024; // GB转字节
        $traffic_usage_percent = ($traffic_quota['dq'] / $traffic_max_bytes) * 100;
        if ($traffic_usage_percent >= 90) {
            $notifications['traffic'] = array(
                'type' => '月流量',
                'current' => formatBytes($traffic_quota['dq']),
                'max' => $traffic_quota['max'],
                'percent' => round($traffic_usage_percent, 2),
                'unit' => 'GB'
            );
        }
    }
    
    // 如果有超限项目，发送邮件通知
    if (!empty($notifications) && !empty($user['mailuser'])) {
        sendQuotaNotification($user, $notifications);
    }
}

/**
 * 发送配额通知邮件
 */
function sendQuotaNotification($user, $notifications) {
    $subject = "MN宝塔主机配额预警通知";
    
    $message = "尊敬的用户 {$user['user']}，您好！\n\n";
    $message .= "您的主机配额使用情况如下，请注意及时处理：\n\n";
    
    foreach ($notifications as $key => $notification) {
        $message .= "⚠️  {$notification['type']}预警：\n";
        $message .= "   当前使用：{$notification['current']}{$notification['unit']} / {$notification['max']}{$notification['unit']}\n";
        $message .= "   使用率：{$notification['percent']}%\n\n";
    }
    
    $message .= "建议处理方式：\n";
    $message .= "1. 清理不必要的文件以释放空间\n";
    $message .= "2. 升级主机配置以获得更多资源\n";
    $message .= "3. 等待下月初流量重置（仅限流量）\n\n";
    
    $message .= "如需帮助，请联系管理员。\n\n";
    $message .= "此邮件由系统自动发送，请勿回复。\n";
    $message .= "MN宝塔主机管理系统";
    
    // 记录发送日志
    $log_file = __DIR__ . '/../logs/quota_notifications.log';
    $log_entry = date('Y-m-d H:i:s') . " - 发送给用户: {$user['user']} ({$user['mailuser']})\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    
    return sendEmail($user['mailuser'], $subject, $message);
}

/**
 * 格式化字节数
 */
function formatBytes($bytes) {
    if ($bytes >= 1073741824) {
        return round($bytes / 1073741824, 2) . 'GB';
    } elseif ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . 'MB';
    } elseif ($bytes >= 1024) {
        return round($bytes / 1024, 2) . 'KB';
    } else {
        return $bytes . 'B';
    }
}

echo "配额检查完成\n";
?>