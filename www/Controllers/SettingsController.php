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
                "dbdriver" => [
                    "type" => "text",
                    "label" => "Database Driver",
                    "id" => "dbdriver",
                    "class" => "form_input"
                ],
                "mailsender" => [
                    "type" => "email",
                    "label" => "Changer le mail expediteur",
                    "id" => "mailsender",
                    "class" => "form_input",
                    "minLength" => 8,
                    "maxLength" => 320,
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "placeholder" => "mailsender@mail.com"
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


		if($user->isAdmin() || $user->isSuperAdmin()){

			if(file_exists(".env")){
				if(!empty($_POST)){

					$data = file_get_contents(".env");

					$infos = "";


					if(empty($errors)){

						$endMessage = "a été modifié avec succès !</br>";

						if(!empty($_POST['dbdriver'])){
							$infos .= "Le database driver ".$endMessage;
							$data = str_ireplace(DBDRIVER, trim($_POST['dbdriver']), $data);

						}
						if(!empty($_POST['mailsender'])){
							$infos .= "L'email d'expédition ".$endMessage;
							$data = str_ireplace(MAILSENDER, trim($_POST['mailsender']), $data);

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