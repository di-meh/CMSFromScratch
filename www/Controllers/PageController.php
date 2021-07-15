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
        //$form = $page->formDeletePage();
        $pages = $page->all();
        $view->assign("pages", $pages);
       // $view->assign("form", $form);
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
                    $page->setEditSlug('/lbly-admin/edit/' . $page->title2slug($_POST['title']));
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
        $user = Security::getConnectedUser();
        if(is_null($user)) header("Location:/lbly-admin/login");

        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);

        $uri = $uriExploded[0];

        $page = new Page();
        $view = new View("editPage");

        //$page = $page->getAllByEditSlug($uri);
        $page->setAllByEditSlug($uri);
        $form = $page->formEditPage();
        $formdelete = $page->formDeletePage();

        $errors = FormValidator::check($form, $_POST);

        if (!empty($_POST)){
            if (empty($errors)){
                if ($_POST['title'] != $page->getTitle()){
                    if (!empty($_POST['title'])){
                        $page->setTitle($_POST['title']);
                        $page->setSlug('/' . $page->title2slug($_POST['title']));
                        $page->setEditSlug('/lbly-admin/edit/' . $page->title2slug($_POST['title']));
                        if (empty($page->getAllBySlug($page->getSlug()))){
                            $page->save();
                            header("Location:/lbly-admin/pages");
                            //$infos[] = "La page a été mis à jour !";
                            //$view->assign("infos", $infos);
                        }else{
                            $view->assign("errors", ["Veuillez changer le titre de votre page"]);
                        }
                    }else{
                        $view->assign("errors", ["Veuillez remplir tous les champs"]);
                    }
                }

                if ($_POST['content'] != $page->getContent()){
                    if (!empty($_POST['content'])){
                        $page->setContent($_POST['content']);
                        $page->save();
                        header("Location:/lbly-admin/pages");
                        //$infos[] = "La page a été mis à jour !";
                        //$view->assign("infos", $infos);
                    }else{
                        $view->assign("errors", ["Veuillez remplir tous les champs"]);
                    }
                }
            }else{
                $view->assign("errors", $errors);
            }
        }

        if (!empty($_POST["delete"])){
            $page->deleteSelectedPage($page->getId());
            header("Location:/lbly-admin/pages");
        }

        $view->assign("formdelete", $formdelete);
        $view->assign("form", $form);

    }

}