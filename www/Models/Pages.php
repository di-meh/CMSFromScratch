<?php 

namespace App\Models;

use App\Core\Singleton;
use PDO;

class Pages extends Singleton
{
	private $id = null;
    protected $titlepage;
    protected $pagecontent;

    public function __construct(){

    }


	public function formAddPage()
	{

		return [

            "config" => [
                "method" => "POST",
                "action" => "submit.php",
                "id" => "form_editprofil",
                "class" => "form_builder",
                "submit" => "Ajouter"
            ],
            "inputs" => [
                "editor" => [
                    "type" => "textarea",
                    "label" => "",
                    "cols" => 80,
                    "rows" => 10,
                    "id" => "editor",
                    "error" => "probleme enregistrement base de données"
                ]
            ]
        ];
	}

}