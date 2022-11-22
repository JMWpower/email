<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");//回传JSON,屏蔽错误提示,测试时可关闭查看执行错误信息
$userid = $_GET["user"];
$email = $_GET["email"];
$email = preg_replace('# #','',$email);//清理空格或空数据
$userid = strtoupper($userid);//账户名转换为大写
$userid = preg_replace('# #','',$userid);//清理空格或空数据
if ($email == "") {//检测邮箱账号是否为空
	$data['status']=false;//执行状态回传
	$data['error']='邮箱账号为空!';//缘由信息回传
	$data['code']='mail → 000';//节点溯源code
	$data['message']="发送验证码失败，请稍后重试！";//用户端显示信息
	echo json_encode($data, JSON_UNESCAPED_UNICODE);  // 不编码中文回传json数据
	die();
}else{
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$data['status']=false;//执行状态回传
		$data['error']='邮箱账号格式错误!';//缘由信息回传
		$data['code']='mail → 000';//节点溯源code
		$data['message']="发送验证码失败，请稍后重试！";//用户端显示信息
		echo json_encode($data, JSON_UNESCAPED_UNICODE);  // 不编码中文回传json数据
		die();
	}
}
##生成验证码
if ($userid != "") {//检测用户名是否为空
	$token = rand(100000, 999999);;//取随机数字为验证码
	//echo $token;//测试查看验证码信息
    $arr['token'] = $token;
    $arr['date'] = date('Y-m-d H:i:s');
    $json_data = json_encode($arr);
	if (!file_put_contents('./token/'.$userid.'.json', $json_data)) {
		$data['status']=false;//执行状态回传
		$data['error']='写入缓存验证信息失败!';//缘由信息回传
		$data['code']='mail → 001';//节点溯源code
		$data['message']="发送验证码失败，请稍后重试！";//用户端显示信息
		echo json_encode($data, JSON_UNESCAPED_UNICODE);  // 不编码中文回传json数据
		die();
	}
}else{
	$data['status']=false;//执行状态回传
	$data['error']='获取关键ID信息失败!';//缘由信息回传
	$data['code']='mail → 002';//节点溯源code
	$data['message']="发送验证码失败，请稍后重试！";//用户端显示信息
	echo json_encode($data, JSON_UNESCAPED_UNICODE);  // 不编码中文回传json数据
	die();
}
##验证码生成结束

##验证码邮箱发送开始
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './lib/Exception.php';
require './lib/PHPMailer.php';
require './lib/SMTP.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //服务器配置
    $mail->CharSet ="UTF-8";                     //设定邮件编码
    $mail->SMTPDebug = 0;                        // 调试模式输出
    $mail->isSMTP();                             // 使用SMTP
    $mail->Host = 'smtp.163.com';                // SMTP服务器
    $mail->SMTPAuth = true;                      // 允许 SMTP 认证
    $mail->Username = 'jmwpower@163.com';                // SMTP 用户名  即邮箱的用户名
    $mail->Password = 'DICBTVVDSSYYHQKG';             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
    $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
    $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

	$mail->setFrom('jmwpower@163.com', '一枝 | yizhi');  //发件人
	$mail->addAddress($email, $userid);  // 收件人
	// $mail->addAddress('ellen@example.com');  // 可添加多个收件人
	// $mail->addReplyTo('@163.com', 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致
	// $mail->addCC('cc@example.com');                    //抄送
	// $mail->addBCC('bcc@example.com');                    //密送

    //发送附件
    // $mail->addAttachment('../xy.zip');         // 添加附件
    // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');    // 发送附件并且重命名

    //Content
    $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
    $mail->Subject = '为你的新 一枝 | YiZhi 帐户验证邮箱 - 欢迎来到 一枝 | YiZhi ！';//邮件标题
    $mail->Body    = '<div></div><div style="position: relative;"><includetail></includetail><includetail><div><div id="isForwardContent"><table width="750" border="0" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" align="center" style="width: 750px; margin: 0 auto; text-align: center; background-color: #ffffff; margin: 0 auto;" class="ntes_not_fresh_table"><tbody><tr><td><br><table width="750" border="0" cellspacing="0" cellpadding="0" align="center" style="text-align: center; color: #3c3c3c;" class="ntes_not_fresh_table"><tbody><tr><td style="padding: 0 50px;"><p style="margin: 0px; line-height: 64px; text-align: center;"><font face="PingFang SC"><span style="font-size: 46px;"><b>欢迎来到一枝|YiZhi</b></span></font></p><p style="margin: 0px; line-height: 64px; text-align: center;"><font face="PingFang SC"><span style="font-size: 46px;"><b><br></b></span></font></p></td></tr><tr><td style="padding: 0 50px;"><br><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><table border="0" cellspacing="0" cellpadding="0" style=" color: rgb(0, 0, 0) ; ; ; ;; font-size: 12px; border-collapse: collapse; border-spacing: 0px; " id="ntes_editor_table_10010" class="ntes_not_fresh_table"><tbody><tr><td style="font-family: LucidaGrande, tahoma, verdana, arial, sans-serif; -webkit-font-smoothing: subpixel-antialiased; padding-bottom: 10px;"><span class="mb_text" style=""><font size="4">亲爱的[&nbsp;<b style="">'.$userid.'&nbsp;</b>]，你好：</font></span></td></tr><tr><td style="font-family: LucidaGrande, tahoma, verdana, arial, sans-serif; -webkit-font-smoothing: subpixel-antialiased; padding-top: 10px; padding-bottom: 10px;"><span class="mb_text" style="  ; ; ; ; ; ; ; "><font size="4">请输入以下验证码来为你的新&nbsp;<b>一枝|YiZhi&nbsp;</b>帐户验证邮箱。</font></span></td></tr><tr><td style="font-family: LucidaGrande, tahoma, verdana, arial, sans-serif; -webkit-font-smoothing: subpixel-antialiased; padding-top: 10px; padding-bottom: 10px;"><table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border-spacing: 0px;" id="ntes_editor_table_10011" class="ntes_not_fresh_table"><tbody><tr><td style="-webkit-font-smoothing: subpixel-antialiased; padding-bottom: 4px;"><font size="4"><span class="mb_text" style="  ; ; ; ; ; ; ; "><b class="">验证码</b></span><span class="mb_text" style="  ; ; ; ; ; ; ; ">:[ </span>5分钟内有效]</font></td></tr><tr><td style="-webkit-font-smoothing: subpixel-antialiased;"><div style="text-align: center; background: rgb(241, 244, 247); border-radius: 4px; letter-spacing: 2px; padding: 16px;"><b style=""><font size="5" style="" face="Arial Black">'.$token.'</font></b></div></td></tr></tbody></table></td></tr><tr><td style="font-family: LucidaGrande, tahoma, verdana, arial, sans-serif; -webkit-font-smoothing: subpixel-antialiased; padding-top: 10px;"><span class="mb_text" style=""><font size="4">如果并未申请验证码，你可以忽略这封邮件。</font><br></span></td></tr></tbody></table></blockquote></blockquote></td></tr><tr><td style="padding: 20px 50px 0;"><br></td></tr></tbody></table></td></tr></tbody></table><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="margin: 0 auto; text-align: center;" class="ntes_not_fresh_table"><tbody><tr><td style="padding: 24px 0 12px;"><p style="padding: 8px 0px 0px; margin: 0px 50px; line-height: 28px; border-top: 1px solid rgb(228, 228, 228);"><span style="color: rgb(153, 153, 153); font-size: 12px;">*这是自动电子邮件服务,请勿回复</span></p><p style="padding: 8px 0px 0px; margin: 0px 50px; line-height: 28px; border-top: 1px solid rgb(228, 228, 228);"><span style="color: rgb(124, 123, 123); font-family: &quot;PingFang SC&quot;; font-size: 14px;">―&nbsp; 一枝|YiZhi&nbsp; 团队&nbsp; ―</span></p><p style="padding: 8px 0px 0px; margin: 0px 50px; line-height: 28px; border-top: 1px solid rgb(228, 228, 228);"><br></p><p style="display:none"><img src="http://count.mail.163.com/beacon/edm.gif?type=dm_read&amp;id=FPUQXQMPOGNAJ6FI&amp;uid=jmwpower@163.com"></p></td></tr></tbody></table></div></div><div style="position: relative;"><br></div></includetail></div><style>@media (max-width:830px){span{font-size:1.2em;}}</style>' . date('Y-m-d H:i:s');
    $mail->AltBody = '验证码 : [ 5 分钟内有效 ] '.$token.' 如果并未申请验证码，你可以忽略这封邮件。';//不支持html格式时显示内容

    $mail->send();
    $data['status'] = ture;//执行状态回传
    $data['error'] = null;//缘由信息回传
    $data['code'] = 'mail → 003';//节点溯源code
    $data['message'] = "发送验证码成功，请查收邮件！";//用户端显示信息
    echo json_encode($data, JSON_UNESCAPED_UNICODE);  // 不编码中文回传json数据
    die();
	
} catch (Exception $e) {
	$data['status'] = false;//执行状态回传
	$data['error'] = $mail->ErrorInfo;//缘由信息回传
	$data['code'] = 'mail → 004';//节点溯源code
	$data['message'] = "发送验证码失败，请稍后重试！";//用户端显示信息
	echo json_encode($data, JSON_UNESCAPED_UNICODE);  // 不编码中文回传json数据
	die();
}
##验证码邮箱发送结束