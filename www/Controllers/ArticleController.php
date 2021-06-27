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