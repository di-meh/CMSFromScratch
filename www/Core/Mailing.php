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

	# prepare mail to confirm user account
	public function mailConfirm($user){

		$this->setSubject('Veuillez confirmer votre compte Libly !');
		$id = $user->getId();
		$token = $user->getToken();
		$content = "Validez votre compte en cliquant sur ce lien : <a href='http://localhost/lbly-admin/uservalidated?id=$id&token=$token'>Confirmer mon compte !</a>";
		$this->setContent($content);
								
		# set template, set subject, set content
	
	}

	public function mailForgetPwd($user){
		$this->setSubject("Renouvellement de mot de passe");
		$id = $user->getId();
		$token = $user->getToken();
		$content = "Choisissez un nouveau mot de passe en cliquant sur ce lien : <a href='http://localhost/resetPwd?id=$id&token=$token'>Choisir un nouveau mot de passe</a>";
		$this->setContent($content);

		# set template, set subject, set content

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
		    $this->mail->addAddress($this->recipient);

		    $this->mail->isHTML(true);
		    $this->mail->Subject = $this->subject;

		    $this->mail->Body    = $this->content; # 'This is the HTML message body <b>in bold!</b>';

		    $this->mail->send();
		    $this->mail->clearAllRecipients();
		} catch (Exception $e) {
		    echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
		}	
	}



}