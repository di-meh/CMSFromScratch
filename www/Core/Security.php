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

	public static function readStatus($status){
		$readStatus = "";
        if($status == 0) return "NON VALIDATED USER";
        if($status & 1) $readStatus .= "SUPERADMIN</br>";
        if($status & 2) $readStatus .= "ADMIN</br>";
        if($status & 4) $readStatus .= "VALIDATED</br>";
        if($status & 8) $readStatus .= "DELETED</br>";
        if($status & 16) $readStatus .= "BANNISHED</br>";
        return $readStatus;


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