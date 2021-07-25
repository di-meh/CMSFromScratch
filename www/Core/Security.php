<?php

namespace App\Core;
use App\Models\User;

class Security
{

	public static function isConnected(){

		session_start();

		return isset($_SESSION['id']);

	}

	public static function getConnectedUser(){

		if(self::isConnected()){
			$user = new User();
			if($user->setAllFromData(["id" => $_SESSION['id']])){
				if($user->isDeleted())
					return null;
				return $user;
			}else{
				return ($user->setAllFromData(["id" => $_SESSION['id']]));
			}
		}
		return null;
	}

	public static function isAdmin(){
		$user = new User();
		if(isset($_SESSION['id'])){
			if($user->setAllFromData(["id" => $_SESSION['id']]))
				return $user->isAdmin();
		}
		
		return false;
	}

	public static function isSuperAdmin(){
		$user = new User();
		if(isset($_SESSION['id'])){
			if($user->setAllFromData(["id" => $_SESSION['id']]))
				return $user->isSuperAdmin();
		}
		
		return false;
	}

	public static function readStatus($status){

        if($status & USERSUPERADMIN) return "SUPERADMIN";
        if($status & USERDELETED) return "DELETED";
        if($status == 0) return "NON VALIDATED USER";
        if(!($status & USERVALIDATED)) return "NON VALIDATED USER";
        if($status & USERADMIN) return "ADMIN";

		$readStatus = "";

        if($status & USERCONTRIBUTOR) $readStatus .= "CONTRIBUTOR</br>";
        if($status & USERAUTHOR) $readStatus .= "AUTHOR</br>";
        if($status & USEREDITOR) $readStatus .= "EDITOR</br>";
        if($status & USERBANNISHED) $readStatus .= "BANNISHED</br>";

        return $readStatus;


    }

	public function newTokenUser(){
		# generate new token user
		# a chaque page
	}

}