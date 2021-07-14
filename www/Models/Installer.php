<?php

namespace App\Models;

use App\Core\Singleton;
use PDO;

class Installer extends Singleton{
    private $id = null;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $pwd;
    protected $country = "fr";
    protected $status = 0; # role, isConfirmed, isDeleted, isBannished
    protected $token = '';

    private $table = DBPREFIX . "user";

    public function __construct(){

    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
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
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * @param mixed $pwd
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    public function formInstall(){
        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_install",
                "class" => "form_builder",
                "submit" => "Valider"
            ],
            "inputs" => [
                "lastname" =>[
                    "type" => "text",
                    "label" => "Nom : ",
                    "minLength" => 2,
                    "maxLength" => 255,
                    "id" => "lastname",
                    "class" => "form_input",
                    "placeholder" => "Votre nom",
                    "value" => '',
                    "error" => "Votre nom doit faire entre 2 et 255 caractères",
                    "required" => true
                ],
                "firstname" => [
                    "type" => "text",
                    "label" => "Prenom : ",
                    "minLength" => 2,
                    "maxLength" => 55,
                    "id" => "firstname",
                    "class" => "form_input",
                    "placeholder" => "Votre nom",
                    "value" => '',
                    "error" => "Votre prénom doit faire entre 2 et 256 caractères",
                    "required" => true
                ],
                "email" => [
                    "type" => "email",
                    "label" => "Votre email : ",
                    "minLength" => 8,
                    "maxLength" => 320,
                    "id" => "email",
                    "class" => "form_input",
                    "placeholder" => "Exemple: nom@gmail.com",
                    "value" => '',
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "required" => true
                ],
                "pwd" => [
                    "type" => "password",
                    "label" => "Votre mot de passe : ",
                    "minLength" => 8,
                    "id" => "pwd",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Votre mot de passe doit faire au minimum 8 caractères",
                    "required" => true
                ],
                "pwdConfirm" => [
                    "type" => "password",
                    "label" => "Confirmation mot de passe : ",
                    "confirm" => "pwd",
                    "id" => "pwdConfirm",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Votre mot de mot de passe de confirmation ne correspond pas",
                    "required" => true
                ],
                "country" => [
                    "type" => "select",
                    "label" => "Votre pays",
                    "options" => [
                        "fr" => "France",
                        "ru" => "Russie",
                        "pl" => "Pologne",
                    ],
                    "minLength" => 2,
                    "maxLength" => 2,
                    "id" => "country",
                    "class" => "form_input",
                    "placeholder" => "Exemple: fr",
                    "error" => "Votre pays doit faire 2 caractères"
                ],
                "site" => [
                    "type" => "text",
                    "label" => "Nom du site : ",
                    "minLength" => 2,
                    "maxLength" => 55,
                    "id" => "site",
                    "class" => "form_input",
                    "placeholder" => "Votre site",
                    "value" => '',
                    "error" => "Votre site doit faire entre 2 et 256 caractères",
                    "required" => true
                ],
                "dbname" =>[
                    "type" => "text",
                    "label" => "Nom de la BDD : ",
                    "minLength" => 1,
                    "id" => "dbname",
                    "class" => "form_input",
                    "placeholder" => "Nom de la BDD",
                    "value" => '',
                    "error" => "BDD introuvable",
                    "required" => true
                ],
                "dbusername" =>[
                    "type" => "text",
                    "label" => "BDD username : ",
                    "minLength" => 1,
                    "id" => "dbusername",
                    "class" => "form_input",
                    "placeholder" => "BDD username",
                    "value" => '',
                    "error" => "Username incorrect",
                    "required" => true
                ],
                "dbpwd" => [
                    "type" => "password",
                    "label" => "Mot de passe BDD : ",
                    "minLength" => 8,
                    "id" => "dbpwd",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Mot de passe incorrect",
                    "required" => true
                ],
                "dbhost" =>[
                    "type" => "text",
                    "label" => "BDD host : ",
                    "minLength" => 1,
                    "id" => "dbhost",
                    "class" => "form_input",
                    "placeholder" => "your host",
                    "value" => '',
                    "error" => "host incorrect",
                    "required" => true
                ],
                "dbport" =>[
                    "type" => "text",
                    "label" => "Port BDD : ",
                    "minLength" => 1,
                    "id" => "dbport",
                    "class" => "form_input",
                    "placeholder" => "0000",
                    "value" => '',
                    "required" => true
                ],
                "dbprefix" =>[
                    "type" => "text",
                    "label" => "BDD Préfix : ",
                    "minLength" => 1,
                    "id" => "dbprefix",
                    "class" => "form_input",
                    "placeholder" => "abcde",
                    "value" => '',
                    "required" => true
                ],
                "mailexp" =>[
                    "type" => "email",
                    "label" => "Mail Expediteur : ",
                    "minLength" => 8,
                    "maxLength" => 320,
                    "id" => "mailexp",
                    "class" => "form_input",
                    "placeholder" => "Exemple: nom@gmail.com",
                    "value" => '',
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "required" => true
                ],
                "mailpwd" =>[
                    "type" => "password",
                    "label" => "Mot de passe mail expediteur   : ",
                    "minLength" => 8,
                    "id" => "mailpwd",
                    "class" => "form_input",
                    "placeholder" => "",
                    "value" => '',
                    "required" => true
                ],
                "mailport" =>[
                    "type" => "text",
                    "label" => "Mailport : ",
                    "minLength" => 1,
                    "id" => "mailport",
                    "class" => "form_input",
                    "placeholder" => "667",
                    "value" => '',
                    "required" => true
                ],
            ]
        ];
    }
}