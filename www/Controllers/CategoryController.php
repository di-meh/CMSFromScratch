<?php
namespace App\Controller;

use App\Core\View;
use App\Core\Security;
use App\Models\Category;

class CategoryController {

	public function defaultAction()
    {
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

        $view = new View("categorys","back");
        $category = new Category();

        $categorys = $category->all();
        $view->assign("categorys", $categorys);
    }

    public function addCategoryAction(){

        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$category = new Category();

		$view = new View("addCategory","back");

        $form = $category->formAddCategory();

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
                        $category->setSlug($category->title2slug($_POST['nameCategory']));
                        if (empty($category->getAllBySlug($category->getSlug()))){
                            var_dump($category);
                            $category->save();
                            header("Location:/lbly-admin/category");
                        }else{
                            echo $category->getSlug();
                            $view->assign("errors", ["Veuillez changer le titre de votre catégorie"]);
                        }
                    }	
                }
            }else{
                $view->assign("errors", $errors);
            }
        }
		$view->assign("form", $form);
	}

    public function editCategoryAction()
	{
		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("editCategory","back"); # appelle View/editProfil.view.php
		$category = new Category();

		$uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 26);

		$category->setAllBySlug($uri);
		$form = $category->formEditCategory();

		if(!empty($_POST)){
			if($_POST['nameCategory'] != $category->getNameCategory()){ # changer le prenom

				if (!empty($_POST['nameCategory'])){
					$category->setNameCategory($_POST['nameCategory']);
					$category->setSlug($category->title2slug($_POST['nameCategory']));
					if (empty($category->getAllBySlug($category->getSlug()))){
						$category->save();
						$form = $category->formEditCategory();
						$infos[] = "Le nom a été mis à jour !";
						$view->assign("infos", $infos);
					}else{
						echo $category->getSlug();
						$view->assign("errors", ["Veuillez changer le nom de votre catégorie"]);
					}
				}else{
					$view->assign("errors", ["Veuillez remplir tous les champs"]);
				}
			}

			if($_POST['colorCategory'] != $category->getColorCategory()){ # changer le nom

				if (!empty($_POST['colorCategory'])){
					$category->setColorCategory($_POST['colorCategory']);
					$category->save();
					$form = $category->formEditCategory();
					$infos[] = "La couleur a été mise à jour !";
					$view->assign("infos", $infos);
				}else{
					$view->assign("errors", ["Veuillez remplir tous les champs"]);
				}
			}
		}
		$view->assign("form", $form); # affiche le formulaire
	}

	public function deleteCategoryAction(){

		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("categorys","back");
		$category = new Category();
		$categorys = $category->all();

		$uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 28);

        $category->setAllBySlug($uri);
        $categorycontent = $category->getAllBySlug($uri);

		if (!empty($_POST["delete"])){
            $category->deleteBySlug($uri);
            header("Location:/lbly-admin/category");
        }

        $view->assign("categorys", $categorys);
        $view->assign("category", $categorycontent);
        $view->assign("deletemodal", true);

        $formdelete = $category->formDeleteCategory();
        $view->assign("formdelete", $formdelete);
		


	}

}