<?php

namespace App\Core;

class Security
{

	# A QUOI SERT CETTE CLASSE ?

	public function isConnected(){

		session_start();

		return isset($_SESSION['id']);

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