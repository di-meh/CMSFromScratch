<?php

namespace App\Controller;

use App\Core\FormValidator;
use App\Core\View;
use App\Core\Security;
use App\Models\Cart;
use App\Models\CartSession;
use App\Models\Book;

class CartController
{
    public function defaultAction () {
        $view = new View("cart","front");

        $cart = CartSession::getCartSession();
        if(!empty($cart)){

            $books = get_object_vars($cart)["books"];
        
            $view->assign("books", $books);
            $forms = null;
            foreach ($books as $book) {
                $bookObject = new Book();
                $bookObject->setAllById($book["id"]);
                $forms[$bookObject->getId()] = $bookObject->formRemoveFromCart();
            }
            $forms["reset_cart"] = Cart::formResetCart();
            $view->assign("forms", $forms);
    
            if (!empty($_POST)) {

                // Retirer du panier
                if(isset($_POST['remove_book_from_cart'])){
                    $id = $_POST['remove_book_from_cart'];
                    $book = new Book();
                    $book = $book->getAllById($id);
                    Cart::removeFromCart($book);
                    header('Location:/cart');
                }

                // Vider le panier
                if(isset($_POST['reset_cart'])){
                    Cart::resetCart();
                    header('Location:/cart');
                }
            }
        }
    }
}