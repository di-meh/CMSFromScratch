<?php

namespace App\Core;

class Helpers
{

	public static function clearLastname($lastname){
		return mb_strtoupper(trim($lastname));
	}


}