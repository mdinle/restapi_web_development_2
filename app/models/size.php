<?php
namespace Models;

use Models\Category;

class Size
{
    public int $size_id;
    public string $size_name;
    public Category $category;
}
