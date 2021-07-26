<?php

namespace App\Core;

use PDO;
use App\Core\Router;
use App\Models\Page;

class Singleton
{

	# private static $instance = null;
	protected static $pdo = null; # jamais manipule hors objet, pas de get
	# static pour ne pas dependre de l'objet je pense

	private function __construct()
	{
	}

	public function getPDO()
	{
		if (is_null(self::$pdo)) {
			#self::$instance = new Singleton();
			try {
				self::$pdo = new PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME . ";port=" . DBPORT, DBUSER, DBPWD);

				self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			} catch (\Exception $e) {
				die("Erreur SQL " . $e->getMessage());
			}
		}
		return self::$pdo;
	}

    /*
    *   set all properties from database from data
    *   from id idealement mais flm
    *   pcq il faut recup id a partir de email puis tout a partir de id
    */
    public function setAllFromData($data)
    {
    	$value = $data[key($data)];
        $email = htmlspecialchars($value);
        $query = "SELECT * FROM " . $this->getTable() . " WHERE ".key($data)." = '" . $value . "'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $res = $prepare->fetch(PDO::FETCH_ASSOC);
        if(!empty($res) && !is_null($res)){
	        $this->setId($res['id']);
	        $this->setFirstname($res['firstname']);
	        $this->setLastname($res['lastname']);
	        $this->setEmail($res['email']);
	        $this->setCountry($res['country']??'');
	        $this->setStatus($res['status']);
	        $this->setToken($res['token'] ?? '');

	        $this->setPwd($res['pwd']); # un peu dangereux non ? même si hashé
	        return true;

	    }
	    return false;

    }

	# DELETE ALL DATA IN CHILD TABLE !
	public function deleteAll()
	{
		$query = "DELETE FROM " . $this->getTable() . "";
		$prepare = self::$pdo->prepare($query);
		$prepare->execute();
	}

	/*	delete one tuple from child table	*/
	public function delete(){
		$query = "DELETE FROM ". $this->getTable()." WHERE id=".$this->getId();
		$prepare = self::$pdo->prepare($query);
		$prepare->execute();

	}

	public function save()
	{

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
				. " )";
		} else {

			//UPDATE
			$query = "UPDATE " . strtolower(($this->getTable())) . " SET ";
			foreach ($columns as $key => $value) {
				$query .= $key . "=:" . $key . ",";
			}
			$query = substr($query, 0, -1); # retire la dernière virgule
			$query .= " WHERE id=" . $this->getId();
		}

		$result = $this->getPDO()->prepare($query)->execute($columns);
	}

	public function all()
	{
		$query = "SELECT * from " . $this->getTable();
		$stmt = $this->getPDO()->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
}
