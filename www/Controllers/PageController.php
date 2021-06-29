<?php 

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;

use App\Core\Singleton;

use App\Core\Security;

use App\Models\Page;
use App\Core\Router;

class PageController
{


	public function defaultAction(){
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

        $page = new Page();
        $view = new View("pages","back");

        $pages = $page->all();
        $view->assign("pages", $pages);
	}

	public function addPageAction(){

        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$page = new Page();

		$view = new View("addPage","back");

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
                        header("Location:/lbly-admin/pages");
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

        $page = new Page();

        $view = new View("seePage");

        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);

        $uri = $uriExploded[0];

        $page = $page->getAllBySlug($uri);
        $view->assign("page", $page[0]);

    }

    public function editPageAction(){
        session_start();

        $page = new Page();

        $view = new View("editPage");
        $view->assign("page", $page);
    }

}