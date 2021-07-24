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
    protected $content;
    protected $created;
    protected $published;
    protected $modified;
    
    public function __construct()
    {
        $date = new \DateTime(date('Y-m-d H:i:s'));
        $this->created = $date->format('Y-m-d H:i:s');
        
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
    
    public function getCreated()
    {
        return $this->created;
    }
    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getModified()
    {
        return $this->modified;
    }
    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    public function getPublished()
    {
        return $this->published;
    }
    public function setPublished($published)
    {
        $this->published = $published;
    }

    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }

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
    
    public function getAllBySlug($slug){
        $query = "SELECT * FROM " . $this->getTable() . " WHERE slug = '".$slug."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getAllById($id){
        $query = "SELECT * FROM " . $this->getTable() . " WHERE id = '".$id."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function setAllById($id)
    {
        $res = $this->getAllById($id);
        $res = $res[0];
        $this->setId($res['id']);
        $this->setAuthor($res['author']);
        $this->setTitle($res['title']);
        $this->setSlug($res['slug']);
        $this->setContent($res['content']);
        $this->setCreated($res['created']);
        $this->setPublished($res['published']);
        $this->setModified($res['modified']);
        $this->setStatus($res['status']);
    }

    public function setAllBySlug($slug)
    {
        $res = $this->getAllBySlug($slug);
        $res = $res[0];
        $this->setId($res['id']);
        $this->setAuthor($res['author']);
        $this->setTitle($res['title']);
        $this->setSlug($res['slug']);
        $this->setContent($res['content']);
        $this->setCreated($res['created']);
        $this->setPublished($res['published']);
        $this->setModified($res['modified']);
        $this->setStatus($res['status']);
    }

    public function deleteBySlug($slug){
        $query = "DELETE FROM " . $this->getTable() . " WHERE slug  = '" . $slug ."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
    }
}
