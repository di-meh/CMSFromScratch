<?php
namespace App\Core;

use App\Models\Page;
use App\Models\Article;

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

			//[/] => Array ( [controller] => Global [action] => default )
			$this->routes = yaml_parse_file($this->routesPath);
			if( !empty($this->routes[$this->uri]) && $this->routes[$this->uri]["controller"] && $this->routes[$this->uri]["action"]){

				$this->setController($this->routes[$this->uri]["controller"]);
				$this->setAction($this->routes[$this->uri]["action"]);
			}elseif (!empty($page->getAllBySlug(substr($this->uri, 1)))){
                $this->setController("Page");
                $this->setAction("seePage");
				
			}elseif (substr($this->uri, 0, 10) == "/articles/" && !empty($article->getAllBySlug(substr($this->uri, 10)))){
                $this->setController("Article");
                $this->setAction("seeArticle");

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
