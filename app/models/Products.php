<?php

use Phalcon\Mvc\Model;

class Products extends Model
{
    public $productId;
    public $productName;
    public $company;
    public $type;
    public $quantity;
    public $price;
    public $image;
}