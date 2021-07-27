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

			$page->setAllBySlug("home");
			if($page->getStatus() == "publish"){
				$view = new View("seePage", "front");

				$view->assign("page", $pagecontent);
				$view->assign("metadescription", $pagecontent['metadescription']);
				$view->assign("title", $pagecontent['title']);
			} else {
				$view = new View("home");
			}
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