<?php

namespace App\Controller;

use App\Core\Helpers;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker as c;

use App\Core\Singleton;

use App\Core\Mailing;

use App\Core\Redirect;

use App\Models\User;

class Security
{
	

	public function defaultAction()
	{
		if(!isset($_SESSION['id'])) $this->logoutAction();
		
		echo "Controller security action default";
	}

	public function userValidatedAction(){

		if(is_null($_GET['id']) || is_null($_GET['token']))
			header("Location: /");

		$id = $_GET['id'];
		$token = $_GET['token'];

		$user = new User();
		
		if($user->verifyUser($id,$token) == 1){ # check user in db with this id and token couple

			$user->setAllFromId($id);
			$user->addStatus(USERVALIDATED);; # status USERVALIDATED = 4

			$user->setToken(Helpers::createToken());

			$user->save();
			session_start();
			$_SESSION['id'] = $user->getId();

			header("Location:/editprofil"); # temporairement

		}else{
			echo "ERREUR VERIFICATION ID ET FIRSTNAME !";
		}

	}

	public function editProfilAction(){

		session_start();

		if(!isset($_SESSION['id'])) header("Location:/"); # si user non connecté => redirection

		$user = new User();
		$user->setAllFromId($_SESSION['id']); # recuperer objet depuis session
		#var_dump($user);
		# CHERCHER LES INFOS USER EN BASE A PARTIR DE SON ID
		# A PARTIR DE SON EMAIL UNIQUE A CHACUN CEST BON AUSSI JPENSE

		$view = new View("editProfil"); # appelle View/editProfil.view.php

		$form = $user->formEditProfil(); # recupere les config et inputs de ce formulaire

		if(!empty($_POST)){

			if(!empty($_POST['oldpwd'])){ # il faut le mot de passe pour valider tout changement

				if(password_verify($_POST['oldpwd'], $user->getPwd())){

					if($_POST['firstname'] != $user->getFirstname()){ # changer le prenom

						$user->setFirstname(htmlspecialchars($_POST['firstname']));
						# $_SESSION['user'] = $user; # update de session
						$user->save();
						#header("Refresh:0");
						$form = $user->formEditProfil(); # reaffichage du formulaire mis a jour
						$infos[] = "Votre prénom a été mis à jour !";
						$view->assign("infos", $infos);
					}

					if($_POST['lastname'] != $user->getLastname()){ # changer le nom

						$user->setLastname(htmlspecialchars($_POST['lastname']));
						# $_SESSION['user'] = $user; # update de session
						$user->save();
						#header("Refresh:0");
						$form = $user->formEditProfil();
						$infos[] = "Votre nom a été mis à jour !";
						$view->assign("infos", $infos);
					}

					if($_POST['country'] != $user->getCountry()){

						$user->setCountry($_POST['country']); # options donc no need specialchars
						# $_SESSION['user'] = $user;
						$user->save();
						$form = $user->formEditProfil();
						$infos[] = "Votre pays a été mis à jour !";
						$view->assign("infos", $infos);

					}


					if(!empty($_POST['pwd'])){

						if(!empty($_POST['pwdConfirm'])){


							if($_POST['pwd'] === $_POST['pwdConfirm']){

								if(strlen($_POST['pwd']) > 7){

									$pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

									$user->setPwd($pwd);
									#$_SESSION['user'] = $user; # update de session
									$infos[] = "Votre mot de passe a été mis à jour !";
									$view->assign("infos", $infos); # not an error but well
									$user->save();

								}else{
									$view->assign('errors', ["La taille du nouveau mot de passe doit faire 8 caractères au minimum."]);
								}

							}else{
								$view->assign("errors", ["La confirmation du mot de passe ne correspond pas."]);
							}

						}else{
							$view->assign("errors", ['Veuillez confirmer votre nouveau mot de passe.']);
						}

					}

				}else{
					$view->assign("errors", ["Le mot de passe actuel est erroné"]);
				}

			}else{
				$view->assign("errors", ['Veuillez indiquer votre mot de passe actuel.']);
			}

			#header("Location:editprofil");

		}

		$view->assign("form", $form); # affiche le formulaire


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

			if($mailExists == 1){

				$pwd = $user->verifyPwd($_POST['email']);
				# cherche le mdp correspond a ce mail en base
				if(password_verify($_POST['pwd'], $pwd)){

					$user->setAllFromEmail($_POST['email']);
					# set tous les attributs depuis la base
					# à partir du mail

					# verify status USERVALIDATED : 4 else no login allowed
					if($user->getStatus() & 4){

						$token = substr(md5(uniqid(true)), 0, 10); # cut length to 10, no prefix, entropy => for more unicity
						$user->setToken($token);

						$_SESSION['id'] = $user->getId();
						$_SESSION['email'] = $user->getEmail(); # email unique donc ca devrait etre bon
						# $_SESSION['pwd'] = $user->getPwd(); # ??
						$_SESSION['token'] = $token; # not sure


						#echo "MAIS OUI TU ES CONNECTE MON FILS.";
						#$user->setEmail($_POST['email']);
						# $id = Singleton::findID($email);
						# $user->setId($id); # peuple l'entité
						# $user->setPwd($_POST['pwd']); # useless to me

						#var_dump($res);

						header("Location:/editprofil"); # temporairement
						# $user->deleteAll(); # pour delete immediatement en 
					}else{
						echo "Vous devez aller <strong style='color:red'>confirmer votre compte</strong> avec le mail que vous avez reçu à cette adresse : <strong style='color:blue'>".$user->getEmail()."</strong><br/>";
						$email = $_POST['email'];
						echo "<a href='http://localhost/userconfirm?email=$email'>Renvoyer le mail de confirmation</a>";
						# redirect here
					}


				}else{

					$form['inputs']['email']['value'] = $_POST['email']; # re remplissage du champ
					$view->assign("errors", ["Mot de passe erroné."]);
					#header("Location:/login");


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

			$form['inputs']['email']['value'] = $_POST['email'];

			$form['inputs']['firstname']['value'] = $_POST['firstname'];

			$form['inputs']['lastname']['value'] = $_POST['lastname'];


			if (empty($errors)) {

				$mailExists = $user->verifyMail($_POST['email'], $user->getTable());
				# verify unicity in database


				if($mailExists == 0){

					if($_POST['pwd'] == $_POST['pwdConfirm']){

						$pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
						
						$user->setFirstname(htmlspecialchars($_POST["firstname"]));
						$user->setLastname(htmlspecialchars($_POST["lastname"]));
						$user->setEmail(htmlspecialchars($_POST["email"]));
						$user->setPwd($pwd);
						$user->setCountry($_POST["country"]);

						$token = substr(md5(uniqid(true)), 0, 10); # cut length to 10, no prefix, entropy => for more unicity
						$user->setToken($token);

						$user->save();

						$email = $_POST['email'];
						header("Location: userconfirm?email=$email");

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

	# send mail to confirm user then redirect to login
	public function userConfirmAction(){
		$user = new User();
		if(is_null($_GET['email']) || empty($_GET['email']))
			header("Location: /");

		$user->setAllFromEmail($_GET['email']); # to get user id

		$mailing = Mailing::getMailing();
		$mailing->mailConfirm($user); # set mail confirmation content
		$mailing->setRecipient($_GET['email']);
		$mailing->sendMail();

		header("Location: /login");
		
	}


	public function logoutAction()
	{

		$security = new Secu();

		if ($security->isConnected()) session_destroy();
		header("Location:/");

	}



	public function category()
	{
		header("Location:/category");

	}
}
