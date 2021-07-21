<?php

namespace App\Models;

use App\Core\Singleton;
use PDO;

class Category extends Singleton

{
        
    private $id = null;
    protected $nameCategory;
    protected $colorCategory;

    private $table = DBPREFIX . "category";

    public  function setAll($nameCategory)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE lbcategory = '" . $nameCategory . "'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $res = $prepare->fetch(PDO::FETCH_ASSOC);
        $this->setId($res['id']);
        $this->setNameCategory($res['nameCategory']);
    }

    public function __construct()
    {

        #Singleton::setPDO(); # set une unique fois !

    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return mixed
     */
    public function getNameCategory()
        {
            return $this->nameCategory;
        }

    /**
     * @param mixed $categoryname
     */
    public function setNameCategory($nameCategory)
        {
            $this->nameCategory = $nameCategory;
        }

    public function formCategory()
    {
        return[
            "config" => [
                    "method" => "POST",
                    "action" => "",
                    "id" => "form_login",
                    "class" => "form_builder",
                    "submit" => "Ajouter une catégorie"
                ],
            "inputs" => [
                "nameCategory" => [
                    "type" => "text",
                    "label" => "Nom de la catégorie",
                    "minLength" => 3,
                    "maxLength" => 255,
                    "id" => "nameCategory",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Horreur",
                    "value" => $this->nameCategory ?? "",
                    "error" => "Votre nom de Catégorie doit faire entre 3 et 255 caractères",
                    "required" => true
                ]
            ]
        ];
    
    }
}
    public function getColorCategory()
        {
            return $this->colorCategory;
        }
    public function setColorCategory($colorCategory)
        {
            $this->colorCategory = $colorCategory;
        }
        
    public function formCategory()
    {
    return[
        "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_category",
                "class" => "form_builder",
                "submit" => "Ajouter une catégorie"
            ],
        "inputs" => [
            "nameCategory" => [
                "type" => "text",
                "label" => "Nom de la catégorie",
                "minLength" => 3,
                "maxLength" => 255,
                "id" => "nameCategory",
                "class" => "form_input",
                "placeholder" => "Exemple: Horreur",
                "value" => $this->nameCategory ?? "",
                "error" => "Votre nom de Catégorie doit faire entre 3 et 255 caractères",
                "required" => true
            ],
            "colorCategory" => [
                "type" => "color",
                "label" => "Couleur de la catégorie",
                "minLength" => 4,
                "maxLength" => 7,
                "id" => "colorCategory",
                "class" => "form_input",
                "placeholder" => "Exemple: #dcdcdc",
                "value" => $this->colorCategory ?? "#dcdcdc",
                "error" => "Le code couleur de la Catégorie doit commencé par un # et faire entre 3 et 7 caractères",
                ]
            ]
        ];
    }   

    public function checkCategory($nameCategory)
    {
        $query = "SELECT * FROM " . $this->getTable() . " WHERE nameCategory = '".$nameCategory."'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $check = $prepare->fetchAll(PDO::FETCH_ASSOC);
        return $check;
    }
}
