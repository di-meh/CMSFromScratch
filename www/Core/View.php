<?php

namespace App\Core;

class View
{


	private $template; // back ou front
	private $view; // home admin login et logout
	private $data = [];

	public function __construct( $view, $template = "front" ){

		$this->setTemplate($template);
		$this->setView($view);

	}

	public function setTemplate($template){
		if(file_exists("Views/Templates/".$template.".tpl.php")){
			$this->template = "Views/Templates/".$template.".tpl.php";
		}else{
			die("Erreur de template");
		}
	}

	public function setView($view){
		if(file_exists("Views/".$view.".view.php")){
			$this->view = "Views/".$view.".view.php";
		}else{
			die("Erreur de vue");
		}
	}

	//$view->assign("pseudo", "Prof");
	public function assign($key, $value){
		$this->data[$key] = $value;
	}


	public function __destruct(){
		//$this->data = ["pseudo"=>"Super Prof"] ==> $pseudo = "Super Prof"
		/*
		foreach ($this->data as $key => $value) {

			// $key = "pseudo"
			// $$key = $"pseudo" = $pseudo
			$$key = $value;
		}
		*/
		extract($this->data);

		include $this->template;
	}


}






