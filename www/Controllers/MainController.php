<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Security;
use App\Models\Page;

class MainController{


	public function defaultAction(){

		session_start();
		$page = new Page();
		$page = $page->getAllBySlug('home');

		if(isset($page) && !empty($page)){

			$view = new View("seePage", "front");
	
			$view->assign("page", $page[0]);
		} else {

			$view = new View("home");
		}




	}

	public function dashboardAction(){

		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("dashboard","back");


	}


}