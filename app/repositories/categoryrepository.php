<?php

namespace Repositories;

use PDO;
use PDOException;
use Exception;
use Repositories\Repository;

class CategoryRepository extends Repository
{
    public function getAll()
    {
        try {
            $query = "SELECT * FROM Categories";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Category');
            $articles = $stmt->fetchAll();

            return $articles;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getOne($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM Categories WHERE category_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Category');
            $product = $stmt->fetch();

            return $product;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function insert($category)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into Categories (category_name) VALUES (?)");

            $stmt->execute([$category->category_name]);

            $category->category_id = $this->connection->lastInsertId();

            return $category;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                if(strpos($e->getMessage(), 'category_name') !== false) {
                    throw new Exception('Category already exists');
                }
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }


    public function update($category)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Categories SET category_name = ? WHERE category_id = ?");

            $stmt->execute([$category->category_name, $category->category_id]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                if(strpos($e->getMessage(), 'category_name') !== false) {
                    throw new Exception('Category already exists');
                }
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Categories WHERE category_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                if(strpos($e->getMessage(), 'Cannot delete or update a parent row') !== false) {
                    throw new Exception('Category in use, failed to delete.');
                }
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }
}
