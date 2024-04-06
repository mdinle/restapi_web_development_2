<?php
namespace Models;

class Product
{

    public int $id;
    public string $name;
    public float $price;
    public int $stock;
    public string $description;
    public string $image;
    public string $category_id;
    public Category $category;
    public Brand $brand;

}
