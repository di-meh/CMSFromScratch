<?php
namespace App\Controller;

use App\Core\View;
use App\Core\Security;
use App\Models\Category;

class CategoryController {
    
    public function addcategoryAction()
	{
		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("category","back");

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