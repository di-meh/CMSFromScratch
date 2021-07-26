<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\View;
use App\Core\Security;
use App\Models\Article;

class ArticleController{

	public function defaultAction(){
	    //verifie si user est connecté sinon redirigé vers login page
		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$article = new Article();
		$view = new View("articles","back");

        if(isset($_GET['articleid'])){
        	$article->setAllById(htmlspecialchars($_GET['articleid']));

        	if(isset($_GET['publish'])){
        		$article->setStatus("publish");
        		$article->save();
        		$view->assign("infos", ["Article publié."]);

        	}

        	if(isset($_GET['withdraw'])){
        		$article->setStatus("withdraw");
        		$article->save();
        		$view->assign("infos", ["Article retiré."]);
        		
        	}
        }

		$articles = $article->all();

        $view->assign("articles", $articles);
	}


	public function addArticleAction(){
        //verifie si user est connecté sinon redirigé vers login page
		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$article = new Article();
		$view = new View("addArticle","back");

		$form = $article->formAddArticle();

		//verifie si le form est soumis
		if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            //verifie s'il n'y a pas d'erreur lors de la validation
		    if (empty($errors)){
                //remplis les setters
				$article->setTitle(htmlspecialchars($_POST['title']));
				$article->setMetadescription(htmlspecialchars($_POST['metadescription']));
				$article->setAuthor($user->getID());
				//crée un string des catégories choisis
                $categories = "";
                foreach ($_POST['category'] as $item) {
                    $categories .= $item . ",";
                }
                $categories = substr($categories,0,-1);
                $article->setCategory(htmlspecialchars($categories));
				$article->setContent($_POST['content']);

				if (empty($_POST['content'])){
					$view->assign("errors", ["Veuillez remplir tous les champs"]);
				}else{
					$article->setSlug($article->title2slug($_POST['title']));
					//verifie si le titre est présent en bdd
					if (empty($article->getAllBySlug($article->getSlug()))){
						$article->setStatus("withdraw");
						$article->save();
						header("Location:/lbly-admin/articles");
					}else{
						$view->assign("errors", ["Veuillez changer le titre de votre article"]);
					}
				}
            }else{
                $view->assign("errors", $errors);
            }
        }

		$view->assign("form", $form);
		
	}

	public function editArticleAction()
	{
        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("editArticle","back"); # appelle View/editProfil.view.php
		$article = new Article();

		//récupération du slug article dans l'url
		$uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 26);

        //set article en fonction du slug
		$article->setAllBySlug($uri);
		$form = $article->formEditArticle();

		//si le formulaire est soumis
		if(!empty($_POST)){
		    //modification si différent et non vide
			if($_POST['title'] != $article->getTitle()){
				if (!empty($_POST['title'])){
					$article->setTitle(htmlspecialchars($_POST['title']));
					$article->setSlug($article->title2slug($_POST['title']));
					if (empty($article->getAllBySlug($article->getSlug()))){
						$article->save();
						$form = $article->formEditArticle();
						$infos[] = "Le titre a été mis à jour !";
						$view->assign("infos", $infos);
					}else{
						$view->assign("errors", ["Veuillez changer le titre de votre article"]);
					}
				}else{
					$view->assign("errors", ["Veuillez remplir tous les champs"]);
				}
			}

            //modification si différent et non vide
            if($_POST['metadescription'] != $article->getMetadescription()){
                if (!empty($_POST['metadescription'])){
                    $article->setMetadescription(htmlspecialchars($_POST['metadescription']));
                    $article->save();
                    $form = $article->formEditArticle();
                    $infos[] = "La metadescription a été mis à jour !";
                    $view->assign("infos", $infos);
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }

            //transformation catégorie en string
            $categories = "";
            foreach ($_POST['category'] as $item) {
                $categories .= $item . ",";
            }
            $categories = substr($categories,0,-1);

            //modification si différent et non vide
            if (!empty($categories)){
                if ($categories != $article->getCategory()){
                    $categories = "";
                    foreach ($_POST['category'] as $item) {
                        $categories .= $item . ",";
                    }
                    $categories = substr($categories,0,-1);
                    $article->setCategory(htmlspecialchars($categories ));
                    $article->save();
                    $form = $article->formEditArticle();
                    $infos[] = "La catégorie a été mis à jour !";
                    $view->assign("infos", $infos);
                }
            }else{
                $view->assign("errors", ["Veuillez choisir une categorie"]);
            }

            //modification si différent et non vide
            if($_POST['content'] != $article->getContent()){
				if (!empty($_POST['content'])){
					$article->setContent($_POST['content']);
					$article->save();
					$form = $article->formEditArticle();
					$infos[] = "Le contenu a été mis à jour !";
					$view->assign("infos", $infos);
				}else{
					$view->assign("errors", ["Veuillez remplir tous les champs"]);
				}
			}
		}
		$view->assign("form", $form); # affiche le formulaire
	}

	public function deleteArticleAction(){
        //verifie si user est connecté sinon redirigé vers login page
		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("articles","back");
		$article = new Article();
		$articles = $article->all();

		//recupere le slug via l'url
		$uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 28);

        //set article en fonction du slug
        $article->setAllBySlug($uri);
        $articlecontent = $article->getAllBySlug($uri);

		if (!empty($_POST["delete"])){
            $article->deleteBySlug($uri);
            header("Location:/lbly-admin/articles");
        }

        $view->assign("articles", $articles);
        $view->assign("article", $articlecontent);
        $view->assign("deletemodal", true);

        $formdelete = $article->formDeleteArticle();
        $view->assign("formdelete", $formdelete);
	}

	public function seeArticleAction(){

        session_start();

        $article = new Article();

        $view = new View("seeArticle", "front");

        //recupération slug dans l'url
        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 10);

        $article->setAllBySlug($uri);
        //recupere l'article en fonction du slug
        $articlecontent = $article->getAllBySlug($uri);

        $view->assign("article", $articlecontent);
        $view->assign("metadescription", $articlecontent['metadescription']);
        $view->assign("title", $articlecontent['title']);

		$breadcrumbs = [
			[SITENAME, $_SERVER["HTTP_HOST"]],
			['Articles', $_SERVER["HTTP_HOST"].'/articles'],
			[$articlecontent['title'], $uriExploded[0]],
		];
        $view->assign("breadcrumbs", $breadcrumbs);

    }

}