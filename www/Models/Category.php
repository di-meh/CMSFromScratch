<?php

namespace App\Models;

use App\Core\Singleton;
use PDO;

class Category extends Singleton

{
        
    private $id = null;
    protected $nameCategory;
    protected $colorCategory;
    protected $slug;

    private $table = "lbly_category";
     
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
    
    public function getColorCategory()
    {
        return $this->colorCategory;
    }
    public function setColorCategory($colorCategory)
    {
        $this->colorCategory = $colorCategory;
    }

        /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function title2slug($title){
        $title = preg_replace('~[^\pL\d]+~u', '-', $title);

        $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        $title = strtr( $title, $unwanted_array );

        //retire symboles spéciaux
        $title = iconv("UTF-8", "ASCII//TRANSLIT", $title);

        $title = preg_replace('~[^-\w]+~', '', $title);

        $title = trim($title, '-');
        //suprimme double -
        $title = preg_replace('~-+~', '-', $title);
        //minuscule
        $title = strtolower($title);

        return $title;
    }
        
    public function formAddCategory()
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

    public function formEditCategory()
    {
    return[
        "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_category",
                "class" => "form_builder",
                "submit" => "Modifier"
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

    public function formDeleteCategory(){
        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_deletecategory",
                "class" => "form_builder",
                "submit" => "Supprimer",
                "btn_class" => "btn btn-danger"
            ],
            "inputs" => [
                "delete" => [
                    "type" => "hidden",
                    "label" => "Voulez vous supprimez cette catégorie : ".$this->nameCategory." ?",
                    "class" => "form_input",
                    "value" => $this->slug,
                    "error" => "id not found",
                    "required" => true
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
        return $check ? $check[0] : null;
    }

    public function getAllBySlug($slug){
        $query = "SELECT * FROM " . $this->getTable() . " WHERE slug = '".$slug."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res ? $res[0] : null;
    }

    public function getAllById($id){
        $query = "SELECT * FROM " . $this->getTable() . " WHERE id = '".$id."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res ? $res[0] : null;
    }

    public function setAllBySlug($slug){
        $res = $this->getAllBySlug($slug);
        $this->setId($res['id']);
        $this->setNameCategory($res['nameCategory']);
        $this->setColorCategory($res['colorCategory']);
        $this->setSlug($res['slug']);
    }

    public function deleteBySlug($slug){
        $query = "DELETE FROM " . $this->getTable() . " WHERE slug = '" . $slug ."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
    }

    public function getDeletedArticleCategory($deleted_category){
        $query = "SELECT category FROM lbly_article WHERE category LIKE '%".$deleted_category."%'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function updateArticleCategory($new_category, $old_category){
        $query = "UPDATE lbly_article SET category='".$new_category."' WHERE category='".$old_category."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getDeletedBookCategory($deleted_category){
        $query = "SELECT category FROM lbly_books WHERE category LIKE '%".$deleted_category."%'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function updateBookCategory($new_category, $old_category){
        $query = "UPDATE lbly_books SET category='".$new_category."' WHERE category='".$old_category."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
}
