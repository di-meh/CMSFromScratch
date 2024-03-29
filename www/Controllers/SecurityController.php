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

	# view all users for admin only
	public function getAllUsersAction(){

        $user = Secu::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("admin","back");

		if($user->isAdmin()){

			$users = $user->all();
			$view->assign("users", $users);

		}else{
		 	$view->assign("errors", ["Vous n'avez pas accès à cette page."]);
		}



	}

	/*	
	*	superadmin can modify all users status
	*	admin can modify all users status except admin
	*/
	public function modifyRoleAction(){

		$user = Secu::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("admin", "back");

		if($user->isAdmin()){

			if(isset($_GET['userid'])){

				$userModified = new User();
				if($userModified->verifyId($_GET['userid']) == 1){

					$userModified->setAllFromData(["id" => $_GET['userid']]);

					$form = $userModified->formRoles();	

					$infos = "";

					if($user->getId() == $userModified->getId()){
							$view->assign("errors", ["Vous ne pouvez pas modifier vos propres droits."]);

					}else if($userModified->isSuperAdmin()){
						$view->assign("errors", ["Vous n'êtes pas autorisé à modifier cet utilisateur."]);

					}else if($userModified->isDeleted()){
						$view->assign("errors", ["Cet utilisateur a été supprimé."]);

					}else{			

						if(!$userModified->isValidated() && !$userModified->isSuperAdmin()){

							$infos .=  "Un Administrateur doit valider cet utilisateur</br>";
							$view->assign("infos", $infos);

						}

						$view->assign("changeRole", true);


						if(isset($_POST['admin'])){
							$userModified->addStatus(USERADMIN);
							$infos .= $userModified->getEmail()." est Administrateur.</br>";
							$view->assign("changeRole", false);


						}else if(isset($_POST['valider'])){
							if($userModified->isAdmin()){
								$infos .= $userModified->getEmail()." n'est plus Administrateur.</br>";

							}
							$userModified->unflagStatus(USERADMIN);
							$view->assign("changeRole", false);


						}

						if(isset($_POST['contributor'])){
							$userModified->addStatus(USERCONTRIBUTOR);
							$infos .= $userModified->getEmail()." est Contributeur.</br>";
							$view->assign("changeRole", false);


						}else if(isset($_POST['valider'])){
							if($userModified->isContributor()){
								$infos .= $userModified->getEmail()." n'est plus Contributeur.</br>";
							}
							$userModified->unflagStatus(USERCONTRIBUTOR);
							$view->assign("changeRole", false);


						}

						if(isset($_POST['author'])){
							$userModified->addStatus(USERAUTHOR);
							$infos .= $userModified->getEmail()." est Auteur.</br>";
							$view->assign("changeRole", false);

						}else if(isset($_POST['valider'])){
							if($userModified->isAuthor()){
								$infos .= $userModified->getEmail()." n'est plus Auteur.</br>";

							}
							$view->assign("changeRole", false);
							$userModified->unflagStatus(USERAUTHOR);

						}

						if(isset($_POST['editor'])){

							$infos .= $userModified->getEmail()." est Editeur.</br>";
							$userModified->addStatus(USEREDITOR);
							$view->assign("changeRole", false);


						}else if(isset($_POST['valider'])){
							if($userModified->isEditor()){
								$infos .= $userModified->getEmail()." n'est plus Editeur.</br>";
							}
							$userModified->unflagStatus(USEREDITOR);
							$view->assign("changeRole", false);

						}

						if(isset($_POST['validated'])){
							$userModified->addStatus(USERVALIDATED);
							$infos = $userModified->getEmail()." a été validé.</br>";
							$view->assign("changeRole", false);

						}

						$view->assign("formRoles",$form);


					}

					if(!empty($infos)){

						$view->assign("infos", [$infos]);
						#header("Refresh:5; url=/lbly-admin/admin", true, 303);

					}

					$userModified->save();

					$view->assign("users", $user->all());					

				}else{
					header("Location: /");
				}


			}else{
				header("Location: /");
			}

		}else{
			header("HTTP/1.0 403 Forbidden");
		 	$view = new View('403');

		}

	}

	/*	delete user form asking for the user pwd or the admin pwd	*/
	public function deleteUserAction(){

		$user = Secu::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("admin", "back");

		$users = $user->all();
		$formDelete = $user->formDelete();

		$userDelete = new User();

		$self = false; # not self delete

		# admin can delete a user 
		if(isset($_GET['userid']) && $user->isAdmin()){

			if($_GET['userid'] == $user->getId())
				$self = true;
			
			if($userDelete->verifyId($_GET['userid']) == 1){
				$userDelete->setAllFromData(["id" => $_GET['userid']]);
					if($userDelete->isSuperAdmin() && !$user->isSuperAdmin()){
						$view->assign("errors", ['Vous ne pouvez pas supprimer ce compte.']);

					}

			}else{
				# id does not exist
				$view->assign("errors", ["Id inexistant.</br>Redirection..."]);
				header("Refresh:3; url=/", true, 303);

			}


		}else if(isset($_GET['userid']) && !$user->isAdmin()){
			header("Location: /");

		# user delete himself
		}else{ 
			$userDelete = $user;
			$self = true;
		}

		if(isset($_POST['pwdConfirm'])){

			if(password_verify($_POST['pwdConfirm'], $user->getPwd())){
				$userDelete->addStatus(USERDELETED);
				$userDelete->save();
				$view->assign("infos", ["Le compte ".$userDelete->getEmail()." a bien été supprimé."]);
				$deleted = true;

				if($self)
					header("Refresh:4; url=/lbly-admin/logout", true, 303); 
				else
					header("Refresh:4; url=/lbly-admin/admin", true, 303);


			}else{
				$view->assign("errors", ["Le mot de passe est erroné"]);

			}
			

		}

			
		$view->assign("users", $users);

		if($user->getId() == $userDelete->getId() && !$user->isSuperAdmin()){
			$message = "Voulez vraiment supprimer votre compte ?";
        	$view->assign("deleteUser", true);

			$view->assign("formDelete", $formDelete);

			$view->assign("infos", [$message]);

		}else if($user->getId() == $userDelete->getId() && $user->isSuperAdmin()){
			$view->assign("errors", ["Vous ne pouvez supprimer votre compte SuperAdmin."]);

		}else if(!$userDelete->isSuperAdmin() && !$userDelete->isDeleted()){
			$message = "Voulez vous vraiment supprimer ".$userDelete->getEmail()." ?";
        	$view->assign("deleteUser", true);

			$view->assign("formDelete", $formDelete);

			$view->assign("infos", [$message]);

		}else if($userDelete->isDeleted() && !isset($deleted)){
			$view->assign("errors", ["Cet utilisateur a déjà été supprimé."]);
		}



	}

	public function forgetPwdAction(){

		$user = new User();

		$view = new View("forgetpwd");

		$form = $user->formForgetPwd();


		if(!empty($_POST['email'])){

			$email = $_POST['email'];

			$mailExists = $user->verifyMail($email); # verify exists and unicity in database

			if($mailExists == 1){ # mail found in db

				$user->setAllFromData(["email" => $email]);

				if($user->isValidated()){ # only superadmin validates

					# send mail with link to change pwd
					$mailing = Mailing::getMailing();
					$mailing->mailForgetPwd($user);
					$mailing->setRecipient($email);
					$mailing->sendMail();
					$infos = htmlspecialchars("Un lien vous a été envoyé par mail pour changer votre mot de passe.");
					$view->assign("infos", [$infos]);

				}else{
					$view->assign("infos", ["Votre compte n'a pas été validé.<a href=\"/lbly-admin/userconfirm?email=$email\" />"]);
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

			$user->setAllFromData(["id" => $id]);

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

			$user->setAllFromData(["id" => $id]);
			$user->addStatus(USERVALIDATED);

			$user->setToken(Helpers::createToken());

			$user->save();

			header("Location:/lbly-admin"); # temporairement

		}else{
			echo "ERREUR VERIFICATION ID ET TOKEN !";
		}

	}

	public function editProfilAction(){

		$user = Secu::getConnectedUser();
		if(is_null($user)) header("Location:/"); # si user non connecté => redirection


		$view = new View("editProfil", 'back'); # appelle View/editProfil.view.php

		$form = $user->formEditProfil(); # recupere les config et inputs de ce formulaire
		if($user->getStatus() > 0 && ($user->getStatus() & ~ USERVALIDATED))
			$view->assign("infos", [Secu::readStatus($user->getStatus())]);

		if (!empty($_POST)) {

			if (!empty($_POST['oldpwd'])) { # il faut le mot de passe pour valider tout changement

				if (password_verify($_POST['oldpwd'], $user->getPwd())) {

					if ($_POST['firstname'] != $user->getFirstname()) { # changer le prenom

						$user->setFirstname(htmlspecialchars($_POST['firstname']));
						# $_SESSION['user'] = $user; # update de session
						$user->save();
						$form = $user->formEditProfil(); # reaffichage du formulaire mis a jour
						$infos[] = "Votre prénom a été mis à jour !";
						$view->assign("infos", $infos);
					}

					if ($_POST['lastname'] != $user->getLastname()) { # changer le nom

						$user->setLastname(htmlspecialchars($_POST['lastname']));
						# $_SESSION['user'] = $user; # update de session
						$user->save();
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
									$view->assign("infos", $infos);
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

					$user->setAllFromData(["email" => $_POST['email']]);

					# set tous les attributs depuis la base
					# à partir du mail

					if($user->isDeleted()){
						$view->assign("infos", ["Ce compte a été supprimé."]);
					

					# verify status USERVALIDATED : 2 else no login allowed
					}else if($user->isValidated() && !$user->isDeleted()){

						$user->setToken(Helpers::createToken());

						$_SESSION['id'] = $user->getId();
						$_SESSION['email'] = $user->getEmail(); # email unique donc ca devrait etre bon
						$_SESSION['token'] = $user->getToken(); # not sure

						$user->save();

						header("Location:/lbly-admin"); # temporairement

					}else{
                        $email = $_POST['email'];
                        if($user->isSuperAdmin())
					    	$html = "Votre compte n'a pas été validé.<a href=\"/lbly-admin/userconfirm?email=$email\"> Renvoyer mail de validation</a>";
					    else
					    	$html = "Votre compte n'a pas été validé.";

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

		$user = Secu::getConnectedUser();


		$userRegister = new User();

		$view = new View("register");

		$form = $userRegister->formRegister();

		if (!empty($_POST)) {

			$errors = FormValidator::check($form, $_POST);

			$form['inputs']['email']['value'] = $_POST['email'];

			$form['inputs']['firstname']['value'] = $_POST['firstname'];

			$form['inputs']['lastname']['value'] = $_POST['lastname'];


			if (empty($errors)) {

				$mailExists = $userRegister->verifyMail($_POST['email'], $userRegister->getTable());
				# verify unicity in database


				if ($mailExists == 0) {

					if ($_POST['pwd'] == $_POST['pwdConfirm']) {

						$pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

						$userRegister->setFirstname(htmlspecialchars($_POST["firstname"]));
						$userRegister->setLastname(Helpers::clearLastname($_POST["lastname"]));
						$userRegister->setEmail(htmlspecialchars($_POST["email"]));
						$userRegister->setPwd($pwd);
						$userRegister->setCountry($_POST["country"]);

						$userRegister->setToken(Helpers::createToken());

						if(isset($user) && $user->isAdmin()){
							$userRegister->addStatus(USERVALIDATED);
							# envoi de mail au nouvel user créé pour quil change son pwd
							header("Location: /lbly-admin/admin");


						}else{

							$email = $_POST['email'];
							header("Location: userconfirm?email=$email");

						}	

							$userRegister->save();


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

		$user->setAllFromData(["email" => $_GET['email']]);

		$mailing = Mailing::getMailing();
		$mailing->mailConfirm($user);
		$mailing->setRecipient(MAILSUPERADMIN);
		$mailing->sendMail();
		header("Location: /lbly-admin/login");
		
	}

	public function logoutAction()
	{

		$security = new Secu();

		if ($security->isConnected()) session_destroy();
		header("Location:/lbly-admin");
	}
}
