<?php


namespace Models;

use Models\Product;
use Models\Size;
use DateTime;

class StockHistory
{
    public int $history_id;

    public Product $product;

    public Size $size;
    public int $quantity_changed;
    public DateTime $change_date;
    public string $reason;
    public string $stock_movement;
}

