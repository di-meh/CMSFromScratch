<?php 

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;

use App\Core\Singleton;

use App\Core\Redirect;

use App\Models\Pages;

class Page
{


	public function defaultAction()
	{
		echo "Controller page action default";
	}

	public function addPageAction(){
		$pages = new Pages();

		$view = new View("addPage");

		$form = $pages->formAddPage();

		if(!empty($_POST)){

		    $errors = FormValidator::check($form, $_POST);

		    $form['inputs']['title']['value'] = $_POST['title'];
		    $form['inputs']['editor']['value'] = $_POST['editor'];

		    if (empty($errors)){

                $pages->setTitle($_POST['title']);
                $pages->setContent($_POST['editor']);
                if (empty($_POST['editor'])){
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }else{

                $pages->save();
                    header("Location:login");
                }

            }else{
                $view->assign("errors", $errors);
            }


	    }
		$view->assign("form", $form);
	}

}