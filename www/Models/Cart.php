<?php

namespace App\Models;

use App\Models\CartSession;

class Cart

{
    public $books;
     
    public function __construct(){
        $this->books = [];
    }

    public static function addToCart($book){
        if (CartSession::existCart()) {
            $cart = CartSession::getCart();
            if($cart->books[$book["id"]]){
                $cart->books[$book["id"]]["qty"]++;
            } else {
                $cart->books[$book["id"]] = $book;
                $cart->books[$book["id"]]["qty"] = 1;
            }
        } else {
            $cart = new Cart();
            $cart->books[$book["id"]] = $book;
            $cart->books[$book["id"]]["qty"] = 1;
        }
    }

    public static function removeFromCart($book){
        if (CartSession::existCart()) {
            $cart = CartSession::getCart();
            if($cart->books[$book["id"]]["qty"] <= 0){
                unset($cart->books[$book["id"]]);
            } else {
                $cart->books[$book["id"]]["qty"]--;
            }
        }
    }
}
