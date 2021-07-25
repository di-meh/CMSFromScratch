<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\View;
use App\Core\Security;
use App\Models\Book;

class BookController
{
    public function defaultAction()
    {
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

        $book = new Book();
        $view = new View("book","back");
        $books = $book->all();
        $view->assign("books", $books);

    }

    public function addBookAction(){

        $user = Security::getConnectedUser();
        if(is_null($user)) header("Location:/lbly-admin/login");

        $book = new Book();

        $view = new View("addBook","back");

        $form = $book->formAddBook();

        if(!empty($_POST)) {
            if (isset($_FILES) && !empty($_FILES["image"]["name"])) {
                $_POST["image"] = $_FILES["image"]["name"];
            }else{
                $_POST["image"] = "no image";
            }
                $errors = FormValidator::check($form, $_POST);
            if (empty($errors)) {
                $book->setTitle(htmlspecialchars($_POST['title']));
                $book->setDescription(htmlspecialchars($_POST['description']));
                $book->setAuthor(htmlspecialchars($_POST['author']));
                $book->setPublicationDate($_POST['publication_date']);
                $book->setPublisher(htmlspecialchars($_POST['publisher']));
                $book->setPrice(htmlspecialchars($_POST['price']));
                $categories = "";
                foreach ($_POST['category'] as $item) {
                    $categories .= $item . ",";
                }
                $categories = substr($categories,0,-1);
                $book->setCategory(htmlspecialchars($categories ));
                $book->setStockNumber(htmlspecialchars($_POST['stock_number']));
                $book->setSlug($book->book2slug($_POST['title'] . "-" . $_POST['author'] . "-" . $_POST['publisher']));
                $maxsize = 2097152;
                if (isset($_FILES) && !empty($_FILES["image"]["name"])) {
                    if ($_FILES["image"]["size"] < $maxsize && $_FILES["image"]["size"] != 0){
                        $acceptable = array('application/pdf','image/jpeg','image/jpg','image/png', 'image/JPG', 'image/JPEG', 'image/PNG');
                        if (in_array($_FILES["image"]["type"],$acceptable)){
                            $target_dir = "img/";
                            $oldfile = $target_dir . basename($_FILES["image"]["name"]);
                            $imageFileType = $_FILES["image"]["type"];
                            $imageFileType = explode("/", $_FILES["image"]["type"]);
                            #$imageFileType = pathinfo($oldfile, PATHINFO_EXTENSION);
                            $target_file = $target_dir . basename($book->getSlug()) .".". $imageFileType[1];
                            $uploadOk = 1;
                            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                echo "Le fichier " . basename($_FILES["image"]["name"]) . " a été téléchargé.";
                            } else {
                                echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
                            }
                            $image = basename($book->getSlug().".".$imageFileType[1]);
                            $book->setImage($target_dir.$image);
                            if (empty($book->getAllBySlug($book->getSlug()))) {
                                $book->save();
                                header("Location:/lbly-admin/books");
                            } else {
                                echo $book->getSlug();
                                $view->assign("errors", ["Veuillez changer le titre de votre page"]);
                            }
                        }else{
                            $view->assign("errors", ["Veuillez changer le format de votre image"]);
                        }
                    }else{
                        $view->assign("errors", ["Votre fichier est trop lourd. Il doit faire moins de 2MB."]);
                    }
                }elseif (empty($book->getAllBySlug($book->getSlug()))) {
                    $book->save();
                     header("Location:/lbly-admin/books");
                } else {
                    $view->assign("errors", ["Veuillez changer le titre de votre page"]);
                }
            }else{
                $view->assign("errors", $errors);
            }
        }
        $view->assign("form", $form);
    }

    public function deleteBookAction(){

        $user = Security::getConnectedUser();
        if(is_null($user)) header("Location:/lbly-admin/login");

        $view = new View("book","back");
        $book = new Book();
        $books = $book->all();

        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 25);

        $book->setAllBySlug($uri);
        $bookcontent = $book->getAllBySlug($uri)[0];

        if (!empty($_POST["delete"])){
            unlink($book->getImage());
            $book->deleteBySlug($uri);
            header("Location:/lbly-admin/books");
        }

        $view->assign("books", $books);
        $view->assign("book", $bookcontent);
        $view->assign("deletemodal", true);

        $formdelete = $book->formDeleteBook();
        $view->assign("formdelete", $formdelete);
    }

    public function editBookAction(){
        session_start();
        if (!isset($_SESSION['id'])) header("Location:/"); # si user non connecté => redirection

        $view = new View("editBook", "back");
        $book = new Book();

        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 23);

        $book->setAllBySlug($uri);
        $form = $book->formEditBook();
        if (!empty($_POST)){
            $oldimage = $book->getImage();
            if (!empty($_POST['title'])){
                if ($_POST['title'] != $book->getTitle()){
                    $book->setTitle(htmlspecialchars($_POST['title']));
                    $book->setSlug($book->book2slug($_POST['title']. "-" . $book->getAuthor(). "-" . $book->getPublisher()));
                    if (empty($book->getAllBySlug($book->getSlug()))){
                        $book->save();
                        $form = $book->formEditBook();
                        $infos[] = "Le titre a été mis a jour";
                        $view->assign("infos", $infos);
                    }else{
                        $view->assign("errors", ["Veuillez changer le titre de votre livre"]);
                    }
                }
            }else{
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

                if (!empty($_POST['description'])){
                    if ($_POST['description'] != $book->getDescription()){
                        $book->setDescription(htmlspecialchars($_POST['description']));
                        $book->save();
                        $form = $book->formEditBook();
                        $infos[] = "La description a été mis a jour";
                        $view->assign("infos", $infos);
                    }
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }

            if (!empty($_POST['author'])){
                if ($_POST['author'] != $book->getAuthor()){
                    $book->setAuthor(htmlspecialchars($_POST['author']));
                    $book->setSlug($book->book2slug($book->getTitle(). "-" . $_POST['author']. "-" . $book->getPublisher()));
                    if (empty($book->getAllBySlug($book->getSlug()))){
                        $book->save();
                        $form = $book->formEditBook();
                        $infos[] = "L'auteur a été mis a jour";
                        $view->assign("infos", $infos);
                    }else{
                        $view->assign("errors", ["Veuillez changer le titre de l'auteur"]);
                    }
                }
            }else{
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            if (!empty($_POST['publication_date'])){
                if ($_POST['publication_date'] != $book->getPublicationDate()){
                    $book->setPublicationDate($_POST['publication_date']);
                    $book->save();
                    $form = $book->formEditBook();
                    $infos[] = "La date de publication a été mis a jour";
                    $view->assign("infos", $infos);
                }
            }else{
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            if (!empty($_POST['publisher'])){
                if ($_POST['publisher'] != $book->getPublisher()){
                    $book->setPublisher(htmlspecialchars($_POST['publisher']));
                    $book->setSlug($book->book2slug($book->getTitle(). "-" . $book->getAuthor(). "-" . $_POST['publisher']));
                    if (empty($book->getAllBySlug($book->getSlug()))){
                        $book->save();
                        $form = $book->formEditBook();
                        $infos[] = "La maison d'édition a été mis a jour";
                        $view->assign("infos", $infos);
                    }else{
                        $view->assign("errors", ["Veuillez changer le titre de l'auteur"]);
                    }
                }
            }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            if (isset($_FILES) && !empty($_FILES["image"]["name"])) {
                $maxsize = 2097152;
                if ($_FILES["image"]["size"] < $maxsize && $_FILES["image"]["size"] != 0) {
                    $acceptable = array('application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'image/JPG', 'image/JPEG', 'image/PNG');
                    if (in_array($_FILES["image"]["type"], $acceptable)) {
                        $target_dir = "img/";
                        $oldfile = $target_dir . basename($_FILES["image"]["name"]);
                        $imageFileType = pathinfo($oldfile, PATHINFO_EXTENSION);
                        $target_file = $target_dir . basename($book->getSlug()) .".". $imageFileType;
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            $infos[] = "Le fichier " . basename($_FILES["image"]["name"]) . " a été téléchargé.";
                        } else {
                            $infos[] = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
                        }
                        $image = basename($book->getSlug().".".$imageFileType);
                        $book->setImage($target_dir.$image);
                        $book->save();
                        $view->assign("infos",$infos);
                    }else{
                        $view->assign("errors", ["Veuillez changer le format de votre image"]);
                    }
                }else{
                    $view->assign("errors", ["Votre fichier est trop lourd. Il doit faire moins de 2MB."]);
                }
            }else{
                $target_dir = "img/";
                $imageFileType = pathinfo($book->getImage(), PATHINFO_EXTENSION);
                $image = basename($book->getSlug().".".$imageFileType);
                $target_file = $target_dir . basename($book->getSlug()) .".". $imageFileType;
                rename($oldimage, $target_file);
                $book->setImage($target_dir.$image);
                $book->save();
            }

            if (!empty($_POST['price'])){
                if ($_POST['price'] != $book->getPrice()){
                    $book->setPrice(htmlspecialchars($_POST['price']));
                    $book->save();
                    $form = $book->formEditBook();
                    $infos[] = "La prix a été mis a jour";
                    $view->assign("infos", $infos);
                }
            }else{
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            if (isset($_POST['category'])){
                if ($_POST['category'] != $book->getCategory()){
                    $categories = "";
                    foreach ($_POST['category'] as $item) {
                        $categories .= $item . ",";
                    }
                    $categories = substr($categories,0,-1);
                    $book->setCategory(htmlspecialchars($categories ));
                    $book->save();
                    $form = $book->formEditBook();
                    $infos[] = "La catgeorie a été mis a jour";
                    $view->assign("infos", $infos);
                }
            }else{
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            if (!empty($_POST['stock_number'])){
                if ($_POST['stock_number'] != $book->getStockNumber()){
                    $book->setStockNumber(htmlspecialchars($_POST['stock_number']));
                    $book->save();
                    $form = $book->formEditBook();
                    $infos[] = "Le nombre de stock a été mis a jour";
                    $view->assign("infos", $infos);
                }
            }else{
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }
        }
        if (isset($infos)) header("Location:/lbly-admin/books/edit/".$book->getSlug());
        $view->assign("form", $form);
    }

    public function seeBookAction(){
        session_start();

        $book = new Book();

        $view = new View("seeBook", "front");

        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);

        $uri = substr($uriExploded[0], 7);

        $book = $book->getAllBySlug($uri);
        $view->assign("book", $book[0]);
    }
}
