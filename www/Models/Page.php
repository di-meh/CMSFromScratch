<?php 

namespace App\Models;

use App\Core\Helpers;
use App\Core\Singleton;
use PDO;

class Page extends Singleton
{
	private $id = null;
    protected $title;
    protected $content;
    protected $createdBy;
    protected $slug;
    private $table = "lbly_page";

    public function __construct(){

    }

    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return mixed
     */
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

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
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
        return Helpers::slugify($title);
    }

	public function formAddPage()
	{

		return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_addpage",
                "class" => "form_builder",
                "submit" => "Ajouter"
            ],
            "inputs" => [
                "title" =>[
                    "type" => "text",
                    "label" => "Titre : ",
                    "minLength" => 2,
                    "maxLength" => 255,
                    "id" => "title",
                    "class" => "title",
                    "placeholder" => "Titre de votre page",
                    "value" => '',
                    "error" => "Votre titre doit faire entre 2 et 60 caractères",
                    "required" => true
                ],
                "editor" => [
                    "type" => "textarea",
                    "label" => "Contenu de la page",
                    "minLength" => 1,
                    "cols" => 80,
                    "rows" => 10,
                    "id" => "editor",
                    "name" => "editor",
                    "value" => '',
                    "error" => "probleme enregistrement base de données"
                ]
            ]
        ];
	}


    public function formEditPage(){
        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_editpage",
                "class" => "form_builder",
                "submit" => "Modifier"
            ],
            "inputs" => [
                "title" => [
                    "type" => "text",
                    "label" => "Editez le titre",
                    "minLength" => 1,
                    "maxLength" => 255,
                    "id" => "title",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Premier article",
                    "value" => htmlspecialchars($this->title),
                    "error" => "Votre titre doit faire entre 2 et 155 caractères",
                    "required" => true
                ],
                "content" => [
                    "type" => "textarea",
                    "label" => "Contenu de la page",
                    "minLength" => 1,
                    "cols" => 80,
                    "rows" => 10,
                    "id" => "content",
                    "name" => "content",
                    "class" => "ckeditor",
                    "placeholder" => "",
                    "value" => $this->content,
                    "error" => "probleme enregistrement base de données",
                    "required" => true
                ],
            ]
        ];
    }

    public function formDeletePage(){
        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_deletepage",
                "class" => "form_builder",
                "submit" => "Supprimer",
                "btn_class" => "btn btn-danger"
            ],
            "inputs" => [
                "delete" => [
                    "type" => "hidden",
                    "label" => "Voulez vous supprimez cette page : ".$this->title." ?",
                    "id" => "title",
                    "class" => "form_input",
                    "value" => $this->slug,
                    "error" => "id not found",
                    "required" => true
                ]
            ]
        ];
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

    public function setAllBySlug($slug){
        $res = $this->getAllBySlug($slug);
        $res = $res[0];
        $this->setId($res['id']);
        $this->setTitle($res['title']);
        $this->setContent($res['content']);
        $this->setCreatedBy($res['createdBy']);
        $this->setSlug($res['slug']);
    }

    public function deleteBySlug($slug){
        $query = "DELETE FROM " . $this->getTable() . " WHERE slug = '" . $slug ."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
    }
}