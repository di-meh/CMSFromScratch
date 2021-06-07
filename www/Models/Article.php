<?php

namespace App\Models;

use App\Core\Singleton;
use DateTime;

class Article extends Singleton
{
    private $table = DBPREFIX . "articles";
    private $id = null;
    protected $title;
    protected $author;
    protected $slug;
    protected $body;
    protected $created;
    protected $published;
    protected $modified;
    
    public function __construct()
    {
        $date = new \DateTime(date('Y-m-d H:i:s'));
        $this->created = $date->format('Y-m-d H:i:s');
        
    }

    # set all properties from database
    public function setAll($id)
    {

        $query = "SELECT * FROM " . $this->table . " WHERE id = '" . $id . "'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $res = $prepare->fetch(PDO::FETCH_ASSOC);
        $this->setAuthor($res['author']);
        $this->setTitle($res['title']);
        $this->setSlug($res['slug']);
        $this->setBody($res['body']);
        $this->setPublished($res['published']);
        $this->setModified($res['modified']);
        $this->setStatus($res['status']);

    }

    public function formAddArticle()
    {

        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_article",
                "class" => "form_builder",
                "submit" => "Valider"
            ],
            "inputs" => [
                "author" => [
                    "type" => "text",
                    "label" => "Editez l'auteur",
                    "minLength" => 2,
                    "maxLength" => 155,
                    "id" => "author",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Moi",
                    "value" => $this->author ?? "",
                    "error" => "Votre auteur doit faire entre 2 et 155 caractères",
                    "required" => true
                ],
                "title" => [
                    "type" => "text",
                    "label" => "Editez le titre",
                    "minLength" => 2,
                    "maxLength" => 155,
                    "id" => "title",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Premier article",
                    "value" => $this->title ?? "",
                    "error" => "Votre titre doit faire entre 2 et 155 caractères",
                    "required" => true
                ],
                "slug" => [
                    "type" => "text",
                    "label" => "Editez le slug",
                    "minLength" => 2,
                    "maxLength" => 155,
                    "id" => "slug",
                    "class" => "form_input",
                    "placeholder" => "Exemple: /article",
                    "value" => $this->slug ?? "",
                    "error" => "Votre slug doit faire entre 2 et 155 caractères",
                    "required" => true
                ],
                "body" => [
                    "type" => "texarea",
                    "label" => "Editez le body",
                    "minLength" => 2,
                    "maxLength" => 155,
                    "id" => "slug",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Ceci est mon article",
                    "value" => $this->body ?? "",
                    "error" => "Votre body doit faire entre 2 et 155 caractères",
                    "required" => true
                ],
            ]
        ];
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
    
    public function getBody()
    {
        return $this->body;
    }
    public function setBody($body)
    {
        $this->body = $body;
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
}
