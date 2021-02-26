<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker as c;

use App\Core\Singleton;

use App\Models\User;

class Security
{


	public function defaultAction()
	{
		echo "Controller security action default";
	}


	public function loginAction()
	{
		echo "Controller security action login";
		try {
			$pdo = new \PDO(DBDRIVER . ":dbname=" . DBNAME . ";host=" . DBHOST . ";port=" . DBPORT, DBUSER, DBPWD);

			if (ENV == "dev") {
				$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
			}
		} catch (\Exception $e) {
			die("Erreur SQL " . $e->getMessage());
		}
	}


	public function registerAction()
	{



		//Vérification des valeurs en POST


		/*
		$user = new User();
		$user->setFirstname("Yves");
		$user->setLastname("SKRZYPCZYK");
		$user->setEmail("y.skrzypczyk@gmail.com");
		$user->setPwd("Test1234");
		$user->setCountry("fr");

		$user->save();


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
		//$formLogin = $user->formLogin();

		if (!empty($_POST)) {

			$errors = FormValidator::check($form, $_POST);

			if (empty($errors)) {

				$user->setFirstname($_POST["firstname"]);
				$user->setLastname($_POST["lastname"]);
				$user->setEmail($_POST["email"]);

				if($user->getEmail() == "errorMail"){

					$view->assign("error","Ce mail est déjà utilisé.");
					
				}else{

					$user->setPwd($_POST["pwd"]); # verify with confirm pwd !
					$user->setCountry($_POST["country"]);

					$user->save();
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
		if ($security->isConnected()) {
			echo "OK";
		} else {
			echo "NOK";
		}
	}
}
