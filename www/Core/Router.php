<?php
namespace App\Core;

class Router
{
	private $routes = [];
	private $uri;
	private $routesPath = "routes.yml";
	private $controller;
	private $action;

	public function __construct($uri){
		$this->setUri($uri);
		if(file_exists($this->routesPath)){
			//[/] => Array ( [controller] => Global [action] => default )
			$this->routes = yaml_parse_file($this->routesPath);

			if( !empty($this->routes[$this->uri]) && $this->routes[$this->uri]["controller"] && $this->routes[$this->uri]["action"]){

				$this->setController($this->routes[$this->uri]["controller"]);
				$this->setAction($this->routes[$this->uri]["action"]);
			/*}elseif (/*vérifie slug en bdd this->uri == fonction qui get slug dans bdd, stocker slug en remplacant
			espace dans le titre de la page par / et tout mettre en minuscule){*/

			}else{
			    echo($this->uri);
				die("Chemin inexistant : 404");
            }

		}else{
			die("Le fichier routes.yml ne fonctionne pas !");
		}
	}

	public function setUri($uri){
		$this->uri = trim(mb_strtolower($uri));

	}


	public function setController($controller){
		$this->controller = $controller;
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
