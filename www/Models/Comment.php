<?php


namespace App\Models;
use App\Core\Singleton;


class Comment extends Singleton
{

    private $id = null;
    protected $user_email;
    protected $title;
    protected $book_id;
    protected $text;
    private $table = "lbly_comment";

    public function __construct()
    {
    }

    public function getAll($id) {
        $query = "SELECT * FROM " . $this->table . "WHERE id = '" . $id . "'" ;
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $res = $prepare->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    public function getAllByBookId($id) {
        $query = "SELECT * FROM " . $this->table . "WHERE book_id = '" . $id . "'" ;
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $res = $prepare->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    public function setAll($id) {
        $query = "SELECT * FROM " . $this->table . "WHERE id = '" . $id . "'" ;
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $res = $prepare->fetch(PDO::FETCH_ASSOC);
        if($res){
            $this->setId($id);
            $this->setUserEmail($res['user_email']);
            $this->setTitle($res['title']);
            $this->setBookId($res['book_id']);
            $this->setText($res['text']);
            return true;
        }
        return false;
    }

    public function formAddComment() {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_addcomment",
                "class" => "form_builder",
                "submit" => "Ajouter"
            ],
            "inputs" => [
                "user_email" => [
                    "type" => "email",
                    "label" => "Votre email",
                    "minLength" => 8,
                    "maxLength" => 320,
                    "id" => "user_email",
                    "class" => "form_input",
                    "placeholder" => "Exemple: nom@gmail.com",
                    "value" => '',
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "required" => true
                ],
                "title" => [
                    "type" => "text",
                    "label" => "Titre de votre commentaire",
                    "minLength" => 1,
                    "maxLength" => 60,
                    "id" => "title",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Un super livre",
                    "value" => "",
                    "error" => "Le titre doit faire entre 1 et 50 caractères",
                    "required" => true
                ],
                "text" => [
                    "type" => "textarea",
                    "label" => "Description de votre commentaire",
                    "minLength" => 1,
                    "id" => "description",
                    "class" => "form_input",
                    "placeholder" => "Dites ce que vous avez pensé...",
                    "value" => "",
                    "error" => "Votre description doit faire entre 1 et 255 caractères",
                    "required" => true
                ],
            ]
        ];
    }

    /**
     * @return null
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
    public function getUserEmail()
    {
        return $this->user_email;
    }

    /**
     * @param mixed $user_email
     */
    public function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
    }

    /**
     * @return mixed
     */
    public function getBookId()
    {
        return $this->book_id;
    }

    /**
     * @param mixed $book_id
     */
    public function setBookId($book_id)
    {
        $this->book_id = $book_id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
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




}