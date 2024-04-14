<?php
namespace Repositories;

use PDO;
use PDOException;
use Exception;

use Repositories\Repository;
use Models\Size;
use Models\Category;

class SizeRepository extends Repository
{
    public function getAll()
    {
        try {
            $query = "SELECT * FROM Sizes
                      JOIN Categories
                      ON Sizes.category_id = Categories.category_id";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            $sizes = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $sizes[] = $this->rowToSize($row);
            }

            return $sizes;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getOne($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM Sizes JOIN Categories ON Sizes.category_id = Categories.category_id WHERE size_id = :id");
            $stmt->execute([':id' => $id]);

            return $this->rowToSize($stmt->fetch());
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function insert($size)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into Sizes (size_name, category_id) VALUES (?, ?)");
            $stmt->execute([$size->size_name, $size->category->category_id]);

            $size->size_id = $this->connection->lastInsertId();

            return $size;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function update($size)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Sizes SET size_name = ?, category_id = ? WHERE size_id = ?");

            $stmt->execute([$size->size_name, $size->category->category_id, $size->size_id]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Sizes WHERE size_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function sizesForCategory($id){
        try {
            // First, fetch the category_id from the Products table
            $productSql = "SELECT category_id FROM Products WHERE product_id = :productId;";
            $productStmt = $this->connection->prepare($productSql);
            $productStmt->execute([':productId' => $id]);
            $productResult = $productStmt->fetch();

            if (!$productResult) {
                throw new Exception("No product found with the given ID.");
            }

            $categoryId = $productResult['category_id'];

            // Next, fetch the sizes for the fetched category_id
            $sizeSql = "SELECT size_id, size_name FROM Sizes WHERE category_id = :categoryId;";
            $sizeStmt = $this->connection->prepare($sizeSql);
            $sizeStmt->execute([':categoryId' => $categoryId]);

            $sizeStmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Size');
            $sizes = $sizeStmt->fetchAll();

            if (empty($sizes)) {
                throw new Exception("No sizes found for the given category ID.");
            }

            return $sizes;

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function rowToSize($row)
    {
        $size = new Size();
        $size->size_id = $row['size_id'];
        $size->size_name = $row['size_name'];
        $size->category = new Category();
        $size->category->category_id = $row['category_id'];
        $size->category->category_name = $row['category_name'];

        return $size;
    }
}
