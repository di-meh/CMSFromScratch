<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Security;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartSession;

class OrderController{


	public function defaultAction(){

		$user = Security::getConnectedUser();
		if(is_null($user)) header("Location:/lbly-admin/login");

		$view = new View("orders","back");
		$order = new Order();
		$orders = $order->all();
        $view->assign("orders", $orders);

	}

	public function confirmCartAction(){

		$cart = CartSession::getCartSession();
		$totalprice = 0;
        if(!empty($cart)){
			foreach($cart->books as $book){
                $totalprice += $book['qty']*$book['price'];
            }

			$view = new View("confirmCart","front");

			\Stripe\Stripe::setApiKey(STRIPE_PRIVATE_KEY);

			$intent = \Stripe\PaymentIntent::create([
				'amount' => $totalprice*100,
				'currency' => 'eur',
				'payment_method_types' => ['card'],
			]);
			$view->assign("intent", $intent);

		} else {
			header("Location:/cart");
		}
	}

	public function successAction(){

		$cart = CartSession::getCartSession();
		$totalprice = 0;
        if(!empty($cart)){
			foreach($cart->books as $book){
                $totalprice += $book['qty']*$book['price'];
            }

			$view = new View("successPayment","front");
			// Nous instancions Stripe en indiquand la clé privée, pour prouver que nous sommes bien à l'origine de cette demande
			// \Stripe\Stripe::setApiKey(STRIPE_PRIVATE_KEY);
			if(!empty($_POST)){
				var_dump($_POST);
				$order = new Order();
			}
			// var_dump($intent);
			$view->assign("intent", $intent);

		} else {
			header("Location:/cart");
		}
	}

}