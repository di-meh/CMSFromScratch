<?php

namespace App\Core;

class Singleton{

	# private static $instance = null;
	protected static $pdo = null; # jamais manipule hors objet, pas de get
	# static pour ne pas dependre de l'objet je pense

	private function __construct(){

	}

	public function getPDO(){
		if(is_null(self::$pdo)){
			#self::$instance = new Singleton();
			try {
				self::$pdo = new \PDO(DBDRIVER . ":dbname=" . DBNAME . ";host=" . DBHOST . ";port=" . DBPORT, DBUSER, DBPWD);

				if (ENV == "dev") {
					self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
					self::$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
				}
			} catch (\Exception $e) {
				die("Erreur SQL " . $e->getMessage());
			}
		}
		return self::$pdo;
	}


	# DELETE ALL DATA IN CHILD TABLE !
	public function deleteAll(){
		$query = "DELETE FROM ".$this->getTable();
		$result = self::$pdo->exec($query);
	}

	public function save(){

		$columns = array_diff_key(
			get_object_vars($this),
			get_class_vars(get_class())
		);

		//INSERT OU UPDATE
		if (is_null($this->getId())) {
			//INSERT
			$query = "INSERT INTO " . $this->getTable() . " (" .
					implode(",", array_keys($columns))
				. ") 
				VALUES ( :" .
					implode(",:", array_keys($columns))
				. " );";

		} else {
			echo "BAH ALORS";
			//UPDATE
			// foreach (array_keys($columns) as $key) {
			// 	$updates[] = "$key = :$key";
			// }
			// $query = $this->pdo->prepare("UPDATE " . strtolower($this->table) . "SET " . implode(', ', $updates) . " WHERE id = " . $this->getId());
		}

		$result = self::$pdo->prepare($query)->execute($columns);


	}
}