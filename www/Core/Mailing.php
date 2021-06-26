<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
//Load Composer's autoloader
require 'vendor/autoload.php';

class Mailing{

	# SINGLETON PATTERN
	private static $mailingInstance = null;
	private $mail;
	private $recipient;
	private $content;
	private $subject;
	private $template;

	private function __construct(){
		//Instantiation and passing `true` enables exceptions
		$this->mail = new PHPMailer(true);
	}

	public static function getMailing(){

		if(is_null(self::$mailingInstance))			
			self::$mailingInstance = new Mailing();
		
		return self::$mailingInstance;
	}

	public function getRecipient(){
		return $this->recipient;
	}

	public function setRecipient($recipient){
		$this->recipient = $recipient;
	}

	public function getContent(){
		return $this->content;
	}

	public function setContent($content){
		$this->content = $content;
	}

	public function getSubject(){
		return $this->subject;
	}

	public function setSubject($subject){
		$this->subject = $subject;
	}

	public function setTemplate($html){
		$this->template = $html;
	}

	public function sendMail(){
		/*
			if(empty($this->content))
				return;
			$this->setTemplate();

		*/
		try {
		    //Server settings
		    #$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		    $this->mail->isSMTP();                                            //Send using SMTP
		    $this->mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
		    $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		    $this->mail->Username   = MAILUSERNAME;                     //SMTP username
		    $this->mail->Password   = MAILPWD;                               //SMTP password
		    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		    $this->mail->Port       = MAILPORT;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

		    //Recipients
		    $this->mail->setFrom(MAILUSERNAME, 'LIBLY');
		    $this->mail->addAddress('jedidialr@outlook.fr', 'Jed');     //Add a recipient
		    # $this->mail->addAddress($this->recipient, 'User');     //Add a recipient

		    # $mail->addAddress('ellen@example.com');               //Name is optional
		    # $mail->addReplyTo('info@example.com', 'Information');
		    # $mail->addCC('cc@example.com');
		    # $mail->addBCC('bcc@example.com');

		    //Attachments
		    # $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
		    # $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

		    //Content
		    $this->mail->isHTML(true);                                  //Set email format to HTML
		    $this->mail->Subject = $this->subject;#'Here is the subject';

		    # $this->mail->Subject = $this->subject;
		    # $this->mail->Body = $this->content;

		    $this->mail->Body    = $this->content; # 'This is the HTML message body <b>in bold!</b>';
		    #$this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		    $this->mail->send();
		    #echo 'Message has been sent';
		} catch (Exception $e) {
		    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}	
	}



}