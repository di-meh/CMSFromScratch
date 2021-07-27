<?php

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker;

use App\Core\Singleton;

use App\Core\Security;
use App\Core\Helpers;


use App\Models\Installer;
use App\Models\User;
use App\Models\Page;
use App\Core\Router;

class InstallerController{
    public function defaultAction(){
        //si le env existe installer déja appeler donc redirection vers dashboard
        if (file_exists("./.env")) header("Location:/lbly-admin");

        $install = new User();
        $view = new View("installer","empty");
        $form = $install->formInstall();

        //verifie si le form est soumis
        if (!empty($_POST)){
            //verifie s'il n'y a pas d'erreur à la validation
            $errors = FormValidator::check($form, $_POST);
            if (empty($errors)){
                if ($_POST['pwd'] == $_POST['pwdConfirm']) {
                    //creation contenu .env
                    $content ="SITENAME=" . htmlspecialchars($_POST["site"]) . "\n"
                        ."DBDRIVER=mysql\n" ."DBPREFIX=lbly_\n" 
                        ."DBHOST=" . htmlspecialchars($_POST["dbhost"]) . "\n"
                        ."DBNAME=" . htmlspecialchars($_POST["dbname"]) . "\n"
                        ."DBUSER=" . htmlspecialchars($_POST["dbusername"]) . "\n"
                        ."DBPWD=" . $_POST["dbpwd"] . "\n"
                        ."DBPORT=" . htmlspecialchars($_POST["dbport"]) . "\n"
                        ."MAILHOST=" . htmlspecialchars($_POST["mailhost"]) . "\n"
                        ."MAILSENDER=" . htmlspecialchars($_POST["mailexp"]) . "\n"
                        ."MAILSUPERADMIN=" . htmlspecialchars($_POST["email"]) . "\n"
                        ."MAILPWD=" . $_POST["mailpwd"] . "\n"
                        ."MAILPORT=" . htmlspecialchars($_POST["mailport"]) . "\n"
                        ."MAILSMTPAUTH=true\n"
                        ."STRIPE_PRIVATE_KEY=" . htmlspecialchars($_POST["stripe_public_key"]) . "\n"
                        ."VITE_STRIPE_PUBLIC_KEY=" . htmlspecialchars($_POST["stripe_private_key"]) . "\n";
                    $handle = fopen("./.env", "w+");
                    fwrite($handle, $content);
                    if (file_exists("./.env")){
                        new ConstantMaker();

                        $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

                        //set l'user avec les info del'installer
                        $install->setFirstname(htmlspecialchars($_POST["firstname"]));
                        $install->setLastname(htmlspecialchars($_POST["lastname"]));
                        $install->setEmail(htmlspecialchars($_POST["email"]));
                        $install->setPwd($pwd);
                        $install->setCountry($_POST["country"]);
                        $install->addStatus(USERSUPERADMIN);

                        $install->setToken(Helpers::createToken());

                        //fonction création bdd
                        $install->dropTables();
                        $install->createTableArticle();
                        $install->createTableBooks();
                        $install->createTableCategory();
                        $install->createTablePages();
                        $install->createTableUser();
                        $install->createTableOrder();
                        $install->alterTables();

                        $install->save();

                        $install->insertFirstPage();
                        $install->insertFirstCategory();
                        $install->insertFirstArticle();
                        $install->insertFirstBook();

                        $email = $_POST['email'];
                        header("Location: lbly-admin/userconfirm?email=$email");
                    }
                }else{
                    $view->assign("errors", ["Vos mots de passe sont différents."]);
                }
            }else {
                $view->assign("errors", $errors);
            }
        }
        $view->assign("form", $form);
    }
}