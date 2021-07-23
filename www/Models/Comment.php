<?php


namespace App\Models;
use App\Core\Singleton;


class Comment extends Singleton
{

    private $id = null;
    protected $user_email;
    protected $title;
    protected $book;
    protected $text;
    private $table = "lbly_comment";

    public function __construct()
    {
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
                "email" => [
                    "type" => "email",
                    "label" => "Votre email",
                    "minLength" => 8,
                    "maxLength" => 320,
                    "id" => "email",
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
                    "maxLength" => 50,
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
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param mixed $book
     */
    public function setBook($book)
    {
        $this->book = $book;
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