<?php

namespace App\Models;

use App\Core\Singleton;
use PDO;
use DateTime;

class Article extends Singleton
{
    private $table = "lbly_article";
    private $id = null;
    protected $title;
    protected $author;
    protected $slug;
    protected $metadescription;
    protected $content;
    protected $category;
    protected $createdAt;
    protected $status;

    
    public function __construct()
    {
        $date = new \DateTime(date('Y-m-d H:i:s'));
        $this->createdAt = $date->format('Y-m-d H:i:s');
        
    }

    public function getTable()
    {
        return $this->table;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getAuthor()
    {
        return $this->author;
    }
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getTitle()
    {
        return $this->title;
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getSlug()
    {
        return $this->slug;
    }
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }


    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getMetadescription()
    {
        return $this->metadescription;
    }

    /**
     * @param mixed $metadescription
     */
    public function setMetadescription($metadescription)
    {
        $this->metadescription = $metadescription;
    }

    //formulaire ajouter article
    public function formAddArticle()
    {

        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_addarticle",
                "class" => "form_builder",
                "submit" => "Ajouter"
            ],
            "inputs" => [
                "title" => [
                    "type" => "text",
                    "label" => "Titre",
                    "minLength" => 2,
                    "maxLength" => 255,
                    "id" => "title",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Premier article",
                    "value" => $this->title ?? "",
                    "error" => "Votre titre doit faire entre 2 et 155 caractères",
                    "required" => true
                ],

                "category[]" => [
                    "type" => "select",
                    "label" => "Catégorie",
                    "multiple" => "multiple",
                    "options" => $this->getCreatedCategory(),
                    "id" => "category",
                    "class" => "form_input",
                    "error" => "La catégorie doit faire entre 1 et 100 caractères",
                    "required" => true
                ],
                "metadescription" => [
                    "type" => "textarea",
                    "label" => "Metadescription",
                    "minLength" => 1,
                    "maxLength" => 200,
                    "id" => "metadescription",
                    "class" => "form_input",
                    "placeholder" => "Une desription de l'article",
                    "value" => "",
                    "error" => "Votre description doit faire entre 1 et 200 caractères",
                    "required" => true
                ],
                "content" => [
                    "type" => "textarea",
                    "label" => "Contenu de l'article",
                    "minLength" => 1,
                    "cols" => 80,
                    "rows" => 10,
                    "id" => "content",
                    "name" => "content",
                    "class" => "ckeditor",
                    "placeholder" => "",
                    "value" => $this->content ?? "",
                    "error" => "probleme enregistrement base de données",
                    "required" => true
                ],
            ]
        ];
    }

    //formulaire modifier article
    public function formEditArticle()
    {

        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_addarticle",
                "class" => "form_builder",
                "submit" => "Modifier"
            ],
            "inputs" => [
                "title" => [
                    "type" => "text",
                    "label" => "Titre",
                    "minLength" => 2,
                    "maxLength" => 255,
                    "id" => "title",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Premier article",
                    "value" => $this->title ?? "",
                    "error" => "Votre titre doit faire entre 2 et 155 caractères",
                    "required" => true
                ],
                "metadescription" => [
                    "type" => "textarea",
                    "label" => "Metadescription",
                    "minLength" => 1,
                    "maxLength" => 200,
                    "id" => "metadescription",
                    "class" => "form_input",
                    "placeholder" => "Une desription de la page",
                    "value" => $this->getMetadescription() ?? "",
                    "error" => "Votre description doit faire entre 1 et 200 caractères",
                    "required" => true
                ],
                "category[]" => [
                    "type" => "select",
                    "label" => "Catégorie",
                    "multiple" => "multiple",
                    "options" => $this->getCreatedCategory(),
                    "value" => explode(",",$this->getCategory()),
                    "id" => "category",
                    "class" => "form_input",
                    "error" => "La catégorie doit faire entre 1 et 100 caractères",
                    "required" => true
                ],
                "content" => [
                    "type" => "textarea",
                    "label" => "Contenu de l'article",
                    "minLength" => 1,
                    "cols" => 80,
                    "rows" => 10,
                    "id" => "content",
                    "name" => "content",
                    "class" => "ckeditor",
                    "placeholder" => "",
                    "value" => $this->content ?? "",
                    "error" => "probleme enregistrement base de données",
                    "required" => true
                ],
            ]
        ];
    }

    //formulaire supprimer article
    public function formDeleteArticle(){
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_deletearticle",
                "class" => "form_builder",
                "submit" => "Supprimer",
                "btn_class" => "btn btn-danger"
            ],
            "inputs" => [
                "delete" => [
                    "type" => "hidden",
                    "label" => "Voulez vous supprimez cet article : ".$this->title." ?",
                    "id" => "title",
                    "class" => "form_input",
                    "value" => $this->slug,
                    "error" => "slug not found",
                    "required" => true
                ]
            ]

        ];
    }

    //transoforme le titre de l'article en slug
    public function title2slug($title){
        // remplace ce qui n'est pas lettre ou nombre par -
        $title = preg_replace('~[^\pL\d]+~u', '-', $title);

        //remplace les accents par non accents
        $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        $title = strtr( $title, $unwanted_array );

        //retire symboles spéciaux
        $title = iconv("UTF-8", "ASCII//TRANSLIT", $title);

        //retire tout ce qui n'est pas chiffre lettre ou -
        $title = preg_replace('~[^-\w]+~', '', $title);

        //retire les - au debut et a la fin
        $title = trim($title, '-');
        //suprimme double -
        $title = preg_replace('~-+~', '-', $title);
        //minuscule
        $title = strtolower($title);

        return $title;
    }

    //recupere toute les info d'un article en fonction du slug
    public function getAllBySlug($slug){
        $query = "SELECT * FROM " . $this->getTable() . " WHERE slug = '".$slug."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res ? $res[0] : null;
    }

    //recupere toute les info d'un article en fonction de l'id
    public function getAllById($id){
        $query = "SELECT * FROM " . $this->getTable() . " WHERE id = '".$id."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res ? $res[0] : null;
    }

    public function setAllById($id)
    {
        $res = $this->getAllById($id);
        $this->setId($res['id']);
        $this->setAuthor($res['author']);
        $this->setTitle($res['title']);
        $this->setSlug($res['slug']);
        $this->setMetadescription($res['metadescription']);
        $this->setContent($res['content']);
        $this->setCategory($res['category']);
        $this->setCreatedAt($res['createdAt']);
        $this->setStatus($res['status']);
    }

    //set toute les info d'un article en fonction du slug
    public function setAllBySlug($slug)
    {
        $res = $this->getAllBySlug($slug);
        $this->setId($res['id']);
        $this->setAuthor($res['author']);
        $this->setTitle($res['title']);
        $this->setSlug($res['slug']);
        $this->setMetadescription($res['metadescription']);
        $this->setContent($res['content']);
        $this->setCategory($res['category']);
        $this->setCreatedAt($res['createdAt']);
        $this->setStatus($res['status']);
    }

    //supprime en fonction du slug
    public function deleteBySlug($slug){
        $query = "DELETE FROM " . $this->getTable() . " WHERE slug  = '" . $slug ."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
    }

    //recupere toute les categorie créer
    public function getCreatedCategory(){
        $array = [];
        $query = "SELECT nameCategory FROM lbly_category ORDER BY nameCategory ASC";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        foreach ($res as $re) {
            array_push($array,$re["nameCategory"]);
        }
        return $array;
    }
}
