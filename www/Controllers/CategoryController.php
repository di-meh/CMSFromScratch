<?php
namespace App\Controller;

use App\Core\View;
use App\Core\Security;
use App\Models\Category;

class CategoryController {

	public function defaultAction()
    {
        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

        $view = new View("categorys","back");
        $category = new Category();

        $categorys = $category->all();
        $view->assign("categorys", $categorys);
    }

    public function addCategoryAction(){
        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$category = new Category();

		$view = new View("addCategory","back");

        $form = $category->formAddCategory();

        //verifie si le formulaire est soumis
		if (!empty($_POST)) {
            //verifie s'il n'y a pas d'erreurs lors de la validation
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
                        //verifie si nom est en bdd
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
        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("editCategory","back"); # appelle View/editProfil.view.php
		$category = new Category();

        //récupération du slug article dans l'url
        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 26);

        //set article en fonction du slug
        $category->setAllBySlug($uri);
		$form = $category->formEditCategory();

		//si le formulaire est souumis
		if(!empty($_POST)){
		    //modification si différent et non vide
			if($_POST['nameCategory'] != $category->getNameCategory()){ # changer le prenom
				if (!empty($_POST['nameCategory'])){
					$category->setNameCategory($_POST['nameCategory']);
					$category->setSlug($category->title2slug($_POST['nameCategory']));
					//verifie si le titre est en bdd
					if (empty($category->getAllBySlug($category->getSlug()))){
						$category->save();
						$form = $category->formEditCategory();
						$infos[] = "Le nom a été mis à jour !";
						$view->assign("infos", $infos);
                        header("Location:/lbly-admin/category");
					}else{
						echo $category->getSlug();
						$view->assign("errors", ["Veuillez changer le nom de votre catégorie"]);
					}
				}else{
					$view->assign("errors", ["Veuillez remplir tous les champs"]);
				}
			}

            //modification si différent et non vide
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

        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("categorys","back");
		$category = new Category();
		$categorys = $category->all();

        //récupération du slug article dans l'url
        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 28);

        //set category en fonction du slug
        $category->setAllBySlug($uri);
        $categorycontent = $category->getAllBySlug($uri);

        //si le form est soumis
		if (!empty($_POST["delete"])){
		    //recupère les livres et articles qui ont la catégorie qui sera supprimé
		    $to_update_book = $category->getDeletedBookCategory($category->getSlug());
            $to_update_article = $category->getDeletedArticleCategory($category->getSlug());

            //pour chaque livre concerné on retire la catégorie supprimé on recrée le string des categorie
            foreach ($to_update_book as $key => $value) {
                $new_category_book = str_replace($category->getSlug(),"",$value['category']);
                $new_category_book = trim($new_category_book, ",");
                $new_category_book = implode(",",array_filter(explode(",",$new_category_book)));

                $category->updateBookCategory($new_category_book,$value['category']);
            }
            //pour chaque article concerné on retire la catégorie supprimé on recrée le string des categorie
            foreach ($to_update_article as $key => $value){
                $new_category_article = str_replace($category->getSlug(),"",$value['category']);
                $new_category_article = trim($new_category_article, ",");
                $new_category_article = implode(",",array_filter(explode(",",$new_category_article)));

                $category->updateArticleCategory($new_category_article,$value['category']);
            }
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