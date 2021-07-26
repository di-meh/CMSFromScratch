<?php

namespace App\Controller;

use App\Core\Helpers;

use App\Core\View;
use App\Core\Security as Secu;
use App\Core\FormValidator;

use App\Core\Mailing;

use App\Models\User;

class SettingsController{


	public function changeSettingsAction(){

        $user = Secu::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("settings", "back");
		$form = $user->formSettings();

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
						if(!empty($_POST['stripe_public_key'])){
							$infos .= "Clé publique Stripe".$endMessage;
							$data = str_ireplace(STRIPE_PRIVATE_KEY, trim($_POST['stripe_public_key']), $data);

						}
  						if(!empty($_POST['stripe_private_key'])){
							$infos .= "Clé privée Stripe".$endMessage;
							$data = str_ireplace(VITE_STRIPE_PUBLIC_KEY, trim($_POST['stripe_private_key']), $data);

						}
		    			file_put_contents(".env",$data);

		    		}else{
						$view->assign("errors", $errors);

		    		}

				}
			}

			if(!empty($infos)){
				$view->assign("infos",["Mise à jour des paramètres réussie !"]);
				header("Refresh:3;");
			}

			$view->assign("form",$form);

		}else{
			$view->assign("errors", ["Vous n'avez pas accès à cette page."]);

		}


	}


}