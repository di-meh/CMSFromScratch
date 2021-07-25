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
        $cart = null;
        if (CartSession::existCartSession()) {
            $cart = CartSession::getCartSession();
            var_dump($cart);
            if($cart->books[$book["id"]]){
                $cart->books[$book["id"]]["qty"]++;
            } else {
                $cart->books[$book["id"]] = $book;
                $cart->books[$book["id"]]["qty"] = 1;
            }
        } else {
            $cart = new CartSession();
            $cart->books[$book["id"]] = $book;
            $cart->books[$book["id"]]["qty"] = 1;
        }
        CartSession::storeCartInSession($cart);
    }

    public static function removeFromCart($book){
        if (CartSession::existCartSession()) {
            $cart = CartSession::getCartSession();
            var_dump($cart);
            if($cart->books[$book["id"]]["qty"] <= 1){
                unset($cart->books[$book["id"]]);
            } else {
                $cart->books[$book["id"]]["qty"]--;
            }
        }
    }

    public static function resetCart(){
        CartSession::resetCartSession();
    }

    public static function formResetCart(){
        return [

            "config" => [
                "method" => "POST",
                "action" => "",
                "id" => "form_reset_cart",
                "class" => "form_builder",
                "submit" => "Vider le panier",
                "btn_class" => "btn btn-danger"
            ],
            "inputs" => [
                "reset_cart" => [
                    "type" => "hidden",
                    "id" => "id",
                    "class" => "form_input",
                    "error" => "id not found",
                    "required" => true
                ]
            ]
        ];
    }
    public static function formConfirmCart(){
        $totalprice = 0;
        if (CartSession::existCartSession()) {
            $cart = CartSession::getCartSession();
            foreach($cart->books as $book){
                $totalprice += $book['qty']*$book['price'];
            }
            
            return [
                "config" => [
                    "method" => "POST",
                    "action" => "",
                    "id" => "form_confirm_cart",
                    "class" => "form_builder",
                    "submit" => "Passer la commande",
                    "btn_class" => "btn btn-primary"
                ],
                "inputs" => [
                    "price" => [
                        "type" => "hidden",
                        "id" => "price",
                        "class" => "form_input",
                        "value" => $totalprice ?? "Error",
                        "error" => "id not found",
                        "required" => true
                    ]
                ]
            ];
        } else {
            return null;
        }
    }
}
