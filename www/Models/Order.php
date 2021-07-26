<?php

namespace App\Models;

use App\Models\CartSession;
use App\Core\Singleton;

class Order extends Singleton

{
    private $table = "lbly_order";
    private $id = null;
    protected $email;
    protected $name;
    protected $cart;
    protected $item_number;
    protected $amount;
    protected $currency;
    protected $payment_status;
    protected $create_at;
     
    public function __construct(){
        $date = new \DateTime(date('Y-m-d H:i:s'));
        $this->create_at = $date->format('Y-m-d H:i:s');
    }

    public function getTable(){
		return $this->table;
	}

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getCart(){
		return $this->cart;
	}

	public function setCart($cart){
		$this->cart = $cart;
	}

	public function getItemNumber(){
		return $this->item_number;
	}

	public function setItemNumber($item_number){
		$this->item_number = $item_number;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getCurrency(){
		return $this->currency;
	}

	public function setCurrency($currency){
		$this->currency = $currency;
	}

	public function getPaymentStatus(){
		return $this->payment_status;
	}

	public function setPaymentStatus($payment_status){
		$this->payment_status = $payment_status;
	}

	public function getCreateAt(){
		return $this->create_at;
	}

	public function setCreateAt($create_at){
		$this->create_at = $create_at;
	}
}
