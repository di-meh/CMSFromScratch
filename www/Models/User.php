<?php

namespace App\Models;

use App\Core\Singleton;
use PDO;

class User extends Singleton{

    private $id = null;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $pwd;
    protected $country = "fr";
    protected $role = 0;
    protected $isDeleted = 0;
    protected $status = 0;
    protected $token = '';

    private $table = DBPREFIX . "user"; # nom de la table en base, static ?

    public function __construct(){

        #Singleton::setPDO(); # set une unique fois !

    }

    # set all properties from database from the email
    public function setAll($email){

        $email = htmlspecialchars($email);
        $query = "SELECT * FROM ".$this->getTable()." WHERE email='".$email."'";
        echo $query;
        $res = $this->getPDO()->query($query);
        $res = $res->fetch(PDO::FETCH_ASSOC);
        #var_dump($res);
        $this->setId($res['id']);
        $this->setFirstname($res['firstname']);
        $this->setLastname($res['lastname']);
        $this->setEmail($email);
        $this->setCountry($res['country']);
        $this->setRole($res['role']);
        $this->setStatus($res['status']);
        $this->setToken($res['token']??'');

        $this->setPwd($res['pwd']); # un peu dangereux non ? même si hashé
        
    }

    # cherche le mdp correspond a ce mail en base
    public function verifyPwd($email){

        $email = htmlspecialchars($email);
        $query = "SELECT pwd FROM ".$this->table." WHERE email='".$email."'";

        $res = $this->getPDO()->query($query);
        return $res->fetchcolumn();

    }

    # verifie que le mail existe en base
    public function verifyMail($email){

        $query = "SELECT COUNT(*) FROM ".$this->table." WHERE email='".$email."'";

        $res = $this->getPDO()->query($query);
        $count = $res->fetchcolumn();

        switch ($count) {
            case 0:
                return 0; # le mail n'existe pas : go pour inscription, ko pour la connexion
                break;
            case 1:
                return 1; # le mail existe en un exemplaire : go pour la connexion
                break;
            
            default:
                echo "ERREUR VERIFY MAIL";
                return 2; # erreur bizarre              
                break;
        }

    }

    public function getTable(){
        return $this->table;
    }

    public function getToken(){
        return $this->token;
    }

    public function setToken($token){
        $this->token = $token;
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
    public function setEmail($email){

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
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    /**
     * @param int $idDeleted
     */
    public function setIsDeleted(int $isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @param int $role
     */
    public function setRole(int $role)
    {
        $this->role = $role;
    }

    public function formEditProfil(){

        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_editprofil",
                "class" => "form_builder",
                "submit" => "Valider"
            ],
            "inputs" => [
                "firstname" => [
                    "type" => "text",
                    "label" => "Editez votre prénom",
                    "minLength" => 2,
                    "maxLength" => 55,
                    "id" => "firstname",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Yves",
                    "value" => $this->firstname??"",
                    "error" => "Votre prénom doit faire entre 2 et 55 caractères",
                    "required" => true
                ],
                "lastname" => [
                    "type" => "text",
                    "label" => "Editez votre nom",
                    "minLength" => 2,
                    "maxLength" => 255,
                    "id" => "lastname",
                    "class" => "form_input",
                    "placeholder" => "Exemple: SKRZYPCZYK",
                    "value" => $this->lastname??"",
                    "error" => "Votre nom doit faire entre 2 et 255 caractères",
                    "required" => true
                ],
                "email" => [
                    "type" => "email",
                    "label" => "Votre email (inchangeable)",
                    "minLength" => 8,
                    "maxLength" => 320,
                    "id" => "email",
                    "class" => "form_input",
                    "placeholder" => "Exemple: nom@gmail.com",
                    "value" => $this->getEmail()??'',
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "required" => true,
                    "disabled" => 'disabled'
                ],
                "oldpwd" => [
                    "type" => "password",
                    "label" => "Votre mot de passe actuel",
                    "minLength" => 8,
                    "id" => "pwd",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Votre mot de passe doit faire au minimum 8 caractères",
                    "required" => false
                ],
                "pwd" => [
                    "type" => "password",
                    "label" => "Votre nouveau mot de passe",
                    "minLength" => 8,
                    "id" => "pwd",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Votre mot de passe doit faire au minimum 8 caractères",
                    "required" => false
                ],
                "pwdConfirm" => [
                    "type" => "password",
                    "label" => "Confirmation",
                    "confirm" => "pwd",
                    "id" => "pwdConfirm",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Votre mot de mot de passe de confirmation ne correspond pas",
                    "required" => false
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
                ]
            ]
        ];
    }


    public function formRegister()
    {
        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_register",
                "class" => "form_builder",
                "submit" => "S'inscrire"
            ],
            "inputs" => [
                "firstname" => [
                    "type" => "text",
                    "label" => "Votre prénom",
                    "minLength" => 2,
                    "maxLength" => 55,
                    "id" => "firstname",
                    "class" => "form_input",
                    "placeholder" => "Exemple: Yves",
                    "error" => "Votre prénom doit faire entre 2 et 55 caractères",
                    "required" => true
                ],
                "lastname" => [
                    "type" => "text",
                    "label" => "Votre nom",
                    "minLength" => 2,
                    "maxLength" => 255,
                    "id" => "lastname",
                    "class" => "form_input",
                    "placeholder" => "Exemple: SKRZYPCZYK",
                    "error" => "Votre nom doit faire entre 2 et 255 caractères",
                    "required" => true
                ],
                "email" => [
                    "type" => "email",
                    "label" => "Votre email",
                    "minLength" => 8,
                    "maxLength" => 320,
                    "id" => "email",
                    "class" => "form_input",
                    "placeholder" => "Exemple: nom@gmail.com",
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "required" => true
                ],
                "pwd" => [
                    "type" => "password",
                    "label" => "Votre mot de passe",
                    "minLength" => 8,
                    "id" => "pwd",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Votre mot de passe doit faire au minimum 8 caractères",
                    "required" => true
                ],
                "pwdConfirm" => [
                    "type" => "password",
                    "label" => "Confirmation",
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
                ]
            ]

        ];
    }


    public function formLogin()
    {
        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_login",
                "class" => "form_builder",
                "submit" => "Se connecter"
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
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "required" => true
                ],
                "pwd" => [
                    "type" => "password",
                    "label" => "Votre mot de passe",
                    "minLength" => 8,
                    "id" => "pwd",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Votre mot de passe doit faire au minimum 8 caractères",
                    "required" => true
                ]
            ]

        ];
    }
}
