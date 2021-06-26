<?php 

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;

use App\Core\Singleton;

use App\Core\Redirect;

use App\Models\Pages;

class Page
{


	public function defaultAction(){
        session_start();
        if (!isset($_SESSION['id'])) header("Location:/login"); # si user non connecté => redirection

        $user = $_SESSION['user'];

        $pages = new Pages();
        $slug = new Pages();
        $view = new View("pages");

        $pages = $pages->getPageList();
        $view->assign("pages", $pages);
        $view->assign("slug", $slug);
	}

	public function addPageAction(){
        session_start();
        if (!isset($_SESSION['id'])) header("Location:/login"); # si user non connecté => redirection

        $user = $_SESSION['user'];

		$pages = new Pages();

		$view = new View("addPage");

		$form = $pages->formAddPage();

		if(!empty($_POST)){

		    $errors = FormValidator::check($form, $_POST);

		    $form['inputs']['title']['value'] = $_POST['title'];
		    $form['inputs']['editor']['value'] = $_POST['editor'];

		    if (empty($errors)){

                $pages->setTitle($_POST['title']);
                $pages->setContent($_POST['editor']);
                $pages->setCreatedBy($user->getID());
                if (empty($_POST['editor'])){
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }else{
                    $pages->setSlug('/' . $pages->title2slug($_POST['title']));
                    if (empty($pages->isSlugThere())){
                        $pages->save();
                        header("Location:/");
                    }else{
                        echo $pages->getSlug();
                        $view->assign("errors", ["Veuillez changer le titre de votre page"]);
                    }
                }

            }else{
                $view->assign("errors", $errors);
            }


	    }
		$view->assign("form", $form);
	}

	public function seePageAction(){
        session_start();
        if (!isset($_SESSION['id'])) header("Location:/login"); # si user non connecté => redirection

        $user = $_SESSION['user'];

        $pages = new Pages();

        $view = new View("seePage");
    }

}