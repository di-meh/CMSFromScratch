<?php
namespace App\Core;

use App\Models\Book;
use App\Models\Page;
use App\Models\Article;
use App\Models\Category;

class Router
{
	private $routes = [];
	private $uri;
	private $routesPath = "routes.yml";
	private $controller;
	private $action;

	public function __construct($uri){

		$this->setUri($uri);
        if ($this->uri == "/installer"){
            if (!file_exists("./.env")){
                $this->setController("Installer");
                $this->setAction("default");
            }else{
                header("Location:/lbly-admin");
            }
        }elseif(file_exists($this->routesPath)){
            if (!file_exists("./.env")){
                header("Location:/installer");
            }
			
			$page = new Page();
			$article = new Article();
			$category = new Category();
            $book = new Book();

            //[/] => Array ( [controller] => Global [action] => default )
			$this->routes = yaml_parse_file($this->routesPath);
			//appele les différent controller et action en fonction des conditions
			if( !empty($this->routes[$this->uri]) && $this->routes[$this->uri]["controller"] && $this->routes[$this->uri]["action"]){

				$this->setController($this->routes[$this->uri]["controller"]);
				$this->setAction($this->routes[$this->uri]["action"]);
			}elseif (!empty($page->getAllBySlug(substr($this->uri, 1)))){
                $page->setAllBySlug(substr($this->uri, 1));
                if($page->getStatus() == "publish"){
                    $this->setController("Page");
                    $this->setAction("seePage");
                } else {
                    header("HTTP/1.0 404 Not Found");
                    $view = new View('404');
                }
			}elseif (substr($this->uri, 0, 10) == "/articles/" && !empty($article->getAllBySlug(substr($this->uri, 10)))){
                $article->setAllBySlug(substr($this->uri, 10));
                if($article->getStatus() == "publish"){
                    $this->setController("Article");
                    $this->setAction("seeArticle");
                } else {
                    header("HTTP/1.0 404 Not Found");
                    $view = new View('404');
                }
            }elseif (substr($this->uri, 0, 7) == "/books/" && !empty($book->getAllBySlug(substr($this->uri, 7)))){
                $book->setAllBySlug(substr($this->uri, 7));
                if($book->getStatus() == "publish"){
                    $this->setController("Book");
                    $this->setAction("seeBook");
                } else {
                    header("HTTP/1.0 404 Not Found");
                    $view = new View('404');
                }
            }elseif (substr($this->uri, 0, 26) === "/lbly-admin/articles/edit/" && !empty($article->getAllBySlug(substr($this->uri, 26)))){
                $this->setController("Article");
                $this->setAction("editArticle");

            }elseif (substr($this->uri, 0, 28) === "/lbly-admin/articles/delete/" && !empty($article->getAllBySlug(substr($this->uri, 28)))){
                $this->setController("Article");
                $this->setAction("deleteArticle");

			}elseif (substr($this->uri, 0, 17) === "/lbly-admin/edit/" && !empty($page->getAllBySlug(substr($this->uri, 17)))){
                $this->setController("Page");
                $this->setAction("editPage");

            }elseif (substr($this->uri, 0, 19) === "/lbly-admin/delete/" && !empty($page->getAllBySlug(substr($this->uri, 19)))){
                $this->setController("Page");
                $this->setAction("deletePage");

			}elseif (substr($this->uri, 0, 26) === "/lbly-admin/category/edit/" && !empty($category->getAllBySlug(substr($this->uri, 26)))){
                $this->setController("Category");
                $this->setAction("editCategory");

            }elseif (substr($this->uri, 0, 28) === "/lbly-admin/category/delete/" && !empty($category->getAllBySlug(substr($this->uri, 28)))){
                $this->setController("Category");
                $this->setAction("deleteCategory");

			}elseif (substr($this->uri, 0, 23) === "/lbly-admin/books/edit/" && !empty($book->getAllBySlug(substr($this->uri, 23)))){
                $this->setController("Book");
                $this->setAction("editBook");

            }elseif (substr($this->uri, 0, 25) === "/lbly-admin/books/delete/" && !empty($book->getAllBySlug(substr($this->uri, 25)))) {
                $this->setController("Book");
                $this->setAction("deleteBook");
            }else{
                header("HTTP/1.0 404 Not Found");
			    $view = new View('404');
            }

		}else{
			die("Le fichier routes.yml ne fonctionne pas !");
		}
	}

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

	public function setUri($uri){
		$this->uri = trim(mb_strtolower($uri));

	}


	public function setController($controller){
		$this->controller = $controller."Controller";
	}


	public function setAction($action){
		$this->action = $action."Action";
	}


	public function getController(){
		return $this->controller;
	}


	public function getAction(){
		return $this->action;
	}

}
