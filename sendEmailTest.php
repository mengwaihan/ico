<?PHP

$path = "E:\PersonalShare\Kindle Books";
$filename = "E:\code\email\error_" . time() . ".txt";
ini_set('memory_limit', '1024M');
//为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
// $mail->addAttachment('./d.jpg','mm.jpg');
$mail = sendMail();
$mail->send();
exit;
$files = getDirFile($path);
$size = $number = $count = 0;
echo count($files) . "\r\n";
// foreach ($files as $key => $file) {
//     if (in_array(pathinfo($file, PATHINFO_EXTENSION), array('txt', 'mobi', 'pdf'))) {
//         echo $key . "\r\n";
//         $size += filesize($file);
//         $number++;
//         if ($size < 1024 * 1024 * 30 && $number < 25) {
//             $mail->addAttachment($file);
//         } else {
//             if ($mail->send()) {
//                 echo ++$count . 'success' . "\r\n";
//             } else {
//                 echo ++$count . $mail->ErrorInfo . "\r\n";
//             }
//             $size = $number = 0;
//             $mail = sendMail();
//             if (filesize($file) < 1024 * 1024 *50) {
//                 $mail->addAttachment($file);
//                 $number++;
//             }
//         }
//     }
// }
// if ($mail->send()) {
//     echo ++$count . 'success' . "\r\n";
// } else {
//     echo ++$count . $mail->ErrorInfo . "\r\n";
// }
foreach ($files as $key => $file) {
    if (in_array(pathinfo($file, PATHINFO_EXTENSION), array('txt', 'mobi', 'pdf'))) {
        $mail = sendMail();
        echo $key . "\r\n";
        if (filesize($file) < 1024 * 1024 *50) {
            $mail->addAttachment($file);
        }
        if ($mail->send()) {
            echo ++$count . 'success' . "\r\n";
        } else {
            echo ++$count . $mail->ErrorInfo . "\r\n";
            file_put_contents($filename, $file, FILE_APPEND);
        }
        if ($count % 4 == 0) {
            sleep(5 * 60);
        }
    }
}
//发送命令 返回布尔值 
//PS：经过测试，要是收件人不存在，若不出现错误依然返回true 也就是说在发送之前 自己需要些方法实现检测该邮箱是否真实有效
// $status = $mail->send();
 
//简单的判断与提示信息
// if($status) {
//  echo '发送邮件成功';
// }else{
//  echo '发送邮件失败，错误信息未：'.$mail->ErrorInfo;
// }

function sendMail(){
    //引入PHPMailer的核心文件 使用require_once包含避免出现PHPMailer类重复定义的警告
    require_once("phpmailer/class.smtp.php"); 
    require_once("phpmailer/class.phpmailer.php"); 
    //示例化PHPMailer核心类
    $mail = new PHPMailer();
     
    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = 2;
     
    //使用smtp鉴权方式发送邮件，当然你可以选择pop方式 sendmail方式等 本文不做详解
    //可以参考http://phpmailer.github.io/PHPMailer/当中的详细介绍
    $mail->isSMTP();
    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth=true;
    //链接qq域名邮箱的服务器地址
    $mail->Host = 'smtp.qq.com';
    //设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = 'ssl';
    //设置ssl连接smtp服务器的远程服务器端口号 可选465或587
    $mail->Port = 465;
    //设置smtp的helo消息头 这个可有可无 内容任意
    // $mail->Helo = 'Hello smtp.qq.com Server';
    //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
    $mail->Hostname = 'jjonline.cn';
    //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
    $mail->CharSet = 'GBK';
    //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = '';
    //smtp登录的账号 这里填入字符串格式的qq号即可
    $mail->Username ='435800667@qq.com';
    //smtp登录的密码 这里填入“独立密码” 若为设置“独立密码”则填入登录qq的密码 建议设置“独立密码”
    $mail->Password = 'sutamslbpayjbiij';
    //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
    $mail->From = '435800667@qq.com';
    //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
    $mail->isHTML(true); 
    //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
    // $mail->addAddress('8613955424735@kindle.cn');
    $mail->addAddress('zjf580@163.com');
    //添加该邮件的主题
    $mail->Subject = 'kindle';
    //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
    $mail->Body = " ";
    return $mail;
}

/**
 * Read the file directory
 * 
 */
function searchDir($path = "", &$data = array())
{
    if (is_dir($path)) {
        $handle = opendir($path);
        while ($re = readdir($handle)) {
            if (in_array($re, array(".", ".."))) {
                continue;
            }
            searchDir($path . '/' . $re, $data);
        }
        closedir($handle);
    } else {
        $data[] = $path;
    }
}

/**
 * Read the file directory
 * 
 * @param string $dir = ""
 * @return array
 */
function getDirFile($dir = "")
{
    if (preg_match("/\/$/", $dir)) {
        $dir = substr($dir, 0, strlen($dir) - 1);
    }

    $data = array();
    searchDir($dir, $data);

    return $data;
}
?>