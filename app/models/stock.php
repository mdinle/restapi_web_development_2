<?php

namespace Models;

use Models\Product;
use Models\Size;

class Stock
{
    public Product $product;
    public Size $size;
    public int $stock_quantity;
    public String $reason;
    public String $stock_movement;
}

