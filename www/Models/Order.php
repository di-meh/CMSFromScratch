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
}
