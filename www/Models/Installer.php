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
                    "class" => "dbhost",
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
                    "class" => "dbport",
                    "placeholder" => "0000",
                    "value" => '',
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
                "mailexp" =>[
                    "type" => "email",
                    "label" => "Mail Expediteur : ",
                    "minLength" => 8,
                    "maxLength" => 320,
                    "id" => "mailexp",
                    "class" => "mailexp",
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
                    "class" => "mailpwd",
                    "placeholder" => "",
                    "value" => '',
                    "required" => true
                ],
                "mailport" =>[
                    "type" => "text",
                    "label" => "Mailport : ",
                    "minLength" => 1,
                    "id" => "mailport",
                    "class" => "mailport",
                    "placeholder" => "667",
                    "value" => '',
                    "required" => true
                ],
            ]
        ];
    }
}