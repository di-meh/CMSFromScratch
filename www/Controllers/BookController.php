<?php

namespace App\Controller;

use App\Core\View;
use App\Models\Book;

class BookController
{
    public function indexAction()
    {
        session_start();
        if (!isset($_SESSION['id'])) header("Location:/login"); # si user non connecté => redirection

        $user = $_SESSION['user']; # recuperer objet depuis session

        $view = new View("book");

        $book = new Book();
        if (!empty($_POST)) {
            $book->setTitle($_POST['title']);
            $book->setDescription($_POST['description']);
            $book->setAuthor($_POST['author']);
            $book->setPublicationDate($_POST['publication_date']);
            // $book->setImage($_POST['image']);
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
