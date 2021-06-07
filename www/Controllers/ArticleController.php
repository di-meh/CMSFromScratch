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

		$view = new View("addArticle");
		$article = new Article();

		if (!empty($_POST)) {
            $article->setTitle($_POST['title']);
            $article->setSlug($_POST['slug']);
            $article->setAuthor($_POST['author']);
            $article->setBody($_POST['body']);
            $article->save();
			header("Location:/articles/add");
        }

		$form = $article->formAddArticle();
		$view->assign("form", $form);
		
	}


}