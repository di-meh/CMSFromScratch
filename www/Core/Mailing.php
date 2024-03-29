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

		$this->setSubject(utf8_decode('Un nouvel utilisateur a été créé'));
		$id = $user->getId();
		$token = $user->getToken();
		$content = utf8_decode("Un compte avec le mail ". $user->getEmail()." a été créé. ");
		$content .= "Validez le en cliquant sur ce lien : <a href='http://dev.libly.xyz/lbly-admin/uservalidated?id=$id&token=$token'>Confirmer le compte</a>";
		$this->setContent($content);
								
		# set template, set subject, set content
	
	}

	public function mailForgetPwd($user){
		$this->setSubject("Renouvellement Mot de Passe");
		$id = $user->getId();
		$token = $user->getToken();
		$content = "Choisissez un nouveau mot de passe en cliquant sur ce lien : <a href='http://dev.libly.xyz/resetPwd?id=$id&token=$token'>Choisir un nouveau mot de passe</a>";
		$this->setContent($content);

		# set template, set subject, set content

	}

	public function sendMail(){

		try {
		    //Server settings
		    # $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
			$this->mail->isSMTP();                                            
			$this->mail->Host       = MAILHOST;
			$this->mail->SMTPAuth   = MAILSMTPAUTH;
			$this->mail->From       = MAILSENDER;
			$this->mail->Username   = MAILSENDER;
			$this->mail->Password   = MAILPWD;
			$this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$this->mail->Port       = MAILPORT;

		    //Recipients

		    $this->mail->setFrom(MAILSENDER, SITENAME);
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
