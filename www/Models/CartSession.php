<?php

namespace App\Models;

class CartSession

{
    const cartKey = 'CART';

    public static function resetCartSession(){
        if (CartSession::existCartSession()) {
            if (!isset($_SESSION)) { session_start(); }
            if (CartSession::existCartSession()) {
                if (isset($_SESSION[CartSession::cartKey])) {
                    unset($_SESSION[CartSession::cartKey]);
                }
            }
        }
    }

    public static function storeCartInSession($shoppingCart){
        if (!isset($_SESSION)) { session_start(); }
        $_SESSION[CartSession::cartKey] = $shoppingCart;
    }

    public static function existCartSession() {
        if (!isset($_SESSION)) { session_start(); }
        return isset($_SESSION[CartSession::cartKey]);
    }

    public static function getCartSession(){
        if (CartSession::existCartSession()) {
            if (!isset($_SESSION)) { session_start(); }
            return $_SESSION[CartSession::cartKey];
        } else {
            return null;
        }
    }

}
