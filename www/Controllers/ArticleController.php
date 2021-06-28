<?php

namespace App\Controller;

use App\Core\View;
use App\Models\Article;

class ArticleController{


	public function defaultAction(){

		
		session_start();

		if(!isset($_SESSION['id'])) header("Location:/login"); # si user non connecté => redirection

		$user = $_SESSION['user']; # recuperer objet depuis session

		$view = new View("articles");
		$article = new Article();
		$articles = $article->all();
        $view->assign("articles", $articles);

	}

	public function addArticleAction(){

		session_start();
		if(!isset($_SESSION['id'])) header("Location:/login"); # si user non connecté => redirection
		$user = $_SESSION['user'];

		$view = new View("addArticle");
		$article = new Article();

		if (!empty($_POST)) {
			
		    if (empty($errors)){
				$article->setTitle($_POST['title']);
				$article->setAuthor($user->getID());
				$article->setContent($_POST['content']);
				if (empty($_POST['content'])){
					$view->assign("errors", ["Veuillez remplir tous les champs"]);
				}else{
					$article->setSlug('/articles/' . $article->title2slug($_POST['title']));
					if (empty($article->getAllBySlug($article->getSlug()))){
						$article->save();
						header("Location:/articles/add");
					}else{
						echo $article->getSlug();
						$view->assign("errors", ["Veuillez changer le titre de votre article"]);
					}
				}
            }else{
                $view->assign("errors", $errors);
            }
        }

		$form = $article->formAddArticle();
		$view->assign("form", $form);
		
	}

	public function editArticleAction()
	{

		session_start();

		if(!isset($_SESSION['id'])) header("Location:/"); # si user non connecté => redirection

		$view = new View("editArticle"); # appelle View/editProfil.view.php
		$article = new Article();
		$article->setAllById($_GET['article']);
		$form = $article->formEditArticle();

		if(!empty($_POST)){
			if($_POST['title'] != $article->getTitle()){ # changer le prenom

				if (!empty($_POST['title'])){
					$article->setTitle($_POST['title']);
					$article->setSlug('/articles/' . $article->title2slug($_POST['title']));
					if (empty($article->getAllBySlug($article->getSlug()))){
						$article->save();
						$form = $article->formEditArticle();
						$infos[] = "Le titre a été mis à jour !";
						$view->assign("infos", $infos);
					}else{
						echo $article->getSlug();
						$view->assign("errors", ["Veuillez changer le titre de votre article"]);
					}
				}else{
					$view->assign("errors", ["Veuillez remplir tous les champs"]);
				}
			}

			if($_POST['content'] != $article->getContent()){ # changer le nom

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

	public function viewArticleAction(){
        session_start();
        if (!isset($_SESSION['id'])) header("Location:/login"); # si user non connecté => redirection

        $user = $_SESSION['user'];

        $article = new Article();

        $view = new View("viewArticle");

        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);

        $uri = $uriExploded[0];

        $articles = $article->getAllBySlug($uri);
        $view->assign("article", $articles[0]);

    }


}