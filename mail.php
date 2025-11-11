<?php
include("./MPHX/common.php");
@header('Content-Type: text/html; charset=UTF-8');

require './mail/vendor/autoload.php';  
function sendEmail($to, $subject, $message) {
    global $DB,$conf;
    $mail = new PHPMailer();
    // 配置 SMTP 设置
    $mail->isSMTP();
    $mail->Host = $conf['mailhost'];
    $mail->SMTPAuth = true;
    $mail->Username = $conf['mailuser']; // 你的 QQ 邮箱地址
    $mail->Password = $conf['mailpassword']; // 你的 QQ 邮箱密码
    $mail->SMTPSecure = 'ssl';
    $mail->Port = $conf['mailport'];


    // 设置发件人和收件人
    $mail->setFrom($conf['mailuser'], 'MN系统');
    $mail->addAddress($to);

    // 设置邮件主题和内容
    $mail->Subject = $subject;
    $mail->Body = $message;

    // 发送邮件
    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}
?>

