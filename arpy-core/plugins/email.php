<?php
class Email {
	
	private $error;
	
	function send($mydata){
	
		$data = array();
		$data['from_add'] 	= Config::get('email.from_add');
		$data['from_name']	= Config::get('email.from_name');
		$data['reply_add'] 	= Config::get('email.reply_add');
		$data['reply_name']	= Config::get('email.reply_name');
		
		$data['to_add']		= '';
		$data['to_name']	= '';
		$data['subject']	= '';
		$data['body']		= '';
		$data['alt_body']	= '';
		
		$data = array_merge($data,$mydata);
		
		if (empty($data['alt_body'])){
			$data['alt_body'] = $data['body'];
			if(strlen($data['body']) != strlen(strip_tags($data['body']))){
				$data['alt_body'] = "To view the message, please use an HTML compatible email viewer.";
			} 
		}
		
		$mail             = new PHPMailer();
		$mail->From       = $data['from_add'];
		$mail->FromName   = $data['from_name'];
		$mail->Subject    = $data['subject'];
		$mail->AltBody    = $data['alt_body'];
		
		if (!empty($data['reply_add'])){
			$mail->AddReplyTo($data['reply_add'], $data['reply_name']);
		}
		if (!empty($data['body'])){
			$mail->MsgHTML($data['body']);
		}
		if (!empty($data['to_add'])){
			$mail->AddAddress($data['to_add'], $data['to_name']);
		}
		
		if(!$mail->Send()) {
			$this->error = $mail->ErrorInfo;
			$sent = false;
		} else {
		  $sent = true;
		}
		return $sent;
	}
}
?>