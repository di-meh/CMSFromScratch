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

                $page->setTitle(htmlspecialchars($_POST['title']));
                $page->setContent($_POST['editor']);
                $page->setCreatedBy($user->getID());
                if (empty($_POST['editor'])){
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }else{
                    $page->setSlug($page->title2slug($_POST['title']));
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

    public function editPageAction()
	{
		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("editPage","back"); # appelle View/editProfil.view.php
		$page = new page();

		$uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 17);

		$page->setAllBySlug($uri);
		$form = $page->formEditPage();

		if(!empty($_POST)){
			if($_POST['title'] != $page->getTitle()){ # changer le prenom

				if (!empty($_POST['title'])){
					$page->setTitle(htmlspecialchars($_POST['title']));
					$page->setSlug($page->title2slug($_POST['title']));
					if (empty($page->getAllBySlug($page->getSlug()))){
						$page->save();
						$form = $page->formEditPage();
						$infos[] = "Le titre a été mis à jour !";
						$view->assign("infos", $infos);
					}else{
						$view->assign("errors", ["Veuillez changer le titre de votre page"]);
					}
				}else{
					$view->assign("errors", ["Veuillez remplir tous les champs"]);
				}
			}

			if($_POST['content'] != $page->getContent()){ # changer le nom

				if (!empty($_POST['content'])){
					$page->setContent($_POST['content']);
					$page->save();
					$form = $page->formEditPage();
					$infos[] = "Le contenu a été mis à jour !";
					$view->assign("infos", $infos);
				}else{
					$view->assign("errors", ["Veuillez remplir tous les champs"]);
				}
			}
		}
		$view->assign("form", $form); # affiche le formulaire
	}

	public function deletePageAction(){

		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("pages","back");
		$page = new page();
		$pages = $page->all();

		$uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 19);

        $page->setAllBySlug($uri);
        $pagecontent = $page->getAllBySlug($uri)[0];

		if (!empty($_POST["delete"])){
            $page->deleteBySlug($uri);
            header("Location:/lbly-admin/pages");
        }

        $view->assign("pages", $pages);
        $view->assign("page", $pagecontent);
        $view->assign("deletemodal", true);

        $formdelete = $page->formDeletePage();
        $view->assign("formdelete", $formdelete);
		


	}
	public function seePageAction(){

        session_start();

        $page = new Page();

        $view = new View("seePage", "front");

        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);

        $uri = substr($uriExploded[0], 1);

        $page = $page->getAllBySlug($uri);
        $view->assign("page", $page[0]);

    }

}