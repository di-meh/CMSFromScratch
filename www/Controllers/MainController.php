<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Security;

class MainController{


	public function defaultAction(){

		session_start();

		$view = new View("home");


	}

	public function dashboardAction(){

		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("dashboard","back");


	}


}