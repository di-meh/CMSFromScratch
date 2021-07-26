<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Security;
use App\Models\Page;

class MainController{


	public function defaultAction(){

		session_start();
		$page = new Page();
		$pagecontent = $page->getAllBySlug('home');

		if(isset($pagecontent) && !empty($pagecontent)){

			$view = new View("seePage", "front");
	
			$view->assign("page", $pagecontent);
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