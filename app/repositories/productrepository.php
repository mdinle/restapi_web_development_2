<?php

namespace Repositories;

use Exception;
use Models\Category;
use Models\Product;
use Models\Brand;
use Models\Size;
use PDO;
use PDOException;
use Repositories\Repository;
use DateTime;

class ProductRepository extends Repository
{
    public function getAll()
    {
        try {
            $query = "SELECT Products.product_id, Products.category_id, Categories.category_name, Products.brand_id, Brands.brand_name, name AS product_name, price AS product_price, image_url, created_at  FROM `Products` 
            JOIN Categories ON Products.category_id = Categories.category_id
            JOIN Brands ON Products.brand_id = Brands.brand_id;";

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

    public function getDetailedProduct($id){
        try {
            $sql = "select * from ProductSizes ps
join Products p on p.product_id = ps.product_id
join Sizes s on s.size_id = ps.size_id
join Categories c on c.category_id = s.category_id
JOIN Brands b on b.brand_id = p.brand_id
where p.product_id = :id;";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':id' => $id
            ]);

            $products = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $products[] = $this->rowToDetailedProduct($row);
            }

            return $products;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getDetailedProducts(){
        try {
            $sql = "select * from ProductSizes ps
join Products p on p.product_id = ps.product_id
join Sizes s on s.size_id = ps.size_id
join Categories c on c.category_id = s.category_id
JOIN Brands b on b.brand_id = p.brand_id;";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $products = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $products[] = $this->rowToDetailedProduct($row);
            }

            return $products;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getOne($id)
    {
        try {
            $query = "SELECT Products.product_id, Products.category_id, Categories.category_name, Products.brand_id, Brands.brand_name, name AS product_name, price AS product_price, image_url, created_at  FROM `Products` 
            JOIN Categories ON Products.category_id = Categories.category_id
            JOIN Brands ON Products.brand_id = Brands.brand_id
            WHERE Products.product_id = :id;";
            $stmt = $this->connection->prepare($query);
            $stmt->execute([
                ':id' => $id
            ]);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0) {
                $product = $this->rowToProduct($stmt->fetch());
                return $product;
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function rowToProduct($row)
    {
        $product = new Product();
        $product->id = $row['product_id'];
        $product->name = $row['product_name'];
        $product->price = $row['product_price'];
        $product->image = $row['image_url'];
        $product->created_at = new DateTime($row['created_at']);
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

    public function rowToDetailedProduct($row){
        $product = new Product();
        $product->id = $row['product_id'];
        $product->name = $row['name'];
        $product->price = $row['price'];
        $product->image = $row['image_url'];
        $product->created_at = new DateTime($row['created_at']);
        $category = new Category();
        $category->category_id = $row['category_id'];
        $category->category_name = $row['category_name'];
        $brand = new Brand();
        $brand->brand_id = $row['brand_id'];
        $brand->brand_name = $row['brand_name'];

        $size = new Size();
        $size->size_id = $row['size_id'];
        $size->size_name = $row['size_name'];

        $product->stock = $row['stock_quantity'];


        $product->brand = $brand;
        $product->category = $category;
        $product->size = $size;
        return $product;
    }

    public function insert($product)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into Products (name, price, image_url, category_id, brand_id) VALUES (?,?,?,?,?)");

            $stmt->execute([$product->name, $product->price, $product->image, $product->category->category_id, $product->brand->brand_id]);

            $product->id = $this->connection->lastInsertId();

            return $this->getOne($product->id);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function update($product)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Products SET name = ?, price = ?, image_url = ?, category_id = ?, brand_id = ? WHERE product_id = ?");

            $stmt->execute([$product->name, $product->price, $product->image, $product->category->category_id, $product->brand->brand_id, $product->id]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Products WHERE product_id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
