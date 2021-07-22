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
                if (isset($_FILES) && !empty($_FILES["image"]["name"])) {
                    $target_dir = "img/";
                    $target_file = $target_dir . basename($_FILES["image"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        echo "The file " . basename($_FILES["image"]["name"]) . " has been uploaded.";
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                    $image = basename($_FILES["image"]["name"], $imageFileType);
                    $book->setImage($image);
                }
                $book->setPublisher(htmlspecialchars($_POST['publisher']));
                $book->setPrice(htmlspecialchars($_POST['price']));
                $book->setCategory(htmlspecialchars($_POST['category']));
                $book->setStockNumber(htmlspecialchars($_POST['stock_number']));
                $book->setSlug($book->book2slug($_POST['title'] . "-" . $_POST['author'] . "-" . $_POST['publisher']));
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

    }
}
