<?php

namespace App\Models;

use App\Core\Singleton;
use PDO;

class Installer extends Singleton{

    public function __construct(){

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
                "name" =>[
                    "type" => "text",
                    "label" => "Nom : ",
                    "minLength" => 2,
                    "maxLength" => 255,
                    "id" => "name",
                    "class" => "name",
                    "placeholder" => "Votre nom",
                    "value" => '',
                    "error" => "Votre nom doit faire entre 2 et 255 caractères",
                    "required" => true
                ],
                "prenom" => [
                    "type" => "text",
                    "label" => "Prenom : ",
                    "minLength" => 2,
                    "maxLength" => 55,
                    "id" => "prenom",
                    "class" => "prenom",
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
                    "class" => "email",
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
                "site" => [
                    "type" => "text",
                    "label" => "Nom du dite : ",
                    "minLength" => 2,
                    "maxLength" => 55,
                    "id" => "site",
                    "class" => "site",
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
                    "class" => "dbname",
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
                    "class" => "dbusername",
                    "placeholder" => "Nom de la BDD",
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
                "dbprefix" =>[
                    "type" => "text",
                    "label" => "BDD Préfix : ",
                    "minLength" => 1,
                    "id" => "dbprefix",
                    "class" => "dbprefix",
                    "placeholder" => "abcde",
                    "value" => '',
                    "required" => true
                ],
                "mailhost" =>[
                    "type" => "text",
                    "label" => "Mailhost : ",
                    "minLength" => 1,
                    "id" => "mailhost",
                    "class" => "mailhost",
                    "placeholder" => "mailhost",
                    "value" => '',
                    "required" => true
                ],
                "mailport" =>[
                    "type" => "text",
                    "label" => "Mailport : ",
                    "minLength" => 1,
                    "id" => "mailport",
                    "class" => "mailport",
                    "placeholder" => "mailport",
                    "value" => '',
                    "required" => true
                ],
                "mailexp" =>[
                    "type" => "text",
                    "label" => "Mail Expediteur : ",
                    "minLength" => 1,
                    "id" => "mailexp",
                    "class" => "mailexp",
                    "placeholder" => "mailexp",
                    "value" => '',
                    "required" => true
                ],


            ]
        ];
    }
}