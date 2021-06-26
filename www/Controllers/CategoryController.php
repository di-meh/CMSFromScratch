<?php
namespace App\Controller;

use App\Core\View;
use App\Models\Category;

class CategoryController {
    
    public function addcategoryAction()
	{
		session_start();
	if (!isset($_SESSION['id'])) header("Location:/login"); # si user non connecté => redirection

		$view = new View("category");

		$category = new Category();
			if (!empty($_POST)) {
						$category->setNameCategory($_POST['nameCategory']);

					$category->save();
				}

		$form = $category->formCategory();
		$view->assign("category", $category);
		$view->assign("form", $form);

}
}