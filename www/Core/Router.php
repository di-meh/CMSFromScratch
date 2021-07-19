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
	    $page = new Page();
	    $article = new Article();


		$this->setUri($uri);
        if (!file_exists("./.env.test")){
            $this->setUri("/installer");
            $this->setController("Installer");
            $this->setAction("default");
        }elseif(file_exists($this->routesPath)){
			//[/] => Array ( [controller] => Global [action] => default )
			$this->routes = yaml_parse_file($this->routesPath);

			if( !empty($this->routes[$this->uri]) && $this->routes[$this->uri]["controller"] && $this->routes[$this->uri]["action"]){

				$this->setController($this->routes[$this->uri]["controller"]);
				$this->setAction($this->routes[$this->uri]["action"]);
			}elseif (!empty($page->getAllBySlug($this->uri))){
                $this->setController("Page");
                $this->setAction("seePage");
				
			}elseif (!empty($article->getAllBySlug($this->uri))){
                $this->setController("Article");
                $this->setAction("viewArticle");

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
