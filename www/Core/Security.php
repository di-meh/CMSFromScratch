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

        if($status & USERSUPERADMIN) return "SUPERADMIN";
        if($status & USERADMIN) $readStatus .= "ADMIN</br>";
        if($status & USERVALIDATED) $readStatus .= "VALIDATED</br>";
        if($status & USERCONTRIBUTOR) $readStatus .= "CONTRIBUTOR</br>";
        if($status & USERAUTHOR) $readStatus .= "AUTHOR</br>";
        if($status & USEREDITOR) $readStatus .= "EDITOR</br>";
        if($status & USERDELETED) $readStatus .= "DELETED</br>";
        if($status & USERBANNISHED) $readStatus .= "BANNISHED</br>";


        return $readStatus;


    }

	public function newTokenUser(){
		# generate new token user
		# a chaque page
	}

}