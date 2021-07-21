<?php
namespace App\Controller;

use App\Core\View;
use App\Core\Security;
use App\Models\Category;

class CategoryController {

	public function indexAction()
    {
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

        $view = new View("categorys","back");
        $category = new Category();

        if (!empty($_POST)) {
            
            if (empty($errors)){
                
                if (empty($_POST['nameCategory'])){
                    $view->assign("errors", ["Veuillez saisir un nom de catégorie."]);
                }else{
                $category->setNameCategory(htmlspecialchars($_POST['nameCategory']));
                $category->setColorCategory(htmlspecialchars($_POST['colorCategory']));

                    if (!empty($category->checkCategory($category->getNameCategory()))){
                        $view->assign("errors", ["La catégorie existe déjà!"]);
                    }else{
                        $category->save();
                        header("Location:/lbly-admin/category"); 
                    }	
                }
            }else{
                $view->assign("errors", $errors);
            }
        }
        
        $categorys = $category->all();
        $form = $category->formCategory();
        $view->assign("categorys", $categorys);
        $view->assign("form", $form);
    }
}