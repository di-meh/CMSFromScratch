<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\View;
use App\Core\Security;
use App\Models\Book;
use App\Models\Cart;
use App\Models\CartSession;

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
                $book->setCategory(htmlspecialchars($_POST['category']));
                $book->setStockNumber(htmlspecialchars($_POST['stock_number']));
                $book->setSlug($book->book2slug($_POST['title'] . "-" . $_POST['author'] . "-" . $_POST['publisher']));
                if (isset($_FILES) && !empty($_FILES["image"]["name"])) {
                    $target_dir = "img/";
                    $oldfile = $target_dir . basename($_FILES["image"]["name"]);
                    $imageFileType = pathinfo($oldfile, PATHINFO_EXTENSION);
                    $target_file = $target_dir . basename($book->getSlug()) .".". $imageFileType;
                    $uploadOk = 1;
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        echo "The file " . basename($_FILES["image"]["name"]) . " has been uploaded.";
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                    $image = basename($book->getSlug().".".$imageFileType);
                    $book->setImage($target_dir.$image);
                }
                if (empty($book->getAllBySlug($book->getSlug()))) {
                    $book->save();
                     header("Location:/lbly-admin/books");
                } else {
                    echo $book->getSlug();
                    $view->assign("errors", ["Veuillez changer le titre de votre page"]);
                }
            } else {
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
            if ($_POST['title'] != $book->getTitle()){
                if (!empty($_POST['title'])){
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
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }

            if ($_POST['description'] != $book->getDescription()){
                if (!empty($_POST['description'])){
                    $book->setDescription(htmlspecialchars($_POST['description']));
                    $book->save();
                    $form = $book->formEditBook();
                    $infos[] = "La description a été mis a jour";
                    $view->assign("infos", $infos);
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }

            if ($_POST['author'] != $book->getAuthor()){
                if (!empty($_POST['author'])){
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
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }

            if ($_POST['publication_date'] != $book->getPublicationDate()){
                if (!empty($_POST['publication_date'])){
                    $book->setPublicationDate($_POST['publication_date']);
                    $book->save();
                    $form = $book->formEditBook();
                    $infos[] = "La date de publication a été mis a jour";
                    $view->assign("infos", $infos);
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }

            if ($_POST['publisher'] != $book->getPublisher()){
                if (!empty($_POST['publisher'])){
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
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }

            if (isset($_FILES) && !empty($_FILES["image"]["name"])) {
                unlink($oldimage);
                $target_dir = "img/";
                $oldfile = $target_dir . basename($_FILES["image"]["name"]);
                $imageFileType = pathinfo($oldfile, PATHINFO_EXTENSION);
                $target_file = $target_dir . basename($book->getSlug()) .".". $imageFileType;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $infos[] = "The file " . basename($_FILES["image"]["name"]) . " has been uploaded.";
                } else {
                    $infos[] = "Sorry, there was an error uploading your file.";
                }
                $image = basename($book->getSlug().$imageFileType);
                $book->setImage($target_dir.$image);
                $book->save();
                $view->assign("infos",$infos);
            }else{
                $target_dir = "img/";
                $imageFileType = pathinfo($book->getImage(), PATHINFO_EXTENSION);
                $image = basename($book->getSlug().".".$imageFileType);
                $target_file = $target_dir . basename($book->getSlug()) .".". $imageFileType;
                rename($oldimage, $target_file);
                $book->setImage($target_dir.$image);
                $book->save();
            }

            if ($_POST['price'] != $book->getPrice()){
                if (!empty($_POST['price'])){
                    $book->setPrice(htmlspecialchars($_POST['price']));
                    $book->save();
                    $form = $book->formEditBook();
                    $infos[] = "La prix a été mis a jour";
                    $view->assign("infos", $infos);
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }

            if ($_POST['category'] != $book->getCategory()){
                if (!empty($_POST['category'])){
                    $book->setCategory(htmlspecialchars($_POST['category']));
                    $book->save();
                    $form = $book->formEditBook();
                    $infos[] = "La catgeorie a été mis a jour";
                    $view->assign("infos", $infos);
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }

            if ($_POST['stock_number'] != $book->getStockNumber()){
                if (!empty($_POST['stock_number'])){
                    $book->setStockNumber(htmlspecialchars($_POST['stock_number']));
                    $book->save();
                    $form = $book->formEditBook();
                    $infos[] = "Le nombre de stock a été mis a jour";
                    $view->assign("infos", $infos);
                }else{
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }
        }
        #if (isset($infos)) header("Location:/lbly-admin/books/edit/".$book->getSlug());
        $view->assign("form", $form);
    }

    public function seeAllBooksAction(){
        
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

        $book = new Book();
        $view = new View("seeAllBooks","front");
        $books = $book->all();
        $view->assign("books", $books);
        foreach ($books as $book) {
            $bookObject = new Book();
            $bookObject->setAllById($book["id"]);
            $forms[$bookObject->getId()] = $bookObject->formAddToCart();
        }
        $view->assign("forms", $forms);

        // Ajout au panier
        if (!empty($_POST)) {

            $id = $_POST['add_book_to_cart'];
            $book = new Book();
            $book = $book->getAllById($id);
            Cart::addToCart($book);

            header("Location:/books");
        }
    }
}
