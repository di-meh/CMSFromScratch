<?php 

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;

use App\Core\Singleton;

use App\Core\Security;

use App\Models\Page;
use App\Core\Router;

class PageController{

	public function defaultAction(){
        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

        $page = new Page();
        $view = new View("pages","back");

        if(isset($_GET['pageid'])){
        	$page->setAllById($_GET['pageid']);

        	if(isset($_GET['publish'])){
        		$page->setStatus("publish");
        		$page->save();
        		$view->assign("infos", ["Page publiée."]);

        	}

        	if(isset($_GET['withdraw'])){
        		$page->setStatus("withdraw");
        		$page->save();
        		$view->assign("infos", ["Page retirée."]);
        		
        	}
        }

        $pages = $page->all();
        $view->assign("pages", $pages);
	}

	public function addPageAction(){
        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$page = new Page();
		$view = new View("addPage","back");

		$form = $page->formAddPage();

		//verifie si le form est soumis
		if(!empty($_POST)){

		    $errors = FormValidator::check($form, $_POST);

		    $form['inputs']['title']['value'] = $_POST['title'];
		    $form['inputs']['editor']['value'] = $_POST['editor'];

		    //verifie s'il n'y a pas d'erreur
		    if (empty($errors)){
                //set les infos
                $page->setTitle(htmlspecialchars($_POST['title']));
                $page->setMetadescription(htmlspecialchars($_POST['metadescription']));
                $page->setContent($_POST['editor']);
                $page->setCreatedBy($user->getID());
                $page->setStatus("withdraw");

                if (empty($_POST['editor'])){
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }else{
                    $page->setSlug($page->title2slug($_POST['title']));
                    //verifie si le titre est en bdd
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
        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("editPage","back"); # appelle View/editProfil.view.php
		$page = new page();

		//recupere le slug dans l'url
		$uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 17);

        //set la page en fonction du slug
		$page->setAllBySlug($uri);
		$form = $page->formEditPage();

		//verifie que le form est soumis
		if(!empty($_POST)){
		    //modification si different et non nul
			if($_POST['title'] != $page->getTitle()){
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

            //modification si different et non nul
            if($_POST['metadescription'] != $page->getMetadescription()){
                if (!empty($_POST['metadescription'])){
                    $page->setMetadescription(htmlspecialchars($_POST['metadescription']));
                    $page->save();
                    $form = $page->formEditPage();
                    $infos[] = "La metadescription a été mis à jour !";
                    $view->assign("infos", $infos);
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }

            //modification si different et non nul
			if($_POST['content'] != $page->getContent()){
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
        //verifie si user est connecté sinon redirigé vers login page
		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("pages","back");
		$page = new Page();
		$pages = $page->all();

		//recupère le slug dans l'url
		$uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 19);

        //set la page en fonction du slug
        $page->setAllBySlug($uri);
        $pagecontent = $page->getAllBySlug($uri);

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

        //recupere le slug dans l'url
        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);

        $uri = substr($uriExploded[0], 1);

        $pagecontent = $page->getAllBySlug($uri);
        $view->assign("page", $pagecontent);
        $view->assign("metadescription", $pagecontent['metadescription']);
        $view->assign("title", $pagecontent['title']);

        $breadcrumbs = [
			[SITENAME, $_SERVER["HTTP_HOST"]],
			[$pagecontent['title'], $uriExploded[0]],
		];
        $view->assign("breadcrumbs", $breadcrumbs);

    }

}