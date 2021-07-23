<?php

namespace App\Models;

class CartSession

{
    const cartKey = 'CART';

    public static function loadCart(){
        if (!isset($_SESSION)) { session_start(); }
        $_SESSION["total"] = 0;
        for ($i=0; $i< count($products); $i++) {
            $_SESSION["qty"][$i] = 0;
            $_SESSION["amounts"][$i] = 0;
        }
    }

    public static function resetCart(){
        if (CartSession::existCart()) {
            if (!isset($_SESSION)) { session_start(); }
            if (CartSession::existCart()) {
                if (isset($_SESSION[CartSession::cartKey])) {
                    unset($_SESSION[CartSession::cartKey]);
                }
            }
        }
    }

    public static function storeCart($shoppingCart){
        if (!isset($_SESSION)) { session_start(); }
        $_SESSION[CartSession::cartKey] = $shoppingCart;
    }

    public static function existCart () {
        if (!isset($_SESSION)) { session_start(); }
        return isset($_SESSION[CartSession::cartKey]);
    }

    public static function getCart(){
        if (CartSession::existCart()) {
            if (!isset($_SESSION)) { session_start(); }
            return $_SESSION[CartSession::cartKey];
        } else {
            return null;
        }
    }

}
