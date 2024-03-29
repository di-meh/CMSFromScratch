<?php 

namespace App\Models;

use App\Core\Singleton;
use PDO;

class Page extends Singleton
{
	private $id = null;
    protected $title;
    protected $metadescription;
    protected $content;
    protected $createdBy;
    protected $slug;
    protected $status;

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

        //retire - en debut et fin de title
        $title = trim($title, '-');

        //suprimme double -
        $title = preg_replace('~-+~', '-', $title);

        //minuscule
        $title = strtolower($title);

        return $title;
    }

    //forms
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
                "metadescription" => [
                    "type" => "textarea",
                    "label" => "Metadescription",
                    "minLength" => 1,
                    "maxLength" => 200,
                    "id" => "metadescription",
                    "class" => "form_input",
                    "placeholder" => "Une desription de la page",
                    "value" => "",
                    "error" => "Votre description doit faire entre 1 et 200 caractères",
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
                    "error" => "Veuillez ajouter du contenu à votre page"
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
                    "value" => $this->title,
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

    //recupere la page en fonction du slug
    public function getAllBySlug($slug){
        $query = "SELECT * FROM " . $this->getTable() . " WHERE slug = '".$slug."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res ? $res[0] : null;
    }


    //recupere la page en fonction de l'id
    public function getAllById($id){
        $query = "SELECT * FROM " . $this->getTable() . " WHERE id = '".$id."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res ? $res[0] : null;
    }

    //recupere les info en fonction du slug
    public function setAllBySlug($slug){
        $res = $this->getAllBySlug($slug);
        $this->setId($res['id']);
        $this->setTitle($res['title']);
        $this->setMetadescription($res['metadescription']);
        $this->setSlug($res['slug']);
        $this->setContent($res['content']);
        $this->setStatus($res['status']);
        $this->setCreatedBy($res['createdBy']);
    }

    //recupere les info en fonction de l'id
    public function setAllById($id)
    {
        $res = $this->getAllById($id);
        $this->setId($res['id']);
        $this->setTitle($res['title']);
        $this->setMetadescription($res['metadescription']);
        $this->setSlug($res['slug']);
        $this->setContent($res['content']);
        $this->setStatus($res['status']);
        $this->setCreatedBy($res['createdBy']);

    }

    //supprime en fonction du slug
    public function deleteBySlug($slug){
        $query = "DELETE FROM " . $this->getTable() . " WHERE slug = '" . $slug ."'";
        $req = $this->getPDO()->prepare($query);
        $req->execute();
    }
}