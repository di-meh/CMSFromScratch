<?php

namespace App\Core;
use App\Models\User;

class Security
{

	# A QUOI SERT CETTE CLASSE ?

	public static function isConnected(){

		session_start();

		return isset($_SESSION['id']);

	}

	public static function getConnectedUser(){
		
		if(self::isConnected()){
			$user = new User();
			if($user->setAllFromId($_SESSION['id'])){
				return $user;
			}
		}
		return null;
	}

	public function isAdmin(){

	}

	public function isSuperAdmin(){

	}

	public function newTokenUser(){
		# generate new token user
		# a chaque page
	}

}