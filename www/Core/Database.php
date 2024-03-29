<?php

namespace App\Core;

class Database
{

	protected $pdo;
	protected $table;

	public function __construct()
	{
		try {
			$this->pdo = new \PDO(DBDRIVER . ":dbname=" . DBNAME . ";host=" . DBHOST . ";port=" . DBPORT, DBUSER, DBPWD);

			if (ENV == "dev") {
				$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
			}
		} catch (\Exception $e) {
			die("Erreur SQL " . $e->getMessage());
		}

		$getCalledClassExploded = explode("\\", get_called_class()); //App\Models\User
		$this->table = mb_strtolower(DBPREFIX . end($getCalledClassExploded));
	}


	public function save()
	{

		/*
		//Prepare
		$query = $this->pdo->prepare("INSERT INTO wpml_user (firstname, lastname, email) 
				VALUES ( :firstname , :lastname , :email );");  // 1

		//Executer
		$query->execute(
				[
					"firstname"=>"Yves",
					"lastname"=>"Skrzypczyk",
					"email"=>"y.skrzypczyk@gmail.com"
				]
		);
		*/




		$columns = array_diff_key(
			get_object_vars($this),
			get_class_vars(get_class())
		);


		//INSERT OU UPDATE
		// $id == null -> INSERT SINON UPDATE
		if (is_null($this->getId())) {
			//INSERT
			$query = $this->pdo->prepare("INSERT INTO " . $this->table . " (" .
				implode(",", array_keys($columns))
				. ") 
				VALUES ( :" .
				implode(",:", array_keys($columns))
				. " );");
		} else {
			//UPDATE
			foreach (array_keys($columns) as $key) {
				$updates[] = "$key = :$key";
			}
			$query = $this->pdo->prepare("UPDATE " . $this->table . "SET " . implode(', ', $updates) . " WHERE id = " . $this->getId());
		}

		$query->execute($columns);
	}
}
