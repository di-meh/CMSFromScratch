<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;

class Security{


	public function defaultAction(){
		echo "Controller security action default";
	}


	public function loginAction(){
		echo "Controller security action login";
	}



	public function logoutAction(){

		$security = new Secu();
		if($security->isConnected()){
			echo "OK";
		}else{
			echo "NOK";
		}
		
	}


	

}