<?php 

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;

use App\Core\Singleton;

use App\Core\Redirect;

use App\Models\Pages;

class Page
{


	public function defaultAction()
	{
		echo "Controller page action default";
	}

	public function addPageAction(){
		$pages = new Pages();

		$view = new View("addPage");

		$form = $pages->formAddPage();

		$view->assign("form", $form);
	}

}