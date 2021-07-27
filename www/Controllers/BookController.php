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
        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

        $view = new View("book","back");
        if(Security::canCreate()){
            $book = new Book();

            if(isset($_GET['bookid'])){
                $book->setAllById(htmlspecialchars($_GET['bookid']));

                if(isset($_GET['publish'])){
                    $book->setStatus("publish");
                    $book->save();
                    $view->assign("infos", ["Livre publié."]);

                }

                if(isset($_GET['withdraw'])){
                    $book->setStatus("withdraw");
                    $book->save();
                    $view->assign("infos", ["Livre retiré."]);

                }
            }

            $books = $book->all();
            $view->assign("books", $books);
        }else{
            $view->assign("errors", ["Vous n'avez pas accès à cette page."]);

        }

    }

    public function addBookAction(){
        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
        if(is_null($user)) header("Location:/lbly-admin/login");

        $book = new Book();

        $view = new View("addBook","back");

        $form = $book->formAddBook();

        //verifie si le form est soumis
        if(!empty($_POST)) {
            if (isset($_FILES) && !empty($_FILES["image"]["name"])) {
                $_POST["image"] = $_FILES["image"]["name"];
            }else{
                $_POST["image"] = "no image";
            }
            $_POST["image"] = (isset($_FILES) && !empty($_FILES["image"]["name"])) ? $_FILES["image"]["name"] : "no image";

            $errors = FormValidator::check($form, $_POST);
            //verifie s'il n'y a pas d'erreur lors de la validation
            if (empty($errors)) {
                //remplis les setters
                $book->setTitle(htmlspecialchars($_POST['title']));
                $book->setDescription(htmlspecialchars($_POST['description']));
                $book->setAuthor(htmlspecialchars($_POST['author']));
                $book->setPublicationDate($_POST['publication_date']);
                $book->setPublisher(htmlspecialchars($_POST['publisher']));
                if ($_POST['price'] < 65535){
                    $book->setPrice(htmlspecialchars($_POST['price']));
                }else{
                    $view->assign("errors", ["Votre prix doit être en 1€ et 65535€"]);
                    header("Location:/lbly-admin/books/add");

                }
                $book->setPrice(htmlspecialchars($_POST['price']));
                $categories = "";
                foreach ($_POST['category'] as $item) {
                    $categories .= $item . ",";
                }
                $categories = substr($categories,0,-1);
                $book->setCategory(htmlspecialchars($categories ));
                if ($_POST['stock_number'] < 2147483648){
                    $book->setStockNumber(htmlspecialchars($_POST['stock_number']));
                }else{
                    $view->assign("errors", ["Votre stock est trop élevé"]);
                    header("Location:/lbly-admin/books/add");
                }
                $book->setStatus("withdraw");
                $book->setSlug($book->book2slug($_POST['title'] . "-" . $_POST['author'] . "-" . $_POST['publisher']));

                $maxsize = 2097152; #2MB
                //verifie si image est choisis et si elle a un nom et si la taille est acceptable
                if (isset($_FILES) && !empty($_FILES["image"]["name"])) {
                    if ($_FILES["image"]["size"] < $maxsize && $_FILES["image"]["size"] != 0){
                        $acceptable = array('application/pdf','image/jpeg','image/jpg','image/png', 'image/JPG', 'image/JPEG', 'image/PNG');
                        if (in_array($_FILES["image"]["type"],$acceptable)){ #verifie si l'exention est accepté
                            //create path for downloaded file
                            $target_dir = "img/";
                            $imageFileType = explode("/", $_FILES["image"]["type"]);
                            $target_file = $target_dir . basename($book->getSlug()) .".". $imageFileType[1];

                            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                $infos[] = "Le fichier " . basename($_FILES["image"]["name"]) . " a été téléchargé.";
                            } else {
                                $infos[] = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
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
        //verifie si user est connecté sinon redirigé vers login page
        $user = Security::getConnectedUser();
        if(is_null($user)) header("Location:/lbly-admin/login");

        $view = new View("book","back");
        $book = new Book();
        $books = $book->all();

        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 25);

        //get artcile en fonction du slug
        $book->setAllBySlug($uri);
        $bookcontent = $book->getAllBySlug($uri);

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
        //verifie si user est connecté sinon redirigé vers login page
        session_start();
        if (!isset($_SESSION['id'])) header("Location:/"); # si user non connecté => redirection

        $view = new View("editBook", "back");
        $book = new Book();

        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 23);

        //recupere le book en fonction du slug
        $book->setAllBySlug($uri);
        $form = $book->formEditBook();

        //si le formulaire est soumis
        if (!empty($_POST)){
            $oldimage = $book->getImage();
            $updated = false;

            //modification si différent et non vide
            if (!empty($_POST['title'])){
                if ($_POST['title'] != $book->getTitle()){
                    $book->setTitle(htmlspecialchars($_POST['title']));
                    $book->setSlug($book->book2slug($_POST['title']. "-" . $book->getAuthor(). "-" . $book->getPublisher()));
                    if (empty($book->getAllBySlug($book->getSlug()))){
                        $book->save();
                        $updated = true;
                        $form = $book->formEditBook();
                        header("Location:/lbly-admin/books");
                    }else{
                        $updated = true;
                        $view->assign("errors", ["Veuillez changer le titre de votre livre"]);
                    }
                }
            }else{
                $updated = true;
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            //modification si différent et non vide
            if (!empty($_POST['description'])){
                if ($_POST['description'] != $book->getDescription()){
                    $book->setDescription(htmlspecialchars($_POST['description']));
                    $book->save();
                    $updated = true;
                    $form = $book->formEditBook();
                    $infos[] = "La description a été mis a jour";
                    $view->assign("infos", $infos);
                }
            }else{
                $updated = true;
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            //modification si différent et non vide
            if (!empty($_POST['author'])){
                if ($_POST['author'] != $book->getAuthor()){
                    $book->setAuthor(htmlspecialchars($_POST['author']));
                    $book->setSlug($book->book2slug($book->getTitle(). "-" . $_POST['author']. "-" . $book->getPublisher()));
                    if (empty($book->getAllBySlug($book->getSlug()))){
                        $book->save();
                        $updated = true;
                        $form = $book->formEditBook();
                        header("Location:/lbly-admin/books");
                    }else{
                        $updated = true;
                        $view->assign("errors", ["Veuillez changer le titre de l'auteur"]);
                    }
                }
            }else{
                $updated = true;
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            //modification si différent et non vide
            if (!empty($_POST['publication_date'])){
                if ($_POST['publication_date'] != $book->getPublicationDate()){
                    $book->setPublicationDate($_POST['publication_date']);
                    $book->save();
                    $updated = true;
                    $form = $book->formEditBook();
                    $infos[] = "La date de publication a été mis a jour";
                    $view->assign("infos", $infos);
                }
            }else{
                $updated = true;
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            //modification si différent et non vide
            if (!empty($_POST['publisher'])){
                if ($_POST['publisher'] != $book->getPublisher()){
                    $book->setPublisher(htmlspecialchars($_POST['publisher']));
                    $book->setSlug($book->book2slug($book->getTitle(). "-" . $book->getAuthor(). "-" . $_POST['publisher']));
                    if (empty($book->getAllBySlug($book->getSlug()))){
                        $book->save();
                        $updated = true;
                        $form = $book->formEditBook();
                        header("Location:/lbly-admin/books");
                    }else{
                        $updated = true;
                        $view->assign("errors", ["Veuillez changer le titre de l'auteur"]);
                    }
                }
            }else{
                $updated = true;
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            //modification si différent et non vide
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
                        $updated = true;
                        $view->assign("infos",$infos);
                    }else{
                        $updated = true;
                        $view->assign("errors", ["Veuillez changer le format de votre image"]);
                    }
                }else{
                    $updated = true;
                    $view->assign("errors", ["Votre fichier est trop lourd. Il doit faire moins de 2MB."]);
                }
            }elseif (substr($book->getImage(),-1) == ".") {
                $target_dir = "img/";
                $imageFileType = pathinfo($book->getImage(), PATHINFO_EXTENSION);
                $image = basename($book->getSlug().".".$imageFileType);
                $target_file = $target_dir . basename($book->getSlug()) .".". $imageFileType;
                rename($oldimage, $target_file);
                $book->setImage($target_dir.$image);
                $book->save();
                $updated = true;
            }else{

            }

            //modification si différent et non vide
            if (!empty($_POST['price'])){
                if ($_POST['price'] < 65535 && $_POST['price'] > 1){
                    if ($_POST['price'] != $book->getPrice()){
                        $book->setPrice(htmlspecialchars($_POST['price']));
                        $book->save();
                        $updated = true;
                        $form = $book->formEditBook();
                        $infos[] = "La prix a été mis a jour";
                        $view->assign("infos", $infos);
                    }
                }else {
                    $updated = true;
                    $view->assign("errors", ["Votre prix doit être entre 1€ et 65535€"]);
                }
            }else{
                $updated = true;
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }

            $categories = "";
            foreach ($_POST['category'] as $item) {
                $categories .= $item . ",";
            }
            $categories = substr($categories,0,-1);
            //modification si différent et non vide
            if ($categories != $book->getCategory()){
                echo $book->getCategory();
                if (!empty($_POST['category'])){
                    $categories = "";
                    foreach ($_POST['category'] as $item) {
                        $categories .= $item . ",";
                    $categories = substr($categories,0,-1);
                    $book->setCategory(htmlspecialchars($categories ));
                    $book->save();
                    $updated = true;
                    $form = $book->formEditBook();
                    $infos[] = "La catgeorie a été mis a jour";
                    $view->assign("infos", $infos);
                    }
                }else{
                    $updated = true;
                    $view->assign("errors", ["Veuillez remplir tous les champs"]);
                }
            }

            //modification si différent et non vide
            if (!empty($_POST['stock_number'])){
                if((int)$_POST['stock_number'] != $book->getStockNumber()){
                    if ((int)$_POST['stock_number'] < 2147483648){
                        $book->setStockNumber(htmlspecialchars($_POST['stock_number']));
                        $book->save();
                        $updated = true;
                        $form = $book->formEditBook();
                        $infos[] = "Le nombre de stock a été mis a jour";
                        $view->assign("infos", $infos);
                    }else{
                        $updated = true;
                        $view->assign("errors", ["Le stock est trop élevé"]);
                    }
                }
            }else{
                $updated = true;
                $view->assign("errors", ["Veuillez remplir tous les champs"]);
            }
            if (!$updated){
                $infos[] = "Vous n'avez modifié aucune données";
                $view->assign("infos", $infos);
            }
        }

        $view->assign("form", $form);
    }

    public function seeAllBooksAction(){

        $book = new Book();
        $view = new View("seeAllBooks","front");
        $books = $book->all();
        $forms = null;
		$array = [];
        foreach ($books as $book) {
            $bookObject = new Book();
            $bookObject->setAllById($book["id"]);
			if($bookObject->getStatus() == "publish"){
				array_push($array, $book);
			}
            $forms[$bookObject->getId()] = $bookObject->formAddToCart();
        }
        $view->assign("books", $array);
        $view->assign("forms", $forms);

        // Ajout au panier
        if (!empty($_POST['add_book_to_cart'])) {
            $id = $_POST['add_book_to_cart'];
            $book = new Book();
            $cart = new Cart();
            $book = $book->getAllById($id);
            $cart->addToCart($book);
            header('location:/books');
            die();
        }
    }
    
    public function seeBookAction(){

        session_start();

        $book = new Book();

        $view = new View("seeBook", "front");

        //recupération slug dans l'url
        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uri = substr($uriExploded[0], 7);

        $book->setAllBySlug($uri);
        //recupere le livre en foction du slug
        $bookcontent = $book->getAllBySlug($uri);
        $view->assign("book", $bookcontent);
        $view->assign("metadescription", $bookcontent['description']);
        $view->assign("title", $bookcontent['title']);

        $breadcrumbs = [
			[SITENAME, $_SERVER["HTTP_HOST"]],
			['Livres', $_SERVER["HTTP_HOST"].'/books'],
			[$bookcontent['title'], $uriExploded[0]],
		];
        $view->assign("breadcrumbs", $breadcrumbs);

        
        $form = $book->formAddToCart();
        $view->assign("form", $form);

        // Ajout au panier
        if (!empty($_POST['add_book_to_cart']) && $_POST['add_book_to_cart'] == $book->getId()) {
            $cart = new Cart();
            $cart->addToCart($bookcontent);
            header('location:'.$uriExploded[0]);
            die();
        }
    }
}
