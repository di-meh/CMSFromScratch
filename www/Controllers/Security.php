<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker as c;

use App\Core\Singleton;

use App\Core\Redirect;

use App\Models\User;

class Security
{


	public function defaultAction()
	{
		echo "Controller security action default";
	}

	public function editProfilAction(){

		session_start();

		if(!isset($_SESSION['id'])) header("Location:/");

		$user = $_SESSION['user'];

		$view = new View("editProfil");

		$form = $user->formEditProfil();

		if(!empty($_POST)){



		}

		$view->assign("form", $form);


	}


	public function loginAction()
	{

		session_start();

		if(isset($_SESSION['id'])) header("Location:/"); # user deja connected


		$user = new User();

		$view = new View("login");

		$form = $user->formLogin();

		if(!empty($_POST['email'])){

			$mailExists = $user->verifyMail($_POST['email']); # verify unicity in database
			#echo $mailExists;


			if($mailExists == 1){

				$pwd = $user->verifyPwd($_POST['email']);
				# cherche le mdp correspond a ce mail en base
				if(password_verify($_POST['pwd'], $pwd)){

					$user->setAll($_POST['email']);
					# set tous les attributs depuis la base
					# à partir du mail

					$token = substr(md5(uniqid(true)), 0, 10); # cut length to 10, no refix, entropy => for more unicity
					$user->setToken($token);

					$_SESSION['id'] = $user->getId();
					# $_SESSION['email'] = $user->getEmail();
					$_SESSION['user'] = $user; # j'ai le droit ?
					# $_SESSION['pwd'] = $user->getPwd(); # ??
					$_SESSION['token'] = $token;



					#echo "MAIS OUI TU ES CONNECTE MON FILS.";
					$user->setEmail($_POST['email']);
					# $id = Singleton::findID($email);
					# $user->setId($id); # peuple l'entité
					# $user->setPwd($_POST['pwd']); # useless to me
					header("Location:/editprofil"); # temporairement
					# $user->deleteAll(); # pour delete immediatement en base

					# gère le token aussi

				}else{
					$view->assign("errors", ["Mot de passe erroné."]);
				}

			}else{
				$view->assign("errors", ["Le mail n'existe pas."]);
			}

		}

		$view->assign("form", $form);
		
	}


	public function registerAction()
	{

		/*


		$log = new Log();
		$log->user("y.skrzypczyk@gmail.com");
		$log->date(time());
		$log->success(false);
		$log->save();

		$user = new User();
		print_r($user) // VIDE
		$user->setId(2); // double action de peupler l'objet avec ce qu'il y a en bdd
		print_r($user) // J'ai le user en bdd
		$user->setFirstname("Toto");
		$user->save();
		*/


		$user = new User();

		$view = new View("register");

		$form = $user->formRegister();

		if (!empty($_POST)) {

			$errors = FormValidator::check($form, $_POST);

			if (empty($errors)) {

				$mailExists = $user->verifyMail($_POST['email'], $user->getTable());
				# verify unicity in database


				if($mailExists == 0){

					if($_POST['pwd'] == $_POST['pwdConfirm']){

						$pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
						
						$user->setFirstname($_POST["firstname"]);
						$user->setLastname($_POST["lastname"]);
						$user->setEmail($_POST["email"]);
						$user->setPwd($pwd);
						$user->setCountry($_POST["country"]);

						$user->save();

						header("Location:login");

					}else{
						$view->assign("errors", ["Vos mots de passe sont différents."]);
					}


				}else{

					$view->assign("errors",["Ce mail est déjà utilisé."]);

				}

			} else {
				$view->assign("errors", $errors);
			}
		}

		$view->assign("form", $form);
		//$view->assign("formLogin", $formLogin);
	}



	public function logoutAction()
	{

		$security = new Secu();

		if ($security->isConnected()) session_destroy();
		header("Location:/");

	}
}
