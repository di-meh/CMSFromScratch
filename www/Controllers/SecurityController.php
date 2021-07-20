<?php

namespace App\Controller;

use App\Core\Helpers;

use App\Core\View;
use App\Core\Security as Secu;
use App\Core\FormValidator;

use App\Core\Mailing;

use App\Models\User;

class SecurityController
{
	

	public function defaultAction()
	{
		session_start();
		if(!isset($_SESSION['id'])) $this->logoutAction();
		
		echo "Controller security action default";
	}

	# view all users for admin only
	public function getAllUsersAction(){

        $user = Secu::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");


		if($user->isAdmin()){
			$view = new View("admin","back");

			$users = $user->all();

			$view->assign("users", $users);

		}else{
			header("HTTP/1.0 403 Forbidden");
		 	$view = new View('403');
		}



	}

	/*	
	*	superadmin can modify all users status
	*	admin can modify all users status except admin
	*/
	public function modifyStatus(){

	}

	/*	delete user form asking for the user pwd or the admin pwd	*/
	public function deleteUserAction(){

		$user = Secu::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("deleteuser");

		$form = $user->formDelete(); # confirm pwd of the user or the admin

		$userDelete = new User();

		$self = false; # not self delete

		# admin can delete a user 
		if(isset($_GET['userid']) && $user->isAdmin()){
			if($_GET['userid'] == $user->getId())
				$self = true;
			$userDelete->setAllFromId($_GET['userid']);

		}else if(isset($_GET['userid']) && !$user->isAdmin()){
			header("Location: /");

		# user delete himself
		}else{ 
			$userDelete = $user;
			$self = true;
		}

		$mailExists = $user->verifyMail($userDelete->getEmail());

		if($mailExists == 1){

			if(isset($_POST['pwdConfirm'])){

				if(password_verify($_POST['pwdConfirm'], $user->getPwd())){
					$userDelete->delete();
					$view->assign("infos", ["Le compte ".$userDelete->getEmail()." a bien été supprimé.</br>Vous allez être redirigé."]);

					if($self)
						header("Refresh:4; url=/lbly-admin/logout", true, 303); 
					else
						header("Refresh:4; url=/", true, 303);


				}else{
					$view->assign("errors", ["Le mot de passe est erroné"]);

				}
				

			}


		}else{

			$view->assign("errors",["mail not exist"]);

			#header("Location: /");

		}
			
		$view->assign("form", $form);


	}

	public function forgetPwdAction(){

		$user = new User();

		$view = new View("forgetpwd");

		$form = $user->formForgetPwd();


		if(!empty($_POST['email'])){

			$email = $_POST['email'];

			$mailExists = $user->verifyMail($email); # verify exists and unicity in database

			if($mailExists == 1){ # mail found in db

				$user->setAllFromEmail($email);

				if($user->isValidated()){ # user has validated his account by mail

					# send mail with link to change pwd
					$mailing = Mailing::getMailing();
					$mailing->mailForgetPwd($user);
					$mailing->setRecipient($email);
					$mailing->sendMail();
					$infos = htmlspecialchars("Un lien vous a été envoyé par mail pour changer votre mot de passe.");
					$view->assign("infos", [$infos]);

				}else{
					$view->assign("infos", ["Vous devez valider votre compte par email.<a href='http://localhost/lbly-admin/userconfirm?email=$email'> Cliquez ici pour renvoyer le mail de confirmation.</a>"]);
				}


			}else{
				$view->assign("infos", ["Ce mail n'est pas inscrit."]);

			}

		}

		$view->assign("form", $form);

	}

	# action from link by mail to reset pwd user account
	public function resetPwdAction(){

		if(is_null($_GET['id']) || is_null($_GET['token']))
			header("Location: /");
		
		$id = $_GET['id'];
		$token = $_GET['token'];

		$user = new User();

		$view = new View("resetpwd");

		$form = $user->formResetPwd();
		
		if($user->verifyUser($id,$token) == 1){ # check user in db with its id and token couple

			$user->setAllFromId($id);

			$user->setToken(Helpers::createToken());

			if(!empty($_POST['pwd']) && !empty($_POST['pwdConfirm'])){

				$errors = FormValidator::check($form, $_POST);

				if(empty($errors)){

					if ($_POST['pwd'] == $_POST['pwdConfirm']) {

						$pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
						$user->setPwd($pwd);

						$user->save();

						header("Location: /lbly-admin/login");


					}else{
						$view->assign("errors", ["Vos mots de passe sont différents."]);

					}

				}else{
					$view->assign("errors",$errors);
				}

			}

		}else{
			header("Location: /"); # ID AND TOKEN NOT FOUND IN DB
		}

		$view->assign("form",$form);

	}


	# action from link sent by mail to confirm user account
	public function userValidatedAction(){

		if(is_null($_GET['id']) || is_null($_GET['token']))
			header("Location: /");

		$id = $_GET['id'];
		$token = $_GET['token'];

		$user = new User();
		
		if($user->verifyUser($id,$token) == 1){ # check user in db with this id and token couple

			$user->setAllFromId($id);
			$user->addStatus(USERVALIDATED);; # status USERVALIDATED = 2

			$user->setToken(Helpers::createToken());

			$user->save();
			session_start();
			$_SESSION['id'] = $user->getId();

			header("Location:/lbly-admin"); # temporairement

		}else{
			echo "ERREUR VERIFICATION ID ET TOKEN !";
		}

	}

	public function editProfilAction(){

		session_start();

		if (!isset($_SESSION['id'])) header("Location:/"); # si user non connecté => redirection

		$user = new User();
		$user->setAllFromId($_SESSION['id']); # recuperer objet depuis session
		#var_dump($user);
		# CHERCHER LES INFOS USER EN BASE A PARTIR DE SON ID
		# A PARTIR DE SON EMAIL UNIQUE A CHACUN CEST BON AUSSI JPENSE

		$view = new View("editProfil", 'back'); # appelle View/editProfil.view.php

		$form = $user->formEditProfil(); # recupere les config et inputs de ce formulaire

		if (!empty($_POST)) {

			if (!empty($_POST['oldpwd'])) { # il faut le mot de passe pour valider tout changement

				if (password_verify($_POST['oldpwd'], $user->getPwd())) {

					if ($_POST['firstname'] != $user->getFirstname()) { # changer le prenom

						$user->setFirstname(htmlspecialchars($_POST['firstname']));
						# $_SESSION['user'] = $user; # update de session
						$user->save();
						#header("Refresh:0");
						$form = $user->formEditProfil(); # reaffichage du formulaire mis a jour
						$infos[] = "Votre prénom a été mis à jour !";
						$view->assign("infos", $infos);
					}

					if ($_POST['lastname'] != $user->getLastname()) { # changer le nom

						$user->setLastname(htmlspecialchars($_POST['lastname']));
						# $_SESSION['user'] = $user; # update de session
						$user->save();
						#header("Refresh:0");
						$form = $user->formEditProfil();
						$infos[] = "Votre nom a été mis à jour !";
						$view->assign("infos", $infos);
					}

					if ($_POST['country'] != $user->getCountry()) {

						$user->setCountry($_POST['country']); # options donc no need specialchars
						# $_SESSION['user'] = $user;
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
									#$_SESSION['user'] = $user; # update de session
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

					$user->setAllFromEmail($_POST['email']);
					# set tous les attributs depuis la base
					# à partir du mail

					# verify status USERVALIDATED : 2 else no login allowed
					if($user->isValidated()){

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

						header("Location:/lbly-admin"); # temporairement
						# $user->deleteAll(); # pour delete immediatement en 
					}else{
                        $email = $_POST['email'];
					    $html = "Vous devez aller confirmer votre compte avec le mail que vous avez reçu à cette adresse: " . $user->getEmail() . " . <a href='http://localhost/lbly-admin/userconfirm?email=$email'>Renvoyer le mail de confirmation</a>";
                        $view->assign("infos", [$html]);
//					    echo "Vous devez aller <strong style='color:red'>confirmer votre compte</strong> avec le mail que vous avez reçu à cette adresse : <strong style='color:blue'>".$user->getEmail()."</strong><br/>";
//
//						echo "<a href='http://localhost/userconfirm?email=$email'>Renvoyer le mail de confirmation</a>";
						# redirect here
					}


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

						$user->setToken(Helpers::createToken());

						$user->save();

						$email = $_POST['email'];
						header("Location: userconfirm?email=$email");

					}else{
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
		header("Location: /lbly-admin/login");
		
	}

	public function logoutAction()
	{

		$security = new Secu();

		if ($security->isConnected()) session_destroy();
		header("Location:/");
	}
}
