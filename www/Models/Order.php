<?php

namespace App\Models;

use App\Models\CartSession;
use App\Core\Singleton;

class Order extends Singleton

{
    private $table = "lbly_article";
    private $id = null;
    protected $cart;
    protected $price;
    protected $status;
    protected $user;
     
    public function __construct(){
    }

    public function getTable()
    {
        return $this->table;
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
}
