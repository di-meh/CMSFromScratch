<?php

namespace App\Controller;
use App\Core\View;


class SuperAdmin{

	public function checkSuperAdmin(){
		session_start();
		# si user non connecté => redirection
		# check if user is superadmin
		if(!isset($_SESSION['id']) || !($_SESSION['status'] & 1)){
			echo "ton status : ". $_SESSION['status']."</br>";
			echo "ton blase : ".$_SESSION['user']->getFirstName()."</br>";
			echo "ton status en base : ". $_SESSION['user']->getStatus()."</br>";
			echo "NOT SUPERADMIN, YOU'll BE REDIRECTED</br>";
			header("refresh:5;url=/");
		}else{
			echo "VOUS ETES SUPERADMIN";

		}


	}

	public function defaultAction(){
		$this->checkSuperAdmin();
		# print all users in base
		# backoffice to chmod everyone
		$view = new View("superadmin");

	}

}