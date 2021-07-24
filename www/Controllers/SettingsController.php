<?php

namespace App\Controller;

use App\Core\Helpers;

use App\Core\View;
use App\Core\Security as Secu;
use App\Core\FormValidator;

use App\Core\Mailing;

use App\Models\User;

class SettingsController{

	public function form(){
		return [

            "config" => [
                    "method" => "POST",
                    "action" => "",
                    "id" => "form_settings",
                    "class" => "form_builder",
                    "submit" => "Valider"
                ],
            "inputs" => [
                "sitename" => [
                    "type" => "text",
                    "label" => "Nom de votre site",
                    "id" => "sitename",
                    "class" => "form_input",
                    "value" => SITENAME
                ],
                "mailsuperadmin" => [
                    "type" => "text",
                    "label" => "Destinataire des mails de validation des comptes",
                    "id" => "mailsuperadmin",
                    "class" => "form_input",
                    "value" => MAILSUPERADMIN
                ],
                "dbdriver" => [
                    "type" => "text",
                    "label" => "Database Driver",
                    "id" => "dbdriver",
                    "class" => "form_input",
                    "value" => DBDRIVER

                ],
                "dbhost" => [
                    "type" => "text",
                    "label" => "Database Host",
                    "id" => "dbhost",
                    "class" => "form_input",
                    "value" => DBHOST

                ],
                "dbname" => [
                    "type" => "text",
                    "label" => "Nom de la base de données",
                    "id" => "dbname",
                    "class" => "form_input",
                    "value" => DBNAME

                ],
                "dbuser" => [
                    "type" => "text",
                    "label" => "Utilisateur base de données",
                    "id" => "dbuser",
                    "class" => "form_input",
                    "value" => DBUSER

                ],
                "dbpwd" => [
                    "type" => "text",
                    "label" => "Mot de passe de la base de données",
                    "id" => "dbpwd",
                    "class" => "form_input"

                ],
                "dbport" => [
                    "type" => "text",
                    "label" => "Port de la base de données",
                    "id" => "dbport",
                    "class" => "form_input",
                    "value" => DBPORT

                ],
                "mailhost" => [
                    "type" => "text",
                    "label" => "Serveur SMTP",
                    "id" => "mailhost",
                    "class" => "form_input",
                    "value" => MAILHOST

                ],
                "mailport" => [
                    "type" => "text",
                    "label" => "Port SMTP",
                    "id" => "mailport",
                    "class" => "form_input",
                    "value" => MAILPORT

                ],
                "mailsender" => [
                    "type" => "email",
                    "label" => "Changer le mail expediteur",
                    "id" => "mailsender",
                    "class" => "form_input",
                    "minLength" => 8,
                    "maxLength" => 320,
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "placeholder" => "mailsender@mail.com",
                    "value" => MAILSENDER

                ],
                "mailpwd" => [
                    "type" => "password",
                    "label" => "Changer le mot de passe de l'email expediteur",
                    "id" => "mailpwd",
                    "class" => "form_input",
                    "minLength" => 8,
                    "error" => "Le mot de passe doit faire 8 caractères minimum."

                ]
            ]

    	];
	}

	public function changeSettingsAction(){

        $user = Secu::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("settings", "back");
		$form = $this->form();


		if($user->isSuperAdmin()){

			if(file_exists(".env")){
				if(!empty($_POST)){

					$data = file_get_contents(".env");

					$infos = "";


					if(empty($errors)){

						$endMessage = "a été modifié avec succès !</br>";

						if(!empty($_POST['sitename'])){
							$infos .= "Le nom de votre site ".$endMessage;
							$data = str_ireplace(SITENAME, trim($_POST['sitename']), $data);

						}
						if(!empty($_POST['mailsuperadmin'])){
							$infos .= "Le mail superadmin ".$endMessage;
							$data = str_ireplace(MAILSUPERADMIN, trim($_POST['mailsuperadmin']), $data);

						}
						if(!empty($_POST['dbdriver'])){
							$infos .= "Le database driver ".$endMessage;
							$data = str_ireplace(DBDRIVER, trim($_POST['dbdriver']), $data);

						}
						if(!empty($_POST['dbhost'])){
							$infos .= "Le host database ".$endMessage;
							$data = str_ireplace(DBHOST, trim($_POST['dbhost']), $data);

						}
						if(!empty($_POST['dbname'])){
							$infos .= "Le nom de la database ".$endMessage;
							$data = str_ireplace(DBNAME, trim($_POST['dbname']), $data);

						}
						if(!empty($_POST['dbuser'])){
							$infos .= "L'utilisateur database ".$endMessage;
							$data = str_ireplace(DBUSER, trim($_POST['dbuser']), $data);

						}
						if(!empty($_POST['dbpwd'])){
							$infos .= "Le mot de passe de la database ".$endMessage;
							$data = str_ireplace(DBPWD, trim($_POST['dbpwd']), $data);

						}
						if(!empty($_POST['dbport'])){
							$infos .= "Le port de la database ".$endMessage;
							$data = str_ireplace(DBPORT, trim($_POST['dbport']), $data);

						}
						if(!empty($_POST['mailhost'])){
							$infos .= "Le SMTP host ".$endMessage;
							$data = str_ireplace(MAILHOST, trim($_POST['mailhost']), $data);

						}
						if(!empty($_POST['mailsender'])){
							$infos .= "L'email d'expédition ".$endMessage;
							$data = str_ireplace(MAILSENDER, trim($_POST['mailsender']), $data);

						}
						if(!empty($_POST['mailport'])){
							$infos .= "Le port SMTP ".$endMessage;
							$data = str_ireplace(MAILPORT, trim($_POST['mailport']), $data);

						}
						if(!empty($_POST['mailpwd'])){
							$infos .= "Le mot de passe de l'email d'expédition ".$endMessage;
							$data = str_ireplace(MAILPWD, trim($_POST['mailpwd']), $data);

						}
						/* THIS ONE IS TOUCHY */
						/*if(isset($_POST['mailsender'])){
							$this->updateEnvFile($data, 9, "MAILSUPERADMIN", $_POST['mailsuperadmin']);
							$infos = ["L'email d'expédition a été modifié avec succès !"];
						}*/
						/*                    */
		    			file_put_contents(".env",$data);
		    		}else{
						$view->assign("errors", $errors);

		    		}

				}
			}
		}
		isset($data)?$view->assign("infos",[$data]):'';
		#!empty($infos)?$view->assign("infos", [$infos]):'';
		$view->assign("form",$form);

	}


}