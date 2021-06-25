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

	public function editProfilAction()
	{

		session_start();

		if (!isset($_SESSION['id'])) header("Location:/"); # si user non connecté => redirection

		$user = $_SESSION['user']; # recuperer objet depuis session

		$view = new View("editProfil", 'back'); # appelle View/editProfil.view.php

		$form = $user->formEditProfil(); # recupere les config et inputs de ce formulaire

		if (!empty($_POST)) {

			if (!empty($_POST['oldpwd'])) { # il faut le mot de passe pour valider tout changement

				if (password_verify($_POST['oldpwd'], $user->getPwd())) {

					if ($_POST['firstname'] != $user->getFirstname()) { # changer le prenom

						$user->setFirstname(htmlspecialchars($_POST['firstname']));
						$_SESSION['user'] = $user; # update de session
						$user->save();
						#header("Refresh:0");
						$form = $user->formEditProfil(); # reaffichage du formulaire mis a jour
						$infos[] = "Votre prénom a été mis à jour !";
						$view->assign("infos", $infos);
					}

					if ($_POST['lastname'] != $user->getLastname()) { # changer le nom

						$user->setLastname(htmlspecialchars($_POST['lastname']));
						$_SESSION['user'] = $user; # update de session
						$user->save();
						#header("Refresh:0");
						$form = $user->formEditProfil();
						$infos[] = "Votre nom a été mis à jour !";
						$view->assign("infos", $infos);
					}

					if ($_POST['country'] != $user->getCountry()) {

						$user->setCountry($_POST['country']); # options donc no need specialchars
						$_SESSION['user'] = $user;
						$user->save();
						$form = $user->formEditProfil();
						$infos[] = "Votre pays a été mis à jour !";
						$view->assign("infos", $infos);
					}


					if (!empty($_POST['pwd'])) {

						if (!empty($_POST['pwdConfirm'])) {


							if ($_POST['pwd'] === $_POST['pwdConfirm']) {

								if (strlen($_POST['pwd']) > 7) {

									$pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

									$user->setPwd($pwd);
									$_SESSION['user'] = $user; # update de session
									$infos[] = "Votre mot de passe a été mis à jour !";
									$view->assign("infos", $infos); # not an error but well
									$user->save();
								} else {
									$view->assign('errors', ["La taille du nouveau mot de passe doit faire 8 caractères au minimum."]);
								}
							} else {
								$view->assign("errors", ["La confirmation du mot de passe ne correspond pas."]);
							}
						} else {
							$view->assign("errors", ['Veuillez confirmer votre nouveau mot de passe.']);
						}
					}
				} else {
					$view->assign("errors", ["Le mot de passe actuel est erroné"]);
				}
			} else {
				$view->assign("errors", ['Veuillez indiquer votre mot de passe actuel.']);
			}

			#header("Location:editprofil");

		}

		$view->assign("form", $form); # affiche le formulaire


	}


	public function loginAction()
	{

		session_start();

		if (isset($_SESSION['id'])) header("Location:/"); # user deja connected

		$user = new User();

		$view = new View("login");

		$form = $user->formLogin();

		if (!empty($_POST['email'])) {

			$mailExists = $user->verifyMail($_POST['email']); # verify unicity in database

			if ($mailExists == 1) {

				$pwd = $user->verifyPwd($_POST['email']);
				# cherche le mdp correspond a ce mail en base
				if (password_verify($_POST['pwd'], $pwd)) {

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

				} else {

					$form['inputs']['email']['value'] = $_POST['email']; # re remplissage du champ
					$view->assign("errors", ["Mot de passe erroné."]);
					#header("Location:/login");


				}
			} else {
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

			$form['inputs']['email']['value'] = $_POST['email'];

			$form['inputs']['firstname']['value'] = $_POST['firstname'];

			$form['inputs']['lastname']['value'] = $_POST['lastname'];


			if (empty($errors)) {

				$mailExists = $user->verifyMail($_POST['email'], $user->getTable());
				# verify unicity in database


				if ($mailExists == 0) {

					if ($_POST['pwd'] == $_POST['pwdConfirm']) {

						$pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

						$user->setFirstname(htmlspecialchars($_POST["firstname"]));
						$user->setLastname(htmlspecialchars($_POST["lastname"]));
						$user->setEmail(htmlspecialchars($_POST["email"]));
						$user->setPwd($pwd);
						$user->setCountry($_POST["country"]);

						$user->save();

						header("Location:login");
					} else {




						$view->assign("errors", ["Vos mots de passe sont différents."]);
					}
				} else {




					$view->assign("errors", ["Ce mail est déjà utilisé."]);
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
