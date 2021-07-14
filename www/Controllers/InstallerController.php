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
        $install = new Installer();
        $view = new View("installer","back");
        $form = $install->formInstall();

        if (!empty($_POST)){
            $content = "DBPRREFIX=".$_POST["dbprefix"]."\n";
            $handle = fopen("./.env.test", "w+");
            fwrite($handle, $content);
            echo "jai echo ce message pour voir affichage avant header";
            header("Location:/lbly-admin");
            echo "jai echo ce message pour voir affichage apres header";
        }

        $view->assign("form", $form);
    }
}