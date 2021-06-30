<?php

namespace App\Core;

class Helpers
{

	public static function clearLastname($lastname){
		return mb_strtoupper(trim($lastname));
	}

	public static function createToken(){
		return substr(md5(uniqid(true)), 0, 10); # cut length to 10, no prefix, entropy => for more unicity
	}

}