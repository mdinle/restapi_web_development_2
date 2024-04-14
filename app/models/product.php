<?php
namespace Models;

use DateTime;

class Product
{

    public $id;
    public string $name;
    public float $price;
    public int $stock;
    public string $description;
    public string $image;
    public string $category_id;
    public Category $category;
    public Brand $brand;
    public Size $size;
    public DateTime $created_at;

}
