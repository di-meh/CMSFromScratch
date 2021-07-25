<?php

namespace App\Models;

use App\Core\Singleton;
use PDO;

class User extends Singleton
{

    private $id = null;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $pwd;
    protected $country = "fr";
    protected $status = 0; # role, isConfirmed, isDeleted, isBannished
    protected $token = '';

    private $table =  "lbly_user";

    public function __construct()
    {

        #Singleton::setPDO(); # set une unique fois !

    }

    /*
    *   set all properties from database from the email
    *   from id idealement mais flm
    *   pcq il faut recup id a partir de email puis tout a partir de id
    */
    public function setAllFromEmail(string $email)
    {

        $email = htmlspecialchars($email);
        $query = "SELECT * FROM " . $this->table . " WHERE email = '" . $email . "'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $res = $prepare->fetch(PDO::FETCH_ASSOC);
        $this->setId($res['id']);
        $this->setFirstname($res['firstname']);
        $this->setLastname($res['lastname']);
        $this->setEmail($email);
        $this->setCountry($res['country']??'');
        $this->setStatus($res['status']);
        $this->setToken($res['token'] ?? '');

        $this->setPwd($res['pwd']); # un peu dangereux non ? même si hashé

    }
    

    # set all properties from id
    public function setAllFromId($id)
    {

        $query = "SELECT * FROM " . $this->table . " WHERE id = '" . $id . "'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $res = $prepare->fetch(PDO::FETCH_ASSOC);
        if($res){
            $this->setId($id);
            $this->setFirstname($res['firstname']);
            $this->setLastname($res['lastname']);
            $this->setEmail($res['email']);
            $this->setCountry($res['country']);
            $this->setStatus($res['status']);
            $this->setToken($res['token'] ?? '');
    
            $this->setPwd($res['pwd']); # un peu dangereux non ? même si hashé

            return true;
        }
        return false;
    }

    # cherche cet id en base
    public function verifyId($id){

        $id = htmlspecialchars($id);

        $query = "SELECT id FROM " . $this->table . " WHERE id = '" . $id."'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $result = $prepare->fetch(PDO::FETCH_ASSOC);

        if(is_null($result['id']) || empty($result['id']))
            return 0;
        else
            return 1;

    }

    # cherche le mdp correspond a ce mail en base
    public function verifyPwd($email)
    {

        $email = htmlspecialchars($email);
        $query = "SELECT pwd FROM " . $this->table . " WHERE email = '" . $email . "'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        return $prepare->fetchColumn();
    }

    # verifie que le mail existe en base
    public function verifyMail($email)
    {


        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE email = '" . $email . "'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $count = $prepare->fetchColumn();

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

    # verify token and firstname given to set user status to uservalidated
    public function verifyUser($id, $token){
        $id = htmlspecialchars($id);
        $token = htmlspecialchars($token);

        $query = "SELECT id FROM " . $this->table . " WHERE id = '" . $id . "' AND token = '".$token."'";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $result = $prepare->fetch(PDO::FETCH_ASSOC);

        if(is_null($result['id']) || empty($result['id']))
            return 0;
        else
            return 1;

    }

    public function getTable()
    {
        return $this->table;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
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

    public function addStatus($status){
        $this->status |= $status;
    }

    public function unflagStatus($status){
        $this->status &= ~$status;
    }

    public function isSuperAdmin(){
        return ($this->status & USERSUPERADMIN);
    }

    public function isAdmin(){
        return (($this->status & USERADMIN) || ($this->status & USERSUPERADMIN));
    }

    public function isValidated(){
        return ($this->status & USERVALIDATED);
    }

    public function isContributor(){
        return $this->status & USERCONTRIBUTOR;
    }

    public function isEditor(){
        return $this->status & USEREDITOR;
    }

    public function isAuthor(){
        return $this->status & USERAUTHOR;
    }

    public function isDeleted(){
        return $this->status & USERDELETED;
    }

    # form enables to edit users status, give roles and rights
    public function formRoles(){
        return [

            "config" => [
                    "method" => "POST",
                    "action" => "",
                    "id" => "form_delete",
                    "class" => "form_builder",
                    "name" => "valider",
                    "submit" => "Valider"
                ],
            "inputs" => [
                "admin" => [
                    "type" => "checkbox",
                    "label" => "Administrateur",
                    "id" => "admin",
                    "class" => "form_input"
                ],
                "contributor" => [
                    "type" => "checkbox",
                    "label" => "Contributeur",
                    "id" => "contributor",
                    "class" => "form_input"
                ],
                "author" => [
                    "type" => "checkbox",
                    "label" => "Auteur",
                    "id" => "author",
                    "class" => "form_input"
                ],
                "editor" => [
                    "type" => "checkbox",
                    "label" => "Editeur",
                    "id" => "editor",
                    "class" => "form_input",

                ],
                "validated" => [
                    "type" => "checkbox",
                    "label" => "Valider",
                    "id" => "validated",
                    "class" => "form_input",
                    "hidden" => $this->isValidated()?true:false


                ]
            ]
        ];
    }

    public function formDelete(){
        return [

            "config" => [
                    "method" => "POST",
                    "action" => "",
                    "id" => "form_delete",
                    "class" => "form_builder",
                    "submit" => "Valider"
                ],
            "inputs" => [
                "pwdConfirm" => [
                    "type" => "password",
                    "label" => "Confirmez avec votre mot de passe",
                    "id" => "confirmpwd",
                    "class" => "form_input",
                    "placeholder" => "",
                    "required" => true
                ]
            ]
        ];
    }

    public function formForgetPwd(){
        return [

            "config" => [
                    "method" => "POST",
                    "action" => "",
                    "id" => "form_forgetpwd",
                    "class" => "form_builder",
                    "submit" => "Valider"
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
                    "value" => $this->getEmail() ?? '',
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "required" => true
                ]
            ]
        ];
    }

    public function formResetPwd(){
        return [

            "config" => [
                    "method" => "POST",
                    "action" => "",
                    "id" => "form_editprofil",
                    "class" => "form_builder",
                    "submit" => "Valider"
                ],
            "inputs" => [
                "pwd" => [
                    "type" => "password",
                    "label" => "Votre nouveau mot de passe",
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
                ]
            ]
        ];
    }

    public function formEditProfil()
    {

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
                    "value" => $this->firstname ?? "",
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
                    "value" => $this->lastname ?? "",
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
                    "value" => $this->getEmail() ?? '',
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
                    "value" => '',
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
                    "value" => '',
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
                    "value" => '',
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
                    "value" => '',
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
                    "placeholder" => "Votre prénom",
                    "value" => '',
                    "error" => "Votre prénom doit faire entre 2 et 256 caractères",
                    "required" => true
                ],
                "email" => [
                    "type" => "email",
                    "label" => "Votre adresse de messagerie : ",
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
                    "multiple" => "",
                    "options" => [
                        "fr" => "France",
                        "ru" => "Russie",
                        "pl" => "Pologne",
                    ],
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
                    "label" => "Nom de la Base de données : ",
                    "minLength" => 1,
                    "id" => "dbname",
                    "class" => "form_input",
                    "placeholder" => "Nom de la Base de données",
                    "value" => '',
                    "error" => "Base de données introuvable",
                    "required" => true
                ],
                "dbhost" =>[
                    "type" => "text",
                    "label" => "Adresse de la Base de données : ",
                    "minLength" => 1,
                    "id" => "dbhost",
                    "class" => "form_input",
                    "placeholder" => "localhost",
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
                "dbusername" =>[
                    "type" => "text",
                    "label" => "BDD Username : ",
                    "minLength" => 1,
                    "id" => "dbusername",
                    "class" => "form_input",
                    "placeholder" => "BDD Username",
                    "value" => '',
                    "error" => "Username BDD incorrect",
                    "required" => true
                ],
                "dbpwd" => [
                    "type" => "password",
                    "label" => "BDD Mot de passe : ",
                    "minLength" => 8,
                    "id" => "dbpwd",
                    "class" => "form_input",
                    "placeholder" => "",
                    "error" => "Mot de passe BDD incorrect",
                    "required" => true
                ],
                "mailhost" =>[
                    "type" => "text",
                    "label" => "Adresse du serveur SMTP : ",
                    "minLength" => 1,
                    "id" => "mailhost",
                    "class" => "form_input",
                    "placeholder" => "Adresse du serveur SMTP",
                    "value" => '',
                    "error" => "host incorrect",
                    "required" => true
                ],
                "mailport" =>[
                    "type" => "text",
                    "label" => "Port du serveur SMTP : ",
                    "minLength" => 1,
                    "id" => "mailport",
                    "class" => "form_input",
                    "placeholder" => "667",
                    "value" => '',
                    "required" => true
                ],
                "mailexp" =>[
                    "type" => "email",
                    "label" => "Adresse de l'expediteur : ",
                    "minLength" => 8,
                    "maxLength" => 320,
                    "id" => "mailexp",
                    "class" => "form_input",
                    "placeholder" => "Exemple: nom@outlook.com",
                    "value" => '',
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                    "required" => true
                ],
                "mailpwd" =>[
                    "type" => "password",
                    "label" => "Mot de passe de l'expediteur : ",
                    "minLength" => 8,
                    "id" => "mailpwd",
                    "class" => "form_input",
                    "placeholder" => "",
                    "value" => '',
                    "required" => true
                ],
            ]
        ];
    }

    public function setDatabase(){
        $query = file_get_contents("./test.sql");
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
        $res = $prepare->fetch(PDO::FETCH_ASSOC);
    }

    public function dropTables(){
        $query1 = "DROP TABLE IF EXISTS `lbly_article`";
        $query2 = "DROP TABLE IF EXISTS `lbly_books`";
        $query3 = "DROP TABLE IF EXISTS `lbly_category`";
        $query4 = "DROP TABLE IF EXISTS `lbly_page`";
        $query5 = "DROP TABLE IF EXISTS `lbly_user`";

        $prepare1 = $this->getPDO()->prepare($query1);
        $prepare2 = $this->getPDO()->prepare($query2);
        $prepare3 = $this->getPDO()->prepare($query3);
        $prepare4 = $this->getPDO()->prepare($query4);
        $prepare5 = $this->getPDO()->prepare($query5);

        $prepare1->execute();
        $prepare2->execute();
        $prepare3->execute();
        $prepare4->execute();
        $prepare5->execute();
    }

    public function createTableArticle(){
        $query = "CREATE TABLE `lbly_article` (
                  `id` int(11) NOT NULL,
                  `author` int(11) NOT NULL DEFAULT '0',
                  `title` tinytext NOT NULL,
                  `slug` varchar(300) NOT NULL,
                  `content` longtext NOT NULL,
                  `category` varchar(30) NOT NULL,
                  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `published` datetime DEFAULT NULL,
                  `modified` datetime DEFAULT NULL,
                  `status` varchar(20) NOT NULL DEFAULT 'publish'
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
    }

    public function createTableBooks(){
        $query = "CREATE TABLE `lbly_books` (
                  `id` int(11) NOT NULL,
                  `title` tinytext COLLATE utf8_bin NOT NULL,
                  `description` text COLLATE utf8_bin,
                  `author` varchar(350) COLLATE utf8_bin NOT NULL,
                  `publication_date` date NOT NULL,
                  `image` text COLLATE utf8_bin,
                  `publisher` varchar(55) COLLATE utf8_bin NOT NULL,
                  `price` smallint(6) NOT NULL,
                  `category` varchar(30) COLLATE utf8_bin DEFAULT NULL,
                  `stock_number` int(11) NOT NULL DEFAULT '0',
                  `slug` text NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
    }

    public function createTableCategory(){
        $query = "CREATE TABLE `lbly_category` (
                  `id` int(11) NOT NULL,
                  `nameCategory` tinytext NOT NULL,
                  `colorCategory` varchar(7),
                  `slug` tinytext NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
    }

    public function createTablePages(){
        $query = "CREATE TABLE `lbly_page` (
                  `id` int(11) NOT NULL,
                  `title` tinytext NOT NULL,
                  `content` longtext NOT NULL,
                  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                  `createdBy` int(11) NOT NULL,
                  `slug` tinytext NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
    }

    public function createTableUser(){
        $query = "CREATE TABLE `lbly_user` (
                  `id` int(11) NOT NULL,
                  `firstname` varchar(55) NOT NULL,
                  `lastname` varchar(255) NOT NULL,
                  `email` varchar(320) NOT NULL,
                  `pwd` varchar(255) NOT NULL,
                  `country` varchar(56) NOT NULL DEFAULT 'fr',
                  `status` int NOT NULL DEFAULT '0',
                  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                  `token` text
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $prepare = $this->getPDO()->prepare($query);
        $prepare->execute();
    }

    public function alterTables(){
        $query1 = "ALTER TABLE `lbly_article` ADD PRIMARY KEY (`id`)";
        $query2 = "ALTER TABLE `lbly_books` ADD PRIMARY KEY (`id`)";
        $query3 = "ALTER TABLE `lbly_category` ADD PRIMARY KEY (`id`)";
        $query4 = "ALTER TABLE `lbly_page` ADD PRIMARY KEY (`id`)";
        $query5 = "ALTER TABLE `lbly_user` ADD PRIMARY KEY (`id`)";
        $query6 = "ALTER TABLE `lbly_article` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
        $query7 = "ALTER TABLE `lbly_books` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
        $query8 = "ALTER TABLE `lbly_category` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
        $query9 = "ALTER TABLE `lbly_page` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
        $query10 = "ALTER TABLE `lbly_user` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
        $query11 = "ALTER TABLE `lbly_page` ADD CONSTRAINT `lbly_page_ibfk_1` FOREIGN KEY (`createdBy`) REFERENCES `lbly_user` (`id`) ON DELETE CASCADE";

        $prepare1 = $this->getPDO()->prepare($query1);
        $prepare2 = $this->getPDO()->prepare($query2);
        $prepare3 = $this->getPDO()->prepare($query3);
        $prepare4 = $this->getPDO()->prepare($query4);
        $prepare5 = $this->getPDO()->prepare($query5);
        $prepare6 = $this->getPDO()->prepare($query6);
        $prepare7 = $this->getPDO()->prepare($query7);
        $prepare8 = $this->getPDO()->prepare($query8);
        $prepare9 = $this->getPDO()->prepare($query9);
        $prepare10 = $this->getPDO()->prepare($query10);
        $prepare11 = $this->getPDO()->prepare($query11);

        $prepare1->execute();
        $prepare2->execute();
        $prepare3->execute();
        $prepare4->execute();
        $prepare5->execute();
        $prepare6->execute();
        $prepare7->execute();
        $prepare8->execute();
        $prepare9->execute();
        $prepare10->execute();
        $prepare11->execute();
    }
}
