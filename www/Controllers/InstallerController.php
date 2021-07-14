<?php

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;

use App\Core\Singleton;

use App\Core\Security;

use App\Models\Installer;
use App\Models\Page;
use App\Core\Router;

class InstallerController{
    public function defaultAction(){
        if (file_exists("./.env.test")){
            header("Location:/lbly-admin");
        }
        $install = new Installer();
        $view = new View("installer","back");
        $form = $install->formInstall();
        $errors = FormValidator::check($form, $_POST);

        if (!empty($_POST)){
            if (empty($errors)){
                if ($_POST['pwd'] == $_POST['pwdConfirm']) {
                    $content = "DBHOST=" . $_POST["dbhost"] . "\n" . "DBNAME=" . $_POST["dbname"] . "\n"
                        ."DBUSER=" . $_POST["dbusername"] . "\n" ."DBPWD=" . $_POST["pwd"] . "\n"
                        ."DBPORT=" . $_POST["dbport"] . "\n" ."MAILUSERNAME=" . $_POST["mailexp"] . "\n"
                        ."MAILPWD=" . $_POST["mailpwd"] . "\n" ."MAILPORT=" . $_POST["mailport"] . "\n";
                    $handle = fopen("./.env.test", "w+");
                    fwrite($handle, $content);

                    $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

                    $install->setFirstname(htmlspecialchars($_POST["firstname"]));
                    $install->setLastname(htmlspecialchars($_POST["lastname"]));
                    $install->setEmail(htmlspecialchars($_POST["email"]));
                    $install->setPwd($pwd);
                    $install->setCountry($_POST["country"]);

                    $token = substr(md5(uniqid(true)), 0, 10); # cut length to 10, no prefix, entropy => for more unicity
                    $install->setToken($token);
                    $install->setDatabase();
                    $install->save();

                    $email = $_POST['email'];
                    header("Location:userconfirm?email=$email");
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