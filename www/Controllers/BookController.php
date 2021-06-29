<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Security;
use App\Models\Book;

class BookController
{
    public function indexAction()
    {
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

        $view = new View("book","back");

        $book = new Book();
        if (!empty($_POST)) {
            $book->setTitle($_POST['title']);
            $book->setDescription($_POST['description']);
            $book->setAuthor($_POST['author']);
            $book->setPublicationDate($_POST['publication_date']);
            // $book->setImage($_POST['image']);
            var_dump($_FILES);
            // store image sur le serveur
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


            $book->setPublisher($_POST['publisher']);
            $book->setPrice($_POST['price']);
            $book->setCategory($_POST['category']);
            $book->setStockNumber($_POST['stock_number']);
            $book->save();
        }
        $books = $book->all();
        $form = $book->formAddBook();
        $view->assign("books", $books);
        $view->assign("form", $form);
    }
}