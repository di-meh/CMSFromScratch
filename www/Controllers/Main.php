<?php

namespace App\Controller;

use App\Core\View;

class Main{


	public function defaultAction(){
		
		$pseudo = "Super Prof"; //Plus tard on le récupèrera depuis la bdd

		$view = new View("home");
		$view->assign("pseudo", $pseudo);
		$view->assign("age", 18);
		$view->assign("email", "y.skrzypczyk@gmail.com");


	}


}