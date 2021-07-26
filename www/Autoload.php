<?php

namespace App;

class Autoload
{

	public static function register(){
		require_once('vendor/autoload.php');
		spl_autoload_register(function($class){
			// App\Core\Router -> \Core\Router
			$class = str_ireplace(__NAMESPACE__, "", $class);
			//  \Core\Router - >  Core\Router
			$class = ltrim($class, "\\");
			// Core\Router -> Core/Router
			$class = str_replace("\\", "/", $class);
		
			if( file_exists($class.".php")){
				include $class.".php";
			}
			
		});

	}


}