<?php 

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;

use App\Core\Singleton;

use App\Core\Redirect;

use App\Models\Page;
use App\Core\Router;

class PageController
{


	public function defaultAction(){
        session_start();
        if (!isset($_SESSION['id'])) header("Location:/login"); # si user non connecté => redirection

        $user = $_SESSION['user'];

        $page = new Page();
        $view = new View("pages");

        $pages = $page->all();
        $view->assign("pages", $pages);
	}

	public function addPageAction(){
        session_start();
        if (!isset($_SESSION['id'])) header("Location:/login"); # si user non connecté => redirection

        $user = $_SESSION['user'];

		$page = new Page();

		$view = new View("addPage");

		$form = $page->formAddPage();

		if(!empty($_POST)){

		    $errors = FormValidator::check($form, $_POST);

		    $form['inputs']['title']['value'] = $_POST['title'];
		    $form['inputs']['editor']['value'] = $_POST['editor'];

		    if (empty($errors)){

                $page->setTitle($_POST['title']);
                $page->setContent($_POST['editor']);
                $page->setCreatedBy($user->getID());
                if (empty($_POST['editor'])){
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }else{
                    $page->setSlug('/' . $page->title2slug($_POST['title']));
                    if (empty($page->getAllBySlug($page->getSlug()))){
                        $page->save();
                        header("Location:/");
                    }else{
                        echo $page->getSlug();
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

        $page = new Page();

        $view = new View("seePage");

        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);

        $uri = $uriExploded[0];

        $page = $page->getAllBySlug($uri);
        $view->assign("page", $page[0]);

    }

}