<?php

namespace App\Controller;

use App\Core\View;

class BookController
{
    public function indexAction()
    {
        session_start();

        if (!isset($_SESSION['id'])) header("Location:/"); # si user non connecté => redirection

        $user = $_SESSION['user']; # recuperer objet depuis session

        $view = new View("book");
    }
}
