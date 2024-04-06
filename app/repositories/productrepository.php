<?php

namespace Repositories;

use Models\Category;
use Models\Product;
use Models\Brand;
use PDO;
use PDOException;
use Repositories\Repository;

class ProductRepository extends Repository
{
    public function getAll()
    {
        try {
            $query = "SELECT Products.product_id, Products.category_id, Categories.category_name, Products.brand_id, Brands.brand_name, name AS product_name, price AS product_price, ProductSizes.stock_quantity AS stock, image_url  FROM `Products` 
            JOIN Categories ON Products.category_id = Categories.category_id
            JOIN Brands ON Products.brand_id = Brands.brand_id
            JOIN ProductSizes ON Products.product_id = ProductSizes.product_id;";

            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            $products = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $products[] = $this->rowToProduct($row);
            }

            return $products;

        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getOne($id)
    {
        try {
            $query = "SELECT product.*, category.name as category_name FROM product INNER JOIN category ON product.category_id = category.id WHERE product.id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();
            $product = $this->rowToProduct($row);

            return $product;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function rowToProduct($row)
    {
        $product = new Product();
        $product->id = $row['product_id'];
        $product->name = $row['product_name'];
        $product->price = $row['product_price'];
        $product->stock = $row['stock'];
        $product->image = $row['image_url'];
        $category = new Category();
        $category->category_id = $row['category_id'];
        $category->category_name = $row['category_name'];
        $brand = new Brand();
        $brand->brand_id = $row['brand_id'];
        $brand->brand_name = $row['brand_name'];


        $product->brand = $brand;
        $product->category = $category;
        return $product;
    }

    public function insert($product)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into product (name, price, description, image, category_id) VALUES (?,?,?,?,?)");

            $stmt->execute([$product->name, $product->price, $product->description, $product->image, $product->category_id]);

            $product->id = $this->connection->lastInsertId();

            return $this->getOne($product->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }


    public function update($product, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE product SET name = ?, price = ?, description = ?, image = ?, category_id = ? WHERE id = ?");

            $stmt->execute([$product->name, $product->price, $product->description, $product->image, $product->category_id, $id]);

            return $this->getOne($product->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM product WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
        return true;
    }
}
