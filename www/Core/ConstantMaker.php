<?php

namespace App\Core;

class ConstantMaker
{

	private $envPath = ".env";
	private $data = [];

	public function __construct(){

		$this->defineUserStatus();

		//.env
		$this->parseEnv($this->envPath);

		if(!empty($this->data["ENV"])){
			// .env.prod ou .env.dev
			$this->parseEnv($this->envPath.".".$this->data["ENV"]);
		}


		$this->defineConstants();
	}

	# binary mask for user status
	public function defineUserStatus(){

		# 0 is a non admin non validated user, just a logged client
		define("USERSUPERADMIN", 1);
		define("USERADMIN", 2);
		define("USERVALIDATED", 4);
		define("USERDELETED", 8);
		define("USERBANNISHED", 16);

	}


	public function defineConstants(){
		foreach ($this->data as $key => $value) {
			self::defineConstant($key, $value);
		}
	}

	public static function defineConstant($key, $value){
		
			$key = str_replace(" ", "_", mb_strtoupper(trim($key)));
			if(!defined($key)){
				define($key, $value);
			}else{
				die("La constante ".$key." existe déjà");
			}
		
	}


	public function parseEnv($file){
		$handle = fopen($file, "r");
		if(!empty($handle)){
			while (!feof($handle)) {
				$line = trim(fgets($handle));
				//$line = DBHOST=database #ceci est un commentaire
				//$data["DBHOST"]="database";
				preg_match("/([^=]*)=([^#]*)/", $line, $results);
				if(!empty($results[1]) && !empty($results[2])){
					$this->data[$results[1]] = trim($results[2]);
				}


			}
		}
	}


}