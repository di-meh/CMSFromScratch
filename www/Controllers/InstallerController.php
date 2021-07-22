<?php

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker;

use App\Core\Singleton;

use App\Core\Security;

use App\Models\Installer;
use App\Models\User;
use App\Models\Page;
use App\Core\Router;

class InstallerController{
    public function defaultAction(){
        if (file_exists("./.env")){
            header("Location:/lbly-admin");
        }
        $install = new User();
        $view = new View("installer","front");
        $form = $install->formInstall();
        $errors = FormValidator::check($form, $_POST);

        if (!empty($_POST)){
                if (empty($errors)){
                    if ($_POST['pwd'] == $_POST['pwdConfirm']) {
                        $content = "DBDRIVER=" . htmlspecialchars($_POST["dbdriver"]) . "\n"
                            . "DBPREFIX=lbly_\n" . "DBHOST=" . htmlspecialchars($_POST["dbhost"]) . "\n"
                            . "DBNAME=" . htmlspecialchars($_POST["dbname"]) . "\n"
                            ."DBUSER=" . htmlspecialchars($_POST["dbusername"]) . "\n"
                            ."DBPWD=" . $_POST["dbpwd"] . "\n"
                            ."DBPORT=" . htmlspecialchars($_POST["dbport"]) . "\n"
                            ."MAILHOST=" . htmlspecialchars($_POST["mailhost"]) . "\n"
                            ."MAILUSERNAME=" . htmlspecialchars($_POST["mailexp"]) . "\n"
                            ."MAILPWD=" . $_POST["mailpwd"] . "\n"
                            ."MAILPORT=" . htmlspecialchars($_POST["mailport"]) . "\n";
                        $handle = fopen("./.env", "w+");
                        fwrite($handle, $content);
                        new ConstantMaker();

                        $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

                        $install->setFirstname(htmlspecialchars($_POST["firstname"]));
                        $install->setLastname(htmlspecialchars($_POST["lastname"]));
                        $install->setEmail(htmlspecialchars($_POST["email"]));
                        $install->setPwd($pwd);
                        $install->setCountry($_POST["country"]);

                        $token = substr(md5(uniqid(true)), 0, 10); # cut length to 10, no prefix, entropy => for more unicity
                        $install->setToken($token);
                        $install->dropTables();
                        $install->createTableArticle();
                        $install->createTableBooks();
                        $install->createTableCategory();
                        $install->createTablePages();
                        $install->createTableUser();
                        $install->alterTables();

                        $install->save();

                        $email = $_POST['email'];
                        header("Location: lbly-admin/userconfirm?email=$email");
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