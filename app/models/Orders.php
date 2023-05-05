<?php

use Phalcon\Mvc\Model;

class Orders extends Model
{
    public $id;
    public $productId;
    public $orderQuantity;
    public $orderStatus;
}