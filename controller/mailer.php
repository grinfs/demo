<?php 
/**
 * 
 */
class relatedemail {
	
	static function sendMail($email, $subject, $content, $messageSuccess) {
		
		$mail = new PHPMailer\PHPMailer\PHPMailer();
		
		$mail->isSMTP();

		$mail->Host = 'smtp.gmail.com';

		$mail->Port = 587;

		$mail->SMTPSecure = "tls";

		$mail->SMTPAuth = true;

		$mail->Username = "imgvn.kt.tester2@gmail.com";

		$mail->Password = "imgvn18ttc";

		$mail->setFrom("imgvn.kt.tester2@gmail.com", "Mang xa hoi ABC");

		$mail->addAddress($email, "");

		$mail->Subject = $subject;

		$mail->Body = $content;

		$mail->IsHTML(true); 

		$mail->AtlBody = $content;

		if (!$mail->Send()) {
			return $mail->ErrorInfo;
		} else {
			return $messageSuccess;
		}
		
	}

	static function sendResetPass($email, $code) {
		return relatedemail::sendMail($email,"Khoi phuc mat khau","Click vào link sau để khôi phục mật khẩu: ".linkResetPass()."?code=" . $code,"Email hướng dẫn khôi phục mật khẩu đã được gửi");
	}

	static function sendActiveAccount($email, $code) {
		return relatedemail::sendMail($email,"Kich hoat tai khoan","Click vào link sau để kích hoạt tài khoản: ".linkActiveAccount()."?code=" . $code,"Email kích hoạt tài khoản đã được gửi.");
	}

	static function sentNotification($email, $title, $content)
	{
		return relatedemail::sendMail($email, $title, $content, "Email thông báo đã gửi.");
	}
}

 ?>