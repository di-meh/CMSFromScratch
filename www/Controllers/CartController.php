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

        $cart = CartSession::getCart();
        $books = get_object_vars($cart)["books"];
        
        $view->assign("books", $books);
        $forms = null;
        foreach ($books as $book) {
            $bookObject = new Book();
            $bookObject->setAllById($book["id"]);
            $forms[$bookObject->getId()] = $bookObject->formRemoveFromCart();
        }
        $view->assign("forms", $forms);

        // Retirer du panier
        if (!empty($_POST)) {
            $id = $_POST['remove_book_from_cart'];
            $book = new Book();
            $book = $book->getAllById($id);
            Cart::removeFromCart($book);
            header('Location:/cart');
        }
    }

    public function emptyAction () {
        CartSession::resetCart();
        // header('');
    }

    public function removeArticleAction () {
        
    }
    
    public function checkoutAction () {
        $cart = Cart::GetShoppingCart();
        $model = $cart->books;
    }

    public function confirmCheckoutAction () {
        if (Cart::ShoppingCartExists()) {
            $cart = Cart::GetShoppingCart();
            $cart->articles;
            foreach ($cart->articles as $article) {
                $user = Security::GetLoggedUser();
                $sale = new Sale(
                    $user->getId(),
                    $article->getId(),
                    $invoiceNumber = (Setting::GetLastInvoiceNumber() + 1),
                    $saleDate = (new DateTime())->format('Y-m-d H:i:s')
                );
                $sale->Create();
            }
            parent::RedirectToController('cart', 'Empty'); // Succesful, redirect to sale history
        } else {
            header("Location:/books");
        }
    }
}